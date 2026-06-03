@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>
                    Tambah Kategori
                </h5>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.categories.store') }}"
                      method="POST">

                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Kategori
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama kategori"
                            required
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit"
                                class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            Simpan
                        </button>

                        <a href="{{ route('admin.categories.index') }}"
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>

                </form>

            </div>

        </div>

    </div>
</div>

@endsection