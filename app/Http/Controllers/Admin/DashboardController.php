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
        // In the future, we will fetch real statistics from models here
        // e.g., $studentCount = User::where('role', 'student')->count();
        //       $testCount = Test::count();

        return view('admin.dashboard');
    }
}
