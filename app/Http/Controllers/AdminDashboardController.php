<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\StockIncoming;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $branchId = 1; // Hardcode, belum ada auth

        // Pendapatan hari ini
        $todayRevenue = Transaction::where('branch_id', $branchId)
            ->where('status', 'success')
            ->whereDate('created_at', today())
            ->sum('total_price');

        // Transaksi hari ini
        $todayTransactions = Transaction::where('branch_id', $branchId)
            ->where('status', 'success')
            ->whereDate('created_at', today())
            ->count();

        // Peringatan stok kadaluarsa (7 hari ke depan)
        $nearExpiryCount = StockIncoming::where('branch_id', $branchId)
            ->whereBetween('expiry_date', [today(), today()->addDays(7)])
            ->count();

        // Transaksi terbaru
        $recentTransactions = Transaction::where('branch_id', $branchId)
            ->with('cashierSession.user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages.admin.dashboard', compact(
            'todayRevenue',
            'todayTransactions',
            'nearExpiryCount',
            'recentTransactions',
            'branchId'
        ));
    }

    public function stok()
    {
        $branchId = 1; // Hardcode, belum ada auth

        // Query stok per produk beserta data kadaluarsa terbaru
        $stocks = Stock::where('stocks.branch_id', $branchId)
            ->with(['product.category', 'product.unit'])
            ->orderBy('quantity', 'asc')
            ->get();

        // Ambil expiry_date terbaru per produk dari stock_incomings
        $latestExpiries = StockIncoming::where('branch_id', $branchId)
            ->selectRaw('product_id, MAX(expiry_date) as latest_expiry, MIN(expiry_date) as earliest_expiry')
            ->groupBy('product_id')
            ->pluck('earliest_expiry', 'product_id');

        return view('pages.admin.stok', compact('stocks', 'latestExpiries', 'branchId'));
    }

    public function penjualan()
    {
        $branchId = 1;

        $transactions = Transaction::where('branch_id', $branchId)
            ->with(['items.product', 'cashierSession.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.admin.penjualan', compact('transactions', 'branchId'));
    }

    public function keuangan()
    {
        $branchId = 1;

        $transactions = Transaction::where('branch_id', $branchId)
            ->where('status', 'success')
            ->orderBy('created_at', 'desc')
            ->get();

        $cashTotal = $transactions->where('payment_method', 'cash')->sum('total_price');
        $qrisTotal = $transactions->where('payment_method', 'qris')->sum('total_price');
        $totalRevenue = $cashTotal + $qrisTotal;

        return view('pages.admin.keuangan', compact('transactions', 'cashTotal', 'qrisTotal', 'totalRevenue', 'branchId'));
    }
}
