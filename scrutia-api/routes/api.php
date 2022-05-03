<?php

use App\Http\Controllers\API\ProjectController;
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

Route::controller(ProjectController::class)->prefix('/projects')->group(
    function () {
        Route::get('/', 'index')->name('project.index');
        Route::get('/{id}', 'show')->name('projet.show');
        //we have to put "/tag" in front because otherwise we would have conflict with the route above
        Route::get('/search/tag={tag}', 'displayByTags')->name('project.showByTag');
        Route::get('/search/start={startDate}&end={endDate}', 'displayBetweenDates')->name('project.showByDate');
        Route::get('/search/tag={tag}&start={startDate}&end={endDate}');
    }
);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/hello', function (Request $request) {
        return 'Hello, World!';
    });

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});
