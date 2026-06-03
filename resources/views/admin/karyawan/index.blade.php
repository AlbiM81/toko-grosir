@extends('layouts.app')
@section('title','Kelola Karyawan')

@section('content')

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white d-flex justify-content-between">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-people me-2"></i>
            Data Karyawan
        </h5>

        <a href="{{ route('admin.karyawan.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Tambah
        </a>
    </div>

    <div class="card-body p-0">

        <table class="table table-hover mb-0">

            <thead class="table-light">
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th width="180">Aksi</th>
            </tr>
            </thead>

            <tbody>

            @foreach($karyawans as $karyawan)
            <tr>
                <td>{{ $karyawan->name }}</td>
                <td>{{ $karyawan->email }}</td>

                <td>

                    <a href="{{ route('admin.karyawan.edit',$karyawan) }}"
                       class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <form method="POST"
                          action="{{ route('admin.karyawan.destroy',$karyawan) }}"
                          class="d-inline">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>

                </td>
            </tr>
            @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection