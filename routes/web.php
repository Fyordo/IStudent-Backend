<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name("home");

Route::get('/about', [HomeController::class, 'about'])->name("about");

Route::get('/privacy', [HomeController::class, 'privacy'])->name("privacy");

Route::middleware(['guest'])->group(function () {

    Route::get('/signin', [AccountController::class, 'signin'])->name("signin");

    Route::get('/callback', [AccountController::class, 'callback'])->name("callback");

    Route::match(['get', 'post'], '/auth/{message?}', [AccountController::class, 'login'])->name("login");

});

Route::middleware(['auth'])->group(function () {

    Route::match(['get', 'post'], '/data/add/{message?}', [AccountController::class, 'add'])->name("loginAdd");

    Route::get('/logout', [AccountController::class, 'logout'])->name("logout");
});

Route::middleware(['auth', 'studentConfirm'])->group(function () {

    // Пользователь

    Route::group(
        [
            'prefix' => '/page'
        ],
        function () {
            Route::get("/{id?}", [UserController::class, 'page'])->name('page');
        }
    );

    Route::group(
        [
            'prefix' => '/group'
        ],
        function () {
            Route::get("/one/{id}", [GroupController::class, 'index'])->name('group');
            Route::get("/all", [GroupController::class, 'all'])->name('all');
        }
    );

    Route::group(
        [
            'prefix' => '/schedule'
        ],
        function () {
            Route::get("/list/{groupId}/{day}/{month}/{year}", [LessonController::class, 'list'])->name('lessonList');
            Route::get("/all/{groupId}", [LessonController::class, 'full'])->name('fullSchedule');
        }
    );


});

Route::group(
    [
        'prefix' => '/api'
    ],
    function () {
        Route::group(
            [
                'prefix' => '/auth'
            ],
            function () {
                Route::get('/login', [\App\Http\Controllers\Api\Auth\ApiController::class, 'login']);
            }
        );

        Route::group(
            [
                'prefix' => '/student'
            ],
            function () {
                Route::get('/get/{id}', [\App\Http\Controllers\Api\Student\ApiController::class, 'get']);
            }
        );

        Route::group(
            [
                'prefix' => '/group'
            ],
            function () {
                Route::get('/get/{id}', [\App\Http\Controllers\Api\Group\ApiController::class, 'get']);
                Route::get('/students/{id}', [\App\Http\Controllers\Api\Group\ApiController::class, 'getStudents']);
                Route::get('/all', [\App\Http\Controllers\Api\Group\ApiController::class, 'all']);
            }
        );

        Route::group(
            [
                'prefix' => '/schedule'
            ],
            function () {
                Route::get('/list/{group_id}/{day}/{month}/{year}', [\App\Http\Controllers\Api\Schedule\ApiController::class, 'day']);
                Route::get('/full/{group_id}', [\App\Http\Controllers\Api\Schedule\ApiController::class, 'full']);
            }
        );

    }
);
