<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function checkout()
    {
        $carts = Cart::with('product')
            ->where('user_id', auth::id())
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('pembeli.cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $total = $carts->sum(fn($c) => $c->quantity * $c->product->price);

        return view('pembeli.orders.checkout', compact('carts', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:500',
            'phone'   => 'required|string|max:20|regex:/^[0-9+\-\s]+$/',
        ]);

        $carts = Cart::with('product')
            ->where('user_id', auth::id())
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('pembeli.cart.index')
                ->with('error', 'Keranjang kosong.');
        }

        foreach ($carts as $cart) {
            if ($cart->quantity > $cart->product->stock) {
                return back()->with('error', "Stok produk '{$cart->product->name}' tidak mencukupi. Tersedia: {$cart->product->stock}");
            }
        }

        DB::transaction(function () use ($request, $carts) {
            $total = $carts->sum(fn($c) => $c->quantity * $c->product->price);

            $order = Order::create([
                'user_id'     => auth::id(),
                'total_price' => $total,
                'status'      => 'pending',
                'address'     => $request->address,
                'phone'       => $request->phone,
            ]);

            foreach ($carts as $cart) {
                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity'   => $cart->quantity,
                    'subtotal'   => $cart->quantity * $cart->product->price,
                ]);
                $cart->product->decrement('stock', $cart->quantity);
            }

            Cart::where('user_id', auth::id())->delete();
        });

        return redirect()->route('pembeli.orders.index')
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran dan upload bukti transfer.');
    }

    public function index()
    {
        $orders = Order::where('user_id', auth::id())
            ->latest()
            ->paginate(10);

        return view('pembeli.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth::id(), 403);
        $order->load('orderDetails.product');

        return view('pembeli.orders.show', compact('order'));
    }

    /**
     * Upload bukti pembayaran
     */
    public function uploadBukti(Request $request, Order $order)
    {
        // Pastikan pesanan milik pembeli yang login
        abort_if($order->user_id !== auth::id(), 403);

        // Hanya bisa upload jika status masih pending
        abort_if($order->status !== 'pending', 403, 'Bukti pembayaran sudah pernah diupload.');

        $request->validate([
            'payment_proof' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048', // max 2MB
            ],
        ], [
            'payment_proof.required' => 'File bukti pembayaran wajib diupload.',
            'payment_proof.image'    => 'File harus berupa gambar.',
            'payment_proof.mimes'    => 'Format file harus JPG atau PNG.',
            'payment_proof.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        // Hapus bukti lama jika ada (seharusnya tidak ada, tapi berjaga-jaga)
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        // Simpan file baru
        $path = $request->file('payment_proof')
            ->store('payment_proofs/' . date('Y/m'), 'public');

        $order->update([
            'payment_proof' => $path,
            'status'        => 'menunggu_verifikasi',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload! Karyawan akan memverifikasi pembayaran Anda.');
    }
}