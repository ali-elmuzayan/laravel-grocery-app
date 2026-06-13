<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycVerification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'document_type', 'document_number', 'status', 'verified_at'];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }
}
