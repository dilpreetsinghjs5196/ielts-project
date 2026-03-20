<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionGroup;
use App\Models\Category;
use App\Models\TestType;
use App\Models\Level;
use App\Models\Test;
use Illuminate\Http\Request;

class QuestionGroupController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('category', 'listening');
        $activeCategory = Category::where('slug', $categorySlug)->firstOrFail();
        
        $testTypeId = $request->query('test_type');
        $levelId = $request->query('level');
        $moduleSetId = $request->query('module_set');
        $testId = $request->query('test');

        // Get options for each step
        $testTypes = TestType::all();
        
        $levels = $testTypeId 
            ? Level::all() 
            : collect();

        $moduleSets = ($testTypeId && $levelId)
            ? \App\Models\ModuleSet::where('category_id', $activeCategory->id)
                ->where('test_type_id', $testTypeId)
                ->where('level_id', $levelId)
                ->get()
            : collect();

        $tests = ($moduleSetId)
            ? Test::where('module_set_id', $moduleSetId)->get()
            : collect();

        // Final groups list
        $query = QuestionGroup::with(['category', 'testType', 'level', 'questions'])
            ->where('category_id', $activeCategory->id);

        if ($testTypeId) $query->where('test_type_id', $testTypeId);
        if ($levelId) $query->where('level_id', $levelId);
        if ($testId) $query->where('test_id', $testId);

        $groups = ($testId) ? $query->latest()->get() : collect();
            
        return view('admin.question_groups.index', compact(
            'groups', 
            'activeCategory', 
            'testTypes', 
            'levels', 
            'moduleSets', 
            'tests',
            'testTypeId',
            'levelId',
            'moduleSetId',
            'testId'
        ));
    }


    public function create()
    {
        $categories = Category::all();
        $testTypes = TestType::all();
        $levels = Level::all();
        $tests = Test::with(['level', 'category', 'testType'])->get();
        return view('admin.question_groups.create', compact('categories', 'testTypes', 'levels', 'tests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'test_type_id' => 'required|exists:test_types,id',
            'level_id' => 'required|exists:levels,id',
            'test_id' => 'nullable|exists:tests,id',
            'title' => 'required|string|max:255',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('audio_file')) {
            $data['audio_file'] = $request->file('audio_file')->store('groups/audio', 'public');
        }

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('groups/attachments', 'public');
        }

        $group = QuestionGroup::create($data);

        return redirect()->route('admin.question-groups.show', $group)->with('success', 'Question Group (Segment) created successfully. Now add questions to it.');
    }

    public function show(QuestionGroup $questionGroup)
    {
        $questionGroup->load('questions');
        $categories = Category::all();
        $testTypes = TestType::all();
        $levels = Level::all();
        $tests = Test::all();
        return view('admin.question_groups.show', compact('questionGroup', 'categories', 'testTypes', 'levels', 'tests'));
    }

    public function edit(QuestionGroup $questionGroup)
    {
        $categories = Category::all();
        $testTypes = TestType::all();
        $levels = Level::all();
        $tests = Test::all();
        return view('admin.question_groups.edit', compact('questionGroup', 'categories', 'testTypes', 'levels', 'tests'));
    }

    public function update(Request $request, QuestionGroup $questionGroup)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'test_type_id' => 'required|exists:test_types,id',
            'level_id' => 'required|exists:levels,id',
            'test_id' => 'nullable|exists:tests,id',
        ]);

        $data = $request->except(['audio_file', 'attachment']);

        if ($request->hasFile('audio_file')) {
            $data['audio_file'] = $request->file('audio_file')->store('groups/audio', 'public');
        }

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('groups/attachments', 'public');
        }

        $questionGroup->update($data);

        return redirect()->route('admin.question-groups.index', ['category' => $questionGroup->category->slug])
            ->with('success', 'Segment updated successfully.');
    }

    public function destroy(QuestionGroup $questionGroup)
    {
        $slug = $questionGroup->category->slug;
        $questionGroup->delete();
        return redirect()->route('admin.question-groups.index', ['category' => $slug])
            ->with('success', 'Segment deleted successfully.');
    }
}
