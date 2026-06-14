<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockIncoming extends Model
{
    /** @use HasFactory<\Database\Factories\StockIncomingFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'branch_id',
        'user_id',
        'quantity',
        'purchase_price',
        'expiry_date',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function expiryNotifications(): HasMany
    {
        return $this->hasMany(ExpiryNotification::class);
    }
}
