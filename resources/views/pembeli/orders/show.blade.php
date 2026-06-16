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

            {{-- ═══ SECTION UPLOAD BUKTI PEMBAYARAN ═══ --}}
            @if($order->status === 'pending')
            <div class="card border-0 shadow-sm border-start border-warning border-4">
                <div class="card-header bg-warning bg-opacity-10 border-0 pt-3 pb-2">
                    <h6 class="fw-bold mb-0 text-warning">
                        <i class="bi bi-upload me-2"></i>Upload Bukti Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    {{-- Info Rekening --}}
                    <div class="alert alert-info d-flex gap-3 mb-4">
                        <i class="bi bi-bank2 fs-4 flex-shrink-0 text-info"></i>
                        <div>
                            <div class="fw-semibold mb-1">Informasi Rekening Tujuan</div>
                            <table class="table table-sm table-borderless mb-0" style="font-size:0.9rem;">
                                <tr><td>Bank</td><td>: <strong>BCA</strong></td></tr>
                                <tr><td>No. Rekening</td><td>: <strong>1234567890</strong></td></tr>
                                <tr><td>Atas Nama</td><td>: <strong>Toko Grosir Sejahtera</strong></td></tr>
                                <tr><td>Jumlah Transfer</td><td>: <strong class="text-danger fs-5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td></tr>
                            </table>
                        </div>
                    </div>

                    {{-- Form Upload --}}
                    <form method="POST"
                          action="{{ route('pembeli.orders.upload-bukti', $order) }}"
                          enctype="multipart/form-data"
                          id="formUploadBukti">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Foto Bukti Transfer
                                <span class="text-danger">*</span>
                            </label>

                            {{-- Drop Zone Upload --}}
                            <div class="border border-2 border-dashed rounded-3 p-4 text-center"
                                 id="dropZone"
                                 style="cursor:pointer;border-color:#dee2e6 !important;transition:all 0.3s;">
                                <div id="uploadPlaceholder">
                                    <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                    <p class="mb-1 fw-semibold">Klik atau drag & drop foto di sini</p>
                                    <small class="text-muted">Format: JPG, PNG | Maksimal: 2MB</small>
                                </div>
                                <div id="previewContainer" class="d-none">
                                    <img id="previewImg" src="" class="img-fluid rounded" style="max-height:250px;" alt="Preview">
                                    <p class="mt-2 text-success small" id="previewName"></p>
                                </div>
                            </div>

                            <input type="file"
                                   name="payment_proof"
                                   id="payment_proof"
                                   accept="image/jpg,image/jpeg,image/png"
                                   class="d-none @error('payment_proof') is-invalid @enderror">

                            @error('payment_proof')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-warning fw-semibold px-4" id="btnUpload" disabled>
                            <i class="bi bi-upload me-2"></i>Upload Bukti Pembayaran
                        </button>
                        <small class="text-muted ms-2">Pesanan akan diverifikasi dalam 1×24 jam</small>
                    </form>
                </div>
            </div>

            @elseif($order->payment_proof)
            {{-- Tampilkan bukti yang sudah diupload --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom pt-3 pb-2">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-image me-2 text-success"></i>Bukti Pembayaran
                        <span class="badge bg-success ms-2">Sudah Diupload</span>
                    </h6>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('storage/' . $order->payment_proof) }}"
                         class="img-fluid rounded shadow-sm"
                         style="max-height:300px;cursor:pointer;"
                         alt="Bukti Pembayaran"
                         data-bs-toggle="modal"
                         data-bs-target="#modalBukti">
                    <p class="text-muted small mt-2">Klik gambar untuk memperbesar</p>
                </div>
            </div>

            {{-- Modal Zoom Gambar --}}
            <div class="modal fade" id="modalBukti" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h6 class="modal-title fw-bold">Bukti Pembayaran - Pesanan #{{ $order->id }}</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center p-2">
                            <img src="{{ asset('storage/' . $order->payment_proof) }}"
                                 class="img-fluid" alt="Bukti Pembayaran">
                        </div>
                    </div>
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
<script>
// Drop zone & preview upload
const dropZone   = document.getElementById('dropZone');
const fileInput  = document.getElementById('payment_proof');
const btnUpload  = document.getElementById('btnUpload');
const placeholder = document.getElementById('uploadPlaceholder');
const previewCont = document.getElementById('previewContainer');
const previewImg  = document.getElementById('previewImg');
const previewName = document.getElementById('previewName');

if (dropZone) {
    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#0d6efd';
        dropZone.style.background  = 'rgba(13,110,253,0.05)';
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = '#dee2e6';
        dropZone.style.background  = '';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#dee2e6';
        dropZone.style.background  = '';
        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files[0]) handleFile(fileInput.files[0]);
    });

    function handleFile(file) {
        // Validasi ukuran
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            return;
        }
        // Validasi tipe
        if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
            alert('Format file harus JPG atau PNG.');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            previewName.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
            placeholder.classList.add('d-none');
            previewCont.classList.remove('d-none');
            btnUpload.disabled = false;
            dropZone.style.borderColor = '#198754';
        };
        reader.readAsDataURL(file);

        // Assign ke input file
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
    }
}
</script>
@endpush
@endsection