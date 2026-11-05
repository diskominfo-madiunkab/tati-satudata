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
            $baseUri = config('ckan_api.container') ?: config('ckan_api.url') ?: 'https://ckan-data.madiunkab.go.id';
            $config = [
                'base_uri' => rtrim($baseUri, '/') . '/',
                'headers' => ['Authorization' => config('ckan_api.api_key')],
                'verify' => false,
                'timeout' => 5,
                'connect_timeout' => 3,
            ];

            return new CkanApiClient(new Client($config));
        });

        $this->app->alias('App\Services\CkanApi\CkanApiClient', 'CkanApi');
    }
}
