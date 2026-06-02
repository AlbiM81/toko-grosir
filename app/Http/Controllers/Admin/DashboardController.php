<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPenjualan = Order::where('status', 'selesai')->sum('total_price');
        $totalOrder     = Order::count();
        $totalProduk    = Product::count();
        $totalPembeli   = User::where('role', 'pembeli')->count();
        $stokHabis      = Product::where('stock', '<=', 5)->count();

        $orderTerbaru = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $produkStokRendah = Product::where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPenjualan',
            'totalOrder',
            'totalProduk',
            'totalPembeli',
            'stokHabis',
            'orderTerbaru',
            'produkStokRendah'
        ));
    }
}