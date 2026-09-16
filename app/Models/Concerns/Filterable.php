<?php 
namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder; 
use Illuminate\Http\Request;

trait Filterable 
{
    /**
     * Apply the filters to the query.
     */
    public function scopeFilter(Builder $query, Request $request) : Builder
    {
        $static = self::resolveFilterClass(); 

        return (new $static($request))->apply($query); 
    }

    /**
     * Resolve the filter class for the model.
     */
    public static function resolveFilterClass(): string
    {
        $class = 'App\\Domain\\Catalog\\Filters\\' . class_basename(static::class) . 'Filter';

        if (! class_exists($class)) {
            throw new \InvalidArgumentException("Filter class [{$class}] not found for model [" . static::class . '].');
        }

        return $class;
    }
}