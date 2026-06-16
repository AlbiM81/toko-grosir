<?php

// app/Http/Controllers/MidtransController.php

namespace App\Http\Controllers;

use App\Models\Order;

use App\Services\MidtransService;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class MidtransController extends Controller

{

    public function __construct(

        protected MidtransService $midtrans

    ) {}

    /**

     * Webhook/Notification Handler

     * Midtrans akan POST ke endpoint ini setiap ada perubahan status pembayaran

     */

    public function notification(Request $request)

    {

        try {

            // Ambil data notifikasi dari Midtrans

            $notification = json_decode($request->getContent(), true);

            Log::info('Midtrans Notification Received:', $notification);

            // Validasi data wajib ada

            if (empty($notification['order_id']) || empty($notification['signature_key'])) {

                return response()->json(['message' => 'Invalid notification'], 400);

            }

            $midtransOrderId   = $notification['order_id'];       // ex: ORDER-123-1234567890

            $statusCode        = $notification['status_code'];

            $grossAmount       = $notification['gross_amount'];

            $receivedSignature = $notification['signature_key'];

            $transactionStatus = $notification['transaction_status'];

            $fraudStatus       = $notification['fraud_status'] ?? null;

            $paymentType       = $notification['payment_type'] ?? null;

            $transactionId     = $notification['transaction_id'] ?? null;

            // ── VERIFIKASI SIGNATURE ─────────────────────────────

            // Pastikan notifikasi benar-benar dari Midtrans, bukan dari pihak lain

            $isValid = $this->midtrans->verifySignature(

                $midtransOrderId,

                $statusCode,

                $grossAmount,

                $receivedSignature

            );

            if (!$isValid) {

                Log::warning('Midtrans: Invalid signature for order ' . $midtransOrderId);

                return response()->json(['message' => 'Invalid signature'], 403);

            }

            // ── AMBIL ORDER DARI DATABASE ────────────────────────

            preg_match('/ORDER-(\d+)-/', $midtransOrderId, $matches);

            $orderRealId = $matches[1] ?? null;

            if (!$orderRealId) {

                Log::error('Midtrans: Cannot parse order ID from ' . $midtransOrderId);

                return response()->json(['message' => 'Order not found'], 404);

            }

            $order = Order::query()->find($orderRealId);

            if (!$order instanceof Order) {

                Log::error('Midtrans: Order ' . $orderRealId . ' not found in database');

                return response()->json(['message' => 'Order not found'], 404);

            }

            // ── TENTUKAN STATUS BERDASARKAN NOTIFIKASI ───────────

            /*

            Status dari Midtrans:

            - capture + accept    → Pembayaran berhasil (kartu kredit)

            - settlement          → Pembayaran berhasil (transfer/e-wallet)

            - pending             → Menunggu pembayaran

            - deny                → Ditolak

            - cancel              → Dibatalkan

            - expire              → Kadaluarsa

            - failure             → Gagal

            */

            if ($transactionStatus === 'capture') {

                if ($fraudStatus === 'accept') {

                    $this->handlePaymentSuccess($order, $notification);

                } elseif ($fraudStatus === 'challenge') {

                    // Butuh review manual Midtrans

                    $order->update([

                        'payment_status'          => 'challenge',

                        'midtrans_transaction_id' => $transactionId,

                        'payment_method'          => $paymentType,

                    ]);

                }

            } elseif ($transactionStatus === 'settlement') {

                // Pembayaran settlement (transfer bank, e-wallet)

                $this->handlePaymentSuccess($order, $notification);

            } elseif ($transactionStatus === 'pending') {

                $order->update([

                    'payment_status'          => 'pending',

                    'midtrans_transaction_id' => $transactionId,

                    'payment_method'          => $paymentType,

                ]);

            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {

                $this->handlePaymentFailed($order, $transactionStatus, $notification);

            }

            Log::info("Midtrans: Order #{$order->id} status updated to {$order->fresh()->status}");

            return response()->json(['message' => 'Notification processed successfully']);

        } catch (\Exception $e) {

            Log::error('Midtrans Notification Error: ' . $e->getMessage());

            Log::error($e->getTraceAsString());

            return response()->json(['message' => 'Internal server error'], 500);

        }

    }

    /**

     * Handle pembayaran berhasil

     */

    private function handlePaymentSuccess(Order $order, array $notification): void

    {

        // Jika order sudah diproses sebelumnya, skip

        if (in_array($order->status, ['diproses', 'dikirim', 'selesai'])) {

            return;

        }

        $order->update([

            'status'                  => 'diproses',  // langsung diproses setelah bayar

            'payment_status'          => 'settlement',

            'midtrans_transaction_id' => $notification['transaction_id'] ?? null,

            'payment_method'          => $notification['payment_type'] ?? null,

            'paid_at'                 => now(),

        ]);

        // ── Bisa tambahkan notifikasi ke karyawan di sini ──

        // Notification::send($karyawans, new PembayaranBerhasil($order));

    }

    /**

     * Handle pembayaran gagal/dibatalkan/expired

     */

    private function handlePaymentFailed(Order $order, string $reason, array $notification): void

    {

        // Kembalikan stok jika order dibatalkan

        if (in_array($order->status, ['pending']) && in_array($reason, ['cancel', 'expire'])) {

            foreach ($order->orderDetails as $detail) {

                $detail->product->increment('stock', $detail->quantity);

            }

        }

        $order->update([

            'status'         => 'dibatalkan',

            'payment_status' => $reason,

        ]);

    }

}

