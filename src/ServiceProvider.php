<?php

namespace DeSmart\Padlock;

use DeSmart\Padlock\Console\ListCommand;
use DeSmart\Padlock\Console\UnlockCommand;
use DeSmart\Padlock\Driver\DatabaseDriver;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $config = config('padlock');

        $this->app->singleton(PadlockHandler::class, function($app) use ($config) {
            $driver = $app->make($config['driver']);

            return new PadlockHandler($driver);
        });
    }

    public function boot()
    {
        $config = config('padlock');

        if (DatabaseDriver::class === $config['driver']) {
            $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        }

        if (true === $this->app->runningInConsole()) {
            $this->commands([
                UnlockCommand::class,
                ListCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/padlock.php' => config_path('padlock.php')
        ]);
    }
}
