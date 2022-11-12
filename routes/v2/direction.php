<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/direction'
], function () {
    Route::apiResource('/', \App\Http\Controllers\ApiV2\DirectionController::class);
});
