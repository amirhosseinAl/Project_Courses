<?php

use Illuminate\Support\Facades\Route;
use Modules\Teacher\App\Http\Controllers\TeacherController;



Route::middleware(['Authentication:teacher'])->group(function () {

    Route::post('/courses/add-student', [TeacherController::class, 'AddByTeacher']);
});
