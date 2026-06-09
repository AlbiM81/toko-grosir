<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = Illuminate\Foundation\Application::getInstance();
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

\Midtrans\Config::$serverKey = config('midtrans.server_key');
\Midtrans\Config::$isProduction = config('midtrans.is_production');
\Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
\Midtrans\Config::$is3ds = config('midtrans.is_3ds');

$params = [
  'transaction_details' => ['order_id' => 'TEST-' . time(), 'gross_amount' => 10000],
  'customer_details' => ['first_name' => 'Test', 'email' => 'test@example.com', 'phone' => '081234567890'],
  'item_details' => [['id' => '1', 'price' => 10000, 'quantity' => 1, 'name' => 'Test']],
];

try {
  $token = \Midtrans\Snap::getSnapToken($params);
  echo 'TOKEN_OK=' . substr($token, 0, 60) . PHP_EOL;
} catch (Throwable $e) {
  echo 'TOKEN_ERR=' . $e->getMessage() . PHP_EOL;
  echo 'TOKEN_TRACE=' . $e->getTraceAsString() . PHP_EOL;
}
