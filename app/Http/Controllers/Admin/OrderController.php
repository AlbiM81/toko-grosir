<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\LaporanPenjualanExport;
use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; // opsional, untuk PDF

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderDetails']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderDetails.product', 'processedBy']);
        return view('admin.orders.show', compact('order'));
    }

    // ── Halaman Laporan ──────────────────────────────────────────────
    public function laporan(Request $request)
    {
        $tanggalMulai = $request->get('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->get('tanggal_akhir', now()->format('Y-m-d'));
        $status       = $request->get('status', 'selesai');

        $query = Order::with(['user', 'orderDetails'])
            ->whereBetween('created_at', [
                $tanggalMulai . ' 00:00:00',
                $tanggalAkhir . ' 23:59:59',
            ]);

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $orders         = $query->latest()->paginate(20)->withQueryString();
        $totalPenjualan = $query->sum('total_price');
        $totalOrder     = $query->count();

        return view('admin.laporan', compact(
            'orders',
            'totalPenjualan',
            'totalOrder',
            'tanggalMulai',
            'tanggalAkhir',
            'status'
        ));
    }

    // ── Export Excel ─────────────────────────────────────────────────
    public function exportExcel(Request $request)
    {
        $tanggalMulai = $request->get('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->get('tanggal_akhir', now()->format('Y-m-d'));
        $status       = $request->get('status', 'selesai');

        $namaFile = 'laporan-penjualan-' . $tanggalMulai . '-sd-' . $tanggalAkhir . '.xlsx';

        return Excel::download(
            new LaporanPenjualanExport($tanggalMulai, $tanggalAkhir, $status),
            $namaFile
        );
    }

    // ── Export PDF (pakai DomPDF) ────────────────────────────────────
    public function exportPdf(Request $request)
    {
        $tanggalMulai = $request->get('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->get('tanggal_akhir', now()->format('Y-m-d'));
        $status       = $request->get('status', 'selesai');

        $query = Order::with(['user', 'orderDetails'])
            ->whereBetween('created_at', [
                $tanggalMulai . ' 00:00:00',
                $tanggalAkhir . ' 23:59:59',
            ]);

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $orders         = $query->orderBy('created_at')->get();
        $totalPenjualan = $orders->sum('total_price');

        $pdf = Pdf::loadView('exports.laporan_pdf', compact(
            'orders', 'totalPenjualan', 'tanggalMulai', 'tanggalAkhir', 'status'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan-' . $tanggalMulai . '.pdf');
    }
}