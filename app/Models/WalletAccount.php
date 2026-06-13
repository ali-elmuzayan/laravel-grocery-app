<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletAccount extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'currency', 'available_balance', 'pending_balance'];

    protected function casts(): array
    {
        return [
            'available_balance' => 'decimal:2',
            'pending_balance' => 'decimal:2',
        ];
    }
}
