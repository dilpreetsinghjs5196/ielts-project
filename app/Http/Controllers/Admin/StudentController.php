<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::latest()->get();
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:255|unique:students',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:students',
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string',
            'status'     => 'required|in:active,inactive',
            'password'   => 'required|string|min:8|confirmed',
        ]);

        // In Laravel 11 with 'hashed' cast, model handles hashing automatically
        // $validated['password'] = Hash::make($validated['password']);

        Student::create($validated);

        return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:255|unique:students,student_id,' . $student->id,
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:students,email,' . $student->id,
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string',
            'status'     => 'required|in:active,inactive',
            'password'   => 'nullable|string|min:8|confirmed',
        ]);

        // Only update password if it was provided
        // Only update password if it was provided. Hashing is handled by the model cast.
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $student->update($validated);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }
}
