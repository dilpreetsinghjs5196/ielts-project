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
        
        // Regular Tests (Listening/Reading/Speaking)
        $tests = Test::whereHas('attempts', function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })
            ->with(['moduleSet', 'category', 'questionGroups.questions', 'attempts' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])->get();

        // Writing Tests
        $writingTests = \App\Models\WritingTest::whereHas('attempts', function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })
            ->with(['attempts' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])->get();
            
        // Speaking Tests
        $speakingTests = \App\Models\SpeakingTest::whereHas('attempts', function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })
            ->with(['attempts' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])->get();
            
        return view('student.tests.index', compact('tests', 'writingTests', 'speakingTests'));
    }

    public function show(Request $request, $id)
    {
        $student = auth('student')->user();
        $categorySlug = $request->get('category');

        // IF CATEGORY IS WRITING, LOAD FROM WRITING_TESTS
        if ($categorySlug === 'writing') {
            $test = \App\Models\WritingTest::with('tasks')->findOrFail($id);
            
            // Find latest writing attempt
            $attempt = \App\Models\WritingAttempt::where('student_id', $student->id)
                ->where('writing_test_id', $test->id)
                ->latest()
                ->first();

            // If no attempt exists, we'll create a placeholder if needed or just handle it in view
            // For writing, we usually just start fresh if no attempt.
            
            return view('student.writing.show', compact('test', 'attempt'));
        }

        // IF CATEGORY IS SPEAKING, LOAD FROM SPEAKING_TESTS
        if ($categorySlug === 'speaking') {
            $test = \App\Models\SpeakingTest::with('parts.questions')->findOrFail($id);
            return view('student.speaking.show', compact('test'));
        }

        // REGULAR TEST HANDLING
        $test = Test::findOrFail($id);
        
        // Find latest attempt (completed or pending)
        $attempt = \App\Models\TestAttempt::where('student_id', $student->id)
            ->where('test_id', $test->id)
            ->latest()
            ->first();

        // If no attempt exists, create a new one
        if (!$attempt) {
            $attempt = \App\Models\TestAttempt::create([
                'student_id' => $student->id,
                'test_id' => $test->id,
                'status' => 'pending',
                'started_at' => now(),
                'time_left' => 3600
            ]);
        }

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
            $studentAnswers = $request->answers;
            $score = 0;
            
            // Load questions for grading
            $test->load('questionGroups.questions');
            
            foreach ($test->questionGroups as $group) {
                foreach ($group->questions as $question) {
                    $qId = $question->id;
                    if (isset($studentAnswers[$qId])) {
                        if ($this->gradeQuestion($question, $studentAnswers[$qId])) {
                            $score += $question->marks;
                        }
                    }
                }
            }

            $attempt->update([
                'status' => 'completed',
                'answers' => $studentAnswers,
                'score' => $score,
                'completed_at' => now()
            ]);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Test submitted successfully!',
            'score' => $score ?? 0,
            'redirect' => route('student.tests.thank-you', $test)
        ]);
    }

    public function submitWriting(Request $request, $id)
    {
        $student = auth('student')->user();
        $test = \App\Models\WritingTest::findOrFail($id);
        
        $answers = $request->answers; // JSON/Array of part_number => text

        $attempt = \App\Models\WritingAttempt::create([
            'student_id' => $student->id,
            'writing_test_id' => $test->id,
            'answers' => $answers,
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Writing test submitted successfully!',
            'redirect' => route('student.dashboard')
        ]);
    }

    private function gradeQuestion($question, $studentAnswer)
    {
        if (empty($studentAnswer)) return false;
        
        $correct = trim(strtolower($question->correct_answer));
        
        if ($question->question_type === 'mcq_multi') {
            // Student answer is array e.g. ['A', 'B']
            if (!is_array($studentAnswer)) return false;
            
            // Normalize correct answer (e.g. "A, B" or "A and B")
            $correctArray = preg_split('/[,]| and /', $correct);
            $correctArray = array_map('trim', $correctArray);
            
            // Normalize student answer
            $studentArray = array_map('trim', array_map('strtolower', $studentAnswer));
            
            sort($correctArray);
            sort($studentArray);
            
            return $correctArray == $studentArray;
        }
        
        // Single answer comparison (case-insensitive)
        return $correct === trim(strtolower((string)$studentAnswer));
    }

    public function saveProgress(Request $request, Test $test)
    {
        $student = auth('student')->user();
        
        $attempt = \App\Models\TestAttempt::where('student_id', $student->id)
            ->where('test_id', $test->id)
            ->where('status', 'pending')
            ->first();

        if ($attempt) {
            $attempt->update([
                'answers' => $request->answers,
                'time_left' => $request->time_left
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function restart(Request $request, $id)
    {
        $student = auth('student')->user();
        $category = $request->get('category');

        if ($category === 'writing') {
            \App\Models\WritingAttempt::where('student_id', $student->id)
                ->where('writing_test_id', $id)
                ->delete();
            return redirect()->route('student.tests.show', ['id' => $id, 'category' => 'writing'])->with('success', 'Test restarted!');
        }

        \App\Models\TestAttempt::where('student_id', $student->id)
            ->where('test_id', $id)
            ->delete();

        return redirect()->route('student.tests.show', $id)->with('success', 'Test restarted!');
    }

    public function thankYou(Test $test)
    {
        $studentId = auth('student')->id();
        $attempt = \App\Models\TestAttempt::where('student_id', $studentId)
            ->where('test_id', $test->id)
            ->where('status', 'completed')
            ->latest()
            ->first();

        if (!$attempt) {
            return redirect()->route('student.dashboard');
        }

        return view('student.tests.thank-you', compact('test', 'attempt'));
    }

    public function review(Request $request, $id)
    {
        $studentId = auth('student')->id();
        $category = $request->get('category');

        if ($category === 'writing') {
            $test = \App\Models\WritingTest::with('tasks')->findOrFail($id);
            $attempt = \App\Models\WritingAttempt::where('student_id', $studentId)
                ->where('writing_test_id', $id)
                ->where('status', 'completed')
                ->latest()
                ->first();

            if (!$attempt) {
                return redirect()->route('student.dashboard');
            }

            return view('student.writing.review', compact('test', 'attempt'));
        }

        // Default (Standard Test)
        $test = Test::findOrFail($id);
        $attempt = \App\Models\TestAttempt::where('student_id', $studentId)
            ->where('test_id', $test->id)
            ->where('status', 'completed')
            ->latest()
            ->first();

        if (!$attempt) {
            return redirect()->route('student.dashboard');
        }

        $test->load(['moduleSet', 'questionGroups.questions', 'questionGroups.category']);
        
        return view('student.tests.review', compact('test', 'attempt'));
    }
}
