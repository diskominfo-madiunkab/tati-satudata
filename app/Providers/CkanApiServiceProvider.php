<?php

namespace App\Providers;

use App\Services\CkanApi\CkanApiClient;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class CkanApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind('App\Services\CkanApi\CkanApiClient', function () {

            $config = [
                'base_uri' => config('ckan_api.url'),
                'headers' => ['Authorization' => config('ckan_api.api_key')],
                'verify' => !app()->environment('local')
            ];

            return new CkanApiClient(new Client($config));
        });

        $this->app->alias('App\Services\CkanApi\CkanApiClient', 'CkanApi');
    }
}
