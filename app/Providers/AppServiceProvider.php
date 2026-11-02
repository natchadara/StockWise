<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use App\Policies\CategoryPolicy;
use App\Policies\ItemPolicy;
use App\Policies\SupplierPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Supplier::class, SupplierPolicy::class);
        Gate::policy(Item::class, ItemPolicy::class);
    }
}
