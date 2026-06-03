@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <form method="GET" class="row g-3">

            <div class="col-md-5">
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Cari produk..."
                       value="{{ request('search') }}">
            </div>

            <div class="col-md-4">
                <select name="category"
                        class="form-select">

                    <option value="">
                        Semua Kategori
                    </option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-3 d-grid">
                <button class="btn btn-primary">
                    Filter
                </button>
            </div>

        </form>

    </div>
</div>

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <h5 class="mb-0 fw-bold">
            Daftar Produk
        </h5>

        <a href="{{ route('karyawan.products.create') }}"
           class="btn btn-primary">
            + Tambah Produk
        </a>

    </div>

    <div class="card-body p-0">

        <table class="table table-hover mb-0">

            <thead class="table-light">
                <tr>
                    <th>Gambar</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($products as $product)

                <tr>

                    <td>
                        <img src="{{ $product->image_url }}"
                             width="60"
                             class="rounded">
                    </td>

                    <td>{{ $product->name }}</td>

                    <td>{{ $product->category->name }}</td>

                    <td>
                        Rp {{ number_format($product->price,0,',','.') }}
                    </td>

                    <td>

                        @if($product->stock <= 10)
                            <span class="badge bg-danger">
                                {{ $product->stock }}
                            </span>
                        @else
                            <span class="badge bg-success">
                                {{ $product->stock }}
                            </span>
                        @endif

                    </td>

                    <td>

                        <a href="{{ route('karyawan.products.edit',$product) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('karyawan.products.destroy',$product) }}"
                              method="POST"
                              class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus produk?')">
                                Hapus
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6"
                        class="text-center py-4 text-muted">
                        Belum ada produk
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer bg-white">
        {{ $products->links() }}
    </div>

</div>

@endsection