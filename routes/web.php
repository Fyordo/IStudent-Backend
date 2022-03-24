<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name("home");

Route::get('/about', [HomeController::class, 'about'])->name("about");

Route::get('/info', [HomeController::class, 'info'])->name("info");

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
                Route::get('/login', [\App\Http\Controllers\Api\Auth\AuthApiController::class, 'login']);
                Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthApiController::class, 'logout']);
                Route::post('/add', [\App\Http\Controllers\Api\Auth\AuthApiController::class, 'add']);
            }
        );

        Route::group(
            [
                'prefix' => '/student'
            ],
            function () {
                Route::get('/get/{id}', [\App\Http\Controllers\Api\Student\StudentApiController::class, 'get']);
            }
        );

        Route::group(
            [
                'prefix' => '/group'
            ],
            function () {
                Route::get('/get/{id}', [\App\Http\Controllers\Api\Group\GroupApiController::class, 'get']);
                Route::get('/students/{id}', [\App\Http\Controllers\Api\Group\GroupApiController::class, 'getStudents']);
                Route::get('/all', [\App\Http\Controllers\Api\Group\GroupApiController::class, 'all']);
            }
        );

        Route::group(
            [
                'prefix' => '/schedule'
            ],
            function () {
                Route::get('/list/{group_id}', [\App\Http\Controllers\Api\Schedule\ScheduleApiController::class, 'day']);
                Route::get('/full/{group_id}', [\App\Http\Controllers\Api\Schedule\ScheduleApiController::class, 'full']);
            }
        );

        // УПРОЩЁННОЕ АПИ

        Route::group(
            [
                'prefix' => '/my'
            ],
            function () {

                Route::group(
                    [
                        'prefix' => '/student'
                    ],
                    function () {
                        Route::get('/get', [\App\Http\Controllers\Api\Student\StudentApiController::class, 'MYget']);
                    }
                );

                Route::group(
                    [
                        'prefix' => '/group'
                    ],
                    function () {
                        Route::get('/get', [\App\Http\Controllers\Api\Group\GroupApiController::class, 'MYget']);
                        Route::get('/students', [\App\Http\Controllers\Api\Group\GroupApiController::class, 'MYgetStudents']);
                    }
                );

                Route::group(
                    [
                        'prefix' => '/schedule'
                    ],
                    function () {
                        Route::get('/list', [\App\Http\Controllers\Api\Schedule\ScheduleApiController::class, 'MYday']);
                        Route::get('/full', [\App\Http\Controllers\Api\Schedule\ScheduleApiController::class, 'MYfull']);
                    }
                );

            }
        );

        // АДМИН ПАНЕЛЬ

        Route::group(
            [
                'prefix' => '/admin'
            ],
            function () {

                Route::group(
                    [
                        'prefix' => '/teacher'
                    ],
                    function () {
                        Route::get('/add', [\App\Http\Controllers\Api\Admin\AdminApiController::class, 'addTeachers']);
                    }
                );

                Route::group(
                    [
                        'prefix' => '/group'
                    ],
                    function () {
                        Route::get('/add', [\App\Http\Controllers\Api\Admin\AdminApiController::class, 'addGroups']);
                    }
                );

                Route::group(
                    [
                        'prefix' => '/schedule'
                    ],
                    function () {
                        Route::get('/add', [\App\Http\Controllers\Api\Admin\AdminApiController::class, 'addSchedule']);
                    }
                );

            }
        );

    }
);
