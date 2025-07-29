<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // ← ye bhi chahiye
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        \Illuminate\Pagination\Paginator::useBootstrap();

        View::share('brands', Brand::with('products')->get());
        View::share('categories', Category::with('products')->get());
        View::share('colors', Product::select('product_color')->distinct()->pluck('product_color')->toArray());
    }
}
