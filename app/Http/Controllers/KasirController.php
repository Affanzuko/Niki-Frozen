<?php

namespace App\Http\Controllers;

use App\Models\CashierSession;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Stock;
use App\Models\Transaction;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('name', 'asc')->get();

        $branchId = auth()->user()->branch_id;
        $stocks = Stock::where('branch_id', $branchId)
            ->with(['product.category'])
            ->where('quantity', '>', 0)
            ->get();

        foreach ($stocks as $stock) {
            $oldestBatch = \App\Models\StockIncoming::where('product_id', $stock->product_id)
                ->where('branch_id', $branchId)
                ->where('current_quantity', '>', 0)
                ->orderBy('expiry_date', 'asc')
                ->first();
                
            $stock->product->promotions = collect();
            
            if ($oldestBatch) {
                $promotion = \App\Models\Promotion::where('stock_incoming_id', $oldestBatch->id)
                    ->where('is_active', true)
                    ->where('valid_until', '>=', now())
                    ->where(function($q) {
                        $q->whereNull('quota')->orWhere('quota', '>', 0);
                    })
                    ->first();
                    
                if ($promotion) {
                    $stock->product->promotions->push($promotion);
                }
            }

            if ($stock->product->promotions->isEmpty()) {
                $productPromotion = \App\Models\Promotion::where('product_id', $stock->product_id)
                    ->whereNull('stock_incoming_id')
                    ->where('is_active', true)
                    ->where('valid_until', '>=', now())
                    ->first();
                    
                if ($productPromotion) {
                    $stock->product->promotions->push($productPromotion);
                }
            }
        }

        $categories = ProductCategory::orderBy('name', 'asc')->get();
        // Pindahkan "Lainnya" ke urutan paling akhir
        $lainnya = $categories->firstWhere('name', 'Lainnya');
        if ($lainnya) {
            $categories = $categories->where('name', '!=', 'Lainnya')->push($lainnya);
        }

        // Ambil sesi kasir aktif (belum ended) untuk user ini
        $cashierSession = CashierSession::where('user_id', auth()->id())
            ->where('branch_id', $branchId)
            ->whereNull('ended_at')
            ->latest()
            ->first();
        $cashierSessionId = $cashierSession ? $cashierSession->id : null;

        return view('pages.kasir.dashboard', [
            'products' => $products,
            'categories' => $categories,
            'stocks' => $stocks,
            'branchId' => $branchId,
            'cashierSessionId' => $cashierSessionId,
        ]);
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

        return view('pages.kasir.penjualan', compact('transactions'));
    }

    public function cetakStruk(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|string',
            'method' => 'required|string',
            'kembalian' => 'nullable|numeric',
            'received' => 'nullable|numeric',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $items = json_decode($data['items'], true);

        return view('pages.kasir.penjualan.struk-pdf', compact('data', 'items'));
    }
}
