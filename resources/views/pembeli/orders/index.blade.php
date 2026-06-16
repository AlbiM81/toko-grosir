{{-- resources/views/pembeli/orders/index.blade.php --}}
@extends('layouts.pembeli')
@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-4">
    <h5 class="fw-bold mb-4"><i class="bi bi-receipt me-2"></i>Riwayat Pesanan</h5>

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-bag-x fs-1 text-muted"></i>
            <p class="text-muted mt-2">Belum ada pesanan</p>
            <a href="{{ route('pembeli.products.index') }}" class="btn btn-primary">Mulai Belanja</a>
        </div>
    @else
        @foreach($orders as $order)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-bold">Pesanan #{{ $order->id }}</div>
                        <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                    </div>
                    <span class="badge bg-{{ $order->status_badge }}">{{ $order->status_label }}</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="fw-semibold text-primary">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pembeli.orders.show', $order) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection