@extends('layouts.app')
@section('title','Data Pembeli')

@section('content')

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-person-check me-2"></i>
            Data Pembeli
        </h5>
    </div>

    <div class="card-body p-0">

        <table class="table table-hover mb-0">

            <thead class="table-light">
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Tanggal Registrasi</th>
            </tr>
            </thead>

            <tbody>

            @foreach($pembeli as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection