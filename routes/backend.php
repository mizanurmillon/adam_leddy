<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\UserController;
use App\Http\Controllers\Web\Backend\PaymentController;
use App\Http\Controllers\Web\Backend\ApprovalController;
use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\InstructorController;
use App\Http\Controllers\Web\Backend\CourseManagementController;
use App\Http\Controllers\Web\Backend\RevenueController;
use App\Http\Controllers\Web\Backend\WatchTimeController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories/index', 'index')->name('admin.categories.index');
    Route::post('/categories/store', 'store')->name('admin.categories.store');
});

Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::get('/index', 'index')->name('admin.users.index');
});

Route::controller(CourseManagementController::class)->group(function () {
    Route::get('/courses/index', 'index')->name('admin.courses.index');
    Route::get('/courses/content', 'content')->name('admin.courses.content');
});

Route::controller(ApprovalController::class)->group(function () {
    Route::get('/approval/index', 'index')->name('admin.approval.index');
    Route::get('/approval/content', 'content')->name('admin.approval.content');
    Route::get('/approval/view', 'view')->name('admin.approval.view');
});

Route::controller(InstructorController::class)->group(function () {
    Route::get('/instructors/index', 'index')->name('admin.instructors.index');
    Route::get('/instructors/details', 'details')->name('admin.instructors.details');
    Route::get('/instructors/video/details', 'content')->name('admin.instructors.video.details');

    // Instructor create 
    Route::get('/instructors/create', 'create')->name('admin.instructors.create');
    Route::post('/instructors/store', 'store')->name('admin.instructors.store');
});

Route::controller(PaymentController::class)->group(function () {
    Route::get('/payment/index', 'index')->name('admin.payments.index');
    Route::get('/payment/method/change', 'change')->name('admin.payments.method.change');
});

Route::controller(WatchTimeController::class)->group(function () {
    Route::get('/watch/time/reports/index', 'index')->name('admin.watch_time.reports.index');
});

Route::controller(RevenueController::class)->group(function () {
    Route::get('/revenue/index', 'index')->name('admin.revenue.index');
});