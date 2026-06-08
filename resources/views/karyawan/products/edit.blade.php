{{-- resources/views/karyawan/products/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between">
                <div>
                    <h5 class="fw-bold mb-1">Edit Produk</h5>
                    <small class="text-muted">{{ $product->name }}</small>
                </div>
                <a href="{{ route('karyawan.products.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('karyawan.products.update', $product) }}"
                      enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="row g-4">
                        {{-- Kolom Kiri --}}
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Nama Produk</label>
                                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                       class="form-control @error('name') is-invalid @enderror">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Kategori</label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">Harga (Rp)</label>
                                    <input type="number" name="price"
                                           value="{{ old('price', $product->price) }}"
                                           class="form-control @error('price') is-invalid @enderror" min="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">Stok</label>
                                    <input type="number" name="stock"
                                           value="{{ old('stock', $product->stock) }}"
                                           class="form-control @error('stock') is-invalid @enderror" min="0">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Deskripsi</label>
                                <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Foto --}}
                        <div class="col-md-5">
                            <label class="form-label fw-semibold small">Foto Produk</label>

                            {{-- Foto Saat Ini --}}
                            @if($product->image)
                            <div class="mb-3 p-3 bg-light rounded-3" id="fotoSaatIni">
                                <p class="text-muted small mb-2 fw-semibold">Foto Saat Ini:</p>
                                <div class="position-relative d-inline-block">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         class="img-fluid rounded-2 shadow-sm"
                                         style="max-height:180px;"
                                         alt="{{ $product->name }}">
                                </div>
                                <div class="mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="hapus_foto" id="hapusFoto" value="1">
                                        <label class="form-check-label text-danger small" for="hapusFoto">
                                            <i class="bi bi-trash me-1"></i>Hapus foto ini
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Upload Foto Baru --}}
                            <div class="border border-2 rounded-3 p-3 text-center"
                                 id="editDropZone"
                                 style="border-style:dashed !important;cursor:pointer;min-height:160px;
                                        display:flex;flex-direction:column;align-items:center;justify-content:center;
                                        background:#fafbfc;transition:all 0.3s;">
                                <div id="editPlaceholder">
                                    <i class="bi bi-cloud-upload fs-2 text-muted mb-1 d-block"></i>
                                    <p class="small text-muted mb-1">
                                        {{ $product->image ? 'Ganti dengan foto baru' : 'Upload foto produk' }}
                                    </p>
                                    <p class="text-muted" style="font-size:0.75rem;">JPG, PNG, WebP — maks 3MB</p>
                                </div>
                                <div id="editPreview" class="d-none">
                                    <img id="editPreviewImg" src="" class="img-fluid rounded-2" style="max-height:140px;" alt="">
                                    <p id="editNamaFile" class="text-success small mt-1 mb-0"></p>
                                </div>
                            </div>

                            <input type="file" id="editFotoInput" name="image"
                                   accept="image/jpeg,image/png,image/webp"
                                   class="d-none @error('image') is-invalid @enderror">
                            @error('image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
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
// Edit drop zone (sama seperti create)
const editDrop    = document.getElementById('editDropZone');
const editInput   = document.getElementById('editFotoInput');
const editPh      = document.getElementById('editPlaceholder');
const editPrev    = document.getElementById('editPreview');
const editImg     = document.getElementById('editPreviewImg');
const editName    = document.getElementById('editNamaFile');

editDrop.addEventListener('click', () => editInput.click());

editDrop.addEventListener('dragover', (e) => {
    e.preventDefault();
    editDrop.style.borderColor = '#1a73e8';
    editDrop.style.background  = 'rgba(26,115,232,0.05)';
});

editDrop.addEventListener('dragleave', () => {
    editDrop.style.borderColor = '';
    editDrop.style.background  = '#fafbfc';
});

editDrop.addEventListener('drop', (e) => {
    e.preventDefault();
    editDrop.style.borderColor = '';
    editDrop.style.background  = '#fafbfc';
    if (e.dataTransfer.files[0]) processEditFile(e.dataTransfer.files[0]);
});

editInput.addEventListener('change', () => {
    if (editInput.files[0]) processEditFile(editInput.files[0]);
});

function processEditFile(file) {
    if (file.size > 3 * 1024 * 1024) { alert('Maks 3MB'); return; }
    const reader = new FileReader();
    reader.onload = (e) => {
        editImg.src  = e.target.result;
        editName.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
        editPh.classList.add('d-none');
        editPrev.classList.remove('d-none');
        editDrop.style.borderColor = '#10b981';

        // Jika ada checkbox hapus foto, otomatis uncheck
        const hapus = document.getElementById('hapusFoto');
        if (hapus) hapus.checked = false;
    };
    reader.readAsDataURL(file);
    const dt = new DataTransfer();
    dt.items.add(file);
    editInput.files = dt.files;
}
</script>
@endpush
@endsection