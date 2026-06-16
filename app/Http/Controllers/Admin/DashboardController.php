<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── STATISTIK UTAMA ─────────────────────────────────────────
        $totalPenjualan = Order::where('status', 'selesai')->sum('total_price');
        $totalOrder     = Order::count();
        $totalProduk    = Product::count();
        $totalPembeli   = User::where('role', 'pembeli')->count();
        $totalKaryawan  = User::where('role', 'karyawan')->count();
        $stokHabis      = Product::where('stock', 0)->count();
        $stokRendah     = Product::where('stock', '>', 0)->where('stock', '<=', 10)->count();

        // Order menunggu aksi
        $orderPending      = Order::where('status', 'pending')->count();
        $orderMenunggu     = Order::where('status', 'menunggu_verifikasi')->count();
        $orderDiproses     = Order::where('status', 'diproses')->count();
        $orderDikirim      = Order::where('status', 'dikirim')->count();
        $orderSelesai      = Order::where('status', 'selesai')->count();

        // ── CHART 1: Penjualan 7 Hari Terakhir (Line Chart) ─────────
        $penjualan7Hari = Order::where('status', 'selesai')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total_price) as total'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        // Lengkapi hari yang tidak ada data (isi 0)
        $chartLabel7Hari  = [];
        $chartPenjualan7  = [];
        $chartJumlahOrder = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->format('Y-m-d');
            $chartLabel7Hari[]  = now()->subDays($i)->format('d M');
            $chartPenjualan7[]  = $penjualan7Hari->get($tgl)?->total ?? 0;
            $chartJumlahOrder[] = $penjualan7Hari->get($tgl)?->jumlah ?? 0;
        }

        // ── CHART 2: Penjualan 12 Bulan Terakhir (Bar Chart) ────────
        $penjualan12Bulan = Order::where('status', 'selesai')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(total_price) as total'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get()
            ->keyBy(fn($item) => $item->tahun . '-' . str_pad($item->bulan, 2, '0', STR_PAD_LEFT));

        $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $chartLabel12Bulan  = [];
        $chartPenjualan12   = [];
        for ($i = 11; $i >= 0; $i--) {
            $dt  = now()->subMonths($i);
            $key = $dt->format('Y-m');
            $chartLabel12Bulan[]  = $namaBulan[$dt->month - 1] . ' ' . $dt->year;
            $chartPenjualan12[]   = $penjualan12Bulan->get($key)?->total ?? 0;
        }

        // ── CHART 3: Status Pesanan (Donut/Pie Chart) ────────────────
        $chartStatusLabel = ['Pending', 'Menunggu Verifikasi', 'Diproses', 'Dikirim', 'Selesai'];
        $chartStatusData  = [$orderPending, $orderMenunggu, $orderDiproses, $orderDikirim, $orderSelesai];
        $chartStatusColor = ['#f59e0b', '#3b82f6', '#8b5cf6', '#06b6d4', '#10b981'];

        // ── CHART 4: Produk Terlaris (Horizontal Bar) ────────────────
        $produkTerlaris = OrderDetail::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as terjual'))
            ->groupBy('product_id')
            ->orderByDesc('terjual')
            ->take(8)
            ->get();

        $chartProdukLabel  = $produkTerlaris->map(fn($p) => $p->product?->name ?? 'Dihapus')->toArray();
        $chartProdukData   = $produkTerlaris->map(fn($p) => (int)$p->terjual)->toArray();

        // ── CHART 5: Stok per Kategori (Radar) ──────────────────────
        $stokKategori = Category::with('products')
            ->withSum('products', 'stock')
            ->get();
        $chartKatLabel = $stokKategori->pluck('name')->toArray();
        $chartKatData  = $stokKategori->map(fn($k) => (int)$k->products_sum_stock)->toArray();

        // ── TABEL: Transaksi Terbaru ─────────────────────────────────
        $orderTerbaru = Order::with('user')
            ->latest()
            ->take(8)
            ->get();

        // ── TABEL: Stok Rendah ───────────────────────────────────────
        $produkStokRendah = Product::with('category')
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            // Statistik
            'totalPenjualan', 'totalOrder', 'totalProduk', 'totalPembeli',
            'totalKaryawan', 'stokHabis', 'stokRendah',
            'orderPending', 'orderMenunggu', 'orderDiproses', 'orderDikirim', 'orderSelesai',
            // Chart data
            'chartLabel7Hari', 'chartPenjualan7', 'chartJumlahOrder',
            'chartLabel12Bulan', 'chartPenjualan12',
            'chartStatusLabel', 'chartStatusData', 'chartStatusColor',
            'chartProdukLabel', 'chartProdukData',
            'chartKatLabel', 'chartKatData',
            // Tabel
            'orderTerbaru', 'produkStokRendah'
        ));
    }
}