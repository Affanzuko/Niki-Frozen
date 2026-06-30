<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'stock_incoming_id' => 'required|exists:stock_incomings,id',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'quota' => 'required|integer|min:1',
            'valid_until' => 'required|date',
        ]);

        $branchId = auth()->user()->branch_id;

        // Deactivate existing promotion for this batch in this branch if any
        Promotion::where('stock_incoming_id', $request->stock_incoming_id)
            ->where('branch_id', $branchId)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        Promotion::create([
            'product_id' => $request->product_id,
            'stock_incoming_id' => $request->stock_incoming_id,
            'branch_id' => $branchId,
            'created_by' => auth()->id(),
            'discount_percentage' => $request->discount_percentage,
            'valid_until' => $request->valid_until,
            'quota' => $request->quota,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Promo berhasil diterapkan!');
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        
        // Ensure only active branch can delete its promo
        if ($promotion->branch_id == auth()->user()->branch_id) {
            $promotion->delete();
            return redirect()->back()->with('success', 'Promo berhasil dihentikan/dihapus.');
        }

        return redirect()->back()->withErrors('Anda tidak memiliki akses untuk menghapus promo ini.');
    }
}
