<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentIntent extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'idempotency_key', 'provider_reference', 'amount', 'status', 'captured_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'captured_at' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
