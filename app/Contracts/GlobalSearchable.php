<?php

namespace App\Contracts;

interface GlobalSearchable
{
    public static function globalSearchColumns(): array; 

    public function globalSearchRelations(): array; 

    public static function globalSearchType(): string; 
}
