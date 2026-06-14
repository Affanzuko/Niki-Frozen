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
            ->with(['category', 'unit'])
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
     * Tampilkan form edit produk.
     */
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = \App\Models\ProductCategory::all();
        $units = \App\Models\ProductUnit::all();
        $stockIncoming = \App\Models\StockIncoming::where('product_id', $id)->orderBy('expiry_date', 'asc')->first();
        $expiry_date = $stockIncoming ? $stockIncoming->expiry_date : null;

        return view('pages.admin.stok.edit', compact('product', 'categories', 'units', 'expiry_date'));
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
            'expiry_date' => 'nullable|date',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'product_category_id' => $request->product_category_id,
            'product_unit_id' => $request->product_unit_id,
        ]);

        if ($request->filled('expiry_date')) {
            $affected = \App\Models\StockIncoming::where('product_id', $id)->update([
                'expiry_date' => $request->expiry_date
            ]);

            // Jika sebelumnya belum ada riwayat barang masuk (misal dari data seeder), buat record baru
            if ($affected === 0) {
                $stock = \App\Models\Stock::where('product_id', $id)->first();
                \App\Models\StockIncoming::create([
                    'product_id' => $id,
                    'branch_id' => $product->branch_id ?? 1,
                    'user_id' => 1, // Default user
                    'quantity' => $stock ? $stock->quantity : 0,
                    'expiry_date' => $request->expiry_date,
                ]);
            }
        }

        return redirect()->route('pages.admin.stok')
            ->with('success', 'Produk "' . $product->name . '" berhasil diperbarui.');
    }
}
