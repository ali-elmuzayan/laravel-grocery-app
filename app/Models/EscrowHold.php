<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscrowHold extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'vendor_id', 'amount', 'release_at', 'released_at', 'status'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'release_at' => 'datetime',
            'released_at' => 'datetime',
        ];
    }
}
