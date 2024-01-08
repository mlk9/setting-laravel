<?php

/**
 * Setting Service Provider File
 * 
 * @category Setting
 * @package  Laravel
 * @author   Mohammad Maleki <malekii24@outlook.com>
 * @license  MIT https://github.com/mlk9/setting-laravel/blob/main/LICENSE
 * @link     https://github.com/mlk9/setting-laravel
 */

namespace Mlk9\Setting;

use Illuminate\Support\ServiceProvider;
use Mlk9\Setting\Facades\Setting as FacadesSetting;
use Mlk9\Setting\Setting;

/**
 * Setting Service Provider Class 
 * 
 * @category Setting
 * @package  Laravel
 * @author   Mohammad Maleki <malekii24@outlook.com>
 * @license  MIT https://github.com/mlk9/setting-laravel/blob/main/LICENSE
 * @link     https://github.com/mlk9/setting-laravel
 */
class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register any application services..
     *
     * @return void
     */
    public function register() : void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ensetting.php', 'ensetting');
        $this->app->singleton(FacadesSetting::class, Setting::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() : array
    {
        return [Setting::class];
    }

    /**
     * Boot any application services.
     *
     * @return void
     */
    public function boot() : void
    {
        $this->settingPublishing();
    }

    /**
     * Setting publishing for the package.
     *
     * @return void
     */
    protected function settingPublishing() : void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/ensetting.php' => config_path('ensetting.php'),
            __DIR__ . '/../database/migrations/2021_09_12_000000_create_setting_table.php' => database_path('migrations/2021_09_12_000000_create_setting_table.php'),
        ], 'setting-laravel');
    }
}
