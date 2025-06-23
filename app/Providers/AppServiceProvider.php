<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use App\Helpers\ProfileHelper;

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
        Vite::prefetch(concurrency: 3);
        Schema::defaultStringLength(191);

        // Enregistrer le helper ProfileHelper comme directive Blade
        Blade::directive('canViewProfile', function ($expression) {
            return "<?php echo App\Helpers\ProfileHelper::canViewProfile($expression); ?>";
        });

        Blade::directive('shouldShowProfileLink', function ($expression) {
            return "<?php echo App\Helpers\ProfileHelper::shouldShowProfileLink($expression); ?>";
        });
    }
}
