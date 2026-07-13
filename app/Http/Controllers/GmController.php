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

            $lowStockCount = \App\Models\Stock::where('branch_id', $branch->id)
                ->where('quantity', '<', 20)
                ->count();

            $nearExpiryCount = \App\Models\StockIncoming::where('branch_id', $branch->id)
                ->where('current_quantity', '>', 0)
                ->whereBetween('expiry_date', [$dateObj, $dateObj->copy()->addDays(30)])
                ->whereHas('product')
                ->count();

            $totalProducts = \App\Models\Product::where('branch_id', $branch->id)->count();

            $totalStock = \App\Models\Stock::where('branch_id', $branch->id)->sum('quantity');

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

        $globalTransactions = \App\Models\Transaction::with(['items'])->where('status', 'success')
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
        $latestPurchasePrices = \App\Models\StockIncoming::orderBy('created_at', 'desc')
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

        return view('pages.general-manager.keuangan', compact(
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
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.string' => 'Username harus berupa teks.',
            'username.max' => 'Username maksimal 255 karakter.',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 6 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'branch_id.exists' => 'Cabang yang dipilih tidak ditemukan.',
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
            'password' => 'nullable|string|min:6',
            'role' => $user->role === 'general_manager' ? 'required|in:general_manager' : 'required|in:admin,cashier',
            'branch_id' => 'nullable|exists:branches,id'
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.string' => 'Username harus berupa teks.',
            'username.max' => 'Username maksimal 255 karakter.',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 6 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'branch_id.exists' => 'Cabang yang dipilih tidak ditemukan.',
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

    public function stok(Request $request)
    {
        $branches = \App\Models\Branch::all();
        $selectedBranchId = $request->query('cabang', 'Semua');

        $query = \App\Models\Stock::with(['product.category', 'product.unit', 'branch']);

        if ($selectedBranchId !== 'Semua') {
            $query->where('branch_id', $selectedBranchId);
        }

        $stocks = $query->orderBy('quantity', 'asc')->get();

        $activeBatchesQuery = \App\Models\StockIncoming::where('current_quantity', '>', 0)
            ->with('branch')
            ->orderBy('expiry_date', 'asc');

        if ($selectedBranchId !== 'Semua') {
            $activeBatchesQuery->where('branch_id', $selectedBranchId);
        }

        $activeBatches = $activeBatchesQuery->get()->groupBy(function($item) {
            return $item->product_id . '-' . $item->branch_id;
        });

        $incomingsQuery = \App\Models\StockIncoming::with(['product', 'branch', 'user'])->orderBy('created_at', 'desc');
        if ($selectedBranchId !== 'Semua') {
            $incomingsQuery->where('branch_id', $selectedBranchId);
        }
        $incomings = $incomingsQuery->get();

        return view('pages.general-manager.stok', compact('stocks', 'activeBatches', 'branches', 'selectedBranchId', 'incomings'));
    }
}
