<?php

namespace App\Http\Controllers;

use App\Models\CashierSession;
use App\Models\Expense;
use App\Models\Stock;
use App\Models\StockIncoming;
use App\Models\Transaction;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $branchId = auth()->user()->branch_id;

        $dateParam = $request->query('date', today()->format('Y-m-d'));
        $targetDate = Carbon::parse($dateParam);

        // Pendapatan hari ini
        $todayRevenue = Transaction::where('branch_id', $branchId)
            ->where('status', 'success')
            ->whereDate('created_at', $targetDate)
            ->sum('total_price');

        // Transaksi hari ini
        $todayTransactions = Transaction::where('branch_id', $branchId)
            ->where('status', 'success')
            ->whereDate('created_at', $targetDate)
            ->count();

        // Peringatan stok kadaluarsa (7 hari ke depan)
        $nearExpiryCount = StockIncoming::where('branch_id', $branchId)
            ->whereBetween('expiry_date', [today(), today()->addDays(7)])
            ->count();

        // Aktivitas kasir terbaru (per sesi)
        $recentSessions = CashierSession::where('branch_id', $branchId)
            ->whereDate('date', $targetDate)
            ->with('user')
            ->withCount(['transactions as total_transactions' => function ($q) use ($targetDate) {
                $q->where('status', 'success')
                  ->whereDate('created_at', $targetDate);
            }])
            ->withSum(['transactions as total_revenue' => function ($q) use ($targetDate) {
                $q->where('status', 'success')
                  ->whereDate('created_at', $targetDate);
            }], 'total_price')
            ->orderBy('started_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages.admin.dashboard', compact(
            'todayRevenue',
            'todayTransactions',
            'nearExpiryCount',
            'recentSessions',
            'branchId',
            'targetDate'
        ));
    }

    public function stok()
    {
        $branchId = auth()->user()->branch_id;

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

    public function penjualan(Request $request)
    {
        $branchId = auth()->user()->branch_id;

        $query = Transaction::where('branch_id', $branchId)
            ->with(['items.product', 'cashierSession.user'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhereHas('cashierSession.user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status') && $request->status !== 'Semua') {
            $status = $request->status;
            if ($status === 'Berhasil') {
                $query->whereIn('status', ['success', 'Selesai']);
            } elseif ($status === 'Batal') {
                $query->whereIn('status', ['failed', 'Batal']);
            } elseif ($status === 'Pending') {
                $query->whereIn('status', ['waiting', 'pending']);
            } elseif ($status === 'Retur') {
                $query->whereIn('status', ['rollback', 'returned', 'Retur']);
            }
        }

        $transactions = $query->paginate(50)->withQueryString();

        return view('pages.admin.penjualan', compact('transactions', 'branchId'));
    }

    public function keuangan(Request $request)
    {
        $branchId = auth()->user()->branch_id;
        $filter = $request->query('waktu', 'Hari');

        $query = Transaction::with(['items'])->where('branch_id', $branchId)->where('status', 'success');
        $expenseQuery = Expense::where('branch_id', $branchId);

        $now = now();
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

        $groupedTransactions = $transactions->groupBy(function($item) use ($dateFormat) {
            return $item->created_at->format($dateFormat);
        });

        $groupedExpenses = $expenses->groupBy(function($item) use ($dateFormat) {
            return Carbon::parse($item->date)->format($dateFormat);
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

        $period = CarbonPeriod::create($startDate, $periodStep, $now);

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

        $page = Paginator::resolveCurrentPage() ?: 1;
        $perPage = 10;
        $tablePaginated = new LengthAwarePaginator(
            $tableData->forPage($page, $perPage)->values(),
            $tableData->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath(), 'query' => request()->query()]
        );

        if ($request->ajax()) {
            return response()->json([
                'cashTotal' => $cashTotal,
                'qrisTotal' => $qrisTotal,
                'totalRevenue' => $totalRevenue,
                'tableData' => collect($tablePaginated->items())->values(),
                'pagination' => (string) $tablePaginated->onEachSide(0)->links('pagination::tailwind'),
                'chartData' => $chartData,
                'filter' => $filter
            ]);
        }

        return view('pages.admin.keuangan', compact(
            'cashTotal', 'qrisTotal', 'totalRevenue', 'tableData', 'tablePaginated', 'chartData', 'filter', 'branchId'
        ));
    }
}
