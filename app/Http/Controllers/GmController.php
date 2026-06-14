<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GmController extends Controller
{


    public function dashboard()
    {
        $branches = \App\Models\Branch::all();
        $branchSummaries = [];

        foreach ($branches as $branch) {
            $todayRevenue = \App\Models\Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->whereDate('created_at', today())
                ->sum('total_price');

            $todayTransactions = \App\Models\Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->whereDate('created_at', today())
                ->count();

            $totalRevenue = \App\Models\Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->sum('total_price');

            $totalTransactions = \App\Models\Transaction::where('branch_id', $branch->id)
                ->where('status', 'success')
                ->count();

            $branchSummaries[] = [
                'branch' => $branch,
                'today_revenue' => $todayRevenue,
                'today_transactions' => $todayTransactions,
                'total_revenue' => $totalRevenue,
                'total_transactions' => $totalTransactions,
            ];
        }

        return view('pages.general-manager.dashboard', compact('branchSummaries'));
    }

    public function keuangan()
    {
        return view('pages.general-manager.keuangan');
    }

    public function detailKeuangan($cabang)
    {
        return view('pages.general-manager.keuangan.keuangan-detail', compact('cabang'));
    }

    public function kelolaAkun()
    {
        return view('pages.general-manager.kelola-akun');
    }

    public function tambahAkun()
    {
        return view('pages.general-manager.kelola-akun.tambah-akun');
    }

    public function editAkun($id)
    {
        return view('pages.general-manager.kelola-akun.edit-akun', compact('id'));
    }
}
