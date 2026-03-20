<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TestTypeController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ModuleSetController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\QuestionGroupController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;

// Public frontend API routes
Route::get('/api/levels', [FrontendController::class, 'getLevels'])->name('frontend.levels');
Route::get('/api/module-sets', [FrontendController::class, 'getModuleSets'])->name('frontend.moduleSets');
Route::get('/api/tests', [FrontendController::class, 'getTests'])->name('frontend.tests');

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth:web'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Test Management
    Route::resource('categories', CategoryController::class);
    Route::resource('test-types', TestTypeController::class);
    Route::resource('levels', LevelController::class);
    Route::resource('module-sets', ModuleSetController::class);
    Route::resource('tests', TestController::class);
    Route::resource('questions', QuestionController::class);
    Route::resource('question-groups', QuestionGroupController::class);

    // User Management
    Route::resource('students', StudentController::class);

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Student Protected Routes
Route::prefix('student')->name('student.')->middleware(['auth:student'])->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('student.profile');
    })->name('profile');

    Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/edit', [StudentProfileController::class, 'update'])->name('profile.update');
});

