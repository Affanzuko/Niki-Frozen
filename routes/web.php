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
    return redirect()->route('pages.kasir.dashboard');
});

Route::prefix('kasir')->name('pages.kasir.')->group(function () {
    Route::get('/', [KasirController::class,'index'])->name('dashboard');
    Route::get('penjualan', [KasirController::class, 'penjualan'])->name('penjualan');
    Route::post('checkout', [PaymentController::class, 'checkoutKasir'])->name('checkout');
    Route::post('midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');
    Route::post('cetak-struk', [KasirController::class, 'cetakStruk'])->name('cetakStruk');
});

Route::prefix('admin')->name('pages.admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('stok', [AdminDashboardController::class, 'stok'])->name('stok');
    Route::get('penjualan', [AdminDashboardController::class, 'penjualan'])->name('penjualan');
    Route::get('keuangan', [AdminDashboardController::class, 'keuangan'])->name('keuangan');
    Route::get('barang-masuk', [StockIncomingController::class, 'create'])->name('barang-masuk.create');
    Route::post('barang-masuk', [StockIncomingController::class, 'store'])->name('barang-masuk.store');
    Route::get('kelola-harga', [ProductController::class, 'index'])->name('kelola-harga');
    Route::put('kelola-harga/{id}', [ProductController::class, 'update'])->name('kelola-harga.update');
});

Route::prefix('gm')->name('pages.general-manager.')->group(function () {
    Route::get('/', [GmController::class, 'dashboard'])->name('dashboard');
    Route::get('keuangan', [GmController::class, 'keuangan'])->name('keuangan');
    Route::get('keuangan/{cabang}', [GmController::class, 'detailKeuangan'])->name('keuangan-detail');
    Route::get('kelola-akun', [GmController::class, 'kelolaAkun'])->name('kelola-akun');
    Route::get('tambah-akun', [GmController::class, 'tambahAkun'])->name('tambah-akun');
    Route::get('edit-akun/{id}', [GmController::class, 'editAkun'])->name('edit-akun');
});

Route::prefix('owner')->name('pages.owner.')->group(function () {
    Route::get('/', [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/cabang/{branchId}', [OwnerController::class, 'detailCabang'])->name('detail-cabang');
});