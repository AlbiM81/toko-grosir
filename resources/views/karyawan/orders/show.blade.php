@extends('layouts.app')
@section('title', 'Detail Pesanan #' . $order->id)
@section('content')

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Detail Produk</h6>
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

        @if($order->payment_proof)
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Bukti Pembayaran</h6>
                <img src="{{ asset('storage/' . $order->payment_proof) }}"
                     class="img-fluid rounded" style="max-height: 300px;" alt="Bukti Pembayaran">
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Informasi Pesanan</h6>
                <p><strong>Pembeli:</strong> {{ $order->user->name }}</p>
                <p><strong>Telepon:</strong> {{ $order->phone }}</p>
                <p><strong>Alamat:</strong> {{ $order->address }}</p>
                <p><strong>Status:</strong>
                    <span class="badge bg-{{ $order->status_badge }}">{{ $order->status_label }}</span>
                </p>
                <p><strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>

                <hr>

                {{-- Tombol aksi berdasarkan status --}}
                @if($order->status === 'menunggu_verifikasi')
                <form action="{{ route('karyawan.orders.verifikasi', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-success w-100 mb-2"
                        onclick="return confirm('Konfirmasi pembayaran ini?')">
                        <i class="bi bi-check-circle me-1"></i> Verifikasi Pembayaran
                    </button>
                </form>
                @endif

                @if($order->status === 'diproses')
                <form action="{{ route('karyawan.orders.kirim', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-primary w-100 mb-2"
                        onclick="return confirm('Tandai sebagai dikirim?')">
                        <i class="bi bi-truck me-1"></i> Kirim Barang
                    </button>
                </form>
                @endif

                @if($order->status === 'dikirim')
                <form action="{{ route('karyawan.orders.selesai', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-secondary w-100"
                        onclick="return confirm('Tandai pesanan selesai?')">
                        <i class="bi bi-check2-all me-1"></i> Selesai
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection