<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IeltsParserService;
use App\Models\Test;
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

    public function __construct(IeltsParserService $parser)
    {
        $this->parser = $parser;
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
        $request->validate([
            'test_file' => 'required|file|mimes:pdf,docx',
            'category_id' => 'required|exists:categories,id',
            'level_id' => 'required|exists:levels,id',
            'test_type_id' => 'required|exists:test_types,id',
            'test_name' => 'required|string|max:255',
        ]);

        $file = $request->file('test_file');
        $text = $this->extractText($file);
        
        $answers = [];
        if ($request->hasFile('answer_file')) {
            $answerFile = $request->file('answer_file');
            $answerText = $this->extractText($answerFile);
            $answers = $this->parser->parseAnswers($answerText);
        }

        $parsedData = $this->parser->parseText($text);

        // CREATE THE TEST
        $test = Test::create([
            'name' => $request->test_name,
            'category_id' => $request->category_id,
            'level_id' => $request->level_id,
            'test_type_id' => $request->test_type_id,
            'status' => 'inactive',
        ]);

        foreach ($parsedData as $segmentData) {
            // Only create segment if it actually has questions
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
                'instruction' => $segmentData['sub_segments'][0]['header'] ?? null, // Just the main header
            ]);
            
            // Link questions...
            foreach ($segmentData['sub_segments'] as $sub) {
                foreach ($sub['questions'] as $q) {
                    Question::create([
                        'question_group_id' => $group->id,
                        'category_id' => $request->category_id,
                        'level_id' => $request->level_id,
                        'test_type_id' => $request->test_type_id,
                        'question_number' => $q['number'],
                        'question_type' => $q['type'],
                        'content' => $q['body'],
                        'title' => $sub['header'],
                        'options' => $q['options'],
                        'settings' => [
                            'instruction' => $sub['instructions']
                        ],
                        'correct_answer' => $answers[$q['number']] ?? null,
                        'status' => 'active',
                    ]);
                }
            }
        }

        return redirect()->route('admin.tests.index', ['category' => Category::find($request->category_id)->slug])
            ->with('success', 'Test imported successfully from document! Please review the questions.');
    }

    protected function extractText($file)
    {
        $text = "";
        if ($file->getClientOriginalExtension() == 'docx') {
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
        
        // Handle Tables
        if (get_class($element) === 'PhpOffice\PhpWord\Element\Table') {
            foreach ($element->getRows() as $row) {
                foreach ($row->getCells() as $cell) {
                    $text .= $this->getRecursiveText($cell) . " | "; // Cell separator
                }
                $text .= "\n"; // Row separator
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

        // Add spaces for specific spacing elements
        if (get_class($element) === 'PhpOffice\PhpWord\Element\TextBreak') {
            $text .= "\n";
        }
        if (get_class($element) === 'PhpOffice\PhpWord\Element\ListItem') {
            $text = "- " . $text . "\n";
        }

        return $text;
    }
}
