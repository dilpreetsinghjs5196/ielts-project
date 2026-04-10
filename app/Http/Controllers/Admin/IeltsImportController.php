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
use App\Models\Level;
use App\Models\TestType;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser as PdfParser;

class IeltsImportController extends Controller
{
    protected $parser;
    protected $writingParser;

    public function __construct(IeltsParserService $parser, WritingParserService $writingParser)
    {
        $this->parser = $parser;
        $this->writingParser = $writingParser;
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
                'test_file' => 'required|file|mimes:pdf,docx',
                'category_id' => 'required|exists:categories,id',
                'level_id' => 'required|exists:levels,id',
                'test_type_id' => 'required|exists:test_types,id',
                'test_name' => 'nullable|string|max:255',
            ]);

            $file = $request->file('test_file');
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

            // HANDLE READING/LISTENING
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
