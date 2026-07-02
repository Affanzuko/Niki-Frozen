<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $branchId = auth()->user()->branch_id;
        
        $dateParam = $request->query('date');
        
        $query = Expense::where('branch_id', $branchId);
        
        if ($dateParam) {
            $query->whereDate('date', $dateParam);
        }
        
        $expenses = $query->orderBy('date', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);
                          
        return view('pages.admin.pengeluaran', compact('expenses', 'dateParam'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        Expense::create([
            'branch_id' => auth()->user()->branch_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('pages.admin.pengeluaran')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $expense = Expense::where('id', $id)->where('branch_id', auth()->user()->branch_id)->firstOrFail();
        
        $expense->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('pages.admin.pengeluaran')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $expense = Expense::where('id', $id)->where('branch_id', auth()->user()->branch_id)->firstOrFail();
        $expense->delete();

        return redirect()->route('pages.admin.pengeluaran')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
