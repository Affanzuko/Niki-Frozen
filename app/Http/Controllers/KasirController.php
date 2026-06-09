<?php

namespace App\Http\Controllers;

use App\Models\Product;

class KasirController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name', 'asc')->get();

        return view('dashboard_kasir', ['products' => $products]);
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

        return view('struk_pdf', compact('data', 'items'));
    }
}
