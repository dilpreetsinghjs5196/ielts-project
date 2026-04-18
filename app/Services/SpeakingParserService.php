<?php

namespace App\Services;

class SpeakingParserService
{
    public function parseText($text)
    {
        $segments = [];
        
        // Split by Part 1, Part 2, Part 3
        $parts = preg_split('/Part\s+(\d+)/i', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
        // Handle introductory boilerplate if any
        if (!empty($parts) && !is_numeric($parts[0])) {
            array_shift($parts);
        }

        for ($i = 0; $i < count($parts); $i += 2) {
            $partNum = $parts[$i];
            $partContent = $parts[$i+1] ?? '';
            
            $segments[] = $this->parsePart($partNum, $partContent);
        }

        // Fallback: If no parts detected but text exists
        if (empty($segments) && strlen(trim($text)) > 50) {
            $segments[] = [
                'title' => 'Speaking Test Content',
                'passage' => $text,
                'questions' => []
            ];
        }

        return $segments;
    }

    protected function parsePart($num, $content)
    {
        $lines = explode("\n", trim($content));
        $title = "Speaking Part $num";
        $instruction = "";
        $questions = [];
        $passage = "";

        if ($num == 1) {
            $currentTopic = "";
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (empty($trimmed)) continue;

                if (strtolower($trimmed) === 'example questions:') {
                    continue;
                } elseif (!str_starts_with($trimmed, '-') && !str_ends_with($trimmed, '?') && strlen($trimmed) < 100) {
                    $currentTopic = $trimmed;
                    $passage .= "\n" . strtoupper($currentTopic) . "\n";
                } elseif (str_starts_with($trimmed, '-') || str_ends_with($trimmed, '?')) {
                    $qBody = ltrim($trimmed, '- ');
                    if ($currentTopic) {
                        $qBody = "[$currentTopic] " . $qBody;
                    }
                    $questions[] = [
                        'number' => count($questions) + 1,
                        'body' => $qBody,
                        'type' => 'speaking_prompt'
                    ];
                } else {
                    $passage .= $trimmed . "\n";
                }
            }
        } elseif ($num == 2) {
            // Cue Card
            $passage = trim($content);
            $questions[] = [
                'number' => 1,
                'body' => 'Describe the topic as instructed in the cue card.',
                'type' => 'speaking_prompt'
            ];
        } elseif ($num == 3) {
            $currentTopic = "";
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (empty($trimmed)) continue;

                if (stripos($trimmed, 'Discussion topics:') !== false) {
                    $passage .= $trimmed . "\n";
                } elseif (strtolower($trimmed) === 'example questions:') {
                    continue;
                } elseif (!str_starts_with($trimmed, '-') && !str_ends_with($trimmed, '?') && strlen($trimmed) < 100) {
                    $currentTopic = $trimmed;
                    $passage .= "\n" . strtoupper($currentTopic) . "\n----------\n";
                } elseif (str_starts_with($trimmed, '-') || str_ends_with($trimmed, '?')) {
                    $qBody = ltrim($trimmed, '- ');
                    if ($currentTopic) {
                        $qBody = "[$currentTopic] " . $qBody;
                    }
                    $questions[] = [
                        'number' => count($questions) + 1,
                        'body' => $qBody,
                        'type' => 'speaking_prompt'
                    ];
                } else {
                    $passage .= $trimmed . "\n";
                }
            }
        }

        return [
            'title' => $title,
            'passage' => trim($passage),
            'questions' => $questions
        ];
    }
}
