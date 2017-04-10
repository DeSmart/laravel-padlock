<?php

namespace DeSmart\Padlock;

use DeSmart\Padlock\Console\ListCommand;
use DeSmart\Padlock\Console\UnlockCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $config = $this->getConfig();

        $this->app->singleton(PadlockHandler::class, function($app) use ($config) {
            $driver = $app->make($config['driver']);

            $enabled = array_key_exists('enabled', $config) ? (bool)$config['enabled'] : true;

            return new PadlockHandler($driver, $enabled);
        });
    }

    /**
     * @return array
     */
    private function getConfig()
    {
        return config('padlock') ?: include(__DIR__ . '/../config/padlock.php');
    }

    public function boot()
    {
        if (true === $this->app->runningInConsole()) {
            $this->commands([
                UnlockCommand::class,
                ListCommand::class,
            ]);
        }

        $this->publishes([__DIR__ . '/../config/padlock.php' => config_path('padlock.php')], 'config');
        $this->publishes([__DIR__ . '/../migrations/' => database_path('migrations')], 'migrations');
    }
}
