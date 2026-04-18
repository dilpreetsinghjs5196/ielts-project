<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WritingParserService;
use App\Models\WritingTest;
use App\Models\WritingTask;
use App\Models\Level;
use App\Models\TestType;
use App\Models\Category;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;

class WritingImportController extends Controller
{
    protected $parser;

    public function __construct(WritingParserService $parser)
    {
        $this->parser = $parser;
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'test_file' => 'required|file',
                'level_id' => 'required|exists:levels,id',
                'test_type_id' => 'required|exists:test_types,id',
                'test_name' => 'nullable|string|max:255',
            ]);

            $file = $request->file('test_file');
            
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, ['pdf', 'docx', 'doc'])) {
                return redirect()->back()->with('error', 'Import failed: The test file field must be a file of type: pdf, docx.');
            }
            
            $text = $this->extractText($file);
            
            if (empty(trim($text))) {
                return redirect()->back()->with('error', 'Could not extract any text from the file. Please check if the file is empty or password protected.');
            }

            $parsedTasks = $this->parser->parseText($text);

            // Name fallback
            $testName = $request->test_name ?: 'Writing Mock Test - ' . date('d M Y H:i');

            // CREATE THE WRITING TEST
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

            return redirect()->route('admin.tests.index', ['category' => 'writing', 'test_type_id' => $writingTest->test_type_id, 'level_id' => $writingTest->level_id])
                ->with('success', "Writing Test '{$testName}' imported successfully!");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    protected function extractText($file)
    {
        $text = "";
        $extension = $file->getClientOriginalExtension();
        
        if ($extension === 'pdf') {
            $pdfParser = new PdfParser();
            $pdf = $pdfParser->parseFile($file->getRealPath());
            return $pdf->getText();
        } elseif ($extension === 'docx') {
            $phpWord = IOFactory::load($file->getRealPath());
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    $text .= $this->getRecursiveText($element) . "\n";
                }
            }
            return $text;
        }
        
        return $text;
    }

    protected function getRecursiveText($element)
    {
        $text = "";
        
        // Handle Tables (Crucial for messy Word files)
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
