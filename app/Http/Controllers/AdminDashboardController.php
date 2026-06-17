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

        // Ambil batch aktif per produk dari stock_incomings
        $activeBatches = StockIncoming::where('branch_id', $branchId)
            ->where('current_quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->get()
            ->groupBy('product_id');

        return view('pages.admin.stok', compact('stocks', 'activeBatches', 'branchId'));
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

    public function keuangan(Request $request)
    {
        $branchId = 1;
        $filter = $request->query('waktu', 'Hari');

        $query = Transaction::with(['items'])->where('branch_id', $branchId)->where('status', 'success');
        $expenseQuery = \App\Models\Expense::where('branch_id', $branchId);

        $now = now();
        if ($filter === 'Hari') {
            $startDate = $now->copy()->subDays(6)->startOfDay();
            $dateFormat = 'Y-m-d';
            $displayFormat = 'l, d M Y';
            $periodStep = '1 day';
        } elseif ($filter === 'Minggu') {
            $startDate = $now->copy()->subWeeks(4)->startOfWeek();
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
            $startDate = $now->copy()->subDays(6)->startOfDay();
            $dateFormat = 'Y-m-d';
            $displayFormat = 'l, d M Y';
            $periodStep = '1 day';
        }

        $query->where('created_at', '>=', $startDate);
        $expenseQuery->where('date', '>=', $startDate);

        $transactions = $query->get();
        $expenses = $expenseQuery->get();

        $cashTotal = $transactions->where('payment_method', 'cash')->sum('total_price');
        $qrisTotal = $transactions->where('payment_method', 'qris')->sum('total_price');
        $totalRevenue = $cashTotal + $qrisTotal;

        $groupedTransactions = $transactions->groupBy(function($item) use ($dateFormat) {
            return $item->created_at->format($dateFormat);
        });
        
        $groupedExpenses = $expenses->groupBy(function($item) use ($dateFormat) {
            return \Carbon\Carbon::parse($item->date)->format($dateFormat);
        });

        $latestPurchasePrices = \App\Models\StockIncoming::where('branch_id', $branchId)
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
                'label' => $filter === 'Hari' ? $date->translatedFormat('D, d M') : $displayDate,
                'total' => $pendapatan
            ]);
        }
        
        $tableData = $tableData->reverse();

        return view('pages.admin.keuangan', compact(
            'cashTotal', 'qrisTotal', 'totalRevenue', 'tableData', 'chartData', 'filter', 'branchId'
        ));
    }
}
