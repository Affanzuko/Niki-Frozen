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

    public function penjualan()
    {
        // Dummy data untuk riwayat penjualan sesuai desain
        $transactions = [
            ['waktu' => 'Hari ini, 14:32', 'no_ref' => 'POS-8921-A', 'total' => 135975, 'metode' => 'QRIS', 'status' => 'Selesai'],
            ['waktu' => 'Hari ini, 13:15', 'no_ref' => 'POS-8920-B', 'total' => 450000, 'metode' => 'Tunai', 'status' => 'Selesai'],
            ['waktu' => 'Hari ini, 11:45', 'no_ref' => 'POS-8919-C', 'total' => 85500, 'metode' => 'Tunai', 'status' => 'Batal'],
            ['waktu' => 'Kemarin, 16:20', 'no_ref' => 'POS-8918-D', 'total' => 1250000, 'metode' => 'Kartu Kredit', 'status' => 'Selesai'],
            ['waktu' => 'Kemarin, 09:30', 'no_ref' => 'POS-8917-E', 'total' => 215000, 'metode' => 'QRIS', 'status' => 'Selesai'],
        ];

        return view('penjualan_kasir', compact('transactions'));
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
