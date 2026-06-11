<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('pages.admin.dashboard');
    }

    public function stok()
    {
        return view('pages.admin.stok');
    }

    public function penjualan()
    {
        $transactions = \App\Models\Transaction::with(['items.product', 'cashierSession.user'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pages.admin.penjualan', compact('transactions'));
    }

    public function keuangan()
    {
        return view('pages.admin.keuangan');
    }
}
