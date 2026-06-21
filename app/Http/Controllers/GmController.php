<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GmController extends Controller
{


    public function dashboard(Request $request)
    {
        $dateStr = $request->query('tanggal', today()->format('Y-m-d'));
        $dateObj = \Carbon\Carbon::parse($dateStr);
        
        $branches = \App\Models\Branch::all();
        $branchSummaries = [];

        foreach ($branches as $branch) {
            $todayRevenue = \App\Models\Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->whereDate('created_at', $dateObj)
                ->sum('total_price');

            $todayTransactions = \App\Models\Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->whereDate('created_at', $dateObj)
                ->count();

            $totalRevenue = \App\Models\Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->whereMonth('created_at', $dateObj->month)
                ->whereYear('created_at', $dateObj->year)
                ->sum('total_price');

            $totalTransactions = \App\Models\Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->whereMonth('created_at', $dateObj->month)
                ->whereYear('created_at', $dateObj->year)
                ->count();

            $todayPengeluaran = $todayRevenue * 0.65; // Estimated expense based on keuangan overview logic

            $branchSummaries[] = [
                'branch' => $branch,
                'today_revenue' => $todayRevenue,
                'today_pengeluaran' => $todayPengeluaran,
                'today_transactions' => $todayTransactions,
                'total_revenue' => $totalRevenue,
                'total_transactions' => $totalTransactions,
            ];
        }

        $totalKaryawan = \App\Models\User::where('role', '!=', 'owner')->count();

        if ($request->ajax()) {
            return response()->json([
                'branchSummaries' => $branchSummaries,
                'totalKaryawan' => $totalKaryawan,
            ]);
        }

        return view('pages.general-manager.dashboard', compact('branchSummaries', 'dateObj', 'totalKaryawan'));
    }

    public function keuangan(Request $request)
    {
        $branches = \App\Models\Branch::all();
        
        $filter = $request->query('waktu', 'Hari');
        
        $now = \Carbon\Carbon::now();

        if ($filter === 'Hari') {
            $startDate = $now->copy()->subDays(14)->startOfDay();
            $dateFormat = 'Y-m-d';
            $displayFormat = 'd/m';
            $periodStep = '1 day';
        } elseif ($filter === 'Minggu') {
            $startDate = $now->copy()->subWeeks(7)->startOfWeek();
            $dateFormat = 'o-W';
            $displayFormat = '\M\i\n\g\g\u W';
            $periodStep = '1 week';
        } elseif ($filter === 'Bulan') {
            $startDate = $now->copy()->startOfYear();
            $dateFormat = 'Y-m';
            $displayFormat = 'M Y';
            $periodStep = '1 month';
        } elseif ($filter === 'Tahun') {
            $startDate = $now->copy()->subYears(4)->startOfYear();
            $dateFormat = 'Y';
            $displayFormat = 'Y';
            $periodStep = '1 year';
        } else {
            $startDate = $now->copy()->subDays(14)->startOfDay();
            $dateFormat = 'Y-m-d';
            $displayFormat = 'd/m';
            $periodStep = '1 day';
        }

        $globalTransactions = \App\Models\Transaction::where('status', 'success')
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

        $trendChartData = collect();
        $period = \Carbon\CarbonPeriod::create($startDate, $periodStep, $now);

        foreach ($period as $date) {
            $key = $date->format($dateFormat);
            $displayDate = $date->translatedFormat($displayFormat);
            $trxs = $groupedGlobal->get($key, collect());
            
            $pendapatan = $trxs->sum('total_price');
            $cash = $trxs->where('payment_method', 'cash')->sum('total_price');
            $qris = $trxs->where('payment_method', 'qris')->sum('total_price');
            
            $trendChartData->push([
                'label' => $filter === 'Hari' ? $date->format('d/m') : $displayDate,
                'total' => $pendapatan,
                'cash' => $cash,
                'qris' => $qris
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'globalTotalPemasukan' => $globalTotalPemasukan,
                'globalTotalCash' => $globalTotalCash,
                'globalTotalQris' => $globalTotalQris,
                'globalTotalTransaksi' => $globalTotalTransaksi,
                'branchSummaries' => $branchSummaries,
                'trendChartData' => $trendChartData,
                'filter' => $filter
            ]);
        }

        return view('pages.general-manager.keuangan', compact(
            'branchSummaries', 
            'globalTotalPemasukan', 
            'globalTotalCash', 
            'globalTotalQris', 
            'globalTotalTransaksi',
            'trendChartData',
            'filter'
        ));
    }

    public function detailKeuangan(Request $request, $cabang)
    {
        $branch = \App\Models\Branch::where('name', $cabang)->firstOrFail();
        $branchId = $branch->id;

        $filter = $request->query('waktu', 'Hari');

        $query = \App\Models\Transaction::with(['items'])->where('branch_id', $branchId)->where('status', 'success');
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

        return view('pages.general-manager.keuangan.keuangan-detail', compact(
            'cabang', 'branchId', 'cashTotal', 'qrisTotal', 'totalRevenue', 'totalTransaksi', 'tableData', 'tablePaginated', 'chartData', 'filter'
        ));
    }

    public function kelolaAkun(Request $request)
    {
        $query = \App\Models\User::where('role', '!=', 'owner')->with('branch');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && $request->role !== 'Semua Role') {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(10)->withQueryString();

        return view('pages.general-manager.kelola-akun', compact('users'));
    }

    public function tambahAkun()
    {
        $branches = \App\Models\Branch::all();
        return view('pages.general-manager.kelola-akun.tambah-akun', compact('branches'));
    }

    public function storeAkun(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,cashier',
            'branch_id' => 'nullable|exists:branches,id'
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'branch_id' => $request->role === 'general_manager' ? null : $request->branch_id,
        ]);

        return redirect()->route('pages.general-manager.kelola-akun')->with('success', 'Akun berhasil ditambahkan');
    }

    public function editAkun($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $branches = \App\Models\Branch::all();
        return view('pages.general-manager.kelola-akun.edit-akun', compact('user', 'branches'));
    }

    public function updateAkun(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'role' => $user->role === 'general_manager' ? 'required|in:general_manager' : 'required|in:admin,cashier',
            'branch_id' => 'nullable|exists:branches,id'
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        
        if ($user->role === 'general_manager') {
            // Role GM tidak bisa diubah menjadi role lain
            $user->role = 'general_manager';
        } else {
            $user->role = $request->role;
        }

        $user->branch_id = $user->role === 'general_manager' ? null : $request->branch_id;

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('pages.general-manager.kelola-akun')->with('success', 'Akun berhasil diperbarui');
    }

    public function destroyAkun($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->route('pages.general-manager.kelola-akun')->with('success', 'Akun berhasil dihapus');
    }
}
