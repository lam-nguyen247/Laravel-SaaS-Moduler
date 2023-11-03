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
        $this->app->bind(
            'App\Repositories\Customer\CustomerRepositoryInterface',
            'App\Repositories\Customer\CustomerRepository'
        );
        $this->app->bind(
                    'App\Repositories\Member_level\MemberLevelRepositoryInterface',
            'App\Repositories\Member_level\MemberLevelRepository'
        );
        $this->app->bind(
            'App\Repositories\Point\PointRepositoryInterface',
            'App\Repositories\Point\PointRepository'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        $this->loadViewsFrom(base_path('app/Modules'), 'modules');
    }
}
