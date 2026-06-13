<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutDisbursement extends Model
{
    use HasFactory;

    protected $fillable = ['payout_request_id', 'provider_reference', 'status', 'processed_at'];

    protected function casts(): array
    {
        return [
            'processed_at' => 'datetime',
        ];
    }
}
