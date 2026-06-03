@extends('layouts.pembeli')

@section('title','Checkout')

@section('content')

<div class="container py-4">

<h3 class="fw-bold mb-4">
Checkout
</h3>

<form method="POST"
      action="{{ route('pembeli.orders.store') }}">

@csrf

<div class="card mb-4">

<div class="card-body">

<div class="mb-3">

<label>Alamat</label>

<textarea name="address"
          class="form-control"
          rows="4"
          required></textarea>

</div>

<div class="mb-3">

<label>Nomor HP</label>

<input type="text"
       name="phone"
       class="form-control"
       required>

</div>

</div>

</div>

<div class="card">

<div class="card-body">

<h5>Total:
Rp {{ number_format($total,0,',','.') }}
</h5>

<button class="btn btn-success">
Buat Pesanan
</button>

</div>

</div>

</form>

</div>

@endsection