<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register the settings singleton.
     */
    public function register(): void
    {
        $this->app->singleton('app.settings', function () {
            try {
                if (Schema::hasTable('settings')) {
                    return Setting::getAllCached();
                }
            } catch (\Exception $e) {
                // DB not available yet (during migrations, etc.)
            }

            return [];
        });
    }

    /**
     * Boot: share settings with all Blade views.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('globalSettings', app('app.settings'));
        });
    }
}
