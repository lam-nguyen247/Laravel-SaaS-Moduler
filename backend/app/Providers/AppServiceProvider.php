<?php

namespace App\Providers;

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
        //register Modules
        $this->loadViewsFrom(base_path('app/Modules'), 'modules');
        $this->loadTranslationsFrom(base_path('app/Modules'), 'modules');
        $this->loadJsonTranslationsFrom(base_path('app/Modules'), 'modules');
        $this->loadMigrationsFrom(base_path('app/Modules'), 'modules');
        $this->loadFactoriesFrom(base_path('app/Modules'));
    }
}
