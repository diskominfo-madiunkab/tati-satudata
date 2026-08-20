<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        date_default_timezone_set('Asia/Jakarta');
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');
        Paginator::useBootstrap();
        if (!$this->app->environment('local') && !$this->app->runningInConsole()) {
            URL::forceScheme('https');
        }
    }
}
