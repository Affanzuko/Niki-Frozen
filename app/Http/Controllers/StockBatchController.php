<?php

namespace App\Http\Controllers;

use App\Models\StockIncoming;
use App\Models\Stock;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StockBatchController extends Controller
{
    /**
     * Memperbarui detail batch (Sisa stok dan Tanggal Kadaluarsa)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'current_quantity' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
        ]);

        $batch = StockIncoming::findOrFail($id);
        $productId = $batch->product_id;
        $branchId = $batch->branch_id;
        
        $batch->update([
            'current_quantity' => $request->current_quantity,
            'expiry_date' => $request->expiry_date,
        ]);

        // Sesuaikan kuota promo untuk batch ini jika stok fisik berkurang
        $promotion = \App\Models\Promotion::where('stock_incoming_id', $id)
            ->where('is_active', true)
            ->first();

        if ($promotion) {
            if ($request->current_quantity <= 0) {
                $promotion->update(['is_active' => false, 'quota' => 0]);
            } elseif ($promotion->quota > $request->current_quantity) {
                $promotion->update(['quota' => $request->current_quantity]);
            }
        }

        // Rekalkulasi total stok produk
        $this->recalculateStock($productId, $branchId);

        return redirect()->back()
            ->with('success', 'Detail batch berhasil diperbarui.');
    }

    /**
     * Menghapus batch
     */
    public function destroy($id)
    {
        $batch = StockIncoming::findOrFail($id);
        $productId = $batch->product_id;
        $branchId = $batch->branch_id;

        $batch->delete();

        // Rekalkulasi total stok produk
        $this->recalculateStock($productId, $branchId);

        return redirect()->back()
            ->with('success', 'Batch berhasil dihapus.');
    }

    /**
     * Rekalkulasi total stok dari semua batch yang ada untuk produk terkait
     */
    private function recalculateStock($productId, $branchId)
    {
        $totalQuantity = StockIncoming::where('product_id', $productId)
            ->where('branch_id', $branchId)
            ->sum('current_quantity');

        Stock::updateOrCreate(
            ['product_id' => $productId, 'branch_id' => $branchId],
            ['quantity' => $totalQuantity]
        );
    }
}
