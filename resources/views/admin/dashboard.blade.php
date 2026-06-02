@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                    <i class="bi bi-currency-dollar fs-4 text-success"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Penjualan</div>
                    <div class="fw-bold fs-5">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-receipt fs-4 text-primary"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Order</div>
                    <div class="fw-bold fs-5">{{ $totalOrder }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                    <i class="bi bi-box-seam fs-4 text-warning"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Produk</div>
                    <div class="fw-bold fs-5">{{ $totalProduk }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                    <i class="bi bi-people fs-4 text-info"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Pembeli</div>
                    <div class="fw-bold fs-5">{{ $totalPembeli }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="fw-bold mb-0">Transaksi Terbaru</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Pembeli</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderTerbaru as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status_badge }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Belum ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="fw-bold mb-0 text-danger">⚠ Stok Rendah</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($produkStokRendah as $produk)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="small">{{ $produk->name }}</span>
                        <span class="badge bg-danger">{{ $produk->stock }}</span>
                    </li>
                    @empty
                    <li class="list-group-item text-muted small text-center">Semua stok aman</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection