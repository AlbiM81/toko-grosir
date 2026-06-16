<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Karyawan;
use App\Http\Controllers\Pembeli;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';
use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// ✅ FIX: Route Midtrans webhook (tanpa CSRF / di luar middleware auth)
Route::post('/midtrans/notification', [\App\Http\Controllers\MidtransController::class, 'notification'])
    ->name('midtrans.notification')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);


Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('categories', Admin\CategoryController::class);

    Route::get('/karyawan', [Admin\KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/karyawan/create', [Admin\KaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('/karyawan', [Admin\KaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('/karyawan/{karyawan}/edit', [Admin\KaryawanController::class, 'edit'])->name('karyawan.edit');
    Route::put('/karyawan/{karyawan}', [Admin\KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{karyawan}', [Admin\KaryawanController::class, 'destroy'])->name('karyawan.destroy');

    Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');

    Route::get('/pembeli', [Admin\PembeliController::class, 'index'])->name('pembeli.index');

    // ✅ FIX: Hapus duplikat route laporan, sisakan satu set saja
    Route::get('/laporan', [Admin\OrderController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/export-excel', [Admin\OrderController::class, 'exportExcel'])->name('laporan.export-excel');
    Route::get('/laporan/export-pdf', [Admin\OrderController::class, 'exportPdf'])->name('laporan.export-pdf');
});


Route::prefix('karyawan')
    ->name('karyawan.')
    ->middleware(['auth', 'role:karyawan'])
    ->group(function () {

    Route::get('/dashboard', [Karyawan\DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('products', Karyawan\ProductController::class);

    Route::get('/orders', [Karyawan\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [Karyawan\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/verifikasi', [Karyawan\OrderController::class, 'verifikasiPembayaran'])->name('orders.verifikasi');
    Route::patch('/orders/{order}/bayar-selesai', [Karyawan\OrderController::class, 'selesaikanPembayaran'])->name('orders.bayar-selesai');
    Route::patch('/orders/{order}/kirim', [Karyawan\OrderController::class, 'kirimBarang'])->name('orders.kirim');
    Route::patch('/orders/{order}/selesai', [Karyawan\OrderController::class, 'selesai'])->name('orders.selesai');
});


Route::prefix('pembeli')
    ->name('pembeli.')
    ->middleware(['auth', 'role:pembeli'])
    ->group(function () {

    Route::get('/dashboard', [Pembeli\DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/products', [Pembeli\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [Pembeli\ProductController::class, 'show'])->name('products.show');

    Route::get('/cart', [Pembeli\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [Pembeli\CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{cart}', [Pembeli\CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/{cart}', [Pembeli\CartController::class, 'update'])->name('cart.update');

    Route::get('/checkout', [Pembeli\OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders', [Pembeli\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [Pembeli\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [Pembeli\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/refresh-snap-token', [Pembeli\OrderController::class, 'refreshSnapToken'])
        ->name('orders.refresh-snap-token');

    // ✅ FIX: tambahkan route payment-finish yang dipakai MidtransService callback
    Route::get('/orders/payment/finish', [Pembeli\OrderController::class, 'paymentFinish'])
        ->name('orders.payment-finish');

    Route::post('/orders/{order}/upload-bukti', [Pembeli\OrderController::class, 'uploadBukti'])
        ->name('orders.upload-bukti');
});
