<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testTypes = TestType::all();
        return view('admin.test_types.index', compact('testTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.test_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:test_types',
            'description' => 'nullable|string',
        ]);

        TestType::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.test-types.index')->with('success', 'Test Type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TestType $testType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TestType $testType)
    {
        return view('admin.test_types.edit', compact('testType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TestType $testType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:test_types,name,' . $testType->id,
            'description' => 'nullable|string',
        ]);

        $testType->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.test-types.index')->with('success', 'Test Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TestType $testType)
    {
        $testType->delete();
        return redirect()->route('admin.test-types.index')->with('success', 'Test Type deleted successfully.');
    }
}
