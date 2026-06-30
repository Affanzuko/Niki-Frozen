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
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'quota' => 'required|integer|min:1',
            'valid_until' => 'required|date',
        ]);

        $branchId = auth()->user()->branch_id;

        // Deactivate existing promotion for this product in this branch if any
        Promotion::where('product_id', $request->product_id)
            ->where('branch_id', $branchId)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        Promotion::create([
            'product_id' => $request->product_id,
            'branch_id' => $branchId,
            'created_by' => auth()->id(),
            'discount_percentage' => $request->discount_percentage,
            'valid_until' => $request->valid_until,
            'quota' => $request->quota,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Promo berhasil diterapkan!');
    }
}
