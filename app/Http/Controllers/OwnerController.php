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
    public function dashboard(Request $request)
    {
        $dateStr = $request->query('tanggal', today()->format('Y-m-d'));
        $dateObj = \Carbon\Carbon::parse($dateStr);

        $branches = Branch::all();

        $branchSummaries = [];

        foreach ($branches as $branch) {
            // Total pendapatan hari ini (transaksi sukses)
            $todayRevenue = Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->whereDate('created_at', $dateObj)
                ->sum('total_price');

            // Total transaksi hari ini
            $todayTransactions = Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->whereDate('created_at', $dateObj)
                ->count();

            // Total semua pendapatan bulan ini
            $totalRevenue = Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->whereMonth('created_at', $dateObj->month)
                ->whereYear('created_at', $dateObj->year)
                ->sum('total_price');

            // Total semua transaksi bulan ini
            $totalTransactions = Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->whereMonth('created_at', $dateObj->month)
                ->whereYear('created_at', $dateObj->year)
                ->count();

            // Stok rendah (quantity < 20)
            $lowStockCount = Stock::where('branch_id', $branch->id)
                ->where('quantity', '<', 20)
                ->count();

            // Barang mendekati kadaluarsa (dalam 30 hari ke depan)
            $nearExpiryCount = StockIncoming::where('branch_id', $branch->id)
                ->whereBetween('expiry_date', [$dateObj, $dateObj->copy()->addDays(30)])
                ->count();

            // Total produk
            $totalProducts = Product::where('branch_id', $branch->id)->count();

            // Total stok (sum quantity)
            $totalStock = Stock::where('branch_id', $branch->id)->sum('quantity');

            $todayPengeluaran = $todayRevenue * 0.65; // Estimasi pengeluaran berdasarkan aturan keuangan

            $branchSummaries[] = [
                'branch' => $branch,
                'today_revenue' => $todayRevenue,
                'today_pengeluaran' => $todayPengeluaran,
                'today_transactions' => $todayTransactions,
                'total_revenue' => $totalRevenue,
                'total_transactions' => $totalTransactions,
                'low_stock_count' => $lowStockCount,
                'near_expiry_count' => $nearExpiryCount,
                'total_products' => $totalProducts,
                'total_stock' => $totalStock,
            ];
        }

        return view('pages.owner.dashboard', compact('branchSummaries', 'dateObj'));
    }



    public function keuangan(Request $request)
    {
        $branches = Branch::all();
        
        $filter = $request->query('waktu', 'Hari');
        
        $now = \Carbon\Carbon::now();

        if ($filter === 'Hari') {
            $startDate = $now->copy()->subDays(14)->startOfDay();
            $dateFormat = 'Y-m-d';
            $displayFormat = 'd/m';
            $tableDisplayFormat = 'l, d m Y';
            $periodStep = '1 day';
        } elseif ($filter === 'Minggu') {
            $startDate = $now->copy()->subWeeks(7)->startOfWeek();
            $dateFormat = 'o-W';
            $displayFormat = '\M\i\n\g\g\u W';
            $tableDisplayFormat = '\M\i\n\g\g\u W, Y';
            $periodStep = '1 week';
        } elseif ($filter === 'Bulan') {
            $startDate = $now->copy()->startOfYear();
            $dateFormat = 'Y-m';
            $displayFormat = 'M Y';
            $tableDisplayFormat = 'F Y';
            $periodStep = '1 month';
        } elseif ($filter === 'Tahun') {
            $startDate = $now->copy()->subYears(4)->startOfYear();
            $dateFormat = 'Y';
            $displayFormat = 'Y';
            $tableDisplayFormat = 'Y';
            $periodStep = '1 year';
        } else {
            $startDate = $now->copy()->subDays(14)->startOfDay();
            $dateFormat = 'Y-m-d';
            $displayFormat = 'd/m';
            $tableDisplayFormat = 'l, d m Y';
            $periodStep = '1 day';
        }

        $globalTransactions = Transaction::with(['items'])->where('status', 'success')
            ->whereBetween('created_at', [$startDate, $now])
            ->get();

        $globalTotalPemasukan = $globalTransactions->sum('total_price');
        $globalTotalCash = $globalTransactions->where('payment_method', 'cash')->sum('total_price');
        $globalTotalQris = $globalTransactions->where('payment_method', 'qris')->sum('total_price');
        $globalTotalTransaksi = $globalTransactions->count();

        $branchSummaries = [];
        foreach ($branches as $branch) {
            $branchTrxs = $globalTransactions->where('branch_id', $branch->id);
            $totalPemasukan = $branchTrxs->sum('total_price');
            $totalPengeluaran = $totalPemasukan * 0.65;
            $labaBersih = $totalPemasukan - $totalPengeluaran;
            $margin = $totalPemasukan > 0 ? ($labaBersih / $totalPemasukan) * 100 : 0;
            $status = $labaBersih >= 0 ? 'Profit' : 'Rugi';

            $branchSummaries[] = [
                'branch' => $branch,
                'total_pemasukan' => $totalPemasukan,
                'total_pengeluaran' => $totalPengeluaran,
                'laba_bersih' => $labaBersih,
                'margin' => $margin,
                'status' => $status,
            ];
        }

        $groupedGlobal = $globalTransactions->groupBy(function($item) use ($dateFormat) {
            return $item->created_at->format($dateFormat);
        });

        // Fetch all expenses across all branches for the period
        $globalExpenses = \App\Models\Expense::whereBetween('date', [$startDate, $now])->get();
        $groupedExpenses = $globalExpenses->groupBy(function($item) use ($dateFormat) {
            return \Carbon\Carbon::parse($item->date)->format($dateFormat);
        });

        // Fetch latest purchase prices for HPP calculation
        $latestPurchasePrices = StockIncoming::orderBy('created_at', 'desc')
            ->get()
            ->groupBy('product_id')
            ->map(function ($incomings) {
                return $incomings->first()->purchase_price ?? 0;
            });

        $trendChartData = collect();
        $globalTableData = collect();
        $saldoAkhir = 0;
        $period = \Carbon\CarbonPeriod::create($startDate, $periodStep, $now);

        foreach ($period as $date) {
            $key = $date->format($dateFormat);
            $displayDate = $date->translatedFormat($displayFormat);
            $trxs = $groupedGlobal->get($key, collect());
            $exps = $groupedExpenses->get($key, collect());
            
            $cash = $trxs->where('payment_method', 'cash')->sum('total_price');
            $qris = $trxs->where('payment_method', 'qris')->sum('total_price');
            $pendapatan = $cash + $qris;

            // Calculate HPP (Harga Pokok Penjualan)
            $hpp = 0;
            foreach ($trxs as $trx) {
                foreach ($trx->items as $item) {
                    $purchasePrice = $latestPurchasePrices->get($item->product_id, 0);
                    $hpp += ($purchasePrice * $item->quantity);
                }
            }

            $pengeluaran = $exps->sum('amount') + $hpp;
            $profit = $pendapatan - $pengeluaran;
            $saldoAkhir += $profit;
            
            $trendChartData->push([
                'label' => $filter === 'Hari' ? $date->format('d/m') : $displayDate,
                'total' => $pendapatan,
                'cash' => $cash,
                'qris' => $qris
            ]);

            $tableDisplayDate = $date->translatedFormat($tableDisplayFormat);

            $globalTableData->push([
                'waktu' => $tableDisplayDate,
                'cash' => $cash,
                'qris' => $qris,
                'pengeluaran' => $pengeluaran,
                'saldo_akhir' => $saldoAkhir,
                'profit' => $profit,
            ]);
        }

        // Reverse so newest is first
        $globalTableData = $globalTableData->reverse()->values();

        // Paginate globalTableData
        $page = $request->input('page', 1);
        $perPage = 10;
        $globalTablePaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $globalTableData->forPage($page, $perPage)->values(),
            $globalTableData->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => request()->query()]
        );

        if ($request->ajax()) {
            return response()->json([
                'globalTotalPemasukan' => $globalTotalPemasukan,
                'globalTotalCash' => $globalTotalCash,
                'globalTotalQris' => $globalTotalQris,
                'globalTotalTransaksi' => $globalTotalTransaksi,
                'branchSummaries' => $branchSummaries,
                'trendChartData' => $trendChartData,
                'globalTableData' => collect($globalTablePaginated->items())->values(),
                'globalTablePagination' => (string) $globalTablePaginated->onEachSide(0)->links('pagination::tailwind'),
                'filter' => $filter
            ]);
        }

        return view('pages.owner.keuangan', compact(
            'branchSummaries', 
            'globalTotalPemasukan', 
            'globalTotalCash', 
            'globalTotalQris', 
            'globalTotalTransaksi',
            'trendChartData',
            'globalTablePaginated',
            'filter'
        ));
    }

    public function detailKeuangan(Request $request, $cabang)
    {
        $branch = Branch::where('name', $cabang)->firstOrFail();
        $branchId = $branch->id;

        $filter = $request->query('waktu', 'Hari');

        $query = Transaction::with(['items'])->where('branch_id', $branchId)->where('status', 'success');
        $expenseQuery = \App\Models\Expense::where('branch_id', $branchId);

        $now = \Carbon\Carbon::now();
        if ($filter === 'Hari') {
            $startDate = $now->copy()->subDays(14)->startOfDay();
            $dateFormat = 'Y-m-d';
            $displayFormat = 'l, d m Y';
            $periodStep = '1 day';
        } elseif ($filter === 'Minggu') {
            $startDate = $now->copy()->subWeeks(7)->startOfWeek();
            $dateFormat = 'o-W';
            $displayFormat = '\M\i\n\g\g\u W, Y';
            $periodStep = '1 week';
        } elseif ($filter === 'Bulan') {
            $startDate = $now->copy()->startOfYear();
            $dateFormat = 'Y-m';
            $displayFormat = 'F Y';
            $periodStep = '1 month';
        } elseif ($filter === 'Tahun') {
            $startDate = $now->copy()->subYears(4)->startOfYear();
            $dateFormat = 'Y';
            $displayFormat = 'Y';
            $periodStep = '1 year';
        } else {
            $startDate = $now->copy()->subDays(14)->startOfDay();
            $dateFormat = 'Y-m-d';
            $displayFormat = 'l, d m Y';
            $periodStep = '1 day';
        }

        $query->where('created_at', '>=', $startDate);
        $expenseQuery->where('date', '>=', $startDate);

        $transactions = $query->get();
        $expenses = $expenseQuery->get();

        $cashTotal = $transactions->where('payment_method', 'cash')->sum('total_price');
        $qrisTotal = $transactions->where('payment_method', 'qris')->sum('total_price');
        $totalRevenue = $cashTotal + $qrisTotal;
        $totalTransaksi = $transactions->count();

        $groupedTransactions = $transactions->groupBy(function($item) use ($dateFormat) {
            return $item->created_at->format($dateFormat);
        });
        
        $groupedExpenses = $expenses->groupBy(function($item) use ($dateFormat) {
            return \Carbon\Carbon::parse($item->date)->format($dateFormat);
        });

        $latestPurchasePrices = StockIncoming::where('branch_id', $branchId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('product_id')
            ->map(function ($incomings) {
                return $incomings->first()->purchase_price ?? 0;
            });

        $tableData = collect();
        $chartData = collect();
        $saldoAkhir = 0; 

        $period = \Carbon\CarbonPeriod::create($startDate, $periodStep, $now);

        foreach ($period as $date) {
            $key = $date->format($dateFormat);
            $displayDate = $date->translatedFormat($displayFormat);

            $trxs = $groupedTransactions->get($key, collect());
            $exps = $groupedExpenses->get($key, collect());

            $cash = $trxs->where('payment_method', 'cash')->sum('total_price');
            $qris = $trxs->where('payment_method', 'qris')->sum('total_price');
            
            $hpp = 0;
            foreach ($trxs as $trx) {
                foreach ($trx->items as $item) {
                    $purchasePrice = $latestPurchasePrices->get($item->product_id, 0);
                    $hpp += ($purchasePrice * $item->quantity);
                }
            }

            $pengeluaran = $exps->sum('amount') + $hpp;
            $pendapatan = $cash + $qris;
            $profit = $pendapatan - $pengeluaran;
            $saldoAkhir += $profit;

            $tableData->push([
                'waktu' => $displayDate,
                'cash' => $cash,
                'qris' => $qris,
                'pengeluaran' => $pengeluaran,
                'saldo_akhir' => $saldoAkhir,
                'profit' => $profit
            ]);

            $chartData->push([
                'label' => $filter === 'Hari' ? $date->format('d/m') : $displayDate,
                'total' => $pendapatan,
                'cash' => $cash,
                'qris' => $qris,
            ]);
        }
        
        $tableData = $tableData->reverse();

        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 10;
        $tablePaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $tableData->forPage($page, $perPage)->values(),
            $tableData->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => request()->query()]
        );

        if ($request->ajax()) {
            return response()->json([
                'cashTotal' => $cashTotal,
                'qrisTotal' => $qrisTotal,
                'totalRevenue' => $totalRevenue,
                'totalTransaksi' => $totalTransaksi,
                'tableData' => collect($tablePaginated->items())->values(),
                'pagination' => (string) $tablePaginated->onEachSide(0)->links('pagination::tailwind'),
                'chartData' => $chartData,
                'filter' => $filter
            ]);
        }

        return view('pages.owner.keuangan.keuangan-detail', compact(
            'cabang', 'branchId', 'cashTotal', 'qrisTotal', 'totalRevenue', 'totalTransaksi', 'tableData', 'tablePaginated', 'chartData', 'filter'
        ));
    }

    public function stok(Request $request)
    {
        $branches = Branch::all();
        $selectedBranchId = $request->query('cabang', 'Semua');

        $query = Stock::with(['product.category', 'product.unit', 'branch']);

        if ($selectedBranchId !== 'Semua') {
            $query->where('branch_id', $selectedBranchId);
        }

        $stocks = $query->orderBy('quantity', 'asc')->get();

        $activeBatchesQuery = StockIncoming::where('current_quantity', '>', 0)
            ->with('branch')
            ->orderBy('expiry_date', 'asc');

        if ($selectedBranchId !== 'Semua') {
            $activeBatchesQuery->where('branch_id', $selectedBranchId);
        }

        $activeBatches = $activeBatchesQuery->get()->groupBy(function($item) {
            return $item->product_id . '-' . $item->branch_id;
        });

        $incomingsQuery = StockIncoming::with(['product', 'branch', 'user'])->orderBy('created_at', 'desc');
        if ($selectedBranchId !== 'Semua') {
            $incomingsQuery->where('branch_id', $selectedBranchId);
        }
        $incomings = $incomingsQuery->get();

        return view('pages.owner.stok', compact('stocks', 'activeBatches', 'branches', 'selectedBranchId', 'incomings'));
    }
}
