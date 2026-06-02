<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'orderDetails'])
            ->latest()
            ->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderDetails.product', 'processedBy']);
        return view('admin.orders.show', compact('order'));
    }

    public function laporan()
    {
        $totalPenjualan = Order::where('status', 'selesai')->sum('total_price');
        $orders = Order::where('status', 'selesai')
            ->with('user')
            ->latest()
            ->paginate(20);
        return view('admin.laporan', compact('totalPenjualan', 'orders'));
    }
}