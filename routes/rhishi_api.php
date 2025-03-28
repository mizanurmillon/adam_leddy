<?php

use App\Http\Controllers\Api\Instructor\ConnectAccount;
use App\Http\Controllers\Api\Student\BookmarkController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Student\CourseController;
use App\Http\Controllers\Api\Student\CourseWatchTimeAndProgressController;
use App\Http\Controllers\Api\Student\InstractorController;
use App\Http\Controllers\Api\Student\PaymentController;
use App\Http\Controllers\Api\Student\SubscriptionController;
use App\Http\Controllers\Api\Student\WatchListController;

//User Profile
Route::group(['middleware' => ['jwt.verify']], function () {

    Route::group(['middleware' => ['student']], function () {

        Route::group(['middleware' => ['is_membership_taken']], function () {
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

            Route::controller(CourseWatchTimeAndProgressController::class)->prefix('course')->group(function () {
                Route::post('/watch', 'watch');
            });

            Route::controller(WatchListController::class)->group(function () {
                Route::get('/my/watch-list', 'watchList');
            });
        });

        Route::controller(SubscriptionController::class)->group(function () {
            Route::get('/subscription-plans', 'subscriptionPlans');
        });

        Route::controller(PaymentController::class)->prefix('student')->group(function () {
            Route::post('/checkout', 'checkout');
            Route::post('/checkout-success', 'checkoutSuccess')->name('checkout.success');
            Route::post('/checkout-cancel', 'checkoutCancel')->name('checkout.cancel');
        });
    });

    Route::group(['middleware' => ['instructor']], function () {
        Route::controller(ConnectAccount::class)->prefix('instructor')->group(function () {
            Route::post('/connect', 'connectAccount');
            Route::get('/connect/success', 'success')->name('connect.success');
            Route::get('/connect/cancel', 'cancel')->name('connect.cancel');
        });
    });
});

Route::controller(PaymentController::class)->group(function () {
    Route::get('/checkout-success', 'checkoutSuccess')->name('checkout.success');
    Route::get('/checkout-cancel', 'checkoutCancel')->name('checkout.cancel');
});
