<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Category;
use App\Models\Level;
use App\Models\TestType;
use App\Models\ModuleSet;
use App\Models\WritingTest;
use App\Models\SpeakingTest;
use App\Models\ListeningTest;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $selectedCategory = null;
        $selectedTestType = null;
        $testTypeIdFromRequest = $request->get('test_type_id');
        
        if ($testTypeIdFromRequest) {
            session(['last_test_type_id' => $testTypeIdFromRequest]);
        }
        
        $effectiveTestTypeId = $testTypeIdFromRequest ?: session('last_test_type_id');
        $selectedModuleSet = null;
        $selectedLevel = null;
        $testTypes = TestType::all();
        $noModuleLevels = [1, 2]; // Level 1&2 and Level 3 (IDs found via tinker)
        
        if ($request->has('category')) {
            $selectedCategory = Category::where('slug', $request->get('category'))->firstOrFail();
            
            // Step 4: Specific Module Set Selected - Show Tests List
            if ($request->has('module_set_id')) {
                $selectedModuleSet = ModuleSet::with(['tests' => function($q) {
                    $q->with(['testType'])->latest();
                }, 'level', 'testType', 'category'])->findOrFail($request->get('module_set_id'));
                
                $selectedTestType = $selectedModuleSet->testType;
                $levels = collect();
            } 
            // Step 4: Specific Level Selected (for levels without modules)
            elseif ($request->has('level_id') && in_array($request->get('level_id'), $noModuleLevels)) {
                $selectedLevel = Level::with(['tests' => function($q) use ($selectedCategory, $effectiveTestTypeId) {
                    $q->where('category_id', $selectedCategory->id)
                      ->where('test_type_id', $effectiveTestTypeId)
                      ->with(['testType'])->latest();
                }])->findOrFail($request->get('level_id'));
                
                $selectedTestType = TestType::findOrFail($effectiveTestTypeId);
                $levels = collect();
            }
            // Step 3: Category and Test Type Selected - Show Portfolio Swipers
            elseif ($effectiveTestTypeId) {
                $selectedTestType = TestType::findOrFail($effectiveTestTypeId);
                
                $levels = Level::with(['moduleSets' => function($query) use ($selectedCategory, $selectedTestType) {
                    $query->where('category_id', $selectedCategory->id)
                          ->where('test_type_id', $selectedTestType->id)
                          ->withCount('tests')
                          ->with(['testType', 'category']);
                }])->get();
            }
            // Step 2: Only Category Selected - Show Test Type Selection Cards
            else {
                $levels = collect();
            }
        } else {
            $levels = collect();
        }

        $categories = Category::withCount('tests')->get();
        $writingCategory = Category::where('slug', 'writing')->first();
        $speakingCategory = Category::where('slug', 'speaking')->first();
        $listeningCategory = Category::where('slug', 'listening')->first();
        
        foreach ($categories as $cat) {
            if ($writingCategory && $cat->id === $writingCategory->id) {
                $cat->tests_count = $cat->tests_count + \App\Models\WritingTest::count();
            }
            if ($speakingCategory && $cat->id === $speakingCategory->id) {
                $cat->tests_count = $cat->tests_count + \App\Models\SpeakingTest::count();
            }
            if ($listeningCategory && $cat->id === $listeningCategory->id) {
                $cat->tests_count = $cat->tests_count + \App\Models\ListeningTest::count();
            }
        }
        
        // Handle Writing Tests collection for the view
        $writingTests = collect();
        if ($selectedCategory && $selectedCategory->slug === 'writing') {
            if ($selectedLevel) {
                $writingTests = WritingTest::where('level_id', $selectedLevel->id)
                    ->where('test_type_id', $effectiveTestTypeId)
                    ->withCount('tasks')
                    ->latest()
                    ->get();
            } elseif ($selectedModuleSet) {
                // If writing tests ever use module sets
                $writingTests = WritingTest::where('test_type_id', $selectedModuleSet->test_type_id)
                    ->withCount('tasks')
                    ->latest()
                    ->get();
            }
        }

        
        // Handle Speaking Tests collection for the view
        $speakingTests = collect();
        if ($selectedCategory && $selectedCategory->slug === 'speaking') {
            if ($selectedLevel) {
                $speakingTests = SpeakingTest::where('level_id', $selectedLevel->id)
                    ->where('test_type_id', $effectiveTestTypeId)
                    ->withCount('parts')
                    ->latest()
                    ->get();
            }
        }
        
        // Handle Listening Tests collection for the view
        $listeningTests = collect();
        if ($selectedCategory && $selectedCategory->slug === 'listening') {
            if ($selectedLevel) {
                $listeningTests = ListeningTest::where('level_id', $selectedLevel->id)
                    ->where('test_type_id', $effectiveTestTypeId)
                    ->withCount('parts')
                    ->latest()
                    ->get();
            }
        }
        
        return view('admin.tests.index', compact('levels', 'categories', 'selectedCategory', 'selectedTestType', 'selectedModuleSet', 'selectedLevel', 'testTypes', 'noModuleLevels', 'writingTests', 'speakingTests', 'listeningTests'));
    }

    public function create(Request $request)
    {
        $levels = Level::all();
        $categories = Category::all();
        $testTypes = TestType::all();
        $moduleSets = ModuleSet::all();
        
        $preselectedCategoryId = $request->get('category_id');
        $preselectedLevelId = $request->get('level_id');
        $preselectedModuleSetId = $request->get('module_set_id');
        $preselectedTestTypeId = $request->get('test_type_id');

        return view('admin.tests.create', compact(
            'levels', 
            'categories', 
            'testTypes', 
            'moduleSets',
            'preselectedCategoryId',
            'preselectedLevelId',
            'preselectedModuleSetId',
            'preselectedTestTypeId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'module_set_id' => 'nullable|exists:module_sets,id',
            'level_id' => 'required|exists:levels,id',
            'category_id' => 'required|exists:categories,id',
            'test_type_id' => 'required|exists:test_types,id',
            'status' => 'required|in:active,inactive',
        ]);

        $test = Test::create($request->all());

        if ($test->module_set_id) {
            $moduleSet = ModuleSet::with('category')->findOrFail($test->module_set_id);
            return redirect()->route('admin.tests.index', [
                'category' => $moduleSet->category->slug,
                'test_type_id' => $moduleSet->test_type_id,
                'module_set_id' => $moduleSet->id
            ])->with('success', 'Mock Test created successfully.');
        } else {
            $category = Category::findOrFail($test->category_id);
            return redirect()->route('admin.tests.index', [
                'category' => $category->slug,
                'test_type_id' => $test->test_type_id,
                'level_id' => $test->level_id
            ])->with('success', 'Mock Test created successfully.');
        }
    }

    public function edit(Test $test)
    {
        $levels = Level::all();
        $categories = Category::all();
        $testTypes = TestType::all();
        $moduleSets = ModuleSet::with(['category', 'testType', 'level'])->get();
        return view('admin.tests.edit', compact('test', 'levels', 'categories', 'testTypes', 'moduleSets'));
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'module_set_id' => 'nullable|exists:module_sets,id',
            'level_id' => 'required|exists:levels,id',
            'category_id' => 'required|exists:categories,id',
            'test_type_id' => 'required|exists:test_types,id',
            'status' => 'required|in:active,inactive',
        ]);

        $test->update($request->all());

        if ($test->module_set_id) {
            $moduleSet = ModuleSet::with('category')->findOrFail($test->module_set_id);
            return redirect()->route('admin.tests.index', [
                'category' => $moduleSet->category->slug,
                'test_type_id' => $moduleSet->test_type_id,
                'module_set_id' => $moduleSet->id
            ])->with('success', 'Mock Test updated successfully.');
        } else {
            $category = Category::findOrFail($test->category_id);
            return redirect()->route('admin.tests.index', [
                'category' => $category->slug,
                'test_type_id' => $test->test_type_id,
                'level_id' => $test->level_id
            ])->with('success', 'Mock Test updated successfully.');
        }
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->back()->with('success', 'Test deleted successfully.');
    }
}
