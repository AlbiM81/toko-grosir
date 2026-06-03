@extends('layouts.pembeli')

@section('title',$product->name)

@section('content')

<div class="container py-4">

<div class="row">

<div class="col-md-5">

<img src="{{ $product->image_url }}"
     class="img-fluid rounded shadow">

</div>

<div class="col-md-7">

<h3>{{ $product->name }}</h3>

<p class="text-muted">
    {{ $product->category->name }}
</p>

<h4 class="text-primary">
    Rp {{ number_format($product->price,0,',','.') }}
</h4>

<p>
    {{ $product->description }}
</p>

<p>
    Stok :
    <strong>{{ $product->stock }}</strong>
</p>

<form method="POST"
      action="{{ route('pembeli.cart.add',$product) }}">
    @csrf

    <div class="mb-3">
        <label>Jumlah</label>

        <input type="number"
               name="quantity"
               value="1"
               min="1"
               max="{{ $product->stock }}"
               class="form-control">
    </div>

    <button class="btn btn-success">
        Tambah ke Keranjang
    </button>

</form>

</div>

</div>

</div>

@endsection