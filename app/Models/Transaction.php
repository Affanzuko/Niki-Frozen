<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'payment_method',
        'total_price',
        'status',
        'snap_token',
        'created_at',
        'updated_at',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
