<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\UserController;
use App\Http\Controllers\Web\Backend\PaymentController;
use App\Http\Controllers\Web\Backend\ApprovalController;
use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\CouponSubscriptionController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\InstructorController;
use App\Http\Controllers\Web\Backend\CourseManagementController;
use App\Http\Controllers\Web\Backend\RevenueController;
use App\Http\Controllers\Web\Backend\SubscriptionController;
use App\Http\Controllers\Web\Backend\WatchTimeController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories/index', 'index')->name('admin.categories.index');
    Route::post('/categories/store', 'store')->name('admin.categories.store');
    Route::post('/tags/store', 'tagStore')->name('admin.tags.store');
    Route::delete('/tags/destroy/{id}', 'tagDestory')->name('admin.tags.destroy');
    Route::delete('/categories/destroy/{id}', 'categoryDestroy')->name('admin.categories.destroy');

    Route::post('/categories/status/{id}', 'status')->name('admin.categories.status');
});

Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::get('/index', 'index')->name('admin.users.index');
});

Route::controller(CourseManagementController::class)->group(function () {
    Route::get('/courses/index', 'index')->name('admin.courses.index');
    Route::get('/courses/content/{id}', 'content')->name('admin.courses.content');
    Route::post('/courses/tags/store', 'tagStore')->name('course.tag.store');

    Route::delete('/courses/destroy/{id}', 'destroy')->name('admin.courses.destroy');

    Route::delete('/courses/module/destroy/{id}', 'moduleDestroy')->name('admin.modules.destroy');

    Route::delete('/courses/videos/destroy/{id}', 'videoDestroy')->name('admin.videos.destroy');
});

Route::controller(ApprovalController::class)->group(function () {
    Route::get('/approval/index', 'index')->name('admin.approval.index');
    Route::get('/approval/content', 'content')->name('admin.approval.content');
    Route::get('/approval/view', 'view')->name('admin.approval.view');
});

Route::controller(InstructorController::class)->group(function () {
    Route::get('/instructors/index', 'index')->name('admin.instructors.index');
    Route::get('/instructors/details/{id}', 'details')->name('admin.instructors.details');
    Route::get('/instructors/video/details/{id}', 'content')->name('admin.instructors.video.details');

    Route::delete('/instructors/destroy/{id}', 'destroy')->name('admin.instructors.destroy');

    Route::post('/instructors/status/{id}', 'status')->name('admin.instructors.status');

    // Instructor create
    Route::get('/instructors/create', 'create')->name('admin.instructors.create');
    Route::post('/instructors/store', 'store')->name('admin.instructors.store');
});

Route::controller(PaymentController::class)->group(function () {
    Route::get('/payment/index', 'index')->name('admin.payments.index');
    Route::get('/payment/method/change', 'change')->name('admin.payments.method.change');
    Route::post('/payment/method/update', 'update')->name('admin.payments.method.update');
});

Route::controller(WatchTimeController::class)->group(function () {
    Route::get('/watch/time/reports/index', 'index')->name('admin.watch_time.reports.index');
});

Route::controller(RevenueController::class)->group(function () {
    Route::get('/revenue/index', 'index')->name('admin.revenue.index');
});

Route::controller(SubscriptionController::class)->group(function () {
    Route::post('/subscription/update', 'update')->name('admin.subscription.update');
});

Route::controller(CouponSubscriptionController::class)->group(function () {
    Route::get('/coupons', 'index')->name('admin.coupon.index');
    Route::post('/coupon/store', 'store')->name('admin.coupon.store');
    Route::delete('/coupon/{id}/delete', 'destroy')->name('admin.coupon.destroy');
    Route::post('/coupon/status/{id}', 'status')->name('admin.coupon.status');
});
