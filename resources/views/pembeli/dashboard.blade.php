@extends('layouts.pembeli')
@section('title', 'Dashboard Pembeli')
@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4">Halo, {{ auth()->user()->name }}! 👋</h4>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="bi bi-bag-check fs-2 text-success"></i>
                    <h4 class="fw-bold mt-2">{{ $totalOrder }}</h4>
                    <p class="text-muted mb-0">Total Pesanan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="bi bi-truck fs-2 text-primary"></i>
                    <h4 class="fw-bold mt-2">{{ $orderDikirim }}</h4>
                    <p class="text-muted mb-0">Sedang Dikirim</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="bi bi-cart fs-2 text-warning"></i>
                    <h4 class="fw-bold mt-2">{{ $itemKeranjang }}</h4>
                    <p class="text-muted mb-0">Item di Keranjang</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <a href="{{ route('pembeli.products.index') }}" class="btn btn-primary">
            <i class="bi bi-shop me-1"></i> Belanja Sekarang
        </a>
        <a href="{{ route('pembeli.orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-receipt me-1"></i> Riwayat Pesanan
        </a>
    </div>
</div>
@endsection