<?php

namespace App\Services;

class WritingParserService
{
    public function parseText($text)
    {
        $tasks = [];
        
        // 1. TRY FLEXIBLE SPLIT (Case insensitive, allows for extra spaces)
        $tasksData = preg_split('/WRITING TASK\s+(\d+)/i', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
        // Skip Boilerplate if present
        if (!empty($tasksData) && !is_numeric($tasksData[0])) {
            array_shift($tasksData);
        }

        for ($i = 0; $i < count($tasksData); $i += 2) {
            $taskNum = $tasksData[$i];
            $taskContent = $tasksData[$i+1] ?? '';
            
            $tasks[] = $this->extractTaskDetails($taskNum, $taskContent);
        }

        // 2. FALLBACK 1: If no specific Task 1/2 headers found, but there is text
        if (empty($tasks) && strlen(trim($text)) > 50) {
            if (stripos($text, 'Task 2') !== false) {
                // Split by "Task 2" keyword
                $bits = preg_split('/Task\s+2/i', $text);
                $tasks[] = $this->extractTaskDetails(1, $bits[0]);
                $tasks[] = $this->extractTaskDetails(2, $bits[1] ?? '');
            } else {
                // Last resort: If it's one big block, put 40% in Task 1, 60% in Task 2
                $len = strlen($text);
                $splitPoint = (int)($len * 0.4);
                $tasks[] = $this->extractTaskDetails(1, substr($text, 0, $splitPoint));
                $tasks[] = $this->extractTaskDetails(2, substr($text, $splitPoint));
            }
        }

        // 3. FALLBACK 2: If absolutely no text was extracted
        if (empty($tasks)) {
            $tasks[] = [
                'task_number' => 1,
                'title' => 'Writing Task 1',
                'instruction' => 'Enter Task 1 instructions here...',
                'question_text' => 'Enter Task 1 prompt here...'
            ];
            $tasks[] = [
                'task_number' => 2,
                'title' => 'Writing Task 2',
                'instruction' => 'Enter Task 2 instructions here...',
                'question_text' => 'Enter Task 2 prompt here...'
            ];
        }

        return $tasks;
    }

    protected function extractTaskDetails($num, $content)
    {
        $lines = explode("\n", trim($content));
        $instruction = "";
        $prompt = "";
        
        $reachedPrompt = false;
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (empty($trimmed)) continue;

            // Common IELTS Instruction markers
            $isInstructionMarker = preg_match('/You\s+should\s+spend/i', $trimmed) || 
                                   preg_match('/Write\s+about\s+the\s+following\s+topic/i', $trimmed) ||
                                   preg_match('/Summarise\s+the\s+information/i', $trimmed) ||
                                   preg_match('/Write\s+at\s+least\s+\d+\s+words/i', $trimmed);

            if ($isInstructionMarker) {
                $instruction .= $trimmed . " ";
                if (preg_match('/topic|information|below/i', $trimmed)) {
                    $reachedPrompt = true;
                }
            } else {
                // If we reach any "Normal" text after an instruction, it's the prompt
                if (strlen($instruction) > 20) {
                   $reachedPrompt = true;
                }

                if ($reachedPrompt) {
                    $prompt .= $line . "\n";
                } else {
                    $instruction .= $line . "\n";
                }
            }
        }

        // ABSOLUTE FALLBACK: If prompt is still empty, the last 70% of instruction is likely the prompt
        if (empty(trim($prompt)) && strlen(trim($instruction)) > 50) {
            $lines = explode("\n", trim($instruction));
            if (count($lines) > 2) {
                $instruction = $lines[0] . "\n" . $lines[1];
                unset($lines[0], $lines[1]);
                $prompt = implode("\n", $lines);
            }
        }

        return [
            'task_number' => $num,
            'title' => "Writing Task $num",
            'instruction' => trim($instruction),
            'question_text' => trim($prompt)
        ];
    }
}
