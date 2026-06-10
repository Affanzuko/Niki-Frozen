<?php

use App\Http\Controllers\KasirController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GmController;

Route::get('/', [KasirController::class, 'index'])->name('dashboard.kasir');
Route::get('kasir', [KasirController::class, 'index']);
Route::get('penjualan_kasir', [KasirController::class, 'penjualan'])->name('penjualan.kasir');
Route::post('checkout-kasir', [PaymentController::class, 'checkoutKasir'])->name('checkout.kasir');
Route::post('midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');
Route::post('cetak-struk', [KasirController::class, 'cetakStruk'])->name('kasir.cetakStruk');

Route::get('/admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');
Route::get('/admin-stok', [AdminDashboardController::class, 'stok'])->name('stok.admin');
Route::get('/admin-penjualan', [AdminDashboardController::class, 'penjualan'])->name('penjualan.admin');
Route::get('/admin-keuangan', [AdminDashboardController::class, 'keuangan'])->name('keuangan.admin');

Route::prefix('gm')->name('pages.general-manager.')->group(function () {
    Route::get('/', [GmController::class, 'dashboard'])->name('dashboard');
    Route::get('/keuangan', [GmController::class, 'keuangan'])->name('keuangan');
    Route::get('/keuangan/{cabang}', [GmController::class, 'detailKeuangan'])->name('keuangan-detail');
    Route::get('/kelola-akun', [GmController::class, 'kelolaAkun'])->name('kelola-akun');
    Route::get('/tambah-akun', [GmController::class, 'tambahAkun'])->name('tambah-akun');
    Route::get('/edit-akun/{id}', [GmController::class, 'editAkun'])->name('edit-akun');
});