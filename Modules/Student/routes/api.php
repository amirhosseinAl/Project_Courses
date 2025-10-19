<?php

use Illuminate\Support\Facades\Route;
use Modules\Student\App\Http\Controllers\StudentController;

Route::middleware(['Authentication'])->group(function () {
    Route::post('/courses/register', [StudentController::class, 'Register']);
    Route::get('/mycourses', [StudentController::class, 'StudentCourses']);
});
