<?php

namespace Sagitarius29\LaravelSubscriptions;

use Illuminate\Support\ServiceProvider;

class LaravelSubscriptionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-subscriptions');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-subscriptions');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('subscriptions.php'),
        ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'subscriptions');

        //$this->app->bind(PlanContract::class, config('subscriptions.entities.plan'));

        // Register the main class to use with the facade
        /*$this->app->singleton('laravel-subscriptions', function () {
            return new LaravelSubscriptions;
        });*/
    }
}
