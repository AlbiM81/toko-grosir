<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderDetails']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('karyawan.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderDetails.product']);
        return view('karyawan.orders.show', compact('order'));
    }

    public function verifikasiPembayaran(Order $order)
    {
        abort_if($order->status !== 'menunggu_verifikasi', 403);

        $order->update([
            'status'       => 'diproses',
            'processed_by' => auth::id(),
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi. Pesanan sedang diproses.');
    }

    public function kirimBarang(Order $order)
    {
        abort_if($order->status !== 'diproses', 403);

        $order->update(['status' => 'dikirim']);

        return back()->with('success', 'Status pesanan berubah menjadi "Dikirim".');
    }

    public function selesai(Order $order)
    {
        abort_if($order->status !== 'dikirim', 403);

        $order->update(['status' => 'selesai']);

        return back()->with('success', 'Pesanan telah selesai.');
    }
}