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
            'score' => $score ?? 0
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

    public function restart(Test $test)
    {
        $student = auth('student')->user();
        
        \App\Models\TestAttempt::where('student_id', $student->id)
            ->where('test_id', $test->id)
            ->delete(); // Delete all attempts for this test to restart fresh

        return redirect()->route('student.tests.show', $test)->with('success', 'Test restarted!');
    }
}
