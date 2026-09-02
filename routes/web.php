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
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\WritingTestController;
use App\Http\Controllers\Admin\WritingTaskController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;

// Public frontend API routes
Route::get('/api/levels', [FrontendController::class, 'getLevels'])->name('frontend.levels');
Route::get('/api/test-types', [FrontendController::class, 'getTestTypes'])->name('frontend.test-types');
Route::get('/api/module-sets', [FrontendController::class, 'getModuleSets'])->name('frontend.module-sets');
Route::get('/api/tests', [FrontendController::class, 'getTests'])->name('frontend.tests');
Route::get('/api/search-tests', [FrontendController::class, 'searchTests'])->name('frontend.search-tests');

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
    Route::resource('writing-tests', WritingTestController::class);
    Route::resource('writing-tasks', WritingTaskController::class);
    Route::resource('speaking-tests', App\Http\Controllers\Admin\SpeakingTestController::class);
    Route::resource('listening-tests', App\Http\Controllers\Admin\ListeningTestController::class);
    Route::get('listening-parts/create', [App\Http\Controllers\Admin\ListeningTestController::class, 'createPart'])->name('listening-parts.create');
    Route::post('listening-parts', [App\Http\Controllers\Admin\ListeningTestController::class, 'storePart'])->name('listening-parts.store');
    Route::get('listening-parts/{part}', [App\Http\Controllers\Admin\ListeningTestController::class, 'showPart'])->name('listening-parts.show');
    Route::post('listening-parts/{part}/update', [App\Http\Controllers\Admin\ListeningTestController::class, 'updatePart'])->name('listening-parts.update');
    Route::delete('listening-parts/{part}', [App\Http\Controllers\Admin\ListeningTestController::class, 'destroyPart'])->name('listening-parts.destroy');

    Route::get('listening-questions/create', [App\Http\Controllers\Admin\ListeningTestController::class, 'createQuestion'])->name('listening-questions.create');
    Route::post('listening-questions', [App\Http\Controllers\Admin\ListeningTestController::class, 'storeQuestion'])->name('listening-questions.store');
    Route::get('listening-questions/{question}/edit', [App\Http\Controllers\Admin\ListeningTestController::class, 'editQuestion'])->name('listening-questions.edit');
    Route::post('listening-questions/{question}/update', [App\Http\Controllers\Admin\ListeningTestController::class, 'updateQuestion'])->name('listening-questions.update');
    Route::delete('listening-questions/{question}', [App\Http\Controllers\Admin\ListeningTestController::class, 'destroyQuestion'])->name('listening-questions.destroy');
    Route::resource('questions', QuestionController::class);
    Route::resource('question-groups', QuestionGroupController::class);
    
    // Automated Import
    Route::get('import', [App\Http\Controllers\Admin\IeltsImportController::class, 'create'])->name('import.create');
    Route::post('import', [App\Http\Controllers\Admin\IeltsImportController::class, 'store'])->name('import.store');

    // User Management
    Route::resource('students', StudentController::class);
    Route::resource('results', ResultController::class);
    Route::get('results/{attempt}/review', [ResultController::class, 'review'])->name('results.review');
    Route::get('results/{attempt}/download-pdf', [ResultController::class, 'downloadPdf'])->name('results.download-pdf');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Exam Timing Settings
    Route::get('/exam-timing', [App\Http\Controllers\Admin\ExamTimingController::class, 'edit'])->name('exam-timing.edit');
    Route::put('/exam-timing', [App\Http\Controllers\Admin\ExamTimingController::class, 'update'])->name('exam-timing.update');

    // Writing Grading
    Route::post('writing-attempts/{id}/grade', [ResultController::class, 'gradeWriting'])->name('writing-attempts.grade');
});

// Student Protected Routes
Route::prefix('student')->name('student.')->middleware(['auth:student'])->group(function () {
    Route::get('/dashboard', function () {
        $studentId = auth('student')->id();
        
        $tests = \App\Models\Test::where('status', 'active')
            ->with(['moduleSet', 'questionGroups.questions', 'attempts' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])->get();
            
        $writingTests = \App\Models\WritingTest::where('status', 'active')
            ->with(['attempts' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])->get();

        $listeningTests = \App\Models\ListeningTest::where('status', 'active')
            ->with(['level', 'attempts' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])->get();

        $speakingTests = \App\Models\SpeakingTest::where('status', 'active')
            ->with(['level'])->get();
            
        $stats = [
            'assigned' => $tests->count() + $writingTests->count() + $listeningTests->count() + $speakingTests->count(),
            'completed' => $tests->filter(fn($t) => $t->attempts->where('status', 'completed')->count() > 0)->count() + 
                         $writingTests->filter(fn($t) => $t->attempts->where('status', 'completed')->count() > 0)->count() +
                         $listeningTests->filter(fn($t) => $t->attempts->where('status', 'completed')->count() > 0)->count(),
            'average' => $tests->flatMap(fn($t) => $t->attempts->where('status', 'completed'))->avg('score') ?? 0
        ];
        
        return view('student.dashboard', compact('tests', 'writingTests', 'listeningTests', 'speakingTests', 'stats'));
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('student.profile');
    })->name('profile');

    Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/edit', [StudentProfileController::class, 'update'])->name('profile.update');

    // Test Management
    Route::get('/take-test', [\App\Http\Controllers\Student\MockTestController::class, 'take'])->name('tests.take');
    Route::get('/my-tests', [\App\Http\Controllers\Student\MockTestController::class, 'index'])->name('tests.index');
    Route::get('/tests/{id}', [\App\Http\Controllers\Student\MockTestController::class, 'show'])->name('tests.show');
    Route::get('/tests/{id}/submit', [\App\Http\Controllers\Student\MockTestController::class, 'thankYou'])->name('tests.thank-you');
    Route::get('/tests/{id}/review', [\App\Http\Controllers\Student\MockTestController::class, 'review'])->name('tests.review');
    Route::get('/tests/{id}/download-pdf', [\App\Http\Controllers\Student\MockTestController::class, 'downloadPdf'])->name('tests.download-pdf');
    Route::post('/tests/{id}/submit', [\App\Http\Controllers\Student\MockTestController::class, 'submit'])->name('tests.submit');
    Route::post('/tests/{id}/save-progress', [\App\Http\Controllers\Student\MockTestController::class, 'saveProgress'])->name('tests.save-progress');
    Route::get('/tests/{id}/restart', [\App\Http\Controllers\Student\MockTestController::class, 'restart'])->name('tests.restart');
    Route::post('/writing-tests/{id}/submit', [\App\Http\Controllers\Student\MockTestController::class, 'submitWriting'])->name('writing-tests.submit');
});

