<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WritingTest;
use App\Models\Level;
use App\Models\TestType;
use App\Models\Category;
use Illuminate\Http\Request;

class WritingTestController extends Controller
{
    public function index()
    {
        $writingCategory = Category::where('slug', 'writing')->first();
        return redirect()->route('admin.tests.index', ['category' => $writingCategory->slug]);
    }

    public function create(Request $request)
    {
        $levels = Level::all();
        $categories = Category::all();
        $testTypes = TestType::all();
        
        $preselectedCategoryId = $request->get('category_id');
        $preselectedLevelId = $request->get('level_id');
        $preselectedTestTypeId = $request->get('test_type_id');

        return view('admin.writing_tests.create', compact(
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
        ]);

        $writingTest = WritingTest::create($request->all());

        $writingCategory = Category::where('slug', 'writing')->first();
        return redirect()->route('admin.tests.index', [
            'category' => $writingCategory ? $writingCategory->slug : 'writing',
            'test_type_id' => $writingTest->test_type_id,
            'level_id' => $writingTest->level_id
        ])->with('success', 'Writing Mock Test created successfully.');
    }

    public function edit(WritingTest $writingTest)
    {
        $test = $writingTest; // For consistent variable naming in views if needed
        $levels = Level::all();
        $testTypes = TestType::all();
        $categories = Category::all();
        return view('admin.writing_tests.edit', compact('writingTest', 'test', 'levels', 'testTypes', 'categories'));
    }

    public function update(Request $request, WritingTest $writingTest)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level_id' => 'required|exists:levels,id',
            'test_type_id' => 'required|exists:test_types,id',
            'status' => 'required|in:active,inactive',
        ]);

        $writingTest->update($request->all());

        $writingCategory = Category::where('slug', 'writing')->first();
        return redirect()->route('admin.tests.index', [
            'category' => $writingCategory->slug,
            'test_type_id' => $writingTest->test_type_id,
            'level_id' => $writingTest->level_id
        ])->with('success', 'Writing Mock Test updated successfully.');
    }

    public function destroy(WritingTest $writingTest)
    {
        $writingTest->delete();
        return redirect()->back()->with('success', 'Writing Test deleted successfully.');
    }
}
