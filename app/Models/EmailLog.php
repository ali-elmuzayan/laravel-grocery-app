<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'recipient_email', 'status', 'sent_at', 'meta'];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'meta' => 'array',
        ];
    }
}
