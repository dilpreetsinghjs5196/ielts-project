<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ListeningTest;
use App\Models\ListeningPart;
use App\Models\ListeningQuestion;
use App\Models\Level;
use App\Models\TestType;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListeningTestController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = 'listening';
        $activeCategory = Category::where('slug', $categorySlug)->firstOrFail();
        
        $testTypeId = $request->query('test_type') ?: session('last_test_type_id');
        if ($request->query('test_type')) session(['last_test_type_id' => $testTypeId]);

        $levelId = $request->query('level');
        $testId = $request->query('test');

        $testTypes = TestType::all();
        $levels = $testTypeId ? Level::all() : collect();
        
        $tests = ($testTypeId && $levelId) 
            ? ListeningTest::where('test_type_id', $testTypeId)->where('level_id', $levelId)->get()->sortBy('name', SORT_NATURAL)->values()
            : collect();

        $parts = $testId ? ListeningPart::where('listening_test_id', $testId)->withCount('questions')->get() : collect();
        
        $noModuleLevels = [1, 2];

        return view('admin.listening_parts.index', compact(
            'parts', 
            'activeCategory', 
            'testTypes', 
            'levels', 
            'tests',
            'testTypeId',
            'levelId',
            'testId',
            'noModuleLevels'
        ));
    }

    public function showPart(ListeningPart $part)
    {
        $part->load('questions');
        $activeCategory = Category::where('slug', 'listening')->first();
        return view('admin.listening_parts.show', compact('part', 'activeCategory'));
    }

    public function createPart(Request $request)
    {
        $testId = $request->query('test_id');
        $test = ListeningTest::findOrFail($testId);
        return view('admin.listening_parts.create', compact('test'));
    }

    public function storePart(Request $request)
    {
        $request->validate([
            'listening_test_id' => 'required|exists:listening_tests,id',
            'part_number' => 'required|integer',
            'title' => 'required|string|max:255',
            'instruction' => 'nullable|string',
            'passage' => 'nullable|string',
        ]);

        $part = ListeningPart::create($request->all());

        return redirect()->route('admin.listening-parts.show', $part)->with('success', 'Part created successfully. Now add questions.');
    }

    public function createQuestion(Request $request)
    {
        $partId = $request->query('part_id');
        $part = ListeningPart::findOrFail($partId);
        return view('admin.listening_questions.create', compact('part'));
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'listening_part_id' => 'required|exists:listening_parts,id',
            'question_number' => 'required|string',
            'question_type' => 'required|string',
            'title' => 'required|string',
            'common_heading' => 'nullable|string',
            'content' => 'nullable|string',
            'correct_answer' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'marks' => 'required|integer',
        ]);

        $data = $request->only(['listening_part_id', 'question_number', 'question_type', 'title', 'common_heading', 'content', 'correct_answer', 'marks']);

        if ($request->hasFile('image')) {
            $data['image'] = $this->handleFileUpload($request->file('image'), 'listening_questions');
        }

        if ($request->has('options')) {
            $data['options'] = array_filter($request->options);
        }

        $question = ListeningQuestion::create($data);

        return redirect()->route('admin.listening-parts.show', $request->listening_part_id)->with('success', 'Question added successfully.');
    }

    public function create(Request $request)
    {
        $levels = Level::all();
        $categories = Category::all();
        $testTypes = TestType::all();
        
        $preselectedCategoryId = $request->get('category_id');
        $preselectedLevelId = $request->get('level_id');
        $preselectedTestTypeId = $request->get('test_type_id');

        return view('admin.listening_tests.create', compact(
            'levels', 
            'categories', 
            'testTypes', 
            'preselectedCategoryId',
            'preselectedLevelId',
            'preselectedTestTypeId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level_id' => 'required|exists:levels,id',
            'test_type_id' => 'required|exists:test_types,id',
            'status' => 'required|in:active,inactive',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg|max:102400',
        ]);

        $data = $request->except('audio_file');
        
        if ($request->hasFile('audio_file')) {
            $data['audio_file'] = $this->handleFileUpload($request->file('audio_file'), 'listening/audio');
        }

        $listeningTest = ListeningTest::create($data);

        return redirect()->route('admin.listening-tests.edit', $listeningTest->id)
            ->with('success', 'Listening Mock Test created successfully. You can now add parts.');
    }

    public function edit(ListeningTest $listeningTest)
    {
        $levels = Level::all();
        $testTypes = TestType::all();
        $listeningTest->load('parts.questions');
        return view('admin.listening_tests.edit', compact('listeningTest', 'levels', 'testTypes'));
    }

    public function update(Request $request, ListeningTest $listeningTest)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level_id' => 'required|exists:levels,id',
            'test_type_id' => 'required|exists:test_types,id',
            'status' => 'required|in:active,inactive',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg|max:102400', // Increased to 100MB
            'remove_audio' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'level_id', 'test_type_id', 'status']);

        if ($request->has('remove_audio')) {
            if ($listeningTest->audio_file) {
                $targetDir = is_dir(base_path('../public_html')) 
                    ? base_path('../public_html/storage') 
                    : public_path('storage');
                if (file_exists($targetDir . '/' . $listeningTest->audio_file)) {
                    unlink($targetDir . '/' . $listeningTest->audio_file);
                }
            }
            $data['audio_file'] = null;
        } elseif ($request->hasFile('audio_file')) {
            $data['audio_file'] = $this->handleFileUpload($request->file('audio_file'), 'listening/audio');
        }

        $listeningTest->update($data);

        return redirect()->route('admin.listening-tests.index', [
            'test_type' => $listeningTest->test_type_id,
            'level' => $listeningTest->level_id
        ])->with('success', 'Listening Test updated successfully.');
    }

    public function updatePart(Request $request, ListeningPart $part)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'instruction' => 'nullable|string',
            'passage' => 'nullable|string',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg|max:20480',
            'image' => 'nullable|image|max:5120',
            'remove_audio' => 'nullable|boolean',
            'remove_image' => 'nullable|boolean',
        ]);

        $data = $request->only(['title', 'instruction', 'passage']);

        if ($request->has('remove_audio')) {
            if ($part->audio_file) {
                $targetDir = is_dir(base_path('../public_html')) 
                    ? base_path('../public_html/storage') 
                    : public_path('storage');
                if (file_exists($targetDir . '/' . $part->audio_file)) {
                    unlink($targetDir . '/' . $part->audio_file);
                }
            }
            $data['audio_file'] = null;
        } elseif ($request->hasFile('audio_file')) {
            $data['audio_file'] = $this->handleFileUpload($request->file('audio_file'), 'listening/parts/audio');
        }

        if ($request->has('remove_image')) {
            if ($part->image) {
                $targetDir = is_dir(base_path('../public_html')) 
                    ? base_path('../public_html/storage') 
                    : public_path('storage');
                if (file_exists($targetDir . '/' . $part->image)) {
                    unlink($targetDir . '/' . $part->image);
                }
            }
            $data['image'] = null;
        } elseif ($request->hasFile('image')) {
            $data['image'] = $this->handleFileUpload($request->file('image'), 'listening_parts');
        }

        $part->update($data);

        return redirect()->back()->with('success', 'Part updated successfully.');
    }

    public function editQuestion(ListeningQuestion $question)
    {
        $question->load('part');
        return view('admin.listening_questions.edit', compact('question'));
    }

    public function updateQuestion(Request $request, ListeningQuestion $question)
    {
        $request->validate([
            'question_number' => 'required|string',
            'question_type' => 'required|string',
            'title' => 'nullable|string',
            'common_heading' => 'nullable|string',
            'content' => 'nullable|string',
            'correct_answer' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'marks' => 'required|integer',
            'remove_image' => 'nullable|boolean',
        ]);

        $data = $request->only(['question_number', 'question_type', 'title', 'common_heading', 'content', 'correct_answer', 'marks']);

        if (in_array($request->question_type, ['mcq', 'mcq_multi', 'fill_blanks']) && $request->has('options')) {
            $data['options'] = array_filter($request->options);
        } else {
            $data['options'] = null;
        }

        if ($request->has('remove_image')) {
            if ($question->image) {
                $targetDir = is_dir(base_path('../public_html')) 
                    ? base_path('../public_html/storage') 
                    : public_path('storage');
                if (file_exists($targetDir . '/' . $question->image)) {
                    unlink($targetDir . '/' . $question->image);
                }
            }
            $data['image'] = null;
        } elseif ($request->hasFile('image')) {
            $data['image'] = $this->handleFileUpload($request->file('image'), 'listening_questions');
        }

        if ($request->has('options')) {
            $data['options'] = array_filter($request->options);
        }

        $question->update($data);

        return redirect()->back()->with('success', 'Question updated successfully.');
    }

    protected function handleFileUpload($file, $subDir)
    {
        $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        
        // Detect environment (Local public vs Live public_html)
        $targetDir = is_dir(base_path('../public_html')) 
            ? base_path('../public_html/storage/' . $subDir) 
            : public_path('storage/' . $subDir);

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $file->move($targetDir, $filename);
        return $subDir . '/' . $filename;
    }

    public function destroy(ListeningTest $listeningTest)
    {
        $listeningTest->delete();
        return redirect()->route('admin.listening-tests.index')->with('success', 'Listening Test deleted successfully.');
    }

    public function destroyPart(ListeningPart $part)
    {
        $testId = $part->listening_test_id;
        $part->delete();
        return redirect()->route('admin.listening-tests.index', ['test' => $testId])->with('success', 'Part deleted successfully.');
    }

    public function destroyQuestion(ListeningQuestion $question)
    {
        $question->delete();
        return redirect()->back()->with('success', 'Question deleted successfully.');
    }
}
