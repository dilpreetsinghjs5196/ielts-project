<?php

namespace App\Services;

class IeltsParserService
{
    public function parseText($text)
    {
        // Remove footer page numbers "Page X"
        $text = preg_replace('/Page\s+\d+/i', '', $text);
        
        $segments = [];
        $passageMarkers = ['READING PASSAGE 1', 'READING PASSAGE 2', 'READING PASSAGE 3', 'Section 1', 'Section 2', 'Section 3', 'Part 1', 'Part 2', 'Part 3'];
        
        $lines = explode("\n", $text);
        
        $currentPassageTitle = "Introduction";
        $currentContent = "";
        
        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            if (empty($trimmedLine)) continue;

            $isMarker = false;
            foreach ($passageMarkers as $marker) {
                if (strcasecmp($trimmedLine, $marker) === 0) {
                    $isMarker = true;
                    break;
                }
            }

            if ($isMarker) {
                // Save previous segment if exists
                if (!empty($currentContent)) {
                    $passageAndQuestions = $this->splitPassageAndQuestions($currentContent);
                    $segments[] = [
                        'title' => $currentPassageTitle,
                        'content' => $passageAndQuestions['passage'],
                        'sub_segments' => $this->parseSubSegments($passageAndQuestions['questions'])
                    ];
                }
                $currentPassageTitle = $trimmedLine;
                $currentContent = "";
            } else {
                $currentContent .= $line . "\n";
            }
        }

        // Final segment
        if (!empty($currentContent)) {
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
        // 1. Identify the 'Actual' question start. 
        // We skip the boilerplate "You should spend about 20 minutes on Questions 1-13..."
        $searchOffset = 0;
        if (preg_match('/You\s+should\s+spend\s+.*?minutes\s+on\s+Questions\s+\d+/i', $text, $m, PREG_OFFSET_CAPTURE)) {
            $searchOffset = $m[0][1] + strlen($m[0][0]);
        }

        // 2. Find the first REAL "Questions X-Y" header after the boilerplate
        if (preg_match('/Questions\s+\d+/i', $text, $matches, PREG_OFFSET_CAPTURE, $searchOffset)) {
            $offset = $matches[0][1];
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
        preg_match_all('/Questions\s+(\d+)\s*[\-\–\—to]+\s*(\d+)/i', $text, $matches, PREG_OFFSET_CAPTURE);
        
        $subSegments = [];
        
        if (empty($matches[0])) {
            $subSegments[] = [
                'header' => 'General Questions',
                'instructions' => 'Follow the instructions below.',
                'questions' => $this->extractQuestions($text)
            ];
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
                // Numbered questions (1. 2.) or any line with a blank
                if (preg_match('/(\d+)[\.\)\s]\s+/', $line) || strpos($line, '___') !== false) {
                    $reachedQuestions = true;
                }
                
                if (!$reachedQuestions) {
                    $instructions .= $line . "\n";
                } else {
                    $questionLines[] = $line;
                }
            }

            // Fallback: If we have a range but NO questions were detected yet,
            // then everything except the first 2-3 lines of instructions must be questions.
            if (!$reachedQuestions && !empty($matches[1][$i][0])) {
                $tempLines = array_filter(array_map('trim', $lines));
                if (count($tempLines) > 1) {
                    // Keep first line as instruction header, rest as questions
                    $instructions = array_shift($tempLines) . "\n";
                    $questionLines = $tempLines;
                }
            }

            // Detect global options for Matching tasks
            $globalOptions = $this->extractOptions($subText);
            
            // Extract questions
            $questions = $this->extractQuestions(implode("\n", $questionLines), $matches[1][$i][0], $matches[2][$i][0]);
            
            // Improved Instruction-based type detection
            $isTFNG = preg_match('/TRUE\s*[\/\-]\s*FALSE/i', $instructions) || preg_match('/YES\s*[\/\-]\s*NO/i', $instructions);
            $isHeading = preg_match('/List\s*of\s*Headings/i', $instructions) || preg_match('/List\s*of\s*Headings/i', $subText);
            // Paragraph Matching: "Write the correct letter, A-G"
            $isParaMatch = preg_match('/Write\s+the\s+correct\s+letter/i', $instructions) && preg_match('/[A-G]\-[A-G]/i', $instructions);

            // Assign types and global options
            foreach ($questions as &$q) {
                $hasBlank = (strpos($q['body'], '___') !== false || strpos($q['body'], '[q') !== false);

                if ($isTFNG) {
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
        // 1. Try to find explicitly numbered questions (Standard list: 1. 2. 3.)
        $pattern = '/(^|\n)\s*(\d+)[\.\)\s]\s+(.*?)(?=\s*\n\s*\d+[\.\)\s]\s+|$)/s';
        preg_match_all($pattern, $text, $matches);
        
        $questions = [];
        if (!empty($matches[2])) {
            foreach ($matches[2] as $index => $number) {
                $qBody = trim($matches[3][$index]);
                $type = $this->detectType($qBody);
                $questions[] = [
                    'number' => $number,
                    'body' => $qBody,
                    'type' => $type,
                    'options' => ($type === 'mcq' || $type === 'mcq_multi' || $type === 'match_heading') ? $this->extractOptions($qBody) : []
                ];
            }
        } 
        
        // 2. Check for Summary Completion / Embedded Numbers (e.g. 32______, 4 ______)
        // We find all embedded numbers and split the text into chunks for each question
        preg_match_all('/(?:^|[^0-9])(\d{1,2})[ \t\x{00A0}]*(?:_{2,}|[\. ]_{2,})/u', $text, $embeddedMatches, PREG_OFFSET_CAPTURE);
        
        if (!empty($embeddedMatches[1])) {
            $questions = [];
            
            // Check if it's a single paragraph (summary) or multiple questions in a range (4-7)
            $newlineCount = substr_count(trim($text), "\n");
            $isSummary = count($embeddedMatches[1]) > 1; 

            if ($isSummary) {
                // Keep whole block together for the first question, others are markers
                $processedText = preg_replace('/(?:^|[^0-9])(\d{1,2})[ \t\x{00A0}]*(?:_{2,}|[\. ]_{2,})/u', ' [q$1] ', $text);
                foreach ($embeddedMatches[1] as $idx => $match) {
                    $num = $match[0];
                    $questions[] = [
                        'number' => $num,
                        'body' => ($idx === 0) ? trim($processedText) : "[q$num]",
                        'type' => 'fill_blanks',
                        'options' => []
                    ];
                }
            } else {
                // Process as individual bulleted segments
                foreach ($embeddedMatches[1] as $idx => $match) {
                    $num = $match[0];
                    $offset = $match[1];
                    $nextOffset = isset($embeddedMatches[1][$idx + 1]) ? $embeddedMatches[1][$idx + 1][1] : strlen($text);
                    $start = ($idx === 0) ? 0 : $offset - 2; 
                    $segmentText = substr($text, $start, $nextOffset - $start);
                    $processedBody = preg_replace('/(?:^|[^0-9])' . $num . '[ \t\x{00A0}]*(?:_{2,}|[\. ]_{2,})/u', ' [q'.$num.'] ', $segmentText);
                    
                    $questions[] = [
                        'number' => $num,
                        'body' => trim($processedBody),
                        'type' => 'fill_blanks',
                        'options' => []
                    ];
                }
            }
            return $questions;
        }

        // 3. Fallback: If no numbers found, and we have a range, use the 'line-by-line' or 'aggressive' approach
        if (empty($questions) && $startRange && $endRange) {
            $currentNum = (int)$startRange;
            $endNum = (int)$endRange;
            
            // Split by newline OR by our new table cell separator " | "
            $blocks = preg_split('/\n| \| /', $text);
            foreach ($blocks as $block) {
                $block = trim($block);
                if (empty($block)) continue;
                if ($currentNum > $endNum) break;

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
        // Strictly stop if we see a number (e.g. 36.) followed by a dot, even without a newline
        $patternAlpha = '/([A-G])[\.\)\s]\s+(.*?)(?=\s+[A-G][\.\)\s]\s+|\s+\d+[\.\)\s]|$)/s';
        preg_match_all($patternAlpha, $body, $matches);
        
        if (!empty($matches[1])) {
            return array_combine($matches[1], array_map('trim', $matches[2]));
        }

        // 2. Try Roman numerals i., ii., iii... (for Headings)
        $patternRoman = '/(v?i{0,3}|iv)[\.\)\s]\s+(.*?)(?=\s+(?:v?i{1,3}|iv|v)[\.\)\s]\s+|\s+\d+[\.\)\s]|$)/s';
        preg_match_all($patternRoman, $body, $matches);
        
        if (!empty($matches[1])) {
            return array_combine($matches[1], array_map('trim', $matches[2]));
        }

        return [];
    }

    public function parseAnswers($text)
    {
        $lines = explode("\n", $text);
        $answers = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Matches "1. Answer", "1 Answer", "1) Answer", "Question 1: Answer"
            // Crucially, the number must be at the START of the line (\b\d+) 
            // and we check if it's followed by a separator and then non-digit content
            if (preg_match('/^(\d+)[\.\)\s:]+\s*(.*)$/i', $line, $matches)) {
                $number = $matches[1];
                $answer = trim($matches[2]);
                
                // Only save if it looks like an answer (length > 0)
                if (!empty($answer)) {
                    $answers[$number] = $answer;
                }
            }
        }
        
        return $answers;
    }
}
