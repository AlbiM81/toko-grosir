@extends('layouts.pembeli')

@section('title','Produk')

@section('content')

<div class="container py-4">

    <h3 class="fw-bold mb-4">
        Katalog Produk
    </h3>

    <div class="row">

        @forelse($products as $product)

        <div class="col-md-3 mb-4">

            <div class="card h-100">

                <img src="{{ $product->image_url }}"
                     class="card-img-top"
                     style="height:220px;object-fit:cover;">

                <div class="card-body">

                    <h6 class="fw-bold">
                        {{ $product->name }}
                    </h6>

                    <p class="text-muted small">
                        {{ $product->category->name }}
                    </p>

                    <p class="fw-bold text-primary">
                        Rp {{ number_format($product->price,0,',','.') }}
                    </p>

                    <a href="{{ route('pembeli.products.show',$product) }}"
                       class="btn btn-primary w-100">
                        Detail
                    </a>

                </div>
            </div>

        </div>

        @empty

        <div class="col-12">
            <div class="alert alert-info">
                Tidak ada produk.
            </div>
        </div>

        @endforelse

    </div>

    {{ $products->links() }}

</div>

@endsection