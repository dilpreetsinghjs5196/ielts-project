<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_students'  => \App\Models\Student::count(),
            'total_tests'     => \App\Models\Test::count(),
            'total_questions' => \App\Models\Question::count(),
            'recent_attempts' => \App\Models\TestAttempt::count(),
        ];

        $recentSubmissions = \App\Models\TestAttempt::with(['student', 'test.moduleSet'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentSubmissions'));
    }
}
