<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $student = auth('student')->user();
        return view('student.profile-edit', compact('student'));
    }

    public function update(Request $request)
    {
        $student = auth('student')->user();

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('students')->ignore($student->id)],
            'phone'    => ['nullable', 'string', 'max:20'],
            'address'  => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $student->name    = $validated['name'];
        $student->email   = $validated['email'];
        $student->phone   = $validated['phone'] ?? $student->phone;
        $student->address = $validated['address'] ?? $student->address;

        if (!empty($validated['password'])) {
            $student->password = $validated['password'];
        }

        $student->save();

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully!');
    }
}
