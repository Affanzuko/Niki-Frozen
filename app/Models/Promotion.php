<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    /** @use HasFactory<\Database\Factories\PromotionFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'stock_incoming_id',
        'branch_id',
        'created_by',
        'discount_percentage',
        'valid_until',
        'is_active',
        'quota',
    ];

    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
            'is_active' => 'boolean',
            'discount_percentage' => 'decimal:2',
            'quota' => 'integer',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function stockIncoming(): BelongsTo
    {
        return $this->belongsTo(StockIncoming::class);
    }
}
