<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getUnread()
    {
        $query = \App\Models\ExpiryNotification::query();

        // GM bisa melihat semua notifikasi, role lain hanya di cabangnya
        if (auth()->check() && auth()->user()->role !== 'general_manager') {
            $branchId = auth()->user()->branch_id ?? 1;
            $query->where('branch_id', $branchId);
        }

        // Ambil 20 notifikasi terbaru berdasarkan update terakhir (baik yang sudah dibaca maupun belum)
        $notifications = $query->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = \App\Models\ExpiryNotification::findOrFail($id);
        
        // Ensure user belongs to the same branch OR is a GM
        if (auth()->check() && auth()->user()->role === 'general_manager') {
            $notification->update(['is_read_by_gm' => true]);
        } else {
            $branchId = auth()->check() ? auth()->user()->branch_id : 1;
            if ($notification->branch_id == $branchId) {
                $notification->update(['is_read' => true]);
            }
        }

        $route = 'pages.admin.stok';
        if (auth()->check() && auth()->user()->role === 'general_manager') {
            $route = 'pages.general-manager.stok';
        }

        // Redirect to stok page with filter parameter
        return redirect()->route($route, ['filter' => 'kadaluarsa']);
    }
}
