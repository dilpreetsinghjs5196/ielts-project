<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestAttempt;
use App\Models\Student;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $query = TestAttempt::with(['student', 'test.moduleSet', 'test.category'])->latest();
        
        $student = null;
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
            $student = Student::find($request->student_id);
        }

        $attempts = $query->get();
        return view('admin.results.index', compact('attempts', 'student'));
    }

    public function show(TestAttempt $attempt)
    {
        $attempt->load(['student', 'test.questionGroups.questions']);
        return view('admin.results.show', compact('attempt'));
    }

    public function destroy(TestAttempt $attempt)
    {
        $attempt->delete();
        return back()->with('success', 'Test attempt deleted successfully.');
    }
}
