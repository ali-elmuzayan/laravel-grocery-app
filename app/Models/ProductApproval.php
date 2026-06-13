<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductApproval extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'reviewed_by', 'status', 'note', 'reviewed_at'];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }
}
