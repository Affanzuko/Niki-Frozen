<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;

class KasirController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('name', 'asc')->get();
        
        $categories = ProductCategory::orderBy('name', 'asc')->get();
        // Pindahkan "Lainnya" ke urutan paling akhir
        $lainnya = $categories->firstWhere('name', 'Lainnya');
        if ($lainnya) {
            $categories = $categories->where('name', '!=', 'Lainnya')->push($lainnya);
        }

        return view('pages.kasir.dashboard', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function penjualan()
    {
        $transactions = \App\Models\Transaction::with(['items.product', 'cashierSession.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.kasir.penjualan', compact('transactions'));
    }

    public function cetakStruk(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'items' => 'required|string',
            'method' => 'required|string',
            'kembalian' => 'nullable|numeric',
            'received' => 'nullable|numeric',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $items = json_decode($data['items'], true);

        return view('pages.kasir.penjualan.struk-pdf', compact('data', 'items'));
    }
}
