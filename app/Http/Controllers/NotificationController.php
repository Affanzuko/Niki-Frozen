<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getUnread()
    {
        $branchId = auth()->check() ? auth()->user()->branch_id : 1; // Fallback to 1

        // Ambil 20 notifikasi terbaru (baik yang sudah dibaca maupun belum)
        $notifications = \App\Models\ExpiryNotification::where('branch_id', $branchId)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = \App\Models\ExpiryNotification::findOrFail($id);
        
        // Ensure user belongs to the same branch
        $branchId = auth()->check() ? auth()->user()->branch_id : 1;
        if ($notification->branch_id == $branchId) {
            $notification->update(['is_read' => true]);
        }

        // Redirect to stok page with filter parameter
        return redirect()->route('pages.admin.stok', ['filter' => 'kadaluarsa']);
    }
}
