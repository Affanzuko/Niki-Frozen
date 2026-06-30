<?php

use App\Http\Controllers\KasirController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StockIncomingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OwnerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GmController;

Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'owner') return redirect()->route('pages.owner.dashboard');
        if ($role === 'general_manager') return redirect()->route('pages.general-manager.dashboard');
        if ($role === 'admin') return redirect()->route('pages.admin.dashboard');
        if ($role === 'cashier') return redirect()->route('pages.kasir.dashboard');
    }
    return redirect()->route('login');
});

Route::get('/login', function () {
    if (auth()->check()) {
        return redirect('/');
    }
    return view('auth.login');
})->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::prefix('kasir')->middleware(['role:cashier'])->name('pages.kasir.')->group(function () {
    Route::get('/', [KasirController::class,'index'])->name('dashboard');
    Route::get('penjualan', [KasirController::class, 'penjualan'])->name('penjualan');
    Route::post('checkout', [PaymentController::class, 'checkoutKasir'])->name('checkout');
    Route::post('checkout/cash', [PaymentController::class, 'checkoutCash'])->name('checkout.cash');
    Route::post('checkout/success', [PaymentController::class, 'markSuccessLocal'])->name('checkout.success');
    Route::post('midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');
    Route::post('cetak-struk', [KasirController::class, 'cetakStruk'])->name('cetakStruk');
});

Route::prefix('admin')->middleware(['role:admin'])->name('pages.admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('stok', [AdminDashboardController::class, 'stok'])->name('stok');
    Route::get('penjualan', [AdminDashboardController::class, 'penjualan'])->name('penjualan');
    Route::get('keuangan', [AdminDashboardController::class, 'keuangan'])->name('keuangan');
    Route::post('/promotions', [\App\Http\Controllers\PromotionController::class, 'store'])->name('promotions.store');
    Route::get('stok/barang-masuk', [StockIncomingController::class, 'create'])->name('stok.barang-masuk.create');
    Route::post('stok/barang-masuk', [StockIncomingController::class, 'store'])->name('stok.barang-masuk.store');
    Route::get('stok/edit/{id}', [ProductController::class, 'editProduct'])->name('stok.edit');
    Route::put('stok/edit/{id}', [ProductController::class, 'updateProduct'])->name('stok.update');
    Route::delete('stok/{id}', [ProductController::class, 'destroy'])->name('stok.destroy');
    Route::put('stok/batch/{id}', [\App\Http\Controllers\StockBatchController::class, 'update'])->name('stok.batch.update');
    Route::delete('stok/batch/{id}', [\App\Http\Controllers\StockBatchController::class, 'destroy'])->name('stok.batch.destroy');
    Route::get('kelola-harga', [ProductController::class, 'index'])->name('kelola-harga');
    Route::put('kelola-harga/{id}', [ProductController::class, 'update'])->name('kelola-harga.update');
    Route::post('kelola-harga/{id}/discount', [ProductController::class, 'addDiscount'])->name('kelola-harga.discount');
});

Route::prefix('gm')->middleware(['role:general_manager'])->name('pages.general-manager.')->group(function () {
    Route::get('/', [GmController::class, 'dashboard'])->name('dashboard');
    Route::get('keuangan', [GmController::class, 'keuangan'])->name('keuangan');
    Route::get('keuangan/{cabang}', [GmController::class, 'detailKeuangan'])->name('keuangan-detail');
    Route::get('kelola-akun', [GmController::class, 'kelolaAkun'])->name('kelola-akun');
    Route::get('tambah-akun', [GmController::class, 'tambahAkun'])->name('tambah-akun');
    Route::post('tambah-akun', [GmController::class, 'storeAkun'])->name('store-akun');
    Route::get('edit-akun/{id}', [GmController::class, 'editAkun'])->name('edit-akun');
    Route::put('edit-akun/{id}', [GmController::class, 'updateAkun'])->name('update-akun');
    Route::delete('hapus-akun/{id}', [GmController::class, 'destroyAkun'])->name('hapus-akun');
    Route::get('stok', [GmController::class, 'stok'])->name('stok');
});

Route::prefix('owner')->middleware(['role:owner'])->name('pages.owner.')->group(function () {
    Route::get('/', [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/keuangan', [OwnerController::class, 'keuangan'])->name('keuangan');
    Route::get('/keuangan/{cabang}', [OwnerController::class, 'detailKeuangan'])->name('keuangan-detail');
    Route::get('/stok', [OwnerController::class, 'stok'])->name('stok');
});