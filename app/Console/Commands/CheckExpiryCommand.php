<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('stok:check-expiry')]
#[Description('Check for products nearing expiry date and generate notifications')]
class CheckExpiryCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai pengecekan tanggal kadaluarsa...');

        // Cari stok masuk yang sisa stoknya > 0 dan kadaluarsa <= 7 hari dari sekarang
        $nearingExpiryBatches = \App\Models\StockIncoming::where('current_quantity', '>', 0)
            ->whereDate('expiry_date', '<=', now()->addDays(7))
            ->with('product')
            ->get();

        $count = 0;

        foreach ($nearingExpiryBatches as $batch) {
            // Hitung sisa hari
            $daysLeft = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($batch->expiry_date)->startOfDay(), false);
            
            // Format pesan
            if ($daysLeft > 0) {
                $message = "Produk {$batch->product->name} (Batch #{$batch->id}) akan kadaluarsa dalam {$daysLeft} hari.";
            } elseif ($daysLeft == 0) {
                $message = "Produk {$batch->product->name} (Batch #{$batch->id}) kadaluarsa hari ini!";
            } else {
                $message = "Produk {$batch->product->name} (Batch #{$batch->id}) sudah KADALUARSA sejak " . abs($daysLeft) . " hari lalu.";
            }

            // Cek apakah notifikasi untuk batch ini sudah ada (baik sudah dibaca maupun belum)
            $existingNotification = \App\Models\ExpiryNotification::where('stock_incoming_id', $batch->id)->first();

            if (!$existingNotification) {
                \App\Models\ExpiryNotification::create([
                    'stock_incoming_id' => $batch->id,
                    'branch_id' => $batch->branch_id,
                    'message' => $message,
                    'is_read' => false,
                    'is_read_by_gm' => false,
                ]);
                $count++;
            } else {
                // Update message jika hari berubah, dan buat jadi unread lagi jika pesannya berubah
                if ($existingNotification->message !== $message) {
                    $existingNotification->update([
                        'message' => $message,
                        'is_read' => false,
                        'is_read_by_gm' => false,
                    ]);
                }
            }
        }

        // Cleanup: Hapus notifikasi yang sudah tidak relevan
        $deletedCount = 0;
        
        // 1. Hapus jika produk sudah lewat 7 hari dari tanggal kadaluarsa (meskipun belum dibaca/sudah dibaca)
        // 2. Atau hapus jika kuantitas barang sudah habis (sudah dibuang/terjual)
        $deletedCount += \App\Models\ExpiryNotification::whereHas('stockIncoming', function ($query) {
            $query->whereDate('expiry_date', '<', now()->subDays(7))
                  ->orWhere('current_quantity', '<=', 0);
        })->delete();

        // 3. (Opsional) Hapus juga notifikasi untuk stock_incoming yang sudah dihapus dari database (jika ada)
        $deletedCount += \App\Models\ExpiryNotification::doesntHave('stockIncoming')->delete();

        $this->info("Pengecekan selesai. {$count} notifikasi baru dibuat. {$deletedCount} notifikasi lama dibersihkan.");
    }
}
