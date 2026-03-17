<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Category;
use App\Models\TestType;
use App\Models\Level;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categorySlug = $request->query('category', 'listening');
        $testTypeSlug = $request->query('type', 'academic');

        $activeCategory = Category::where('slug', $categorySlug)->firstOrFail();
        $activeTestType = TestType::where('slug', $testTypeSlug)->first();

        $query = Question::with(['category', 'testType', 'level'])
            ->where('category_id', $activeCategory->id);

        if ($activeTestType) {
            $query->where('test_type_id', $activeTestType->id);
        }

        $questions = $query->latest()->get();
        $testTypes = TestType::all();
        $categories = Category::all();

        return view('admin.questions.index', compact('questions', 'activeCategory', 'activeTestType', 'testTypes', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = Category::all();
        $testTypes = TestType::all();
        $levels = Level::all();
        
        $selectedGroup = null;
        if ($request->has('group_id')) {
            $selectedGroup = \App\Models\QuestionGroup::find($request->group_id);
        }

        return view('admin.questions.create', compact('categories', 'testTypes', 'levels', 'selectedGroup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required_without:question_group_id|exists:categories,id',
            'test_type_id' => 'required_without:question_group_id|exists:test_types,id',
            'level_id' => 'required_without:question_group_id|exists:levels,id',
            'question_type' => 'required|string',
            'content' => 'required|string',
            'correct_answer' => 'required|string',
            'marks' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'passage' => 'nullable|string',
            'question_group_id' => 'nullable|exists:question_groups,id',
        ]);

        $data = $request->all();
        
        // Handle Audio Upload
        if ($request->hasFile('audio_file')) {
            $data['audio_file'] = $request->file('audio_file')->store('questions/audio', 'public');
        }

        // Handle Image Attachment
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('questions/attachments', 'public');
        }

        // Handle options if it's an MCQ
        if ($request->question_type == 'mcq' && $request->has('options')) {
            $data['options'] = array_filter($request->options);
        }

        $question = Question::create($data);

        if ($request->filled('question_group_id')) {
            return redirect()->route('admin.question-groups.show', $request->question_group_id)
                ->with('success', 'Question added to segment successfully.');
        }

        return redirect()->route('admin.questions.index', ['category' => Category::find($request->category_id)->slug])
            ->with('success', 'Question added to bank successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $categories = Category::all();
        $testTypes = TestType::all();
        $levels = Level::all();
        return view('admin.questions.edit', compact('question', 'categories', 'testTypes', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'test_type_id' => 'required|exists:test_types,id',
            'level_id' => 'required|exists:levels,id',
            'question_type' => 'required|string',
            'content' => 'required|string',
            'correct_answer' => 'required|string',
            'marks' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'passage' => 'nullable|string',
        ]);

        $data = $request->all();
        
        // Handle Audio Upload
        if ($request->hasFile('audio_file')) {
            $data['audio_file'] = $request->file('audio_file')->store('questions/audio', 'public');
        }

        // Handle Image Attachment
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('questions/attachments', 'public');
        }

        if ($request->question_type == 'mcq' && $request->has('options')) {
            $data['options'] = array_filter($request->options);
        } else {
            $data['options'] = null;
        }

        $question->update($data);

        return redirect()->route('admin.questions.index', ['category' => $question->category->slug])
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('admin.questions.index')->with('success', 'Question deleted successfully.');
    }
}
