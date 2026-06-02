@extends('layouts.pembeli')
@section('title', 'Keranjang Belanja')
@section('content')
<div class="container py-4">
    <h5 class="fw-bold mb-4"><i class="bi bi-cart me-2"></i>Keranjang Belanja</h5>

    @if($carts->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-cart-x fs-1 text-muted"></i>
            <p class="text-muted mt-2">Keranjang Anda kosong</p>
            <a href="{{ route('pembeli.products.index') }}" class="btn btn-primary">Belanja Sekarang</a>
        </div>
    @else
        <div class="row g-4">
            <div class="col-md-8">
                @foreach($carts as $cart)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body d-flex align-items-center gap-3">
                        <img src="{{ $cart->product->image_url }}"
                             style="width:80px;height:80px;object-fit:cover;border-radius:8px;" alt="">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $cart->product->name }}</h6>
                            <p class="text-muted mb-0">Rp {{ number_format($cart->product->price, 0, ',', '.') }}</p>
                        </div>
                        <form method="POST" action="{{ route('pembeli.cart.update', $cart) }}" class="d-flex align-items-center gap-2">
                            @csrf @method('PATCH')
                            <input type="number" name="quantity" value="{{ $cart->quantity }}"
                                   min="1" max="{{ $cart->product->stock }}"
                                   class="form-control form-control-sm" style="width:70px;">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
                        </form>
                        <div class="fw-bold">Rp {{ number_format($cart->quantity * $cart->product->price, 0, ',', '.') }}</div>
                        <form method="POST" action="{{ route('pembeli.cart.remove', $cart) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Ringkasan Belanja</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                            <span>Total</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('pembeli.orders.checkout') }}" class="btn btn-primary w-100">
                            Checkout <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection