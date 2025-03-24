<?php

use App\Http\Controllers\Api\Student\BookmarkController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Student\CourseController;
use App\Http\Controllers\Api\Student\InstractorController;

//User Profile
Route::group(['middleware' => ['jwt.verify']], function () {

    Route::group(['middleware' => ['student']], function () {
        Route::controller(CourseController::class)->prefix('student')->group(function () {
            Route::get('/courses', 'getCourse');
            Route::get('/course/{id}', 'getCourseDetails');
        });

        Route::controller(InstractorController::class)->group(function () {
            Route::get('/instructors', 'getInstructors');
            Route::get('/instructor/{id}', 'getInstructorDetails');
        });

        Route::controller(BookmarkController::class)->prefix('course')->group(function () {
            Route::post('/bookmark/toggle/{id}', 'toggleBookmark');
            Route::get('/bookmarks', 'getBookmarks');
        });
    });

    Route::group(['middleware' => ['instructor']], function () {});
});
