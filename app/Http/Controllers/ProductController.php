<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk katalog milik UMKM yang login (Layar 8: Daftar Produk).
     */
    public function index(Request $request): View
    {
        $query = Product::query();

        // 1. Filter Pencarian Nama Produk (aman SQL Injection dengan parameter binding)
        if ($request->filled('q')) {
            $searchTerm = '%' . trim($request->q) . '%';
            $query->where('name', 'like', $searchTerm);
        }

        // 2. Filter Kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // 3. Filter Status (Tampil / Sembunyi)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->latest('id')->paginate(10)->withQueryString();

        // Ambil daftar kategori unik milik UMKM ini untuk dropdown filter
        $categories = Product::pluck('category')->unique()->filter()->values();

        return view('dashboard.products.index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'q' => $request->q,
                'category' => $request->category,
                'status' => $request->status,
            ],
            'organization' => Auth::user()->organization,
        ]);
    }

    /**
     * Tampilkan formulir tambah produk baru (Layar 8: Form Produk).
     */
    public function create(): View
    {
        return view('dashboard.products.create', [
            'organization' => Auth::user()->organization,
        ]);
    }

    /**
     * Simpan produk baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'photo_url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', 'in:Tampil,Sembunyi'],
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category.required' => 'Kategori produk wajib dipilih.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga produk harus berupa angka.',
            'price.min' => 'Harga produk tidak boleh bernilai negatif (>= 0).',
            'photo_url.url' => 'Format URL foto produk tidak valid (gunakan https://...).',
            'status.required' => 'Status produk wajib dipilih.',
            'status.in' => 'Status produk hanya boleh Tampil atau Sembunyi.',
        ]);

        // organization_id otomatis terikat ke tenant login via trait & assignment
        $validated['organization_id'] = Auth::user()->organization_id;

        $product = Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk "' . $product->name . '" berhasil ditambahkan ke katalog!');
    }

    /**
     * Tampilkan detail produk (diarahkan langsung ke form edit dengan proteksi IDOR).
     */
    public function show(Product $product): RedirectResponse
    {
        $this->authorizeProductOwnership($product);

        return redirect()->route('products.edit', $product);
    }

    /**
     * Tampilkan formulir edit produk.
     */
    public function edit(Product $product): View
    {
        // Proteksi IDOR eksplisit
        $this->authorizeProductOwnership($product);

        return view('dashboard.products.edit', [
            'product' => $product,
            'organization' => Auth::user()->organization,
        ]);
    }

    /**
     * Perbarui data produk di database.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        // Proteksi IDOR eksplisit
        $this->authorizeProductOwnership($product);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'photo_url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', 'in:Tampil,Sembunyi'],
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category.required' => 'Kategori produk wajib dipilih.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga produk harus berupa angka.',
            'price.min' => 'Harga produk tidak boleh bernilai negatif (>= 0).',
            'photo_url.url' => 'Format URL foto produk tidak valid (gunakan https://...).',
            'status.required' => 'Status produk wajib dipilih.',
            'status.in' => 'Status produk hanya boleh Tampil atau Sembunyi.',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Perubahan produk "' . $product->name . '" berhasil disimpan!');
    }

    /**
     * Hapus produk dari database.
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Proteksi IDOR eksplisit
        $this->authorizeProductOwnership($product);

        $productName = $product->name;
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk "' . $productName . '" berhasil dihapus dari katalog.');
    }

    /**
     * Helper proteksi IDOR: pastikan produk benar-benar milik UMKM user yang login.
     */
    protected function authorizeProductOwnership(Product $product): void
    {
        if ((int)$product->organization_id !== (int)Auth::user()->organization_id) {
            abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk mengelola produk milik UMKM lain.');
        }
    }
}
