<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Test;
use App\Models\Category;

class MockTestController extends Controller
{
    public function take()
    {
        $categories = Category::all();
        return view('student.tests.take', compact('categories'));
    }

    public function index()
    {
        $studentId = auth('student')->id();
        $tests = Test::where('status', 'active')
            ->whereHas('attempts', function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })
            ->with(['moduleSet', 'category', 'attempts' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])->get();
            
        return view('student.tests.index', compact('tests'));
    }

    public function show(Test $test)
    {
        $student = auth('student')->user();
        
        // Find or create pending attempt
        $attempt = \App\Models\TestAttempt::firstOrCreate([
            'student_id' => $student->id,
            'test_id' => $test->id,
            'status' => 'pending'
        ], [
            'started_at' => now(),
            'time_left' => 3600
        ]);

        $test->load(['moduleSet', 'questionGroups.questions', 'questionGroups.category']);
        
        return view('student.tests.show', compact('test', 'attempt'));
    }

    public function submit(Request $request, Test $test)
    {
        $student = auth('student')->user();
        
        $attempt = \App\Models\TestAttempt::where('student_id', $student->id)
            ->where('test_id', $test->id)
            ->where('status', 'pending')
            ->first();

        if ($attempt) {
            $attempt->update([
                'status' => 'completed',
                'answers' => $request->answers,
                'completed_at' => now()
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Test submitted successfully!']);
    }

    public function restart(Test $test)
    {
        $student = auth('student')->user();
        
        \App\Models\TestAttempt::where('student_id', $student->id)
            ->where('test_id', $test->id)
            ->delete(); // Delete all attempts for this test to restart fresh

        return redirect()->route('student.tests.show', $test)->with('success', 'Test restarted!');
    }
}
