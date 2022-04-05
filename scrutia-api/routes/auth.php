<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

//Route::post('/register', [RegistrationController::class, 'register'])
//                ->middleware('guest:api')
//                ->name('register');
//
//Route::post('/login', [LoginController::class, 'login'])
//                ->middleware('guest:api')
//                ->name('login');
//
//Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//                ->middleware('guest:api')
//                ->name('password.email');
//
////Route::post('/reset-password', [NewPasswordController::class, 'store'])
////                ->middleware('guest:api')
////                ->name('password.update');
////
////Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
////                ->middleware(['auth', 'signed', 'throttle:6,1'])
////                ->name('verification.verify');
////
////Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
////                ->middleware(['auth', 'throttle:6,1'])
////                ->name('verification.send');
//
//Route::post('/logout', [LoginController::class, 'destroy'])
//                ->middleware('auth')
//                ->name('logout');
