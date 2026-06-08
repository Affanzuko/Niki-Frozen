<?php

use App\Http\Controllers\KasirController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [KasirController::class, 'index'])->name('dashboard.kasir');
Route::get('dashboard_kasir', [KasirController::class, 'index']);
Route::post('checkout-kasir', [PaymentController::class, 'checkoutKasir'])->name('checkout.kasir');
Route::post('midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');
