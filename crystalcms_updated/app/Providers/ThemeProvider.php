<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ThemeProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Providers\Theme\ThemeProviderSingleton');
    }
}
