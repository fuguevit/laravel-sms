<?php

namespace Fuguevit\Sms\Providers;

use Fuguevit\Sms\Exceptions\AdapterNotFoundException;
use Fuguevit\Sms\Sms;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
           realpath(__DIR__.'/../../config/laravel-sms.php') => config_path('laravel-sms.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/laravel-sms.php'), 'laravel-sms');

        $this->registerSmsSystem();
    }

    /**
     * Register Sms System.
     */
    public function registerSmsSystem()
    {
        $this->app->singleton('sms', function () {
            $adapter = $this->getDefaultDriver();

            return new Sms(new $adapter());
        });
    }

    /**
     * Get Default Sms Driver.
     *
     * @throws AdapterNotFoundException
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        $driver = $this->app['config']['laravel-sms.default'];

        $class = __DIR__.'/../Adapter/'.ucfirst($driver).'SmsAdapter';

        if (!class_exists($class)) {
            throw new AdapterNotFoundException();
        }

        return $class;
    }
}
