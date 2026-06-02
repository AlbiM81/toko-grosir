@extends('layouts.app')
@section('title', 'Dashboard Karyawan')
@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
            <div class="card-body text-center py-4">
                <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                <h4 class="fw-bold mt-2">{{ $orderMenunggu }}</h4>
                <p class="text-muted mb-0">Menunggu Verifikasi</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
            <div class="card-body text-center py-4">
                <i class="bi bi-gear fs-2 text-primary"></i>
                <h4 class="fw-bold mt-2">{{ $orderDiproses }}</h4>
                <p class="text-muted mb-0">Sedang Diproses</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success bg-opacity-10">
            <div class="card-body text-center py-4">
                <i class="bi bi-truck fs-2 text-success"></i>
                <h4 class="fw-bold mt-2">{{ $orderDikirim }}</h4>
                <p class="text-muted mb-0">Sedang Dikirim</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between">
        <h6 class="fw-bold mb-0">Pesanan Terbaru</h6>
        <a href="{{ route('karyawan.orders.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>ID</th><th>Pembeli</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($orderTerbaru as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td><span class="badge bg-{{ $order->status_badge }}">{{ $order->status_label }}</span></td>
                    <td>
                        <a href="{{ route('karyawan.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada pesanan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

