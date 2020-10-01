<?php

namespace CleancodeId\LaravelJwtGuard;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class LaravelJwtGuardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        Auth::extend('jwt-guard', function ($app, $name, array $config) {
            return new LaravelJwtGuard(Auth::createUserProvider($config['provider']), $app->request);
        });
    }
}
