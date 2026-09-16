<?php 

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class QueryFilter 
{
    protected Builder $builder; 

    /**
     * @param Request $request
     */
    public function __construct(protected Request $request) {}

    /**
     * Apply the filters to the builder.
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;
        foreach ($this->filterableParameters() as $name => $value) {
            $this->{$name}($value);
        }
        return $this->builder;
    }


    /**
     * Get the filterable parameters from the request.
     */
    protected function filterableParameters(): array
    {
        return collect($this->request->keys())
            ->filter(fn (string $key) => $this->request->filled($key))
            ->map(fn (string $key) => [$key, $this->resolveFilterMethod($key)])
            ->filter(fn (array $pair) => $pair[1] !== null)
            ->mapWithKeys(fn (array $pair) => [$pair[1] => $this->request->input($pair[0])])
            ->all();
    }

    protected function resolveFilterMethod(string $key): ?string
    {
        foreach ([Str::camel($key), Str::snake($key)] as $method) {
            if (method_exists($this, $method)) {
                return $method;
            }
        }

        return null;
    }
}