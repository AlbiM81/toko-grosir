<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'address' => 'required|string',
            'phone'   => 'required|string|max:20',
        ]);

        $carts = Cart::with('product')
            ->where('user_id', auth::id())
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('pembeli.cart.index')
                ->with('error', 'Keranjang kosong.');
        }

        // Validasi stok
        foreach ($carts as $cart) {
            if ($cart->quantity > $cart->product->stock) {
                return back()->with('error', "Stok {$cart->product->name} tidak mencukupi.");
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

                // Kurangi stok
                $cart->product->decrement('stock', $cart->quantity);
            }

            // Kosongkan keranjang
            Cart::where('user_id', auth::id())->delete();
        });

        return redirect()->route('pembeli.orders.index')
            ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
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

    public function uploadBukti(Request $request, Order $order)
    {
        abort_if($order->user_id !== auth::id(), 403);
        abort_if($order->status !== 'pending', 403);

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'status'        => 'menunggu_verifikasi',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload.');
    }
}