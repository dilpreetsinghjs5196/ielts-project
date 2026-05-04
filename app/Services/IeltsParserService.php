<?php

namespace App\Services;

class IeltsParserService
{
    public function parseText($text)
    {
        // Remove footer page numbers "Page X"
        $text = preg_replace('/Page\s+\d+/i', '', $text);
        
        $segments = [];
        $passageMarkers = [
            '/READING\s+PASSAGE\s+\d+/i', 
            '/\b(?:Section|Part)\s+(\d+)\b/i',
            '/TRANSCRIPT\s+FOR\s+(?:PART|SECTION)\s+(\d+)/i'
        ];
        
        $lines = explode("\n", $text);
        
        $currentPassageTitle = "Introduction";
        $currentContent = "";
        
        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            if (empty($trimmedLine)) continue;

            $isMarker = false;
            foreach ($passageMarkers as $pattern) {
                if (preg_match($pattern, $trimmedLine)) {
                    $isMarker = true;
                    break;
                }
            }

            if ($isMarker) {
                // Save previous segment if exists
                if (!empty(trim($currentContent))) {
                    $passageAndQuestions = $this->splitPassageAndQuestions($currentContent);
                    $segments[] = [
                        'title' => $currentPassageTitle,
                        'content' => $passageAndQuestions['passage'],
                        'sub_segments' => $this->parseSubSegments($passageAndQuestions['questions'])
                    ];
                }
                $currentPassageTitle = $trimmedLine;
                
                // If the marker line also contains "Questions X-Y", keep that part for the new segment's content
                if (preg_match('/Questions?\s+\d+\s*(?:[\-\–\—to\s+and\s+]*\s*\d+)?/i', $trimmedLine, $qMatch, PREG_OFFSET_CAPTURE)) {
                    $currentContent = substr($trimmedLine, $qMatch[0][1]) . "\n";
                } else {
                    $currentContent = "";
                }
            } else {
                $currentContent .= $line . "\n";
            }
        }

        // Final segment
        if (!empty(trim($currentContent))) {
            $passageAndQuestions = $this->splitPassageAndQuestions($currentContent);
            $segments[] = [
                'title' => $currentPassageTitle,
                'content' => $passageAndQuestions['passage'],
                'sub_segments' => $this->parseSubSegments($passageAndQuestions['questions'])
            ];
        }
        
