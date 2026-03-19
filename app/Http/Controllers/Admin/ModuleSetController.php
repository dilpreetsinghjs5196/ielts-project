<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModuleSet;
use App\Models\Category;
use App\Models\Level;
use App\Models\TestType;
use Illuminate\Http\Request;

class ModuleSetController extends Controller
{
    public function index(Request $request)
    {
        $selectedCategory = null;
        $selectedTestType = null;
        $testTypes = TestType::all();
        
        if ($request->has('category')) {
            $selectedCategory = Category::where('slug', $request->get('category'))->firstOrFail();
            
            if ($request->has('test_type_id')) {
                $selectedTestType = TestType::findOrFail($request->get('test_type_id'));
                
                $levels = Level::with(['moduleSets' => function($query) use ($selectedCategory, $selectedTestType) {
                    $query->where('category_id', $selectedCategory->id)
                          ->where('test_type_id', $selectedTestType->id)
                          ->with(['category', 'testType', 'tests'])
                          ->latest();
                }])->get()->filter(function($l) {
                    return $l->moduleSets->count() > 0;
                });
            } else {
                $levels = collect();
            }
        } else {
            $levels = collect();
        }

        $categories = Category::withCount('moduleSets')->get();
        
        return view('admin.module_sets.index', compact('levels', 'categories', 'selectedCategory', 'selectedTestType', 'testTypes'));
    }

    public function create(Request $request)
    {
        $levels = Level::all();
        $categories = Category::all();
        $testTypes = TestType::all();
        $preselectedCategoryId = $request->get('category_id');
        $preselectedTestTypeId = $request->get('test_type_id');
        
        return view('admin.module_sets.create', compact(
            'levels', 
            'categories', 
            'testTypes', 
            'preselectedCategoryId',
            'preselectedTestTypeId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level_id' => 'required|exists:levels,id',
            'category_id' => 'required|exists:categories,id',
            'test_type_id' => 'required|exists:test_types,id',
            'status' => 'required|in:active,inactive',
        ]);

        $set = ModuleSet::create($request->all());
        $category = Category::find($request->category_id);

        return redirect()->route('admin.module-sets.index', ['category' => $category->slug])->with('success', 'Module Set created successfully.');
    }

    public function edit(ModuleSet $moduleSet)
    {
        $levels = Level::all();
        $categories = Category::all();
        $testTypes = TestType::all();
        return view('admin.module_sets.edit', compact('moduleSet', 'levels', 'categories', 'testTypes'));
    }

    public function update(Request $request, ModuleSet $moduleSet)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level_id' => 'required|exists:levels,id',
            'category_id' => 'required|exists:categories,id',
            'test_type_id' => 'required|exists:test_types,id',
            'status' => 'required|in:active,inactive',
        ]);

        $moduleSet->update($request->all());

        return redirect()->route('admin.module-sets.index', ['category' => $moduleSet->category->slug])->with('success', 'Module Set updated successfully.');
    }

    public function destroy(ModuleSet $moduleSet)
    {
        $moduleSet->delete();
        return redirect()->route('admin.module-sets.index')->with('success', 'Module Set deleted successfully.');
    }
}
