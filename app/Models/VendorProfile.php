<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'business_name', 'status', 'rating'];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:2',
        ];
    }
}
