<?php

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


Route::group(['middleware' => 'auth:sanctum'], function(){
  Route::get('/hello', function (Request $request) {
      return 'Hello, World!';
  });

  Route::get("/projects", [\App\Http\Controllers\ProjectController::class, 'index'])->name("projet index");
  // QUESTION : how to have id argument ?
   //  route::get("/projects/id"[\App\Http\Controllers\ProjectController::class, 'show']) _> need argument to function
    // Question best way to group routes ?

  // QUESTION: alignement ? php-cs fixer how make it works ?
    // _> documentation readme to complete
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});
