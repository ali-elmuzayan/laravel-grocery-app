<?php

namespace App\Filters;

class ProductFilter extends QueryFilter
{
    public function name(string $value): void
    {
        $this->builder->where('name', 'like', "%{$value}%");
    }

    public function description(string $value): void
    {
        $this->builder->where('description', 'like', "%{$value}%");
    }

    public function price(float $value): void
    {
        $this->builder->where('price', $value);
    }
}