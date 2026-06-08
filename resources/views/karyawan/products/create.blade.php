{{-- resources/views/karyawan/products/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Tambah Produk')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1">Tambah Produk Baru</h5>
                        <small class="text-muted">Lengkapi semua informasi produk</small>
                    </div>
                    <a href="{{ route('karyawan.products.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('karyawan.products.store') }}"
                      enctype="multipart/form-data" id="formProduk">
                    @csrf

                    <div class="row g-4">
                        {{-- Kolom Kiri: Info Produk --}}
                        <div class="col-md-7">
                            <h6 class="fw-semibold text-muted mb-3 text-uppercase small tracking-wide">Informasi Produk</h6>

                            {{-- Nama Produk --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="cth: Beras Pandan Wangi 5kg">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Harga & Stok --}}
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">Harga (Rp) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="price" value="{{ old('price') }}"
                                               class="form-control @error('price') is-invalid @enderror"
                                               placeholder="0" min="0">
                                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">Stok <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="stock" value="{{ old('stock', 0) }}"
                                               class="form-control @error('stock') is-invalid @enderror"
                                               placeholder="0" min="0">
                                        <span class="input-group-text">unit</span>
                                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Deskripsi Produk</label>
                                <textarea name="description" rows="5"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Tuliskan deskripsi lengkap produk...">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Kolom Kanan: Upload Foto --}}
                        <div class="col-md-5">
                            <h6 class="fw-semibold text-muted mb-3 text-uppercase small">Foto Produk</h6>

                            {{-- Drop Zone Foto --}}
                            <div class="border border-2 rounded-3 p-3 text-center mb-3"
                                 id="fotoDropZone"
                                 style="border-style:dashed !important;cursor:pointer;min-height:220px;
                                        display:flex;flex-direction:column;align-items:center;justify-content:center;
                                        transition:all 0.3s;background:#fafbfc;">

                                <div id="fotoPlaceholder">
                                    <i class="bi bi-image fs-1 text-muted mb-2 d-block"></i>
                                    <p class="fw-semibold text-muted mb-1">Klik atau seret foto ke sini</p>
                                    <p class="text-muted small mb-0">JPG, PNG, WebP — maks 3MB</p>
                                    <div class="mt-3">
                                        <span class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-upload me-1"></i>Pilih File
                                        </span>
                                    </div>
                                </div>

                                <div id="fotoPreview" class="d-none w-100">
                                    <div class="position-relative d-inline-block">
                                        <img id="fotoPreviewImg" src="" alt="Preview"
                                             class="img-fluid rounded-2 shadow-sm"
                                             style="max-height:200px;max-width:100%;">
                                        <button type="button" id="hapusFotoPreview"
                                                class="btn btn-sm btn-danger position-absolute"
                                                style="top:-8px;right:-8px;border-radius:50%;width:28px;height:28px;padding:0;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <p id="fotoNamaFile" class="text-success small mt-2 mb-0"></p>
                                </div>
                            </div>

                            <input type="file" id="fotoInput" name="image"
                                   accept="image/jpeg,image/png,image/webp"
                                   class="d-none @error('image') is-invalid @enderror">
                            @error('image')
                                <div class="text-danger small mb-2">{{ $message }}</div>
                            @enderror

                            <div class="alert alert-light border small p-3">
                                <i class="bi bi-lightbulb text-warning me-1"></i>
                                <strong>Tips foto produk:</strong>
                                <ul class="mb-0 mt-1 ps-3">
                                    <li>Gunakan foto yang jelas dan terang</li>
                                    <li>Rasio 1:1 (kotak) lebih disarankan</li>
                                    <li>Ukuran minimal 400×400 pixel</li>
                                    <li>Latar belakang putih terlihat lebih profesional</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <hr class="my-4">
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Produk
                        </button>
                        <a href="{{ route('karyawan.products.index') }}" class="btn btn-outline-secondary px-4">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const dropZone    = document.getElementById('fotoDropZone');
const fileInput   = document.getElementById('fotoInput');
const placeholder = document.getElementById('fotoPlaceholder');
const preview     = document.getElementById('fotoPreview');
const previewImg  = document.getElementById('fotoPreviewImg');
const namaFile    = document.getElementById('fotoNamaFile');
const hapusBtn    = document.getElementById('hapusFotoPreview');

// Klik area untuk buka file dialog
dropZone.addEventListener('click', (e) => {
    if (e.target !== hapusBtn && !hapusBtn.contains(e.target)) {
        fileInput.click();
    }
});

// Drag & drop events
['dragenter','dragover'].forEach(event => {
    dropZone.addEventListener(event, (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#1a73e8';
        dropZone.style.background  = 'rgba(26,115,232,0.05)';
    });
});

['dragleave','dragend'].forEach(event => {
    dropZone.addEventListener(event, () => {
        dropZone.style.borderColor = '';
        dropZone.style.background  = '#fafbfc';
    });
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.style.borderColor = '';
    dropZone.style.background  = '#fafbfc';
    const file = e.dataTransfer.files[0];
    if (file) processFile(file);
});

// Input file change
fileInput.addEventListener('change', () => {
    if (fileInput.files[0]) processFile(fileInput.files[0]);
});

function processFile(file) {
    // Validasi ukuran
    if (file.size > 3 * 1024 * 1024) {
        alert('Ukuran file melebihi 3MB. Pilih gambar yang lebih kecil.');
        return;
    }
    // Validasi tipe
    const allowed = ['image/jpeg','image/jpg','image/png','image/webp'];
    if (!allowed.includes(file.type)) {
        alert('Format tidak didukung. Gunakan JPG, PNG, atau WebP.');
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        previewImg.src = e.target.result;
        namaFile.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
        placeholder.classList.add('d-none');
        preview.classList.remove('d-none');
        dropZone.style.borderColor = '#10b981';
    };
    reader.readAsDataURL(file);

    const dt = new DataTransfer();
    dt.items.add(file);
    fileInput.files = dt.files;
}

// Hapus preview
hapusBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    fileInput.value = '';
    previewImg.src  = '';
    preview.classList.add('d-none');
    placeholder.classList.remove('d-none');
    dropZone.style.borderColor = '';
});
</script>
@endpush
@endsection