<?php

// app/Services/MidtransService.php

namespace App\Services;

use App\Models\Order;

use Illuminate\Http\Request;

use Midtrans\Config;

use Midtrans\Snap;

use Midtrans\Transaction;

class MidtransService

{

    public function __construct(protected ?Request $request = null)

    {

        // Konfigurasi Midtrans dari config/midtrans.php

        Config::$serverKey    = config('midtrans.server_key');

        Config::$isProduction = config('midtrans.is_production');

        Config::$isSanitized  = config('midtrans.is_sanitized');

        Config::$is3ds        = config('midtrans.is_3ds');

    }

    /**

     * Buat Snap Token untuk membuka popup Midtrans

     * Dipanggil saat pembeli checkout

     */

    public function createSnapToken(Order $order): string

    {

        $order->load(['user', 'orderDetails.product']);

        // Siapkan item details

        $itemDetails = $order->orderDetails->map(function ($detail) {

            return [

                'id'       => (string) $detail->product_id,

                'price'    => (int) $detail->product->price,

                'quantity' => $detail->quantity,

                'name'     => substr($detail->product->name, 0, 50), // max 50 karakter

            ];

        })->toArray();

        // Parameter transaksi

        $params = [

            'transaction_details' => [

                'order_id'      => 'ORDER-' . $order->id . '-' . time(), // unik setiap request

                'gross_amount'  => (int) $order->total_price,

            ],

            'customer_details' => [

                'first_name' => $order->user->name,

                'email'      => $order->user->email,

                'phone'      => $order->phone,

                'billing_address' => [

                    'address' => $order->address,

                ],

                'shipping_address' => [

                    'address' => $order->address,

                ],

            ],

            'item_details' => $itemDetails,

            // Batasi metode pembayaran (opsional, hapus untuk tampilkan semua)

            'enabled_payments' => [

                'credit_card',

                'mandiri_clickpay',

                'cimb_clicks',

                'bca_klikbca',

                'bca_klikpay',

                'bri_epay',

                'echannel',

                'permata_va',

                'bca_va',

                'bni_va',

                'bri_va',

                'other_va',

                'gopay',

                'indomaret',

                'danamon_online',

                'akulaku',

                'shopeepay',

                'qris',

            ],

            // Konfigurasi Snap

            'callbacks' => [

                'finish' => $this->getFinishCallbackUrl(),

            ],

        ];

        return Snap::getSnapToken($params);

    }

    protected function getFinishCallbackUrl(): string
    {
        if ($this->request instanceof Request) {

            return rtrim($this->request->root(), '/') . '/pembeli/orders/payment/finish';

        }

        return url('/pembeli/orders/payment/finish');
    }

    /**

     * Cek status transaksi dari Midtrans

     * Dipanggil di webhook/notification handler

     */

    public function getTransactionStatus(string $orderId): array

    {

        return Transaction::status($orderId);

    }

    /**

     * Verifikasi signature key dari webhook Midtrans

     * Untuk memastikan notifikasi benar-benar dari Midtrans

     */

    public function verifySignature(

        string $orderId,

        string $statusCode,

        string $grossAmount,

        string $receivedSignature

    ): bool {

        $serverKey         = config('midtrans.server_key');

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expectedSignature, $receivedSignature);

    }

}
