<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('/admin/categories/index', 'index')->name('admin.categories.index');
    Route::post('/admin/categories/store', 'store')->name('admin.categories.store');
});