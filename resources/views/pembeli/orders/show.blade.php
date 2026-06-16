{{-- resources/views/pembeli/orders/show.blade.php --}}
@extends('layouts.pembeli')
@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('pembeli.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('pembeli.orders.index') }}">Pesanan Saya</a>
            </li>
            <li class="breadcrumb-item active">Pesanan #{{ $order->id }}</li>
        </ol>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Progress Status --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-4">
            <div class="d-flex justify-content-between align-items-center position-relative">
                {{-- Garis penghubung --}}
                <div style="position:absolute;top:20px;left:10%;right:10%;height:4px;background:#e9ecef;z-index:0;">
                    <div style="height:100%;background:linear-gradient(90deg,#198754,#0d6efd);
                        width:{{ match($order->status) {
                            'pending' => '0%',
                            'menunggu_verifikasi' => '25%',
                            'diproses' => '50%',
                            'dikirim' => '75%',
                            'selesai' => '100%',
                        } }};transition:width 0.5s ease;"></div>
                </div>

                @php
                    $steps = [
                        'pending'             => ['icon' => 'bi-clock',         'label' => 'Menunggu Bayar'],
                        'menunggu_verifikasi' => ['icon' => 'bi-search',         'label' => 'Verifikasi'],
                        'diproses'            => ['icon' => 'bi-gear',           'label' => 'Diproses'],
                        'dikirim'             => ['icon' => 'bi-truck',          'label' => 'Dikirim'],
                        'selesai'             => ['icon' => 'bi-check-circle',   'label' => 'Selesai'],
                    ];
                    $statusOrder = array_keys($steps);
                    $currentIndex = array_search($order->status, $statusOrder);
                @endphp

                @foreach($steps as $key => $step)
                    @php $idx = array_search($key, $statusOrder); @endphp
                    <div class="text-center" style="position:relative;z-index:1;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2
                            {{ $idx <= $currentIndex ? 'bg-success text-white' : 'bg-white border-2 border text-muted' }}"
                            style="width:44px;height:44px;font-size:1.1rem;
                                   {{ $idx <= $currentIndex ? 'box-shadow:0 0 0 4px rgba(25,135,84,0.15)' : '' }}">
                            <i class="bi {{ $step['icon'] }}"></i>
                        </div>
                        <small class="{{ $idx <= $currentIndex ? 'text-success fw-semibold' : 'text-muted' }}" style="font-size:0.75rem;">
                            {{ $step['label'] }}
                        </small>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Kiri: Detail Produk + Upload Bukti --}}
        <div class="col-lg-8">

            {{-- Detail Produk --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom pt-3 pb-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-bag me-2 text-primary"></i>Detail Produk</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderDetails as $detail)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($detail->product->image)
                                            <img src="{{ asset('storage/' . $detail->product->image) }}"
                                                 style="width:40px;height:40px;object-fit:cover;border-radius:6px;" alt="">
                                        @endif
                                        {{ $detail->product->name }}
                                    </div>
                                </td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total Pembayaran</td>
                                <td class="text-end fw-bold fs-5 text-primary">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- ═══ SECTION PEMBAYARAN VIA MIDTRANS ═══ --}}
            @if($order->status === 'pending')
            <div class="card border-0 shadow-sm border-start border-primary border-4">
                <div class="card-header bg-primary bg-opacity-10 border-0 pt-3 pb-2">
                    <h6 class="fw-bold mb-0 text-primary">
                        <i class="bi bi-credit-card me-2"></i>Pembayaran via Midtrans
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-shield-lock me-2"></i>
                        Klik tombol di bawah untuk menyelesaikan pembayaran melalui Midtrans dengan metode yang aman.
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <div class="fw-semibold">Total yang harus dibayar</div>
                            <div class="text-danger fs-4 fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            <small class="text-muted">Pesanan akan otomatis berubah status setelah pembayaran berhasil.</small>
                        </div>

                        <button type="button" class="btn btn-primary btn-lg px-4" id="btnPayNow" {{ empty($order->snap_token) ? 'disabled' : '' }}>
                            <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                        </button>

                        @if(empty($order->snap_token))
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRefreshToken">
                                <i class="bi bi-arrow-repeat me-2"></i>Siapkan Token Pembayaran
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom pt-3 pb-2">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-shield-check me-2 text-success"></i>Status Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-muted">
                        Pembayaran untuk pesanan ini sudah diproses melalui Midtrans.
                        @if($order->payment_method)
                            Metode pembayaran: <strong>{{ $order->payment_method_label }}</strong>.
                        @endif
                    </p>
                </div>
            </div>
            @endif

        </div>

        {{-- Kanan: Info Pesanan --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom pt-3 pb-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Info Pesanan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small">Status Pesanan</div>
                        <span class="badge bg-{{ $order->status_badge }} fs-6 mt-1">
                            {{ $order->status_label }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">No. Pesanan</div>
                        <div class="fw-semibold">#{{ $order->id }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Tanggal Pesan</div>
                        <div class="fw-semibold">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Telepon</div>
                        <div class="fw-semibold">{{ $order->phone }}</div>
                    </div>
                    <div class="mb-0">
                        <div class="text-muted small">Alamat Pengiriman</div>
                        <div class="fw-semibold">{{ $order->address }}</div>
                    </div>
                </div>
            </div>

            <a href="{{ route('pembeli.orders.index') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Pesanan
            </a>
        </div>

    </div>
</div>

@push('scripts')
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $clientKey }}"></script>
<script>
    const btnPayNow = document.getElementById('btnPayNow');
    const btnRefreshToken = document.getElementById('btnRefreshToken');
    const snapToken = @json($order->snap_token ?? null);

    function showTokenStatus(message, type = 'info') {
        const alertBox = document.createElement('div');
        alertBox.className = `alert alert-${type} mt-3 mb-0 small`;
        alertBox.innerHTML = `<i class="bi bi-info-circle me-2"></i>${message}`;

        const container = document.querySelector('.card-body');
        if (container) {
            container.appendChild(alertBox);
        }
    }

    async function refreshSnapToken() {
        if (!btnRefreshToken && !btnPayNow) return;

        if (btnRefreshToken) {
            btnRefreshToken.disabled = true;
            btnRefreshToken.innerHTML = '<i class="bi bi-arrow-repeat me-2"></i>Memuat token...';
        }

        try {
            const response = await fetch('{{ route("pembeli.orders.refresh-snap-token", $order) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Gagal membuat token pembayaran.');
            }

            if (btnRefreshToken) {
                btnRefreshToken.remove();
            }

            window.location.reload();
        } catch (error) {
            if (btnRefreshToken) {
                btnRefreshToken.disabled = false;
                btnRefreshToken.innerHTML = '<i class="bi bi-arrow-repeat me-2"></i>Siapkan Token Pembayaran';
            }
            showTokenStatus(error.message, 'danger');
        }
    }

    if (btnPayNow) {
        btnPayNow.disabled = !snapToken;
    }

    if (btnRefreshToken) {
        btnRefreshToken.addEventListener('click', refreshSnapToken);
        refreshSnapToken();
    }

    if (btnPayNow) {
        btnPayNow.addEventListener('click', function () {

            if (!snapToken) {
                showTokenStatus('Token pembayaran belum siap. Klik tombol “Siapkan Token Pembayaran” terlebih dahulu.', 'warning');
                return;
            }

            if (typeof window.snap === 'undefined') {
                alert('Midtrans belum siap. Silakan refresh halaman.');
                return;
            }

            window.snap.pay(snapToken, {
                onSuccess: function (result) {
                    window.location.href = '{{ route('pembeli.orders.payment-finish') }}?order_id=' + encodeURIComponent(result.order_id || '') + '&status_code=' + encodeURIComponent(result.status_code || '') + '&transaction_status=' + encodeURIComponent(result.transaction_status || '');
                },
                onPending: function (result) {
                    window.location.href = '{{ route('pembeli.orders.payment-finish') }}?order_id=' + encodeURIComponent(result.order_id || '') + '&status_code=' + encodeURIComponent(result.status_code || '') + '&transaction_status=' + encodeURIComponent(result.transaction_status || '');
                },
                onError: function () {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                },
                onClose: function () {
                    alert('Anda menutup popup pembayaran.');
                }
            });
        });
    }
</script>

@endpush

@endsection