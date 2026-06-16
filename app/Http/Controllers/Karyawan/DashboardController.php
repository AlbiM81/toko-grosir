<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = DB::table('orders');

        $orderMenunggu = (clone $orders)->where('status', 'menunggu_verifikasi')->count();
        $orderDiproses = (clone $orders)->where('status', 'diproses')->count();
        $orderDikirim  = (clone $orders)->where('status', 'dikirim')->count();

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