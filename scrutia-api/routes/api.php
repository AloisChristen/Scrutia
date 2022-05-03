<?php

use App\Http\Controllers\API\AnswerController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TagController;
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


Route::controller(TagController::class)->prefix('tags')->group(
    function () {
        Route::get('/', 'index')->name('tag.index');
    }
);


Route::controller(QuestionController::class)->prefix('/questions')->group( // TODO: put in middleware auth
    function () {
        Route::get('/', 'index')->name('question.index');
        Route::post('/', 'store')->name('question.store');
        Route::delete('/{id}', 'destroy')->name('question.delete');

        Route::controller(UserController::class)->prefix('/users')->group( // TODO: put in middleware auth
            function () {
                Route::get('/', 'index')->name('user.index');
                Route::get('/{id}', 'show')->name('user.show');
                Route::delete('/{id}', 'destroy')->name('user.delete');
            }
        );

        Route::group(['middleware' => 'auth:sanctum'], function () {

            Route::controller(AnswerController::class)->prefix('/answers')->group(
                function () {
                    Route::post('/', 'store')->name('answer.store');
                    Route::put('/{id}', 'update')->name('answer.update');
                    Route::delete('/{id}', 'destroy')->name('answer.delete');
                }
            );

            Route::controller(QuestionController::class)->prefix('/questions')->group(
                function () {
                    Route::post('/', 'store')->name('question.store');
                    Route::put('/{id}', 'update')->name('question.update');
                    Route::delete('/{id}', 'destroy')->name('question.delete');
                }
            );

            Route::post('/logout', [LoginController::class, 'logout'])
                ->name('logout');

            Route::controller(ProjectController::class)->prefix('/projects')->group( // TODO: put in middleware auth
                function () {
                    Route::get('/', 'index')->name('project.index');
                    Route::get('/{id}', 'show')->name('project.show');
                    Route::post('/', 'store')->name('project.store');
                    Route::delete('/{id}', 'destroy')->name('project.delete');
                    Route::get('/ideas', 'showIdeas')->name('project.show.ideas');
                    Route::get('/initiatives', 'showInitiatives')->name('project.show.initiatives');
                    Route::put('/{id}/promote', 'promoteToInitiative')->name('project.promote');
                    //we have to put "/tag" in front because otherwise we would have conflict with the route above
                    Route::get('/search/tag={tag}', 'displayByTags')->name('project.showByTag');
                    Route::get('/search/start={startDate}&end={endDate}', 'displayBetweenDates')->name('project.showByDate');
                    Route::get('/search/tag={tag}&start={startDate}&end={endDate}');
                }
            );

        });
    }
);
