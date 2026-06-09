<?php

use App\Http\Controllers\KasirController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [KasirController::class, 'index'])->name('dashboard.kasir');
Route::get('dashboard_kasir', [KasirController::class, 'index']);
Route::post('checkout-kasir', [PaymentController::class, 'checkoutKasir'])->name('checkout.kasir');
Route::post('midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');
Route::post('cetak-struk', [KasirController::class, 'cetakStruk'])->name('kasir.cetakStruk');

Route::get('/dashboard-admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');
