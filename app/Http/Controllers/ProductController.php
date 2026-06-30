<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use Illuminate\Http\Request;

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
            'name' => 'required|string|max:255',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'product_unit_id' => 'nullable|exists:product_units,id',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'product_category_id' => $request->product_category_id,
            'product_unit_id' => $request->product_unit_id,
        ]);

        return redirect()->route('pages.admin.stok')
            ->with('success', 'Produk "' . $product->name . '" berhasil diperbarui.');
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
