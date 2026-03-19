<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Category;
use App\Models\Level;
use App\Models\TestType;
use App\Models\ModuleSet;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $selectedCategory = null;
        $selectedTestType = null;
        $selectedModuleSet = null;
        $testTypes = TestType::all();
        
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
            // Step 3: Category and Test Type Selected - Show Portfolio Swipers
            elseif ($request->has('test_type_id')) {
                $selectedTestType = TestType::findOrFail($request->get('test_type_id'));
                
                $levels = Level::with(['moduleSets' => function($query) use ($selectedCategory, $selectedTestType) {
                    $query->where('category_id', $selectedCategory->id)
                          ->where('test_type_id', $selectedTestType->id)
                          ->withCount('tests')
                          ->with(['testType', 'category']);
                }])->get()->filter(function($l) {
                    return $l->moduleSets->count() > 0;
                });
            }
            // Step 2: Only Category Selected - Show Test Type Selection Cards
            else {
                $levels = collect();
            }
        } else {
            $levels = collect();
        }

        $categories = Category::withCount('tests')->get();
        
        return view('admin.tests.index', compact('levels', 'categories', 'selectedCategory', 'selectedTestType', 'selectedModuleSet', 'testTypes'));
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

        return view('admin.tests.create', compact(
            'levels', 
            'categories', 
            'testTypes', 
            'moduleSets',
            'preselectedCategoryId',
            'preselectedLevelId',
            'preselectedModuleSetId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'module_set_id' => 'required|exists:module_sets,id',
            'level_id' => 'required|exists:levels,id',
            'category_id' => 'required|exists:categories,id',
            'test_type_id' => 'required|exists:test_types,id',
            'status' => 'required|in:active,inactive',
        ]);

        $test = Test::create($request->all());

        $moduleSet = ModuleSet::with('category')->findOrFail($test->module_set_id);

        return redirect()->route('admin.tests.index', [
            'category' => $moduleSet->category->slug,
            'test_type_id' => $moduleSet->test_type_id,
            'module_set_id' => $moduleSet->id
        ])->with('success', 'Mock Test created successfully.');
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
            'module_set_id' => 'required|exists:module_sets,id',
            'level_id' => 'required|exists:levels,id',
            'category_id' => 'required|exists:categories,id',
            'test_type_id' => 'required|exists:test_types,id',
            'status' => 'required|in:active,inactive',
        ]);

        $test->update($request->all());

        $moduleSet = ModuleSet::with('category')->findOrFail($test->module_set_id);

        return redirect()->route('admin.tests.index', [
            'category' => $moduleSet->category->slug,
            'test_type_id' => $moduleSet->test_type_id,
            'module_set_id' => $moduleSet->id
        ])->with('success', 'Mock Test updated successfully.');
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('admin.tests.index')->with('success', 'Test deleted successfully.');
    }
}
