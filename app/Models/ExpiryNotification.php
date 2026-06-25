<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpiryNotification extends Model
{
    /** @use HasFactory<\Database\Factories\ExpiryNotificationFactory> */
    use HasFactory;

    protected $fillable = [
        'stock_incoming_id',
        'branch_id',
        'message',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function stockIncoming(): BelongsTo
    {
        return $this->belongsTo(StockIncoming::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
