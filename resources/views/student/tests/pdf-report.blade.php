<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>IELTS Review Result - {{ $test->name }}</title>
    <style>
        @page {
            margin: 20px 25px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.5;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #ce9d3c;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #0d1624;
            letter-spacing: 1px;
        }
        .logo-sub {
            font-size: 10px;
            color: #ce9d3c;
            text-transform: uppercase;
            font-weight: bold;
        }
        .doc-title {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #0d1624;
            text-transform: uppercase;
        }
        .meta-box {
            width: 100%;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            margin-bottom: 25px;
            padding: 12px 15px;
        }
        .meta-table {
            width: 100%;
        }
        .meta-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .meta-label {
            font-weight: bold;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
        }
        .meta-value {
            font-size: 13px;
            font-weight: bold;
            color: #0d1624;
        }
        .score-badge {
            background-color: #10b981;
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
        }
        .score-badge-writing {
            background-color: #3b82f6;
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
        }
        .part-header {
            background-color: #0d1624;
            color: #ffffff;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 12px;
            border-left: 5px solid #ce9d3c;
        }
        .passage-box {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 15px;
            margin-bottom: 20px;
            max-height: none;
            line-height: 1.6;
        }
        .passage-title {
            font-size: 14px;
            font-weight: bold;
            color: #0d1624;
            margin-bottom: 8px;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .questions-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .questions-table th {
            background-color: #f1f5f9;
            color: #334155;
            text-align: left;
            padding: 8px 10px;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 2px solid #cbd5e1;
        }
        .questions-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .q-num {
            font-weight: bold;
            text-align: center;
            width: 35px;
            background-color: #f8fafc;
        }
        .status-correct {
            font-family: 'DejaVu Sans', sans-serif;
            color: #10b981;
            font-weight: bold;
        }
        .status-incorrect {
            font-family: 'DejaVu Sans', sans-serif;
            color: #ef4444;
            font-weight: bold;
        }
        .status-unanswered {
            color: #64748b;
            font-style: italic;
        }
        .writing-task {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .writing-task-header {
            background-color: #f1f5f9;
            padding: 10px 15px;
            font-weight: bold;
            border-bottom: 1px solid #cbd5e1;
            color: #0d1624;
        }
        .writing-prompt {
            padding: 15px;
            background-color: #fffaf0;
            border-left: 4px solid #ce9d3c;
            margin: 10px 15px;
            font-size: 12px;
        }
        .writing-response {
            padding: 15px;
            border: 1px solid #e2e8f0;
            margin: 0 15px 15px 15px;
            background-color: #ffffff;
            white-space: pre-wrap;
            line-height: 1.6;
        }
        .feedback-box {
            padding: 12px 15px;
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 4px solid #10b981;
            margin: 0 15px 15px 15px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                <div class="logo-text">OPERA IELTS PORTAL</div>
                <div class="logo-sub">Official Test Result & Review Copy</div>
            </td>
            <td style="width: 50%;" class="doc-title">
                {{ strtoupper($moduleName) }} TEST REVIEW
            </td>
        </tr>
    </table>

    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td style="width: 25%;">
                    <div class="meta-label">Student Name</div>
                    <div class="meta-value">{{ $student->name ?? 'Student' }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="meta-label">Student ID</div>
                    <div class="meta-value">{{ $student->student_id ?? 'N/A' }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="meta-label">Date Completed</div>
                    <div class="meta-value">{{ $attempt->completed_at ? \Carbon\Carbon::parse($attempt->completed_at)->format('d M Y, H:i') : 'N/A' }}</div>
                </td>
                <td style="width: 25%; text-align: right;">
                    <div class="meta-label">Final Score</div>
                    <div class="meta-value">
                        @if($category === 'writing' || $category === 'speaking')
                            <span class="score-badge-writing">Band {{ $attempt->score ?? 'Pending' }} / 9.0</span>
                        @else
                            <span class="score-badge">{{ $attempt->score ?? 0 }} / {{ $totalMarks }}</span>
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 10px;">
                    <div class="meta-label">Test Title</div>
                    <div class="meta-value">{{ $test->name }}</div>
                </td>
                <td colspan="2" style="padding-top: 10px;">
                    <div class="meta-label">Time Taken</div>
                    <div class="meta-value">
                        @if($attempt->started_at && $attempt->completed_at)
                            @php
                                $start = \Carbon\Carbon::parse($attempt->started_at);
                                $end = \Carbon\Carbon::parse($attempt->completed_at);
                            @endphp
                            {{ round($start->diffInMinutes($end)) }} minutes
                        @else
                            N/A
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    @if($category === 'writing')
        <!-- WRITING REVIEW -->
        @foreach($test->tasks as $index => $task)
            <div class="writing-task">
                <div class="writing-task-header">
                    Part {{ $task->task_number ?? ($index + 1) }} &bull; Writing Task
                </div>
                <div class="writing-prompt">
                    <strong>Question / Prompt:</strong><br>
                    {!! nl2br(e($task->question_text ?? '')) !!}
                    @if($task->image)
                        @php
                            $taskImg = str_starts_with($task->image, 'http') ? $task->image : (file_exists(public_path('storage/' . $task->image)) ? public_path('storage/' . $task->image) : asset('storage/' . $task->image));
                        @endphp
                        <div style="margin-top: 10px;">
                            <img src="{{ $taskImg }}" style="max-width: 450px; max-height: 350px; border: 1px solid #cbd5e1; border-radius: 4px;" alt="Task Image">
                        </div>
                    @endif
                </div>
                <div style="padding: 0 15px 5px 15px; font-weight: bold; color: #334155;">
                    Your Submitted Response (Word Count: {{ isset($attempt->answers[$task->task_number]) ? count(explode(' ', trim($attempt->answers[$task->task_number]))) : 0 }} words):
                </div>
                <div class="writing-response">{{ $attempt->answers[$task->task_number] ?? 'No response submitted.' }}</div>

                @if($attempt->score || $attempt->feedback)
                    <div class="feedback-box">
                        <strong style="color: #166534;">Trainer Feedback (Band Score: {{ $attempt->score }}):</strong><br>
                        {!! nl2br(e($attempt->feedback ?? 'No verbal feedback provided.')) !!}
                    </div>
                @endif
            </div>
        @endforeach

    @elseif($category === 'listening')
        <!-- LISTENING REVIEW -->
        @foreach($test->parts as $p_index => $part)
            <div class="part-header">
                Part {{ $part->part_number ?? ($p_index + 1) }}
            </div>
            @if($part->image)
                @php
                    $partImg = str_starts_with($part->image, 'http') ? $part->image : (file_exists(public_path('storage/' . $part->image)) ? public_path('storage/' . $part->image) : asset('storage/' . $part->image));
                @endphp
                <div style="margin: 10px 0; text-align: center;">
                    <img src="{{ $partImg }}" style="max-width: 500px; max-height: 350px; border: 1px solid #cbd5e1; border-radius: 4px;" alt="Part Image">
                </div>
            @endif
            
            <table class="questions-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">Q#</th>
                        <th style="width: 50%;">Question Details</th>
                        <th style="width: 25%;">Your Answer</th>
                        <th style="width: 20%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($part->questions as $question)
                        @php
                            $studentAnswer = $attempt->answers[$question->id] ?? null;
                            $correctAnswer = trim(strtolower($question->correct_answer));
                            $isCorrect = false;
                            
                            if ($question->question_type === 'mcq_multi') {
                                $correctArray = preg_split('/[,]| and /', $correctAnswer);
                                $correctArray = array_map('trim', array_map('strtolower', $correctArray));
                                $studentArray = is_array($studentAnswer) ? array_map('trim', array_map('strtolower', $studentAnswer)) : [];
                                sort($correctArray);
                                sort($studentArray);
                                $hasAnswered = !empty($studentAnswer) && is_array($studentAnswer) && count($studentAnswer) > 0;
                                $isCorrect = ($correctArray == $studentArray && $hasAnswered);
                                $displayAnswer = is_array($studentAnswer) ? implode(', ', $studentAnswer) : $studentAnswer;
                            } else {
                                $hasAnswered = !empty($studentAnswer) && trim((string)$studentAnswer) !== '';
                                $displayAnswer = (string) $studentAnswer;
                                if ($hasAnswered) {
                                    $alternatives = preg_split('/\s*(?:\/|\||\bor\b)\s*/i', $correctAnswer);
                                    $studentNormalized = trim(strtolower((string)$studentAnswer));
                                    foreach ($alternatives as $alt) {
                                        $alt = trim($alt);
                                        $singular = preg_replace('/\s*\(.*?\)\s*/', '', $alt);
                                        $plural = str_replace(['(', ')'], '', $alt);
                                        if ($studentNormalized === $singular || $studentNormalized === $plural) {
                                            $isCorrect = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        @endphp
                        <tr>
                            <td class="q-num">{{ $question->question_number }}</td>
                            <td>
                                <div style="font-weight: bold; margin-bottom: 4px;">{{ strip_tags($question->content ?: ($question->title ?: 'Question ' . $question->question_number)) }}</div>
                                @if($question->image)
                                    @php
                                        $qImg = str_starts_with($question->image, 'http') ? $question->image : (file_exists(public_path('storage/' . $question->image)) ? public_path('storage/' . $question->image) : asset('storage/' . $question->image));
                                    @endphp
                                    <div style="margin: 6px 0;">
                                        <img src="{{ $qImg }}" style="max-width: 250px; max-height: 200px; border: 1px solid #cbd5e1; border-radius: 4px;" alt="Question Image">
                                    </div>
                                @endif
                                @if($question->images && is_array($question->images))
                                    @foreach($question->images as $imgPath)
                                        @php
                                            $qImgPath = str_starts_with($imgPath, 'http') ? $imgPath : (file_exists(public_path('storage/' . $imgPath)) ? public_path('storage/' . $imgPath) : asset('storage/' . $imgPath));
                                        @endphp
                                        <div style="margin: 6px 0;">
                                            <img src="{{ $qImgPath }}" style="max-width: 250px; max-height: 200px; border: 1px solid #cbd5e1; border-radius: 4px;" alt="Question Image">
                                        </div>
                                    @endforeach
                                @endif
                                @php
                                    $validOptions = is_array($question->options) ? array_filter($question->options, fn($val) => trim((string)$val) !== '') : [];
                                @endphp
                                @if(count($validOptions) > 0 && in_array($question->question_type, ['mcq', 'mcq_multi', 'tfng']))
                                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                                        @foreach($validOptions as $idx => $opt)
                                            @php $optKey = is_numeric($idx) ? chr(65 + (int)$idx) : $idx; @endphp
                                            <strong>{{ $optKey }}:</strong> {{ $opt }} &nbsp;&nbsp;
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>{{ $displayAnswer ?: '—' }}</td>
                            <td>
                                @if($isCorrect)
                                    <span class="status-correct">&#10004; Correct</span>
                                @elseif($hasAnswered)
                                    <span class="status-incorrect">&#10008; Wrong</span>
                                @else
                                    <span class="status-unanswered">Not Answered</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach

    @else
        <!-- READING REVIEW -->
        @foreach($test->questionGroups as $g_index => $group)
            <div class="part-header">
                READING PASSAGE {{ $g_index + 1 }}
            </div>
            
            @if(!empty($group->passage) || $group->image || $group->attachment)
                <div class="passage-box">
                    <div class="passage-title">Passage Reference Text</div>
                    @if(!empty($group->passage))
                        {!! strip_tags($group->passage, '<p><br><strong><em><ul><ol><li><table><tr><td><th>') !!}
                    @endif
                    @if($group->image)
                        @php
                            $grpImg = str_starts_with($group->image, 'http') ? $group->image : (file_exists(public_path('storage/' . $group->image)) ? public_path('storage/' . $group->image) : asset('storage/' . $group->image));
                        @endphp
                        <div style="margin-top: 10px; text-align: center;">
                            <img src="{{ $grpImg }}" style="max-width: 500px; max-height: 350px; border: 1px solid #cbd5e1; border-radius: 4px;" alt="Passage Image">
                        </div>
                    @endif
                    @if($group->images && is_array($group->images))
                        @foreach($group->images as $imgPath)
                            @php
                                $grpImgPath = str_starts_with($imgPath, 'http') ? $imgPath : (file_exists(public_path('storage/' . $imgPath)) ? public_path('storage/' . $imgPath) : asset('storage/' . $imgPath));
                            @endphp
                            <div style="margin-top: 10px; text-align: center;">
                                <img src="{{ $grpImgPath }}" style="max-width: 500px; max-height: 350px; border: 1px solid #cbd5e1; border-radius: 4px;" alt="Passage Image">
                            </div>
                        @endforeach
                    @endif
                    @if($group->attachment)
                        @php
                            $attImg = str_starts_with($group->attachment, 'http') ? $group->attachment : (file_exists(public_path('storage/' . $group->attachment)) ? public_path('storage/' . $group->attachment) : asset('storage/' . $group->attachment));
                        @endphp
                        <div style="margin-top: 10px; text-align: center;">
                            <img src="{{ $attImg }}" style="max-width: 500px; max-height: 350px; border: 1px solid #cbd5e1; border-radius: 4px;" alt="Passage Attachment">
                        </div>
                    @endif
                </div>
            @endif

            <div style="font-weight: bold; font-size: 13px; margin-bottom: 8px; color: #0d1624;">
                Questions &amp; Answers (Passage {{ $g_index + 1 }})
            </div>
            <table class="questions-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">Q#</th>
                        <th style="width: 50%;">Question Details</th>
                        <th style="width: 25%;">Your Answer</th>
                        <th style="width: 20%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($group->questions as $question)
                        @php
                            $studentAnswer = $attempt->answers[$question->id] ?? null;
                            $correctAnswer = trim(strtolower($question->correct_answer));
                            $isCorrect = false;
                            
                            if ($question->question_type === 'mcq_multi') {
                                $correctArray = preg_split('/[,]| and /', $correctAnswer);
                                $correctArray = array_map('trim', array_map('strtolower', $correctArray));
                                $studentArray = is_array($studentAnswer) ? array_map('trim', array_map('strtolower', $studentAnswer)) : [];
                                sort($correctArray);
                                sort($studentArray);
                                $hasAnswered = !empty($studentAnswer) && is_array($studentAnswer) && count($studentAnswer) > 0;
                                $isCorrect = ($correctArray == $studentArray && $hasAnswered);
                                $displayAnswer = is_array($studentAnswer) ? implode(', ', $studentAnswer) : $studentAnswer;
                            } else {
                                $hasAnswered = !empty($studentAnswer) && trim((string)$studentAnswer) !== '';
                                $displayAnswer = (string) $studentAnswer;
                                if ($hasAnswered) {
                                    $alternatives = preg_split('/\s*(?:\/|\||\bor\b)\s*/i', $correctAnswer);
                                    $studentNormalized = trim(strtolower((string)$studentAnswer));
                                    foreach ($alternatives as $alt) {
                                        $alt = trim($alt);
                                        $singular = preg_replace('/\s*\(.*?\)\s*/', '', $alt);
                                        $plural = str_replace(['(', ')'], '', $alt);
                                        if ($studentNormalized === $singular || $studentNormalized === $plural) {
                                            $isCorrect = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        @endphp
                        <tr>
                            <td class="q-num">{{ $question->question_number }}</td>
                            <td>
                                <div style="font-weight: bold; margin-bottom: 4px;">{{ strip_tags($question->content ?: ($question->title ?: 'Question ' . $question->question_number)) }}</div>
                                @if($question->image)
                                    @php
                                        $qImg = str_starts_with($question->image, 'http') ? $question->image : (file_exists(public_path('storage/' . $question->image)) ? public_path('storage/' . $question->image) : asset('storage/' . $question->image));
                                    @endphp
                                    <div style="margin: 6px 0;">
                                        <img src="{{ $qImg }}" style="max-width: 250px; max-height: 200px; border: 1px solid #cbd5e1; border-radius: 4px;" alt="Question Image">
                                    </div>
                                @endif
                                @php
                                    $validOptions = is_array($question->options) ? array_filter($question->options, fn($val) => trim((string)$val) !== '') : [];
                                @endphp
                                @if(count($validOptions) > 0 && in_array($question->question_type, ['mcq', 'mcq_multi', 'tfng']))
                                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                                        @foreach($validOptions as $idx => $opt)
                                            @php $optKey = is_numeric($idx) ? chr(65 + (int)$idx) : $idx; @endphp
                                            <strong>{{ $optKey }}:</strong> {{ $opt }} &nbsp;&nbsp;
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>{{ $displayAnswer ?: '—' }}</td>
                            <td>
                                @if($isCorrect)
                                    <span class="status-correct">&#10004; Correct</span>
                                @elseif($hasAnswered)
                                    <span class="status-incorrect">&#10008; Wrong</span>
                                @else
                                    <span class="status-unanswered">Not Answered</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endif

    <div class="footer">
        Generated by Opera IELTS Test Management System on {{ date('d M Y, H:i') }} &bull; Confidential Review Document
    </div>
</body>
</html>
