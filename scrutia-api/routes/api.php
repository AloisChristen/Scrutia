<?php

use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use Illuminate\Http\Request;
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


Route::controller(ProjectController::class)->prefix('/projects')->group( // TODO: put in middleware auth
    function () {
        Route::get('/', 'index')->name('project.index');
        Route::get('/{id}', 'show')->name('project.show');
        Route::post('/', 'store')->name('project.store');
        Route::delete('/{id}', 'destroy')->name('project.delete');
    }
);



Route::controller(QuestionController::class)->prefix('/questions')->group(
    function () {
        Route::post('/', 'store')->name('question.store');
        Route::put('/{question}', 'update')->name('question.update');
        Route::delete('/{id}', 'destroy')->name('question.delete');
    }
);

Route::controller(UserController::class)->prefix('/users')->group( // TODO: put in middleware auth
    function () {
        Route::get('/', 'index')->name('user.index');
        Route::get('/{id}', 'show')->name('user.show');
        Route::delete('/{id}', 'destroy')->name('user.delete');
    }
);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/hello', function (Request $request) {
        return 'Hello, World!';
    });

    // QUESTION : how to have id argument ?
    // route::get("/projects/id"[\App\Http\Controllers\ProjectController::class, 'show']) _> need argument to function
    // Question best way to group routes ?

    // _> documentation readme to complete
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});
