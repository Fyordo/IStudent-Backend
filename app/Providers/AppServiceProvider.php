<?php

namespace App\Providers;

use App\Providers\CustomProviders\DirectionServiceProvider;
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
        $this->app->register(DirectionServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!env("APP_DEBUG")){
            URL::forceScheme('https');
        }
    }
}
