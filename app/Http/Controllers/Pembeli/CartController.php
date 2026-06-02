<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product')
            ->where('user_id', auth::id())
            ->get();

        $total = $carts->sum(fn($c) => $c->quantity * $c->product->price);

        return view('pembeli.cart.index', compact('carts', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $cart = Cart::where('user_id', auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            $newQty = $cart->quantity + $request->quantity;
            if ($newQty > $product->stock) {
                return back()->with('error', 'Stok tidak mencukupi.');
            }
            $cart->update(['quantity' => $newQty]);
        } else {
            Cart::create([
                'user_id'    => auth::id(),
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
            ]);
        }

        return redirect()->route('pembeli.cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function remove(Cart $cart)
    {
        abort_if($cart->user_id !== auth::id(), 403);
        $cart->delete();
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function update(Request $request, Cart $cart)
    {
        abort_if($cart->user_id !== auth::id(), 403);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cart->product->stock,
        ]);

        $cart->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Keranjang diperbarui.');
    }
}