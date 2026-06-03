@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')

<div class="card border-0 shadow-sm">

    <div class="card-header bg-warning">
        Edit Produk
    </div>

    <div class="card-body">

        <form action="{{ route('karyawan.products.update',$product) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label>Kategori</label>

                <select name="category_id"
                        class="form-select">

                    @foreach($categories as $category)

                        <option value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-3">
                <label>Nama Produk</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ $product->name }}">
            </div>

            <div class="mb-3">
                <label>Harga</label>

                <input type="number"
                       name="price"
                       class="form-control"
                       value="{{ $product->price }}">
            </div>

            <div class="mb-3">
                <label>Stok</label>

                <input type="number"
                       name="stock"
                       class="form-control"
                       value="{{ $product->stock }}">
            </div>

            <div class="mb-3">

                <label>Deskripsi</label>

                <textarea name="description"
                          rows="5"
                          class="form-control">{{ $product->description }}</textarea>

            </div>

            @if($product->image)
                <div class="mb-3">
                    <img src="{{ $product->image_url }}"
                         width="120"
                         class="rounded shadow">
                </div>
            @endif

            <div class="mb-3">
                <label>Ganti Gambar</label>

                <input type="file"
                       name="image"
                       class="form-control">
            </div>

            <button class="btn btn-warning">
                Update Produk
            </button>

        </form>

    </div>

</div>

@endsection