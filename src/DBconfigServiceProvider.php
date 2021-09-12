<?php

namespace Mlk9\DBconfig;

use Illuminate\Support\ServiceProvider;

class DBconfigServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Boot any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePublishing();
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../database/migrations/2021_09_12_000000_create_configs_table.php' => database_path('migrations/2021_09_12_000000_create_configs_table.php'),
        ], 'dbconfig');
    }

}