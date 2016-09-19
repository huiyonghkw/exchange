<?php

namespace  Bravist\Exchange;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Bravist\Exchange\Authorize;
use Pikirasa\RSA;

class ServiceProvider extends BaseServiceProvider
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
        $this->registerRsa();
        
        $this->app->singleton(['Bravist\\Exchange\\Authorize' => 'Exchange'], function($app){
            return new Authorize(app('Rsa'), app('guzzle.http.client'), config('exchange'), app('cache'));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function registerRsa()
    {
        $this->app->bind(['Pikirasa\\RSA' => 'Rsa'], function ($app) {
            return new RSA(config('exchange.public_key_file'), config('exchange.private_key_file'));
        });
    }
}
