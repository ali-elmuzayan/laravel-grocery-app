<?php 

namespace App\Domain\Catalog\Filters;

use App\Support\QueryFilter;

class ProductFilter extends QueryFilter
{
    public function name(string $value): void
    {
        $this->builder->where('products.name', 'like', "%{$value}%");
    }

    public function category_id(string $value): void
    {
        $this->builder->where('products.category_id', $value);
    }

    public function category(string $value): void
    {
        $this->category_id($value);
    }

    public function price(string $value): void
    {
        $this->builder->where('products.price', $value);
    }

    public function stock(string $value): void
    {
        $this->builder->where('products.stock', $value);
    }

    public function status(string $value): void
    {
        $this->builder->where('products.status', $value);
    }
}