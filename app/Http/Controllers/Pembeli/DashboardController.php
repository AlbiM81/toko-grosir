<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth::id();

        $totalOrder   = Order::where('user_id', $userId)->count();
        $orderDikirim = Order::where('user_id', $userId)->where('status', 'dikirim')->count();
        $itemKeranjang = Cart::where('user_id', $userId)->sum('quantity');

        return view('pembeli.dashboard', compact('totalOrder', 'orderDikirim', 'itemKeranjang'));
    }
}