@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $order->id)

@section('content')

<div class="row g-4">

    {{-- Detail Produk --}}
    <div class="col-md-8">

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-cart-check me-2"></i>
                    Detail Produk
                </h5>
            </div>

            <div class="card-body p-0">

                <table class="table table-hover mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($order->orderDetails as $detail)

                        <tr>

                            <td>
                                {{ $detail->product->name }}
                            </td>

                            <td>
                                {{ $detail->quantity }}
                            </td>

                            <td>
                                Rp {{ number_format($detail->product->price,0,',','.') }}
                            </td>

                            <td>
                                Rp {{ number_format($detail->subtotal,0,',','.') }}
                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                    <tfoot>

                        <tr class="table-light fw-bold">

                            <td colspan="3" class="text-end">
                                Total
                            </td>

                            <td>
                                Rp {{ number_format($order->total_price,0,',','.') }}
                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

        {{-- Bukti Pembayaran --}}
        @if($order->payment_proof)

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-image me-2"></i>
                    Bukti Pembayaran
                </h5>
            </div>

            <div class="card-body text-center">

                <img
                    src="{{ asset('storage/' . $order->payment_proof) }}"
                    class="img-fluid rounded shadow-sm"
                    style="max-height:500px;"
                    alt="Bukti Pembayaran">

            </div>

        </div>

        @endif

    </div>

    {{-- Informasi Pesanan --}}
    <div class="col-md-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Informasi Pesanan
                </h5>
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <small class="text-muted">ID Order</small>
                    <div class="fw-bold">
                        #{{ $order->id }}
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Pembeli</small>
                    <div class="fw-bold">
                        {{ $order->user->name }}
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Email</small>
                    <div>
                        {{ $order->user->email }}
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">No Telepon</small>
                    <div>
                        {{ $order->phone }}
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Alamat Pengiriman</small>
                    <div>
                        {{ $order->address }}
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Status</small>

                    <div>

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

                    </div>

                </div>

                <div class="mb-3">
                    <small class="text-muted">Tanggal Pesanan</small>
                    <div>
                        {{ $order->created_at->format('d M Y H:i') }}
                    </div>
                </div>

                @if($order->processedBy)

                <div class="mb-3">
                    <small class="text-muted">Diproses Oleh</small>
                    <div>
                        {{ $order->processedBy->name }}
                    </div>
                </div>

                @endif

                <hr>

                <div class="d-grid">

                    <a href="{{ route('admin.orders.index') }}"
                       class="btn btn-secondary">

                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection