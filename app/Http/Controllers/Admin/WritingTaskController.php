<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WritingTask;
use Illuminate\Http\Request;

class WritingTaskController extends Controller
{
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
                
                // Ensure directory exists
                $targetDir = public_path('storage/writing_tasks');
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
