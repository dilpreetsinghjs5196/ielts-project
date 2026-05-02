<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IeltsParserService;
use App\Services\WritingParserService;
use App\Models\Test;
use App\Models\WritingTest;
use App\Models\WritingTask;
use App\Models\QuestionGroup;
use App\Models\Question;
use App\Models\Category;
use App\Models\SpeakingTest;
use App\Models\SpeakingPart;
use App\Models\SpeakingQuestion;
use App\Services\SpeakingParserService;
use App\Models\Level;
use App\Models\TestType;
use App\Models\ListeningTest;
use App\Models\ListeningPart;
use App\Models\ListeningQuestion;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser as PdfParser;

class IeltsImportController extends Controller
{
    protected $parser;
    protected $writingParser;
    protected $speakingParser;

    public function __construct(IeltsParserService $parser, WritingParserService $writingParser, SpeakingParserService $speakingParser)
    {
        $this->parser = $parser;
        $this->writingParser = $writingParser;
        $this->speakingParser = $speakingParser;
    }

    public function create()
    {
        $categories = Category::all();
        $levels = Level::all();
        $testTypes = TestType::all();
        return view('admin.import.create', compact('categories', 'levels', 'testTypes'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'test_file' => 'required|file',
                'category_id' => 'required|exists:categories,id',
                'level_id' => 'required|exists:levels,id',
                'test_type_id' => 'required|exists:test_types,id',
                'test_name' => 'nullable|string|max:255',
            ]);

            $file = $request->file('test_file');
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, ['pdf', 'docx', 'doc'])) {
                return redirect()->back()->with('error', 'Import failed: The test file field must be a file of type: pdf, docx.');
            }
            $activeCategory = Category::find($request->category_id);
            $text = $this->extractText($file);

            if (empty(trim($text))) {
                return redirect()->back()->with('error', 'Could not extract any text from the file.');
            }

            // AUTO-NAME FALLBACK
            $testName = $request->test_name ?: 'Mock Test - ' . date('d M Y H:i');

            // HANDLE WRITING CATEGORY
            if ($activeCategory->slug === 'writing') {
                return $this->handleWritingImport($request, $text, $testName);
            }

            // HANDLE SPEAKING CATEGORY
            if ($activeCategory->slug === 'speaking') {
                return $this->handleSpeakingImport($request, $text, $testName);
            }

            // HANDLE LISTENING CATEGORY
            if ($activeCategory->slug === 'listening') {
                return $this->handleListeningImport($request, $text, $testName);
            }

            // HANDLE READING
            $parsedData = $this->parser->parseText($text);
            
            $test = Test::create([
                'name' => $testName,
                'category_id' => $request->category_id,
                'level_id' => $request->level_id,
                'test_type_id' => $request->test_type_id,
                'status' => 'inactive',
            ]);

            $segmentsCreated = 0;
            foreach ($parsedData as $segmentData) {
                // ... same Reading/Listening logic ...
                $totalQuestions = 0;
                foreach ($segmentData['sub_segments'] as $sub) {
                    $totalQuestions += count($sub['questions']);
                }
                if ($totalQuestions === 0) continue;

                $group = QuestionGroup::create([
                    'test_id' => $test->id,
                    'category_id' => $request->category_id,
                    'level_id' => $request->level_id,
                    'test_type_id' => $request->test_type_id,
                    'title' => $segmentData['title'],
                    'passage' => $segmentData['content'],
                    'instruction' => $segmentData['sub_segments'][0]['header'] ?? null,
                ]);
                $segmentsCreated++;
            }

            if ($segmentsCreated === 0) {
                $test->delete();
                return redirect()->back()->with('error', 'No valid questions found for Reading/Listening.');
            }

            return redirect()->route('admin.tests.index', ['category' => $activeCategory->slug])
                ->with('success', 'Test imported successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    protected function handleWritingImport($request, $text, $testName)
    {
        $parsedTasks = $this->writingParser->parseText($text);

        $writingTest = WritingTest::create([
            'name' => $testName,
            'level_id' => $request->level_id,
            'test_type_id' => $request->test_type_id,
            'status' => 'inactive',
        ]);

        foreach ($parsedTasks as $taskData) {
            WritingTask::create([
                'writing_test_id' => $writingTest->id,
                'task_number' => $taskData['task_number'],
                'title' => $taskData['title'],
                'instruction' => $taskData['instruction'],
                'question_text' => $taskData['question_text'],
                'marks' => ($taskData['task_number'] == 1 ? 3 : 6)
            ]);
        }

        return redirect()->route('admin.writing-tests.edit', $writingTest->id)
            ->with('success', "Writing Test '{$testName}' imported successfully! You can now review the tasks.");
    }

    protected function handleSpeakingImport($request, $text, $testName)
    {
        $parsedSegments = $this->speakingParser->parseText($text);
        
        $speakingTest = SpeakingTest::create([
            'name' => $testName,
            'level_id' => $request->level_id,
            'test_type_id' => $request->test_type_id,
            'status' => 'inactive',
        ]);

        foreach ($parsedSegments as $index => $segmentData) {
            $part = SpeakingPart::create([
                'speaking_test_id' => $speakingTest->id,
                'part_number' => $index + 1,
                'title' => $segmentData['title'],
                'passage' => $segmentData['passage'],
            ]);

            foreach ($segmentData['questions'] as $qData) {
                SpeakingQuestion::create([
                    'speaking_part_id' => $part->id,
                    'question_text' => $qData['body'],
                ]);
            }
        }

        return redirect()->route('admin.speaking-tests.edit', $speakingTest->id)
            ->with('success', "Speaking Test '{$testName}' imported successfully into new dedicated tables!");
    }

    protected function handleListeningImport($request, $text, $testName)
    {
        $parsedData = $this->parser->parseText($text);
        
        $listeningTest = ListeningTest::create([
            'name' => $testName,
            'test_type_id' => $request->test_type_id,
            'level_id' => $request->level_id,
            'status' => 'inactive',
        ]);

        $partsCreated = 0;
        foreach ($parsedData as $index => $segmentData) {
            // Check if there are any questions in this segment
            $totalQuestions = 0;
            foreach ($segmentData['sub_segments'] as $sub) {
                $totalQuestions += count($sub['questions']);
            }
            if ($totalQuestions === 0) continue;

            $part = ListeningPart::create([
                'listening_test_id' => $listeningTest->id,
                'part_number' => $partsCreated + 1,
                'title' => $segmentData['title'],
                'instruction' => $segmentData['sub_segments'][0]['header'] ?? null,
                'passage' => $segmentData['content'], // This stores the transcript if provided
            ]);

            foreach ($segmentData['sub_segments'] as $subSegment) {
                foreach ($subSegment['questions'] as $qData) {
                    ListeningQuestion::create([
                        'listening_part_id' => $part->id,
                        'question_number' => $qData['number'],
                        'question_type' => $qData['type'],
                        'title' => $qData['body'],
                        'options' => $qData['options'],
                        'marks' => 1,
                    ]);
                }
            }
            $partsCreated++;
        }

        if ($partsCreated === 0) {
            $listeningTest->delete();
            throw new \Exception("No valid parts or questions found in the file.");
        }

        return redirect()->route('admin.listening-tests.edit', $listeningTest->id)
            ->with('success', "Listening Test '{$testName}' imported successfully! You can now upload audio and images.");
    }

    protected function extractText($file)
    {
        $text = "";
        $extension = strtolower($file->getClientOriginalExtension());
        
        if ($extension === 'docx') {
            $phpWord = IOFactory::load($file->getRealPath());
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    $text .= $this->getRecursiveText($element) . "\n";
                }
            }
        } else {
            $pdfParser = new PdfParser();
            $pdf = $pdfParser->parseFile($file->getRealPath());
            $text = $pdf->getText();
        }
        return $text;
    }

    protected function getRecursiveText($element)
    {
        $text = "";
        if (get_class($element) === 'PhpOffice\PhpWord\Element\Table') {
            foreach ($element->getRows() as $row) {
                foreach ($row->getCells() as $cell) {
                    $text .= $this->getRecursiveText($cell) . " | ";
                }
                $text .= "\n";
            }
            return $text;
        }

        if (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $child) {
                $text .= $this->getRecursiveText($child);
            }
        } elseif (method_exists($element, 'getText')) {
            $val = $element->getText();
            if (is_string($val)) {
                $text .= $val;
            }
        }

        if (get_class($element) === 'PhpOffice\PhpWord\Element\TextBreak') {
            $text .= "\n";
        }
        if (get_class($element) === 'PhpOffice\PhpWord\Element\ListItem') {
            $text = "- " . $text . "\n";
        }

        return $text;
    }
}
