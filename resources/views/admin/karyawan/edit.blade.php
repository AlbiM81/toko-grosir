@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-pencil-square me-2 text-warning"></i>
                    Edit Karyawan
                </h5>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.karyawan.update', $karyawan) }}"
                      method="POST">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Karyawan</label>

                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $karyawan->name) }}"
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
                               value="{{ old('email', $karyawan->email) }}"
                               required>

                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <hr>

                    <div class="alert alert-info">
                        Kosongkan password jika tidak ingin mengubah password.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>

                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror">

                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control">
                    </div>

                    <div class="d-flex gap-2">

                        <button type="submit"
                                class="btn btn-warning text-white">
                            <i class="bi bi-check-circle me-1"></i>
                            Update
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