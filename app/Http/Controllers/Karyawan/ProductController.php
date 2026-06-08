<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products   = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('karyawan.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('karyawan.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string|max:2000',
            // Validasi foto produk
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072', // max 3MB
        ], [
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG, PNG, atau WebP.',
            'image.max'   => 'Ukuran gambar maksimal 3MB.',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Simpan ke storage/app/public/products/YYYY/MM/
            $data['image'] = $request->file('image')
                ->store('products/' . date('Y/m'), 'public');
        }

        Product::create($data);

        return redirect()->route('karyawan.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        return view('karyawan.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('karyawan.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string|max:2000',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data = $request->except(['image', 'hapus_foto']);

        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')
                ->store('products/' . date('Y/m'), 'public');
        }

        // Opsi hapus foto tanpa ganti
        if ($request->boolean('hapus_foto') && !$request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = null;
        }

        $product->update($data);

        return redirect()->route('karyawan.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('karyawan.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}