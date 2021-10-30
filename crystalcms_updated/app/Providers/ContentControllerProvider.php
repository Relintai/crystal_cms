<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ContentControllerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
         //dd($this->app);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Providers\ContentController\ContentControllerStatic');
    }
}
