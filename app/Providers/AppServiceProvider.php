<?php

namespace App\Providers;

use App\Models\GeneralSettings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if (Schema::hasTable('general_settings')) {
            $generalSettings = Cache::rememberForever(CACHE_GENERAL_SETTINGS, function () {
                return GeneralSettings::pluck('value', 'key')->all();
            });
            Config::set("settings", $generalSettings);
            if (Config::has("settings.site_name")) {
                Config::set('app.name', Config::get('settings.site_name'));
            }
        }
    }
}
