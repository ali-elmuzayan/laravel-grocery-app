<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInstallment extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'sequence', 'amount', 'due_at', 'paid_at', 'status'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }
}
