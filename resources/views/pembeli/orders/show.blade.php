@extends('layouts.pembeli')
@section('title', 'Pesanan #' . $order->id)
@section('content')
<div class="container py-4">
    <h5 class="fw-bold mb-4">Detail Pesanan #{{ $order->id }}</h5>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <table class="table">
                        <thead class="table-light">
                            <tr><th>Produk</th><th>Qty</th><th>Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderDetails as $detail)
                            <tr>
                                <td>{{ $detail->product->name }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="2">Total</td>
                                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Upload Bukti Pembayaran --}}
            @if($order->status === 'pending')
            <div class="card border-0 shadow-sm border-warning">
                <div class="card-header bg-warning bg-opacity-10 border-0">
                    <h6 class="fw-bold mb-0 text-warning"><i class="bi bi-upload me-2"></i>Upload Bukti Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info small">
                        Transfer ke: <strong>BCA 1234567890 a.n Toko Grosir Sejahtera</strong>
                        <br>Total: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                    </div>
                    <form method="POST" action="{{ route('pembeli.orders.upload-bukti', $order) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">File Bukti Transfer (JPG/PNG, max 2MB)</label>
                            <input type="file" name="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror" accept="image/*">
                            @error('payment_proof')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-warning">Upload Bukti Pembayaran</button>
                    </form>
                </div>
            </div>
            @elseif($order->payment_proof)
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Bukti Pembayaran</h6>
                    <img src="{{ asset('storage/' . $order->payment_proof) }}"
                         class="img-fluid rounded" style="max-height: 250px;" alt="">
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Info Pesanan</h6>
                    <p><strong>Alamat:</strong> {{ $order->address }}</p>
                    <p><strong>Telepon:</strong> {{ $order->phone }}</p>
                    <p><strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge bg-{{ $order->status_badge }} fs-6">{{ $order->status_label }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection