<?php

namespace BeeDelivery\Bs2;

use Illuminate\Support\ServiceProvider;

class Bs2ServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/bs2.php', 'bs2');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Register the service the package provides.
        $this->app->singleton('bs2', function ($app) {
            return new Bs2;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/bs2.php' => config_path('bs2.php'),
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['bs2'];
    }
}
