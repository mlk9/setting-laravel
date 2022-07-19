<?php

/**
 * DBConfig Service Provider File
 * 
 * @category Configure
 * @package  Laravel
 * @author   Mohammad Maleki <malekii24@outlook.com>
 * @license  MIT https://github.com/mlk9/config-storage-laravel/blob/main/LICENSE
 * @link     https://github.com/mlk9/config-storage-laravel
 */

namespace Mlk9\DBConfig;

use Illuminate\Support\ServiceProvider;
use Mlk9\DBConfig\DBConfig;

/**
 * DBConfig Service Provider Class 
 * 
 * @category Configure
 * @package  Laravel
 * @author   Mohammad Maleki <malekii24@outlook.com>
 * @license  MIT https://github.com/mlk9/config-storage-laravel/blob/main/LICENSE
 * @link     https://github.com/mlk9/config-storage-laravel
 */
class DBConfigServiceProvider extends ServiceProvider
{
    /**
     * Register any application services..
     *
     * @return void
     */
    public function register() : void
    {
        $this->app->singleton('dbconfig', function ($app) {
            return new DBConfig();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() : array
    {
        return [DBConfig::class];
    }

    /**
     * Boot any application services.
     *
     * @return void
     */
    public function boot() : void
    {
        $this->configurePublishing();
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing() : void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../database/migrations/2021_09_12_000000_create_configs_table.php' => database_path('migrations/2021_09_12_000000_create_configs_table.php'),
        ], 'dbconfig');
    }
}
