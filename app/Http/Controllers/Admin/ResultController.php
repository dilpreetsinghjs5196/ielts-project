<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestAttempt;
use App\Models\WritingAttempt;
use App\Models\ListeningAttempt;
use App\Models\Student;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $testQuery = TestAttempt::with(['student', 'test.moduleSet', 'test.category'])->latest();
        $writingQuery = WritingAttempt::with(['student', 'writingTest.level'])->latest();
        $listeningQuery = ListeningAttempt::with(['student', 'listeningTest.level'])->latest();
        
        if ($request->has('student_id')) {
            $testQuery->where('student_id', $request->student_id);
            $writingQuery->where('student_id', $request->student_id);
            $listeningQuery->where('student_id', $request->student_id);
        }

        $testAttempts = $testQuery->get()->map(function($item) {
            $item->attempt_type = 'standard';
            return $item;
        });

        $writingAttempts = $writingQuery->get()->map(function($item) {
            $item->attempt_type = 'writing';
            return $item;
        });

        $listeningAttempts = $listeningQuery->get()->map(function($item) {
            $item->attempt_type = 'listening';
            return $item;
        });

        $attempts = $testAttempts->concat($writingAttempts)->concat($listeningAttempts)->sortByDesc('created_at');
        
        $student = $request->student_id ? Student::find($request->student_id) : null;

        return view('admin.results.index', compact('attempts', 'student'));
    }

    public function review(Request $request, $id)
    {
        $type = $request->get('type', 'standard');

        if ($type === 'writing') {
            $attempt = WritingAttempt::with(['student', 'writingTest.tasks'])->findOrFail($id);
            $test = $attempt->writingTest;
            return view('student.writing.review', compact('test', 'attempt'));
        }

        if ($type === 'listening') {
            $attempt = ListeningAttempt::with(['student', 'listeningTest.parts.questions'])->findOrFail($id);
            $test = $attempt->listeningTest;
            return view('student.listening.review', compact('test', 'attempt'));
        }

        $attempt = TestAttempt::with(['student', 'test.questionGroups.questions', 'test.questionGroups.category'])->findOrFail($id);
        $test = $attempt->test;
        return view('student.tests.review', compact('test', 'attempt'));
    }

    public function show(Request $request, $id)
    {
        $type = $request->get('type', 'standard');

        if ($type === 'listening') {
            $attempt = ListeningAttempt::with(['student', 'listeningTest.parts.questions'])->findOrFail($id);
            return view('admin.results.listening_show', compact('attempt'));
        }

        if ($type === 'writing') {
            $attempt = WritingAttempt::with(['student', 'writingTest.tasks'])->findOrFail($id);
            return view('admin.results.writing_show', compact('attempt')); // Assuming you have/need this
        }

        $attempt = TestAttempt::with(['student', 'test.questionGroups.questions'])->findOrFail($id);
        return view('admin.results.show', compact('attempt'));
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->get('type', 'standard');

        if ($type === 'writing') {
            WritingAttempt::findOrFail($id)->delete();
        } elseif ($type === 'listening') {
            ListeningAttempt::findOrFail($id)->delete();
        } else {
            TestAttempt::findOrFail($id)->delete();
        }

        return back()->with('success', 'Attempt deleted successfully.');
    }

    public function gradeWriting(Request $request, $id)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:9',
            'feedback' => 'nullable|string'
        ]);

        $attempt = WritingAttempt::findOrFail($id);
        $attempt->update([
            'score' => $request->score,
            'feedback' => $request->feedback
        ]);

        return redirect()->back()->with('success', 'Writing test graded successfully!');
    }
}
