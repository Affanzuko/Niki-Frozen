<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\StockIncoming;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockIncomingController extends Controller
{
    /**
     * Tampilkan form input barang masuk.
     * Admin memilih produk, jumlah, dan mengisi tanggal kadaluarsa secara manual.
     */
    public function create()
    {
        // Gunakan branch_id dari user yang login (fallback ke 1 jika null)
        $branchId = auth()->user()->branch_id ?? 1;

        $products = Product::where('branch_id', $branchId)
            ->orderBy('name', 'asc')
            ->get();

        return view('pages.admin.stok.barang-masuk', compact('products', 'branchId'));
    }

    /**
     * Simpan data barang masuk ke stock_incomings dan update stok.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'total_purchase_price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after:today',
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk tidak ditemukan.',
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'total_purchase_price.required' => 'Total Harga Beli wajib diisi.',
            'total_purchase_price.numeric' => 'Total Harga Beli harus berupa angka.',
            'total_purchase_price.min' => 'Total Harga Beli tidak boleh negatif.',
            'expiry_date.required' => 'Tanggal kadaluarsa wajib diisi.',
            'expiry_date.after' => 'Tanggal kadaluarsa harus setelah hari ini.',
        ]);

        $branchId = auth()->user()->branch_id ?? 1; // Gunakan branch dari auth
        $userId = auth()->id() ?? 1;   // Gunakan ID user dari auth

        // Hitung harga per satuan
        $purchasePricePerUnit = $request->total_purchase_price / $request->quantity;

        DB::beginTransaction();
        try {
            // Simpan record barang masuk dengan tanggal kadaluarsa manual
            StockIncoming::create([
                'product_id' => $request->product_id,
                'branch_id' => $branchId,
                'user_id' => $userId,
                'quantity' => $request->quantity,
                'current_quantity' => $request->quantity,
                'purchase_price' => $purchasePricePerUnit,
                'expiry_date' => $request->expiry_date,
            ]);

            // Update stok (increment quantity, atau create jika belum ada)
            Stock::updateOrCreate(
                [
                    'product_id' => $request->product_id,
                    'branch_id' => $branchId,
                ],
                [] // No additional attributes on create
            )->increment('quantity', $request->quantity);

            // Catat pengeluaran untuk pembelian stock
            $productName = Product::find($request->product_id)->name ?? 'Produk';
            Expense::create([
                'branch_id' => $branchId,
                'description' => 'Pembelian stock : ' . $productName . ' (' . $request->quantity . ' unit)',
                'amount' => $request->total_purchase_price,
                'date' => now(),
            ]);

            DB::commit();

            // Jalankan pengecekan kadaluarsa secara instan agar notifikasi langsung muncul
            \Illuminate\Support\Facades\Artisan::call('stok:check-expiry');

            return redirect()->route('pages.admin.stok')
                ->with('success', 'Barang masuk berhasil dicatat. Stok terupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
