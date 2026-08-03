<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Test;
use App\Models\Category;
use App\Models\ListeningTest;
use App\Models\SpeakingTest;
use App\Models\WritingTest;
use App\Models\ListeningPart;
use App\Models\TestAttempt;
use App\Models\ListeningAttempt;

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
        
        // Regular Tests (Reading)
        $tests = Test::where('status', 'active')
            ->with(['moduleSet', 'category', 'questionGroups.questions', 'attempts' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])->get();

        // Writing Tests
        $writingTests = \App\Models\WritingTest::where('status', 'active')
            ->with(['attempts' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])->get();

        // Listening Tests
        $listeningTests = \App\Models\ListeningTest::where('status', 'active')
            ->with(['level', 'attempts' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])->get();
            
        // Speaking Tests
        $speakingTests = \App\Models\SpeakingTest::where('status', 'active')
            ->with(['level'])->get();
            
        return view('student.tests.index', compact('tests', 'writingTests', 'listeningTests', 'speakingTests'));
    }

    public function show(Request $request, $id)
    {
        $student = auth('student')->user();
        $categorySlug = $request->get('category');

        // Fetch configured timing (fallback to 60 minutes)
        $timing = \App\Models\ExamTiming::first();
        $examDurationInSeconds = ($timing ? $timing->exam_time : 60) * 60;

        // IF CATEGORY IS WRITING, LOAD FROM WRITING_TESTS
        if ($categorySlug === 'writing') {
            $test = WritingTest::with('tasks')->findOrFail($id);
            
            // Find latest writing attempt
            $attempt = \App\Models\WritingAttempt::where('student_id', $student->id)
                ->where('writing_test_id', $test->id)
                ->latest()
                ->first();

            // If no attempt exists, create a new pending one
            if (!$attempt) {
                $attempt = \App\Models\WritingAttempt::create([
                    'student_id' => $student->id,
                    'writing_test_id' => $test->id,
                    'status' => 'pending',
                    'started_at' => now(),
                    'time_left' => $examDurationInSeconds
                ]);
            }

            return view('student.writing.show', compact('test', 'attempt', 'examDurationInSeconds'));
        }

        // IF CATEGORY IS SPEAKING, LOAD FROM SPEAKING_TESTS
        if ($categorySlug === 'speaking') {
            $test = SpeakingTest::with('parts.questions')->findOrFail($id);
            return view('student.speaking.show', compact('test'));
        }

        // IF CATEGORY IS LISTENING, LOAD FROM LISTENING_TESTS
        if ($categorySlug === 'listening') {
            $test = ListeningTest::with('parts.questions')->findOrFail($id);
            
            // Find or create attempt
            $attempt = ListeningAttempt::where('student_id', $student->id)
                ->where('listening_test_id', $test->id)
                ->latest()
                ->first();

            if (!$attempt) {
                $attempt = ListeningAttempt::create([
                    'student_id' => $student->id,
                    'listening_test_id' => $test->id,
                    'status' => 'pending',
                    'started_at' => now(),
                    'time_left' => $examDurationInSeconds
                ]);
            }
            
            return view('student.listening.show', compact('test', 'attempt', 'examDurationInSeconds'));
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
                'time_left' => $examDurationInSeconds
            ]);
        }

        $test->load(['moduleSet', 'questionGroups.questions', 'questionGroups.category']);
        
        return view('student.tests.show', compact('test', 'attempt', 'examDurationInSeconds'));
    }

    public function submit(Request $request, $id)
    {
        $student = auth('student')->user();
        $category = $request->get('category');

        if ($category === 'listening') {
            $test = ListeningTest::with('parts.questions')->findOrFail($id);
            $attempt = ListeningAttempt::where('student_id', $student->id)
                ->where('listening_test_id', $test->id)
                ->where('status', 'pending')
                ->first();

            if ($attempt) {
                $studentAnswers = $request->answers;
                $score = 0;
                
                foreach ($test->parts as $part) {
                    foreach ($part->questions as $question) {
                        $qId = $question->id;
                        if (isset($studentAnswers[$qId])) {
                            $score += $this->gradeQuestion($question, $studentAnswers[$qId]);
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
                'message' => 'Listening test submitted successfully!',
                'score' => $score ?? 0,
                'redirect' => route('student.dashboard')
            ]);
        }

        // Default: Reading/General Test
        $test = Test::with('questionGroups.questions')->findOrFail($id);
        $attempt = \App\Models\TestAttempt::where('student_id', $student->id)
            ->where('test_id', $test->id)
            ->where('status', 'pending')
            ->first();

        if ($attempt) {
            $studentAnswers = $request->answers;
            $score = 0;
            
            foreach ($test->questionGroups as $group) {
                foreach ($group->questions as $question) {
                    $qId = $question->id;
                    if (isset($studentAnswers[$qId])) {
                        $score += $this->gradeQuestion($question, $studentAnswers[$qId]);
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
            'redirect' => route('student.tests.thank-you', $test->id)
        ]);
    }

    public function submitWriting(Request $request, $id)
    {
        $student = auth('student')->user();
        $test = \App\Models\WritingTest::findOrFail($id);
        
        $answers = $request->answers; // JSON/Array of part_number => text

        $attempt = \App\Models\WritingAttempt::where('student_id', $student->id)
            ->where('writing_test_id', $test->id)
            ->where('status', 'pending')
            ->first();

        if ($attempt) {
            $attempt->update([
                'answers' => $answers,
                'status' => 'completed',
                'completed_at' => now()
            ]);
        } else {
            $attempt = \App\Models\WritingAttempt::create([
                'student_id' => $student->id,
                'writing_test_id' => $test->id,
                'answers' => $answers,
                'status' => 'completed',
                'completed_at' => now()
            ]);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Writing test submitted successfully!',
            'redirect' => route('student.dashboard')
        ]);
    }

    private function gradeQuestion($question, $studentAnswer)
    {
        if (empty($studentAnswer)) return 0;
        
        $correct = trim(strtolower($question->correct_answer));

        // Handle Range Questions (e.g. "1-10") - Partial Scoring
        if (strpos($question->question_number, '-') !== false) {
            // Split correct answers by comma, " and ", or semicolon
            $correctArray = preg_split('/[,]| and |;/', $correct);
            $correctArray = array_map('trim', $correctArray);
            
            // Split student answers (usually joined by comma in JS)
            $studentArray = is_array($studentAnswer) ? $studentAnswer : explode(',', $studentAnswer);
            $studentArray = array_map('trim', array_map('strtolower', $studentArray));

            $score = 0;
            foreach ($correctArray as $idx => $val) {
                if (isset($studentArray[$idx]) && $val === $studentArray[$idx]) {
                    $score++;
                }
            }
            return $score;
        }
        
        if ($question->question_type === 'mcq_multi') {
            // Student answer can be array or comma-separated string
            $studentArray = is_array($studentAnswer) ? $studentAnswer : explode(',', $studentAnswer);
            $studentArray = array_map('trim', array_map('strtolower', $studentArray));
            $studentArray = array_filter($studentArray);
            
            if (empty($studentArray)) return 0;
            
            // Normalize correct answer (e.g. "A, B" or "A and B")
            $correctArray = preg_split('/[,]| and |;/', $correct);
            $correctArray = array_map('trim', $correctArray);
            
            $score = 0;
            foreach ($studentArray as $ans) {
                if (in_array($ans, $correctArray)) {
                    $score++;
                }
            }
            return $score;
        }
        
        // Single answer comparison with alternative options and singular/plural parenthesis options (e.g. "item / items", "item(s)")
        $alternatives = preg_split('/\s*(?:\/|\||\bor\b)\s*/i', $correct);
        $studentNormalized = trim(strtolower((string)$studentAnswer));
        
        foreach ($alternatives as $alt) {
            $alt = trim($alt);
            // Singular form (remove parentheses contents e.g. "item(s)" -> "item")
            $singular = preg_replace('/\s*\(.*?\)\s*/', '', $alt);
            // Plural form (strip parentheses characters e.g. "item(s)" -> "items")
            $plural = str_replace(['(', ')'], '', $alt);
            
            if ($studentNormalized === $singular || $studentNormalized === $plural) {
                return $question->marks ?: 1;
            }
        }
        
        return 0;
    }

    public function saveProgress(Request $request, $id)
    {
        $student = auth('student')->user();
        $category = $request->get('category');

        if ($category === 'listening') {
            $attempt = ListeningAttempt::where('student_id', $student->id)
                ->where('listening_test_id', $id)
                ->where('status', 'pending')
                ->first();
        } elseif ($category === 'writing') {
            $attempt = \App\Models\WritingAttempt::where('student_id', $student->id)
                ->where('writing_test_id', $id)
                ->where('status', 'pending')
                ->first();
        } else {
            $attempt = TestAttempt::where('student_id', $student->id)
                ->where('test_id', $id)
                ->where('status', 'pending')
                ->first();
        }

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

        if ($category === 'listening') {
            \App\Models\ListeningAttempt::where('student_id', $student->id)
                ->where('listening_test_id', $id)
                ->delete();
            return redirect()->route('student.tests.show', ['id' => $id, 'category' => 'listening'])->with('success', 'Test restarted!');
        }

        \App\Models\TestAttempt::where('student_id', $student->id)
            ->where('test_id', $id)
            ->delete();

        return redirect()->route('student.tests.show', $id)->with('success', 'Test restarted!');
    }

    public function thankYou(Request $request, $id)
    {
        $studentId = auth('student')->id();
        $category = $request->get('category');

        if ($category === 'listening') {
            $test = ListeningTest::findOrFail($id);
            $attempt = ListeningAttempt::where('student_id', $studentId)
                ->where('listening_test_id', $id)
                ->where('status', 'completed')
                ->latest()
                ->first();
        } else {
            $test = Test::findOrFail($id);
            $attempt = \App\Models\TestAttempt::where('student_id', $studentId)
                ->where('test_id', $id)
                ->where('status', 'completed')
                ->latest()
                ->first();
        }

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

        if ($category === 'listening') {
            $test = ListeningTest::with('parts.questions')->findOrFail($id);
            $attempt = ListeningAttempt::where('student_id', $studentId)
                ->where('listening_test_id', $id)
                ->where('status', 'completed')
                ->latest()
                ->first();

            if (!$attempt) {
                return redirect()->route('student.dashboard');
            }

            return view('student.listening.review', compact('test', 'attempt'));
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

    public function downloadPdf(Request $request, $id)
    {
        $studentId = auth('student')->id();
        $student = auth('student')->user();
        $category = $request->get('category', 'reading');

        $test = null;
        $attempt = null;
        $totalMarks = 40;
        $moduleName = 'Reading';

        if ($category === 'writing') {
            $test = \App\Models\WritingTest::with('tasks')->findOrFail($id);
            $attempt = \App\Models\WritingAttempt::where('student_id', $studentId)
                ->where('writing_test_id', $id)
                ->where(function ($q) {
                    $q->where('status', 'completed')->orWhere('status', 'graded')->orWhereNotNull('score');
                })
                ->latest()
                ->first();
            $totalMarks = 9.0;
            $moduleName = 'Writing';
        } elseif ($category === 'listening') {
            $test = ListeningTest::with('parts.questions')->findOrFail($id);
            $attempt = ListeningAttempt::where('student_id', $studentId)
                ->where('listening_test_id', $id)
                ->where('status', 'completed')
                ->latest()
                ->first();
            $totalMarks = 40;
            $moduleName = 'Listening';
        } else {
            $test = Test::with(['moduleSet', 'questionGroups.questions'])->findOrFail($id);
            $attempt = \App\Models\TestAttempt::where('student_id', $studentId)
                ->where('test_id', $test->id)
                ->where('status', 'completed')
                ->latest()
                ->first();
            $calcTotal = $test->questionGroups->sum(fn($g) => $g->questions->sum('marks'));
            $totalMarks = $calcTotal > 0 ? $calcTotal : 40;
            $moduleName = 'Reading';
        }

        if (!$attempt) {
            return redirect()->route('student.dashboard')->with('error', 'Test attempt not found or not completed yet.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('student.tests.pdf-report', compact('student', 'test', 'attempt', 'category', 'totalMarks', 'moduleName'))
            ->setOptions(['isRemoteEnabled' => true]);
        
        $filename = \Illuminate\Support\Str::slug(($student ? $student->name : 'Student') . '-' . $test->name . '-Review') . '.pdf';
        return $pdf->download($filename);
    }
}
