<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\BaseModel;
use App\Models\User;
use App\Observers\AdminObserver;
use App\Observers\GlobalObserver;
use App\Observers\UserObserver;
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
        $this->loadJsonTranslationsFrom(base_path('app/Modules'));
        $this->loadMigrationsFrom(base_path('app/Modules'));
        $this->loadFactoriesFrom(base_path('app/Modules'));

        //register observers
        Admin::observe(AdminObserver::class);
        User::observe(UserObserver::class);
        //register global observer
        BaseModel::observe(GlobalObserver::class);
    }
}
