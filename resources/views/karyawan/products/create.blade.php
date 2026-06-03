@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')

<div class="card border-0 shadow-sm">

    <div class="card-header bg-primary text-white">
        Tambah Produk
    </div>

    <div class="card-body">

        <form action="{{ route('karyawan.products.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label>Kategori</label>

                <select name="category_id"
                        class="form-select">

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="mb-3">
                <label>Nama Produk</label>
                <input type="text"
                       name="name"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Harga</label>
                <input type="number"
                       name="price"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Stok</label>
                <input type="number"
                       name="stock"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="description"
                          rows="5"
                          class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Gambar</label>
                <input type="file"
                       name="image"
                       class="form-control">
            </div>

            <button class="btn btn-primary">
                Simpan Produk
            </button>

        </form>

    </div>

</div>

@endsection