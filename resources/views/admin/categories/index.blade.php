@extends('layouts.app')
@section('title', 'Kelola Kategori')

@section('content')

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-tags me-2"></i>Daftar Kategori
        </h5>

        <a href="{{ route('admin.categories.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Kategori
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Nama Kategori</th>
                <th>Jumlah Produk</th>
                <th width="180">Aksi</th>
            </tr>
            </thead>

            <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <span class="badge bg-info">
                            {{ $category->products_count }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.categories.edit',$category) }}"
                           class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form action="{{ route('admin.categories.destroy',$category) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Hapus kategori?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4">
                        Belum ada kategori
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white">
        {{ $categories->links() }}
    </div>
</div>

@endsection