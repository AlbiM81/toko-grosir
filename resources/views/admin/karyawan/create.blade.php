@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-person-plus me-2 text-primary"></i>
                    Tambah Karyawan
                </h5>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.karyawan.store') }}"
                      method="POST">

                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Karyawan</label>

                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               required>

                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>

                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               required>

                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>

                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required>

                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit"
                                class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            Simpan
                        </button>

                        <a href="{{ route('admin.karyawan.index') }}"
                           class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>

                </form>

            </div>

        </div>

    </div>
</div>

@endsection