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
}
