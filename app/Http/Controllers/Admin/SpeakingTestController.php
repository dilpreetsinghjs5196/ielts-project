<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpeakingTest;
use App\Models\Level;
use App\Models\TestType;
use App\Models\Category;
use Illuminate\Http\Request;

class SpeakingTestController extends Controller
{
    public function index()
    {
        $speakingCategory = Category::where('slug', 'speaking')->first();
        return redirect()->route('admin.tests.index', ['category' => $speakingCategory->slug]);
    }

    public function edit(SpeakingTest $speakingTest)
    {
        $test = $speakingTest;
        $levels = Level::all();
        $testTypes = TestType::all();
        $categories = Category::all();
        return view('admin.speaking_tests.edit', compact('speakingTest', 'test', 'levels', 'testTypes', 'categories'));
    }

    public function update(Request $request, SpeakingTest $speakingTest)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level_id' => 'required|exists:levels,id',
            'test_type_id' => 'required|exists:test_types,id',
            'status' => 'required|in:active,inactive',
        ]);

        $speakingTest->update($request->all());

        $speakingCategory = Category::where('slug', 'speaking')->first();
        return redirect()->route('admin.tests.index', [
            'category' => $speakingCategory->slug,
            'test_type_id' => $speakingTest->test_type_id,
            'level_id' => $speakingTest->level_id
        ])->with('success', 'Speaking Mock Test updated successfully.');
    }

    public function destroy(SpeakingTest $speakingTest)
    {
        $speakingTest->delete();
        return redirect()->back()->with('success', 'Speaking Test deleted successfully.');
    }
}
