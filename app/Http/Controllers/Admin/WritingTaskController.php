<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WritingTask;
use Illuminate\Http\Request;

class WritingTaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'writing_test_id' => 'required|exists:writing_tests,id'
        ]);

        $testId = $request->writing_test_id;
        $existingTasks = WritingTask::where('writing_test_id', $testId)->pluck('task_number')->toArray();

        if (count($existingTasks) >= 2) {
            return redirect()->back()->with('error', 'A writing test can have at most 2 tasks.');
        }

        // Determine missing task number (1 or 2)
        $taskNumber = 1;
        if (in_array(1, $existingTasks)) {
            $taskNumber = 2;
        }

        WritingTask::create([
            'writing_test_id' => $testId,
            'task_number' => $taskNumber,
            'title' => "Writing Task {$taskNumber}",
            'instruction' => "You should spend about " . ($taskNumber == 1 ? "20" : "40") . " minutes on this task.",
            'question_text' => "Enter Task {$taskNumber} prompt here...",
            'marks' => ($taskNumber == 1 ? 3 : 6)
        ]);

        return redirect()->back()->with('success', 'Task added successfully.');
    }

    public function edit(WritingTask $writingTask)
    {
        return view('admin.writing_tasks.edit', compact('writingTask'));
    }

    public function update(Request $request, $id)
    {
        try {
            $writingTask = WritingTask::findOrFail($id);
            
            // 1. UPDATE TEXT FIELDS DIRECTLY
            $writingTask->title = $request->title;
            $writingTask->instruction = $request->instruction;
            $writingTask->question_text = $request->task_prompt;
            $writingTask->sample_answer = $request->sample_answer;
            
            // Safety Net for Marks
            $writingTask->marks = $request->marks ?: ($writingTask->task_number == 1 ? 3 : 6);

            // 2. FORCE IMAGE SAVE
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Determine target directory (Local uses public, Live often uses public_html)
                $targetDir = is_dir(base_path('../public_html')) 
                    ? base_path('../public_html/storage/writing_tasks') 
                    : public_path('storage/writing_tasks');

                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $file->move($targetDir, $filename);
                $writingTask->image = 'writing_tasks/' . $filename;
            }

            // 3. SECURE SAVE
            $writingTask->save();

            return redirect()->back()->with('success', 'FORCE SAVED: Task and Image updated.');

        } catch (\Exception $e) {
            // IF IT FAILS, SHOW US THE EXACT PROBLEM
            dd("SAVE FAILED! Error: " . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $writingTask = WritingTask::findOrFail($id);
        $testId = $writingTask->writing_test_id;
        $writingTask->delete();
        return redirect()->back()->with('success', 'Task deleted.');
    }
}
