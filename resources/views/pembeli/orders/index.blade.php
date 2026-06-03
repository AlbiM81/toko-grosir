@extends('layouts.pembeli')

@section('title','Pesanan')

@section('content')

<div class="container py-4">

<h3 class="fw-bold mb-4">
    Riwayat Pesanan
</h3>

@forelse($orders as $order)

<div class="card mb-3">

<div class="card-body">

<div class="d-flex justify-content-between">

<div>

<h5>
Pesanan #{{ $order->id }}
</h5>

<p>
Rp {{ number_format($order->total_price,0,',','.') }}
</p>

</div>

<div>

<span class="badge bg-primary">
{{ $order->status_label }}
</span>

</div>

</div>

<a href="{{ route('pembeli.orders.show',$order) }}"
   class="btn btn-outline-primary">
    Detail
</a>

</div>

</div>

@empty

<div class="alert alert-info">
Belum ada pesanan.
</div>

@endforelse

{{ $orders->links() }}

</div>

@endsection