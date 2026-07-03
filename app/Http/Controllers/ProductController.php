<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk cabang admin untuk kelola harga.
     */
    public function index()
    {
        $branchId = 1; // Hardcode, belum ada auth

        $products = Product::where('branch_id', $branchId)
            ->with(['category', 'unit', 'promotions' => function ($query) {
                $query->where('is_active', true)->where('valid_until', '>=', now());
            }])
            ->orderBy('name', 'asc')
            ->get();

        return view('pages.admin.kelola-harga', compact('products', 'branchId'));
    }

    /**
     * Update harga produk.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
        ], [
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'price' => $request->price,
        ]);

        return redirect()->route('pages.admin.kelola-harga')
            ->with('success', 'Harga produk "' . $product->name . '" berhasil diperbarui.');
    }

    /**
     * Tambah/Update diskon produk.
     */
    public function addDiscount(Request $request, $id)
    {
        $request->validate([
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'valid_until' => 'required|date',
        ]);

        $product = Product::findOrFail($id);

        // Nonaktifkan promosi lama untuk produk ini di cabang ini
        \App\Models\Promotion::where('product_id', $product->id)
            ->where('branch_id', $product->branch_id)
            ->update(['is_active' => false]);

        if ($request->discount_percentage > 0) {
            \App\Models\Promotion::create([
                'product_id' => $product->id,
                'branch_id' => $product->branch_id,
                'created_by' => auth()->id() ?? 1,
                'discount_percentage' => $request->discount_percentage,
                'valid_until' => $request->valid_until,
                'is_active' => true,
            ]);
        }

        return redirect()->route('pages.admin.kelola-harga')
            ->with('success', 'Diskon produk "' . $product->name . '" berhasil diperbarui.');
    }

    /**
     * Tampilkan form edit produk.
     */
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = \App\Models\ProductCategory::all();
        $units = \App\Models\ProductUnit::all();
        return view('pages.admin.stok.edit', compact('product', 'categories', 'units'));
    }

    /**
     * Update data produk.
     */
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'product_category_id' => 'required|exists:product_categories,id',
            'product_unit_id' => 'required|exists:product_units,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk maksimal 150 karakter.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'product_category_id.required' => 'Kategori produk wajib dipilih.',
            'product_category_id.exists' => 'Kategori produk tidak ditemukan.',
            'product_unit_id.required' => 'Unit produk wajib dipilih.',
            'product_unit_id.exists' => 'Unit produk tidak ditemukan.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $product = Product::findOrFail($id);

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'product_category_id' => $request->product_category_id,
            'product_unit_id' => $request->product_unit_id,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('pages.admin.stok')
            ->with('success', 'Produk "' . $product->name . '" berhasil diperbarui.');
    }

    /**
     * Tampilkan form tambah produk baru.
     */
    public function createProduct()
    {
        $branchId = 1;
        $categories = ProductCategory::all();
        $units = ProductUnit::all();

        return view('pages.admin.stok.tambah-produk', compact('categories', 'units', 'branchId'));
    }

    /**
     * Simpan produk baru ke database.
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'price' => 'required|numeric|min:0',
            'product_category_id' => 'required|exists:product_categories,id',
            'product_unit_id' => 'required|exists:product_units,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk maksimal 150 karakter.',
            'barcode.max' => 'Barcode maksimal 100 karakter.',
            'barcode.unique' => 'Barcode sudah digunakan produk lain.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'product_category_id.required' => 'Kategori produk wajib dipilih.',
            'product_category_id.exists' => 'Kategori produk tidak ditemukan.',
            'product_unit_id.required' => 'Unit produk wajib dipilih.',
            'product_unit_id.exists' => 'Unit produk tidak ditemukan.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $branchId = 1;

        DB::beginTransaction();
        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            $product = Product::create([
                'branch_id' => $branchId,
                'product_category_id' => $request->product_category_id,
                'product_unit_id' => $request->product_unit_id,
                'barcode' => $request->barcode,
                'name' => $request->name,
                'price' => $request->price,
                'image' => $imagePath,
            ]);

            Stock::create([
                'product_id' => $product->id,
                'branch_id' => $branchId,
                'quantity' => 0,
            ]);

            DB::commit();

            return redirect()->route('pages.admin.stok')
                ->with('success', 'Produk "' . $product->name . '" berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Hapus produk.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $name = $product->name;

        // Hapus stok, promosi, dan riwayat yang terkait
        \App\Models\Stock::where('product_id', $id)->delete();
        \App\Models\StockIncoming::where('product_id', $id)->delete();
        \App\Models\Promotion::where('product_id', $id)->delete();

        $product->delete();

        return redirect()->route('pages.admin.stok')
            ->with('success', 'Produk "' . $name . '" berhasil dihapus.');
    }
}
