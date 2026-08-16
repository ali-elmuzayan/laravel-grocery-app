<?php

namespace App\Models;

use App\Models\Concerns\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Filterable, HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'created_by'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
