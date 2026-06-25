<?php

namespace App\Http\Controllers;

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
        $stocks = Stock::with('product')->where('branch_id', $branchId)->orderBy('product_id', 'asc')->get();

        $categories = ProductCategory::orderBy('name', 'asc')->get();
        // Pindahkan "Lainnya" ke urutan paling akhir
        $lainnya = $categories->firstWhere('name', 'Lainnya');
        if ($lainnya) {
            $categories = $categories->where('name', '!=', 'Lainnya')->push($lainnya);
        }

        return view('pages.kasir.dashboard', [
            'products' => $products,
            'categories' => $categories,
            'stocks' => $stocks
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
