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
}
