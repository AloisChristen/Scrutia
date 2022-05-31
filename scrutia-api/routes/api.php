<?php

use App\Http\Controllers\API\AnswerController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VersionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [RegistrationController::class, 'register'])
    ->name('register');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login');


Route::get('/tags', [TagController::class,'index'])->name('tag.index');


Route::controller(ProjectController::class)->middleware("auth.optional")->prefix('/projects')->group(
    function () {
        Route::get('/', 'index')->name('project.index');
        Route::get('/{id}', 'show')->name('project.show');
    }
);

/**
 * Routes that need authentication
 */
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::controller(AnswerController::class)->prefix('/answers')->group(
        function () {
            Route::post('/', 'store')->name('answer.store');
            Route::put('/{id}', 'update')->name('answer.update');
            Route::delete('/{id}', 'destroy')->name('answer.delete');
            Route::post('/{id}/like','like')->name('answer.like');
        }
    );

    Route::controller(FavoriteController::class)->prefix("/favorites")->group(
        function () {
            Route::get('/', 'index')->name('favorite.index');
            Route::post('/', 'store')->name('favorite.store');
            Route::delete('/{id}', 'destroy')->name('favorite.destroy');
        }
    );

    Route::controller(ProjectController::class)->prefix('/projects')->group(
        function () {
            Route::post('/', 'store')->name('project.store');
            Route::put('/{id}/promote', 'promote')->name('project.promote');

            Route::post('/{id}/like', 'like')->name('project.like');

        }
    );

    Route::controller(QuestionController::class)->prefix('/questions')->group(
        function () {
            Route::post('/', 'store')->name('question.store');
            Route::put('/{id}', 'update')->name('question.update');
            Route::delete('/{id}', 'destroy')->name('question.delete');

            Route::post('/{id}/like', 'like')->name('question.like');
        }
    );

    Route::controller(UserController::class)->prefix('/user')->group(
        function () {
            Route::get('/', 'index')->name('user.index');
            Route::put('/', 'update')->name('user.update');
        }
    );

    Route::controller(VersionController::class)->prefix('/versions')->group(
        function () {
            Route::post('/', 'store')->name('version.store');
            Route::put('/{id}', 'update')->name('version.update');
            Route::delete('/{id}', 'destroy')->name('version.delete');

            Route::post('/{id}/like', 'like')->name('version.like');
        }
    );

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
