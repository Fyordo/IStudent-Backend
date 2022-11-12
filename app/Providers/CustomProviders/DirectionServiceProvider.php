<?php

namespace App\Providers\CustomProviders;

use App\Http\Facades\DirectionManager;
use App\Http\Repositories\DirectionRepository;
use App\Http\Services\DirectionService;
use Illuminate\Support\ServiceProvider;

class DirectionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(DirectionManager::class, function () {
            return new DirectionService(
                new DirectionRepository(),
            );
        });

        $this->app->bind('App\Facades\DirectionManager', 'App\Services\DirectionService');
    }
}
