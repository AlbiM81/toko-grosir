{{-- resources/views/admin/laporan.blade.php --}}
@extends('layouts.app')
@section('title', 'Laporan Penjualan')

@section('content')

{{-- Filter Form --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3"><i class="bi bi-funnel me-2"></i>Filter Laporan</h6>
        <form method="GET" action="{{ route('admin.laporan') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control"
                       value="{{ $tanggalMulai }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control"
                       value="{{ $tanggalAkhir }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    @foreach(['selesai'=>'Selesai','semua'=>'Semua Status','pending'=>'Pending','menunggu_verifikasi'=>'Menunggu Verifikasi','diproses'=>'Diproses','dikirim'=>'Dikirim'] as $val => $label)
                    <option value="{{ $val }}" {{ $status === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-4" style="border-radius:16px;background:linear-gradient(135deg,#1a73e8,#0f4c81);color:white;">
            <i class="bi bi-currency-dollar fs-2 mb-2"></i>
            <div class="fw-bold fs-4">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
            <div class="opacity-75 small">Total Penjualan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-4" style="border-radius:16px;background:linear-gradient(135deg,#10b981,#059669);color:white;">
            <i class="bi bi-receipt fs-2 mb-2"></i>
            <div class="fw-bold fs-4">{{ number_format($totalOrder) }}</div>
            <div class="opacity-75 small">Jumlah Transaksi</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-4" style="border-radius:16px;background:linear-gradient(135deg,#f59e0b,#d97706);color:white;">
            <i class="bi bi-calculator fs-2 mb-2"></i>
            <div class="fw-bold fs-4">
                Rp {{ $totalOrder > 0 ? number_format($totalPenjualan / $totalOrder, 0, ',', '.') : 0 }}
            </div>
            <div class="opacity-75 small">Rata-rata per Transaksi</div>
        </div>
    </div>
</div>

{{-- Tabel Laporan + Tombol Export --}}
<div class="card border-0 shadow-sm" style="border-radius:16px;">
    <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">Data Transaksi</h6>

        {{-- TOMBOL EXPORT --}}
        <div class="d-flex gap-2">
            {{-- Export Excel --}}
            <a href="{{ route('admin.laporan.export-excel', request()->query()) }}"
               class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
            </a>

            {{-- Export PDF --}}
            <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}"
               class="btn btn-danger btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="px-4 py-3 border-0 text-muted small">NO. ORDER</th>
                        <th class="py-3 border-0 text-muted small">PEMBELI</th>
                        <th class="py-3 border-0 text-muted small">ALAMAT</th>
                        <th class="py-3 border-0 text-muted small">TOTAL</th>
                        <th class="py-3 border-0 text-muted small">STATUS</th>
                        <th class="py-3 border-0 text-muted small">TANGGAL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="fw-semibold text-primary text-decoration-none">
                                #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>
                        <td class="py-3">{{ $order->user->name }}</td>
                        <td class="py-3 text-muted small">{{ Str::limit($order->address, 35) }}</td>
                        <td class="py-3 fw-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="py-3">
                            <span class="badge bg-{{ $order->status_badge }}">{{ $order->status_label }}</span>
                        </td>
                        <td class="py-3 text-muted small">{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Tidak ada data untuk periode ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 px-4">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>
@endsection