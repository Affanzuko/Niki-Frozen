<?php

use App\Http\Controllers\KasirController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [KasirController::class, 'index'])->name('dashboard.kasir');
Route::get('dashboard_kasir', [KasirController::class, 'index']);
Route::get('penjualan_kasir', [KasirController::class, 'penjualan'])->name('penjualan.kasir');
Route::post('checkout-kasir', [PaymentController::class, 'checkoutKasir'])->name('checkout.kasir');
Route::post('midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');
Route::post('cetak-struk', [KasirController::class, 'cetakStruk'])->name('kasir.cetakStruk');
