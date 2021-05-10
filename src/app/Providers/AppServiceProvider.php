<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        $proxyUrl    = config('proxy.url');
        $proxyScheme = config('proxy.scheme');

        if (!empty($proxyUrl)) {
            URL::forceRootUrl($proxyUrl);
        }

        if (!empty($proxyScheme)) {
            URL::forceScheme($proxyScheme);
        }
    }
}
