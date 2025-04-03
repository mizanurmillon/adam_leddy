<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Instructor\TagsController;
use App\Http\Controllers\Api\Instructor\VideoController;
use App\Http\Controllers\Api\Instructor\CourseController;
use App\Http\Controllers\Api\Instructor\CategoryController;
use App\Http\Controllers\Api\Instructor\EarningsController;
use App\Http\Controllers\Api\Instructor\DashboardController;
use App\Http\Controllers\Api\Instructor\NotificationController;

Route::controller(RegisterController::class)->prefix('users')->group(function () {

    // User Register
    Route::post('/register', 'register');

    // Verify OTP
    Route::post('/otp-verify', 'otpVerify');

    // Resend OTP
    Route::post('/otp-resend', 'otpResend');
});

//Login API
Route::controller(LoginController::class)->prefix('users')->group(function () {

    // User Login
    Route::post('/login', 'userLogin');

    // Verify Email
    Route::post('/email-verify', 'emailVerify');

    // Resend OTP
    Route::post('/otp-resend', 'otpResend');

    // Verify OTP
    Route::post('/otp-verify', 'otpVerify');

    //Reset Password
    Route::post('/reset-password', 'resetPassword');
});

//User Profile
Route::group(['middleware' => ['jwt.verify']], function () {

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/data', 'getUserData');
        Route::post('/update', 'updateUserData');
        Route::post('/update-image', 'updateUserImage');
        Route::post('/logout', 'userLogout');
        Route::post('/update-password', 'changePassword');
    });

    Route::group(['middleware' => ['student']], function () {});

    Route::group(['middleware' => ['instructor']], function () {

        Route::controller(CourseController::class)->prefix('instructor')->group(function () {
            Route::post('/create/course', 'create');
            Route::post('/create/course/module/{id}', 'createModule');
            Route::get('/my/courses', 'getCourse');
            Route::get('/course/{id}', 'getCourseDetails');
            Route::post('/edit/course/{id}', 'update');
            Route::post('/edit/course/module/{id}', 'updateModule');
            Route::post('/delete/course/{id}', 'delete');
            Route::post('/delete/course/module/{id}', 'deleteModule');

            Route::get('/submit/for/approval/{id}', 'submitForApproval');
        });

        Route::controller(VideoController::class)->prefix('instructor')->group(function () {
            Route::post('/create/video/{id}', 'create');
        });

        Route::controller(DashboardController::class)->prefix('instructor')->group(function () {
            Route::get('/dashboard', 'index');
        });

        Route::controller(EarningsController::class)->prefix('instructor')->group(function () {
            Route::get('/payments-history', 'index');
        });

        Route::controller(NotificationController::class)->prefix('instructor')->group(function () {
            Route::get('/notifications', 'index');
        });
    });

});
Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('/', 'AllCategories');
});

Route::controller(TagsController::class)->prefix('tags')->group(function () {
    Route::get('/', 'AllTags');
});
