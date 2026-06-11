<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockIncoming;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    /**
     * Dashboard owner: ringkasan semua cabang.
     * Data transaksi, keuangan, dan stok terpisah per cabang.
     */
    public function dashboard()
    {
        $branches = Branch::all();

        $branchSummaries = [];

        foreach ($branches as $branch) {
            // Total pendapatan hari ini (transaksi sukses)
            $todayRevenue = Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->whereDate('created_at', today())
                ->sum('total_price');

            // Total transaksi hari ini
            $todayTransactions = Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->whereDate('created_at', today())
                ->count();

            // Total semua pendapatan (all time)
            $totalRevenue = Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->sum('total_price');

            // Total semua transaksi
            $totalTransactions = Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->count();

            // Stok rendah (quantity < 20)
            $lowStockCount = Stock::where('branch_id', $branch->id)
                ->where('quantity', '<', 20)
                ->count();

            // Barang mendekati kadaluarsa (dalam 30 hari ke depan)
            $nearExpiryCount = StockIncoming::where('branch_id', $branch->id)
                ->whereBetween('expiry_date', [today(), today()->addDays(30)])
                ->count();

            // Total produk
            $totalProducts = Product::where('branch_id', $branch->id)->count();

            // Total stok (sum quantity)
            $totalStock = Stock::where('branch_id', $branch->id)->sum('quantity');

            $branchSummaries[] = [
                'branch' => $branch,
                'today_revenue' => $todayRevenue,
                'today_transactions' => $todayTransactions,
                'total_revenue' => $totalRevenue,
                'total_transactions' => $totalTransactions,
                'low_stock_count' => $lowStockCount,
                'near_expiry_count' => $nearExpiryCount,
                'total_products' => $totalProducts,
                'total_stock' => $totalStock,
            ];
        }

        return view('pages.owner.dashboard', compact('branchSummaries'));
    }

    /**
     * Detail per cabang: transaksi, keuangan, stok terpisah.
     */
    public function detailCabang($branchId)
    {
        $branch = Branch::findOrFail($branchId);

        // Transaksi terbaru cabang ini
        $transactions = Transaction::where('branch_id', $branchId)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Stok produk cabang ini
        $stocks = Stock::where('branch_id', $branchId)
            ->with('product')
            ->orderBy('quantity', 'asc')
            ->get();

        // Barang masuk terbaru
        $stockIncomings = StockIncoming::where('branch_id', $branchId)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Ringkasan keuangan
        $totalRevenue = Transaction::where('branch_id', $branchId)
            ->where('status', 'success')
            ->sum('total_price');

        $todayRevenue = Transaction::where('branch_id', $branchId)
            ->where('status', 'success')
            ->whereDate('created_at', today())
            ->sum('total_price');

        $totalTransactions = Transaction::where('branch_id', $branchId)
            ->where('status', 'success')
            ->count();

        $cashRevenue = Transaction::where('branch_id', $branchId)
            ->where('status', 'success')
            ->where('payment_method', 'cash')
            ->sum('total_price');

        $qrisRevenue = Transaction::where('branch_id', $branchId)
            ->where('status', 'success')
            ->where('payment_method', 'qris')
            ->sum('total_price');

        return view('pages.owner.detail-cabang', compact(
            'branch',
            'transactions',
            'stocks',
            'stockIncomings',
            'totalRevenue',
            'todayRevenue',
            'totalTransactions',
            'cashRevenue',
            'qrisRevenue'
        ));
    }
}
