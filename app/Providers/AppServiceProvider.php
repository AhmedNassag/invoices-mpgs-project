<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        View::composer('_inertia_main_layout', 'App\Http\Composers\NotificationComposer');
        View::composer('backend/notification/index', 'App\Http\Composers\NotificationComposer');
        View::composer('backend/components/_page_header', 'App\Http\Composers\NotificationComposer');
        View::composer('backend/components/_page_sidebar', 'App\Http\Composers\BackendMenuComposer');
    }
}
