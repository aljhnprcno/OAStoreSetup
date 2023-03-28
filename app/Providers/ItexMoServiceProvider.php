<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ItexMo\ItexMoApi;

class ItexMoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ItexMoApi::class, function () {
            $config = $this->app->config['itexmo'];

            return new ItexMoApi($config);
        });
    }
}
