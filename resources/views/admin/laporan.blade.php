@extends('layouts.app')
@section('title','Laporan Penjualan')

@section('content')

<div class="row g-4 mb-4">

    <div class="col-md-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center">

                <i class="bi bi-cash-stack fs-1 text-success"></i>

                <h3 class="fw-bold mt-3">
                    Rp {{ number_format($totalPenjualan,0,',','.') }}
                </h3>

                <p class="text-muted mb-0">
                    Total Penjualan
                </p>

            </div>

        </div>

    </div>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white">
        <h5 class="fw-bold mb-0">
            Riwayat Penjualan
        </h5>
    </div>

    <div class="card-body p-0">

        <table class="table table-hover">

            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Pembeli</th>
                <th>Total</th>
                <th>Tanggal</th>
            </tr>
            </thead>

            <tbody>

            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td>
                <td>Rp {{ number_format($order->total_price,0,',','.') }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection