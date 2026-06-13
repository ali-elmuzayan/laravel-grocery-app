<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'tracking_number', 'status', 'carrier', 'estimated_delivery_at', 'delivered_at'];

    protected function casts(): array
    {
        return [
            'estimated_delivery_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function events()
    {
        return $this->hasMany(ShipmentEvent::class);
    }
}
