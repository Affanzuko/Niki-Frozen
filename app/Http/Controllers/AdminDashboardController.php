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
        return view('pages.admin.penjualan');
    }

    public function keuangan()
    {
        return view('pages.admin.keuangan');
    }
}
