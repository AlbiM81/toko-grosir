<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $orderMenunggu = Order::where('status', 'menunggu_verifikasi')->count();
        $orderDiproses = Order::where('status', 'diproses')->count();
        $orderDikirim  = Order::where('status', 'dikirim')->count();

        $orderTerbaru = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('karyawan.dashboard', compact(
            'orderMenunggu',
            'orderDiproses',
            'orderDikirim',
            'orderTerbaru'
        ));
    }
}