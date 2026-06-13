<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());

        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(120)->by($request->user()?->id ?: $request->ip()));
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(8)->by($request->input('email').$request->ip()));

        DB::whenQueryingForLongerThan(300, function ($connection, $event): void {
            Log::warning('Slow query detected', [
                'connection' => $connection->getName(),
                'sql' => $event->sql,
                'time_ms' => $event->time,
            ]);
        });
    }
}
