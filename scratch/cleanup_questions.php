<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Question;
use App\Models\ListeningQuestion;
use App\Models\QuestionGroup;
use App\Models\ListeningPart;

function cleanSeparateQuestions($modelClass, $parentRelation, $numberField, $contentField) {
    echo "Cleaning up " . $modelClass . "...\n";
    
    $parents = ($modelClass == Question::class) ? QuestionGroup::with('questions')->get() : ListeningPart::with('questions')->get();
    
    foreach ($parents as $parent) {
        $questions = $parent->questions->sortBy('id');
        $toDelete = [];
        $updates = [];
        
        foreach ($questions as $q) {
            $num = $q->$numberField;
            $content = $q->$contentField;
            
            // If this question's content is just its own tag (e.g. "[q4]"), it's likely a redundant separate question
            if (trim($content) == "[q$num]" || trim($content) == "[$num]" || trim($content) == "[q $num]") {
                // Check if any OTHER question in the same group contains this tag in its body
                foreach ($questions as $otherQ) {
                    if ($q->id != $otherQ->id && str_contains($otherQ->$contentField, "[q$num]")) {
                        $toDelete[] = $q->id;
                        
                        // Update the primary question's number to a range if not already
                        if (!isset($updates[$otherQ->id])) {
                            $updates[$otherQ->id] = [$otherQ->$numberField, $num];
                        } else {
                            $updates[$otherQ->id][] = $num;
                        }
                        break;
                    }
                }
            }
        }
        
        foreach ($updates as $id => $nums) {
            $primary = $modelClass::find($id);
            sort($nums);
            $range = $nums[0] . '-' . end($nums);
            $primary->update([
                $numberField => $range,
                'marks' => count($nums)
            ]);
            echo "  Merged range $range for Question #$id\n";
        }
        
        if (!empty($toDelete)) {
            $modelClass::whereIn('id', $toDelete)->delete();
            echo "  Deleted " . count($toDelete) . " separate questions in Group/Part " . $parent->id . "\n";
        }
    }
}

cleanSeparateQuestions(Question::class, 'questions', 'question_number', 'content');
cleanSeparateQuestions(ListeningQuestion::class, 'questions', 'question_number', 'title');

echo "Cleanup complete!\n";
