<?php

// app/Http/Controllers/Pembeli/OrderController.php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;

use App\Models\Cart;

use App\Models\Order;

use App\Models\OrderDetail;

use App\Services\MidtransService;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller

{

    public function __construct(

        protected MidtransService $midtrans

    ) {}

    // ── Halaman Checkout ─────────────────────────────────────────

    public function checkout()

    {

        $carts = Cart::with('product')

            ->where('user_id', Auth::id())

            ->get();

        if ($carts->isEmpty()) {

            return redirect()->route('pembeli.cart.index')

                ->with('error', 'Keranjang masih kosong.');

        }

        $total = $carts->sum(fn($c) => $c->quantity * $c->product->price);

        return view('pembeli.orders.checkout', compact('carts', 'total'));

    }

    // ── Simpan Order + Generate Snap Token ───────────────────────

    public function store(Request $request)

    {

        $request->validate([

            'address' => 'required|string|max:500',

            'phone'   => 'required|string|max:20|regex:/^[0-9+\-\s]+$/',

        ]);

        $carts = Cart::with('product')

            ->where('user_id', Auth::id())

            ->get();

        if ($carts->isEmpty()) {

            return redirect()->route('pembeli.cart.index')

                ->with('error', 'Keranjang kosong.');

        }

        // Validasi stok

        foreach ($carts as $cart) {

            if ($cart->quantity > $cart->product->stock) {

                return back()->with('error',

                    "Stok '{$cart->product->name}' tidak mencukupi. Tersedia: {$cart->product->stock}"

                );

            }

        }

        $order = null;

        DB::transaction(function () use ($request, $carts, &$order) {

            $total = $carts->sum(fn($c) => $c->quantity * $c->product->price);

            // Buat order dengan status pending

            $order = Order::create([

                'user_id'     => Auth::id(),

                'total_price' => $total,

                'status'      => 'pending',

                'address'     => $request->address,

                'phone'       => $request->phone,

            ]);

            // Simpan detail produk

            foreach ($carts as $cart) {

                OrderDetail::create([

                    'order_id'   => $order->id,

                    'product_id' => $cart->product_id,

                    'quantity'   => $cart->quantity,

                    'subtotal'   => $cart->quantity * $cart->product->price,

                ]);

                // Kurangi stok

                $cart->product->decrement('stock', $cart->quantity);

            }

            // Kosongkan keranjang

            Cart::query()->where('user_id', Auth::id())->delete();

        });

        if (!$order instanceof Order) {

            return redirect()->route('pembeli.cart.index')

                ->with('error', 'Gagal membuat pesanan. Silakan coba lagi.');

        }

        // Generate Snap Token dari Midtrans

        try {

            $snapToken = $this->midtrans->createSnapToken($order);

            $order->update(['snap_token' => $snapToken]);

        } catch (\Exception $e) {

            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());

            // Order tetap dibuat, token bisa di-generate ulang saat show

        }

        return redirect()->route('pembeli.orders.show', $order)

            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

    }

    // ── Detail Order + Tombol Bayar ──────────────────────────────

    public function show(Order $order)

    {

        abort_if($order->user_id !== Auth::id(), 403);

        $order->load('orderDetails.product');

        // Jika snap token belum ada atau expired, generate ulang

        if ($order->status === 'pending' && !$order->snap_token) {

            try {

                $snapToken = $this->midtrans->createSnapToken($order);

                $order->update(['snap_token' => $snapToken]);

                $order->refresh();

            } catch (\Exception $e) {

                Log::error('Midtrans Token Regenerate Error: ' . $e->getMessage());

            }

        }

        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production');

        return view('pembeli.orders.show', compact('order', 'clientKey', 'isProduction'));

    }

    // ── Daftar Semua Pesanan ─────────────────────────────────────

    public function index()

    {

        $orders = Order::query()

            ->where('user_id', Auth::id())

            ->latest()

            ->paginate(10);

        return view('pembeli.orders.index', compact('orders'));

    }

    public function refreshSnapToken(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        if ($order->status !== 'pending') {

            return response()->json([

                'success' => false,

                'message' => 'Token hanya bisa dibuat untuk pesanan yang masih menunggu pembayaran.',

            ], 422);

        }

        try {

            $snapToken = $this->midtrans->createSnapToken($order);

            $order->update(['snap_token' => $snapToken]);

            return response()->json([

                'success' => true,

                'snap_token' => $snapToken,

            ]);

        } catch (\Throwable $e) {

            Log::error('Midtrans Token Refresh Error: ' . $e->getMessage());

            return response()->json([

                'success' => false,

                'message' => 'Gagal membuat token pembayaran. Pastikan URL ngrok/APP_URL sudah benar dan kunci Midtrans valid.',

            ], 500);

        }
    }

    // ── Halaman Finish (Redirect dari Midtrans) ──────────────────

    public function paymentFinish(Request $request)

    {
        $orderId = $request->get('order_id');
        $transactionStatus = $request->get('transaction_status');

        if ($orderId) {
            preg_match('/ORDER-(\d+)-/', $orderId, $matches);
            $orderRealId = $matches[1] ?? null;

            if ($orderRealId) {
                $order = Order::query()
                    ->where('id', $orderRealId)
                    ->where('user_id', Auth::id())
                    ->first();

                if ($order) {
                    $this->syncPaymentStatus($order, $transactionStatus);

                    return redirect()->route('pembeli.orders.show', $order)
                        ->with('info', 'Status pembayaran telah diperbarui.');
                }
            }
        }

        return redirect()->route('pembeli.orders.index');

    }

    protected function syncPaymentStatus(Order $order, ?string $transactionStatus): void
    {
        if ($transactionStatus === 'settlement') {
            $order->update([
                'status' => 'diproses',
                'payment_status' => 'settlement',
                'paid_at' => now(),
            ]);
            return;
        }

        if (in_array($transactionStatus, ['pending', 'capture'], true)) {
            $order->update([
                'status' => 'pending',
                'payment_status' => $transactionStatus,
            ]);
            return;
        }

        $order->update([
            'status' => 'dibatalkan',
            'payment_status' => $transactionStatus ?? 'failed',
        ]);
    }

}

