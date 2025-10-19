<?php

use Illuminate\Support\Facades\Route;
use Modules\User\App\Http\Controllers\LoginController;
use Modules\User\App\Http\Controllers\SignupController;

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('users', UserController::class)->names('user');
// });


Route::post('/signup', [SignupController::class, 'SignupForm']);
Route::post('/signup/verify', [SignupController::class, 'SignupVerify']);
Route::post('/resend', [SignupController::class, 'Resend']);
Route::post('/login', [LoginController::class, 'LoginForm']);

