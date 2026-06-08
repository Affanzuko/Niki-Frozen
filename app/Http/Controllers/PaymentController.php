<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds        = config('midtrans.is_3ds');
    }
    public function checkoutKasir(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        $items = $request->items;
        $grossAmount = 0;
        $itemDetails = [];

        foreach ($items as $item) {
            $grossAmount += ($item['harga'] * $item['jumlah']);

            $itemDetails[] = [
                'id'       => $item['id'],
                'price'    => $item['harga'],
                'quantity' => $item['jumlah'],
                'name'     => substr($item['nama'], 0, 50),
            ];
        }

        // 4. Generate Kode Transaksi
        $transactionCode = 'TRX-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

        // $cashierSessionId = $request->cashier_session_id ?? 1;
        // $branchId         = $request->branch_id ?? 1;

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                // 'cashier_session_id' => $cashierSessionId,
                // 'branch_id'          => $branchId,
                'transaction_code'   => $transactionCode,
                'payment_method'     => 'qris',
                'total_price'        => $grossAmount,
                'status'             => 'pending',
            ]);

            foreach ($items as $item) {
                TransactionItem::create([
                    'transaction_id'   => $transaction->id,
                    'product_id'       => $item['id'],
                    'product_name'     => $item['nama'],
                    'unit_price'       => $item['harga'],
                    'quantity'         => $item['jumlah'],
                    'subtotal'         => $item['harga'] * $item['jumlah']
                ]);
            }

            // Konfigurasi Midtrans
            $params = [
                'transaction_details' => [
                    'order_id'     => $transactionCode,
                    'gross_amount' => $grossAmount,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => 'Pelanggan',
                    'last_name'  => 'Kasir',
                ],
                'enabled_payments' => ['qris', 'gopay', 'shopeepay'],
                'notifications' => [
                    'finish_redirect_url' => url('/'),
                ],
            ];

            \Log::info('Midtrans params:', $params);

            // Dapatkan dan simpan Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Simpan token ke database
            $transaction->update(['snap_token' => $snapToken]);

            DB::commit();

            return response()->json([
                'status'     => 'success',
                'snap_token' => $snapToken,
                'order_id'   => $transactionCode
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function callback(Request $request)
    {
        \Log::info('Midtrans callback received:', $request->all());

        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $transaction = \App\Models\Transaction::where('transaction_code', $request->order_id)->first();
            \Log::info('Transaction found:', ['transaction' => $transaction]);

            if ($transaction) {
                $status = 'pending';
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $status = 'success';
                } elseif (in_array($request->transaction_status, ['deny', 'expire', 'cancel'])) {
                    $status = 'failed';
                }

                \Log::info('Updating transaction status', ['order_id' => $request->order_id, 'new_status' => $status]);
                $transaction->update(['status' => $status]);
                \Log::info('Transaction updated successfully', ['transaction' => $transaction->fresh()]);
            } else {
                \Log::warning('Transaction not found', ['order_id' => $request->order_id]);
            }
        } else {
            \Log::warning('Signature verification failed', ['hashed' => $hashed, 'signature_key' => $request->signature_key]);
        }

        return response()->json(['message' => 'Callback received']);
    }
}
