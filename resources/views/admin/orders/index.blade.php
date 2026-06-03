@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('content')

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-receipt-cutoff me-2"></i>
                Data Transaksi
            </h5>

            <span class="badge bg-primary fs-6">
                Total: {{ $orders->total() }}
            </span>
        </div>
    </div>

    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>ID Order</th>
                    <th>Pembeli</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($orders as $order)

                <tr>

                    <td>
                        <strong>#{{ $order->id }}</strong>
                    </td>

                    <td>
                        {{ $order->user->name }}
                    </td>

                    <td>
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>

                    <td>

                        @php
                            $badge = match($order->status){
                                'pending' => 'warning',
                                'menunggu_verifikasi' => 'info',
                                'diproses' => 'primary',
                                'dikirim' => 'secondary',
                                'selesai' => 'success',
                                default => 'dark'
                            };
                        @endphp

                        <span class="badge bg-{{ $badge }}">
                            {{ ucfirst(str_replace('_',' ', $order->status)) }}
                        </span>

                    </td>

                    <td>
                        {{ $order->created_at->format('d M Y H:i') }}
                    </td>

                    <td>

                        <a href="{{ route('admin.orders.show',$order) }}"
                           class="btn btn-sm btn-outline-primary">

                            <i class="bi bi-eye"></i>
                            Detail

                        </a>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        Belum ada transaksi
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if($orders->hasPages())
    <div class="card-footer bg-white">
        {{ $orders->links() }}
    </div>
    @endif

</div>

@endsection