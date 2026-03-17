<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TestTypeController;
use App\Http\Controllers\Admin\LevelController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Test Management
    Route::resource('categories', CategoryController::class);
    Route::resource('test-types', TestTypeController::class);
    Route::resource('levels', LevelController::class);
});
