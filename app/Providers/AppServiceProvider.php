<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Support\Facades\Blade;

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
        Product::observe(ProductObserver::class);

        Blade::directive('currency', function ($expression) {
            return "<?php echo number_format($expression, 2, ',', ' ') . ' MAD'; ?>";
        });
    }
}
