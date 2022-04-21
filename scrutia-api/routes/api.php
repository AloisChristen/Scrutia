<?php

use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\UserController;
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
        Route::get('/', 'index')->name('project index');
        Route::get('/{id}', 'getProject')->name('project by id');
        Route::post('/', 'store')->name('create project');
        Route::delete('/{id}', 'destroy')->name('delete project');
    }
);



Route::controller(QuestionController::class)->prefix('/questions')->group( // TODO: put in middleware auth
    function () {
        Route::get('/', 'index')->name('questions list');
        Route::post('/', 'store')->name('create question');
        Route::delete('/{id}', 'destroy')->name('delete question');

    }
);

Route::controller(UserController::class)->prefix('/users')->group( // TODO: put in middleware auth
    function () {
        Route::get('/', 'index')->name('users list');
        Route::get('/{id}', 'getUser')->name('get user');
        Route::delete('/{id}', 'destroy')->name('delete user');
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
