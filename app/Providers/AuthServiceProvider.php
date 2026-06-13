<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('admin-access', fn ($user) => $user->hasRole('admin'));
        Gate::define('vendor-access', fn ($user) => $user->hasAnyRole(['vendor', 'admin']));
        Gate::define('customer-access', fn ($user) => $user->hasAnyRole(['user', 'admin']));

        Gate::define('moderate-products', fn ($user) => $user->can('products.approve'));
        Gate::define('manage-categories', fn ($user) => $user->can('categories.manage'));

        Gate::define('view-order', fn ($user, Order $order) => $user->hasRole('admin') || $order->user_id === $user->id || $order->vendor_id === $user->id
        );

        Gate::define('update-product', fn ($user, Product $product) => $user->hasRole('admin') || $product->vendor_id === $user->id
        );

        Gate::define('update-category', fn ($user, Category $category) => $user->hasRole('admin') || $user->can('categories.manage')
        );
    }
}