        return $segments;
    }

    protected function splitPassageAndQuestions($text)
    {
        // For listening, the 'passage' is the transcript. Often it's BEFORE the questions.
        // We look for the first occurrence of "Questions X-Y"
        if (preg_match('/Questions\s+\d+/i', $text, $matches, PREG_OFFSET_CAPTURE)) {
            $offset = $matches[0][1];
            
            // Check if there's an "instructions" block like "You should spend..." 
            // We want to keep that with the questions, not the passage.
            $searchBefore = substr($text, 0, $offset);
            if (preg_match('/You\s+should\s+spend\s+.*?minutes\s+on\s+Questions\s+\d+/i', $searchBefore, $m, PREG_OFFSET_CAPTURE)) {
                $offset = $m[0][1];
            }

            return [
                'passage' => trim(substr($text, 0, $offset)),
                'questions' => trim(substr($text, $offset))
            ];
        }

        return [
            'passage' => trim($text),
            'questions' => ''
        ];
    }
    
    protected function parseSubSegments($text)
    {
        // Support all dash types: -, –, — and "to"
        // Standard IELTS labels: Part X, Section X, Questions X-Y, Questions X and Y
        preg_match_all('/(?:Questions?|Q\.?|Part\s?\d+|Section\s?\d+)\s*(\d+)\s*(?:(?:[\-\–\—]|to|and|&|\+)\s*(\d+))?/i', $text, $matches, PREG_OFFSET_CAPTURE);
        
        $subSegments = [];
        
        if (empty($matches[0])) {
            $questions = $this->extractQuestions($text);
            if (!empty($questions)) {
                $subSegments[] = [
                    'header' => 'General Questions',
                    'instructions' => 'Follow the instructions below.',
                    'questions' => $questions
                ];
            }
            return $subSegments;
        }
        
        for ($i = 0; $i < count($matches[0]); $i++) {
            $currentMatch = $matches[0][$i];
            $nextMatchOffset = isset($matches[0][$i+1]) ? $matches[0][$i+1][1] : strlen($text);
            
            $subText = substr($text, $currentMatch[1], $nextMatchOffset - $currentMatch[1]);
            
            $lines = explode("\n", trim($subText));
            $header = array_shift($lines);
            $instructions = "";
            $questionLines = [];
            
            $reachedQuestions = false;
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (empty($trimmed)) continue;

                // Numbered questions (1. 2. or just 1 2) or any line with a blank
                if (preg_match('/^(\d+)[\.\)\s]/', $trimmed) || strpos($trimmed, '___') !== false || strpos($trimmed, '[q') !== false || strpos($trimmed, '______') !== false) {
                    $reachedQuestions = true;
                }
                
                if (!$reachedQuestions) {
                    $instructions .= $line . "\n";
                } else {
                    $questionLines[] = $line;
                }
            }

            // Fallback: If we have a range but NO questions were detected yet
            if (!$reachedQuestions) {
                $tempLines = array_filter(array_map('trim', $lines));
                if (count($tempLines) > 0) {
                    $questionLines = $tempLines;
                }
            }

            $startNum = $matches[1][$i][0];
            $endNum = $matches[2][$i][0] ?? $startNum;
            
            // Extract questions
            $questions = $this->extractQuestions(implode("\n", $questionLines), $startNum, $endNum);
            
            // Detect global options for Matching tasks
            $globalOptions = $this->extractOptions($subText);
            
            // Improved Instruction-based type detection
            $isTFNG = preg_match('/TRUE\s*[\/\-]\s*FALSE/i', $instructions) || preg_match('/YES\s*[\/\-]\s*NO/i', $instructions);
            $isHeading = preg_match('/List\s*of\s*Headings/i', $instructions) || preg_match('/List\s*of\s*Headings/i', $subText);
            // Paragraph Matching: "Write the correct letter, A-G"
            $isParaMatch = preg_match('/Write\s+the\s+correct\s+letter/i', $instructions) && preg_match('/[A-G]\-[A-G]/i', $instructions);
            $isMultiMCQ = preg_match('/Choose\s+(?:TWO|THREE|FOUR|FIVE|2|3|4|5)\s+letters/i', $instructions);

            // If it's a multi-select MCQ but no questions were extracted (common for "Questions 14 and 15")
            // we should manually create a merged question for the range.
            if (empty($questions) && $isMultiMCQ && $startNum != $endNum) {
                $questions[] = [
                    'number' => $startNum . '-' . $endNum,
                    'body' => trim($instructions),
                    'type' => 'mcq_multi',
                    'options' => $this->extractOptions($subText),
                    'marks' => (int)$endNum - (int)$startNum + 1
                ];
            }

            // Assign types and global options
            foreach ($questions as &$q) {
                $hasBlank = (strpos($q['body'], '___') !== false || strpos($q['body'], '[q') !== false);

                if ($isMultiMCQ) {
                    $q['type'] = 'mcq_multi';
                    if (!empty($globalOptions)) $q['options'] = $globalOptions;
                } elseif ($isTFNG) {
                    $q['type'] = 'tfng';
                } elseif ($isHeading) {
                    $q['type'] = 'match_heading';
                    if (!empty($globalOptions)) $q['options'] = $globalOptions;
                } elseif ($isParaMatch && !$hasBlank) {
                    $q['type'] = 'mcq';
                    // Generate A-G options if none found
                    if (empty($globalOptions)) {
                        $q['options'] = array_combine(range('A', 'G'), range('A', 'G'));
                    } else {
                        $q['options'] = $globalOptions;
                    }
                } elseif (!$hasBlank && empty($q['options']) && count($globalOptions) >= 2) {
                    $q['options'] = $globalOptions;
                    $q['type'] = 'mcq';
                }
            }

            $subSegments[] = [
                'header' => $header,
                'instructions' => trim($instructions),
                'questions' => $questions
            ];
        }
        
        return $subSegments;
    }
    
    protected function extractQuestions($text, $startRange = null, $endRange = null)
    {
        // 1. Try to find explicitly numbered questions (Standard list: 1. 2. 3. or 1 2 3)
        // Improved pattern: catch numbers at start of line, after separator, or in table, or just mid-line if clearly a question
        $pattern = '/(?:^|\n| \| |\t|(?<=[a-z])\s+)\s*(\d+)[ \.\)\:\-]+\s*(.*?)(?=\s*(?:\n| \| |\t|\s{2,})\s*\d+[ \.\)\:\-]+|$)/s';
        preg_match_all($pattern, $text, $matches);
        
        $questions = [];
        $foundNumbers = [];
        if (!empty($matches[1])) {
            foreach ($matches[1] as $index => $number) {
                $qBody = trim($matches[2][$index]);
                $type = $this->detectType($qBody);
                $questions[] = [
                    'number' => $number,
                    'body' => $qBody,
                    'type' => $type,
                    'options' => ($type === 'mcq' || $type === 'mcq_multi' || $type === 'match_heading') ? $this->extractOptions($qBody) : []
                ];
                $foundNumbers[] = (int)$number;
            }
        } 
        
        // 2. Check for Summary Completion / Embedded Numbers (e.g. 32______, 4 ______)
        preg_match_all('/(?:^|[^0-9])(\d{1,2})[ \t\x{00A0}]*(?:_{2,}|[\. ]_{2,})/u', $text, $embeddedMatches, PREG_OFFSET_CAPTURE);
        
        if (!empty($embeddedMatches[1])) {
            $embeddedQuestions = [];
            
            // If multiple embedded questions are found in the same block, MERGE them into one Question record
            // as requested by the user ("i fill the ans not create separate")
            if (count($embeddedMatches[1]) > 1) {
                $nums = array_column($embeddedMatches[1], 0);
                sort($nums);
                $range = $nums[0] . '-' . end($nums);
                
                // Replace all instances of "12 ____" with "[q12]"
                $processedText = preg_replace('/(?:^|[^0-9])(\d{1,2})[ \t\x{00A0}]*(?:_{2,}|[\. ]_{2,})/u', ' [q$1] ', $text);
                
                $embeddedQuestions[] = [
                    'number' => $range,
                    'body' => trim($processedText),
                    'type' => 'fill_blanks',
                    'options' => [],
                    'marks' => count($nums)
                ];
            } else {
                $lastSplitOffset = 0;
                for ($idx = 0; $idx < count($embeddedMatches[1]); $idx++) {
                    $num = $embeddedMatches[1][$idx][0];
                    $matchOffset = $embeddedMatches[1][$idx][1];
                    $matchText = $embeddedMatches[0][$idx][0]; // Full match text like "4 ____"
                    $matchEnd = $matchOffset + strlen($matchText);
                    
                    // Find where this question should end (the split point)
                    if (isset($embeddedMatches[1][$idx + 1])) {
                        $nextMatchOffset = $embeddedMatches[1][$idx + 1][1];
                        
                        // Look for a newline between the END of current match and START of next match
                        $textAfterMatch = substr($text, $matchEnd, $nextMatchOffset - $matchEnd);
                        $lastNewline = strrpos($textAfterMatch, "\n");
                        
                        if ($lastNewline !== false) {
                            $endOffset = $matchEnd + $lastNewline;
                        } else {
                            $endOffset = $nextMatchOffset;
                        }
                    } else {
                        $endOffset = strlen($text);
                    }
                    
                    $segmentText = substr($text, $lastSplitOffset, $endOffset - $lastSplitOffset);
                    $lastSplitOffset = $endOffset;

                    // Replace only the current number's blank with [qX]
                    $processedBody = preg_replace('/(?:^|[^0-9])' . $num . '[ \t\x{00A0}]*(?:_{2,}|[\. ]_{2,})/u', ' [q'.$num.'] ', $segmentText);
                    
                    $embeddedQuestions[] = [
                        'number' => $num,
                        'body' => trim($processedBody),
                        'type' => 'fill_blanks',
                        'options' => [],
                        'marks' => 1
                    ];
                }
            }

            // Merge embedded questions into the main list if they weren't found by the first pattern
            foreach ($embeddedQuestions as $eq) {
                if (!in_array((int)$eq['number'], $foundNumbers)) {
                    $questions[] = $eq;
                    $foundNumbers[] = (int)$eq['number'];
                }
            }
        }

        // Sort questions by number
        usort($questions, function($a, $b) {
            return (int)$a['number'] <=> (int)$b['number'];
        });

        // 3. Fallback: If we have a range but some questions are still missing
        if ($startRange && $endRange) {
            $expectedCount = (int)$endRange - (int)$startRange + 1;
            if (count($questions) < $expectedCount) {
                $currentNum = (int)$startRange;
                $endNum = (int)$endRange;
                
                // If we found NO questions, use the line-by-line approach
                if (empty($questions)) {
                    $blocks = preg_split('/\n| \| /', $text);
                    foreach ($blocks as $block) {
                        $block = trim($block);
                        if (empty($block) || $currentNum > $endNum) continue;

                        $body = preg_replace('/^[ \t]*[\-\*\•\.\)]\s+/u', '', $block);
                        if (strpos($body, '___') === false && strpos($body, '[q') === false) {
                            $body = "______ " . $body;
                        }

                        $questions[] = [
                            'number' => (string)$currentNum,
                            'body' => $body,
                            'type' => 'fill_blanks',
                            'options' => []
                        ];
                        $currentNum++;
                    }
                } else {
                    // We found some, but not all. Fill missing numbers in the range with placeholders
                    $start = (int)$startRange;
                    $end = (int)$endRange;
                    
                    // If range is large (like 1-10) but we only found a few, fill the rest
                    for ($n = $start; $n <= $end; $n++) {
                        $exists = false;
                        foreach ($questions as $q) {
                            if ((int)$q['number'] === $n) {
                                $exists = true;
                                break;
                            }
                        }
                        
                        if (!$exists) {
                            $questions[] = [
                                'number' => (string)$n,
                                'body' => 'Question ' . $n . ' (Auto-placeholder)',
                                'type' => 'short_answer',
                                'options' => []
                            ];
                        }
                    }

                    // Sort again to maintain order
                    usort($questions, function($a, $b) {
                        return (int)$a['number'] <=> (int)$b['number'];
                    });
                }
            }
        }
        
        return $questions;
    }
    
    protected function detectType($body)
    {
        if (preg_match('/[A-D]\.\s/', $body)) return 'mcq';
        if (preg_match('/TRUE\s*\/\s*FALSE\s*\/\s*NOT\s*GIVEN/i', $body) || preg_match('/YES\s*\/\s*NO\s*\/\s*NOT\s*GIVEN/i', $body)) return 'tfng';
        if (strpos($body, '______') !== false) return 'fill_blanks';
        if (preg_match('/List\s*of\s*Headings/i', $body)) return 'match_heading';
        return 'short_answer';
    }
    
    public function extractOptions($body)
    {
        // 1. Try alphabet A., B., C...
        // Support table separators " | " and flexible separators, and mid-line options
        $patternAlpha = '/(?:^|\n| \| |\t|(?<=[a-z])\s{2,})\s*([A-G])[\.\)\s]+\s*(.*?)(?=\s*(?:\n| \| |\t|\s{2,})\s*[A-G][\.\)\s]+|\s*\d+[ \.\)\:\-]+|$)/s';
        preg_match_all($patternAlpha, $body, $matches);
        
        if (!empty($matches[1])) {
            return array_combine($matches[1], array_map('trim', $matches[2]));
        }

        // 2. Try Roman numerals i., ii., iii... (for Headings)
        $patternRoman = '/(?:^|\n| \| |\t|(?<=[a-z])\s{2,})\s*(v?i{0,3}|iv)[\.\)\s]+\s*(.*?)(?=\s*(?:\n| \| |\t|\s{2,})\s*(?:v?i{1,3}|iv|v)[\.\)\s]+|\s*\d+[ \.\)\:\-]+|$)/s';
        preg_match_all($patternRoman, $body, $matches);
        
        if (!empty($matches[1])) {
            return array_combine($matches[1], array_map('trim', $matches[2]));
        }

        return [];
    }

    public function parseAnswers($text)
    {
        $answers = [];
        
        // 1. Try line-by-line parsing (standard list)
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Pattern: optional separator, then number, then separator, then answer
            // Handles: "1. Answer", "1) Answer", " | 1 Answer", "  1: Answer"
            if (preg_match('/(?:^|\||\t)\s*(\d+)[\.\)\s:]+\s*([^\n|]+)/i', $line, $matches)) {
                $number = $matches[1];
                $answer = trim($matches[2]);
                if (!empty($answer)) {
                    $answers[$number] = $answer;
                }
            }
        }

        // 2. Fallback: Full text search if few answers found (handles multi-column or wrap-around)
        if (count($answers) < 5) {
            // Pattern: look for "1. Answer" anywhere in text
            preg_match_all('/(?:\b|(?<=\|))\s*(\d{1,2})[\.\)\s:]+\s*([^\n|]{1,50})(?=\s*(?:\b\d{1,2}[\.\)\s:]+|$))/i', $text, $matches);
            
            if (!empty($matches[1])) {
                foreach ($matches[1] as $idx => $number) {
                    $answer = trim($matches[2][$idx]);
                    if (!empty($answer) && !isset($answers[$number])) {
                        $answers[$number] = $answer;
                    }
                }
            }
        }
        
        return $answers;
    }
}
