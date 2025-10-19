<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\App\Http\Controllers\AnswerController;
use Modules\Course\App\Http\Controllers\CourseController;
use Modules\Course\App\Http\Controllers\EpisodeController;
use Modules\Course\App\Http\Controllers\QuestionController;
use Modules\Course\App\Http\Controllers\SeasonController;
use Modules\Course\App\Http\Controllers\VoteController;


Route::get('/course/{coursePath}', [CourseController::class, 'Show'])->middleware('Authentication:admin,student');

Route::middleware(['Authentication:admin,teacher'])->group(function () {

    Route::post('/courseCreate/create', [CourseController::class, 'Add']);
    Route::post('/course/edit', [CourseController::class, 'Edit']);
    Route::post('/course/delete', [CourseController::class, 'Delete']);


    Route::get('/teacher/mycourse', [CourseController::class, 'TeacherCourses']);
    Route::get('/teacher/studentsCourse', [CourseController::class, 'TeacherStudents']);


    Route::post('/season/create', [SeasonController::class, 'Add']);
    Route::post('/season/edit/{season}', [SeasonController::class, 'Edit']);
    Route::post('/season/delete', [SeasonController::class, 'Delete']);


    Route::post('/episode/create', [EpisodeController::class, 'Add']);
    Route::post('/episode/edit', [EpisodeController::class, 'Edit']);
    Route::post('/episode/delete', [EpisodeController::class, 'Delete']);

    Route::get('/course/{coursePath}/episode/{episodeNumber}', [EpisodeController::class, 'Show']);



    Route::post('/answers/create', [AnswerController::class, 'add']);
    Route::post('/answers/edit', [AnswerController::class, 'Edit']);
    Route::post('/answers/delete', [AnswerController::class, 'Delete']);


    Route::get('/question-answers', [CourseController::class, 'QuestionAnswers']);


    Route::post('/courses/qa', [CourseController::class, 'CourseQuestionsAnswers']);

    Route::get('/topAnswer', [CourseController::class, 'topAnswer']);
});




Route::middleware(['Authentication'])->group(function () {

    Route::post('/episodes/addQuestions', [QuestionController::class, 'add']);
    Route::post('/questions/edit', [QuestionController::class, 'edit']);
    Route::post('/questions/delete', [QuestionController::class, 'delete']);

    Route::post('/answers/vote', [VoteController::class, 'add']);
});
