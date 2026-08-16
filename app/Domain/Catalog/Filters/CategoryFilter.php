<?php 

namespace App\Domain\Catalog\Filters;

use App\Support\QueryFilter;

class CategoryFilter extends QueryFilter
{
    public function name(string $value): void
    {
        $this->builder->where('categories.name', 'like', "%{$value}%");
    }

    public function description(string $value): void
    {
        $this->builder->where('categories.description', 'like', "%{$value}%");
    }

    public function createdBy(string $value): void
    {
        $this->builder->where('categories.created_by', $value);
    }
}