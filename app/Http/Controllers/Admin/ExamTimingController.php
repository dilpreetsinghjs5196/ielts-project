<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ExamTiming;

class ExamTimingController extends Controller
{
    public function edit()
    {
        $timing = ExamTiming::firstOrCreate([], [
            'exam_time' => 60
        ]);

        return view('admin.exam-timing.edit', compact('timing'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'exam_time' => ['required', 'integer', 'min:1', 'max:1440'],
        ]);

        $timing = ExamTiming::firstOrCreate([], [
            'exam_time' => 60
        ]);
        $timing->update($validated);

        return redirect()->route('admin.exam-timing.edit')->with('success', 'Exam timing settings updated successfully.');
    }
}
