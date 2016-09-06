<?php

namespace  Bravist\Exchange;

use Illuminate\Support\ServiceProvider;
use Bravist\Exchange\Authorize;

class ExchangeProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $path = realpath(__DIR__ . '/config/exchange.php');
        $this->publishes([$path => config_path('exchange.php')], 'config');
        $this->mergeConfigFrom($path, 'exchange');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(['Bravist\\Exchange\\Authorize' => 'Exchange'], function($app){
            return new Authorize(app('rsa'), app('guzzle.http.client'), config('exchange'), app('cache'));
        });
    }
}
