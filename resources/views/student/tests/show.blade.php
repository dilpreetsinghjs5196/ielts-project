@extends('layouts.app_test_mode') {{-- Special lightweight layout for tests --}}

@section('content')

@if(!$attempt->wasRecentlyCreated)
<div id="resume-confirm-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="z-index: 9999; background: rgba(13, 22, 36, 0.95); backdrop-filter: blur(10px);">
    <div class="card border-0 shadow-lg text-center p-5" style="max-width: 500px; border-radius: 24px;">
        <div class="mb-4">
            <div class="rounded-circle bg-warning-subtle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="fas fa-history text-warning fs-1"></i>
            </div>
            <h2 class="fw-bold mb-3">Resume Test?</h2>
            <p class="text-muted">You have an ongoing attempt for <strong>{{ $test->name }}</strong>. Would you like to pick up where you left off?</p>
        </div>
        <div class="d-grid gap-3">
            <button class="btn btn-warning py-3 rounded-pill fw-bold" onclick="document.getElementById('resume-confirm-overlay').remove()">
                <i class="fas fa-play me-2"></i> Continue Previous Attempt
            </button>
            <a href="{{ route('student.tests.restart', $test) }}" class="btn btn-outline-secondary py-3 rounded-pill fw-bold" onclick="return confirm('Starting fresh will permanently delete your current progress. Are you sure?')">
                <i class="fas fa-redo me-2"></i> Restart from Beginning
            </a>
        </div>
    </div>
</div>
@endif

<div class="test-container">
    <!-- Test Header -->
    <header class="test-header d-flex justify-content-between align-items-center px-4">
        <div class="header-left d-flex align-items-center gap-3">
            <img src="{{ asset('images/opera-dark-logo.webp') }}" height="40" alt="Logo" class="test-logo">
            <div class="test-info">
                <h5 class="mb-0 fw-bold">{{ $test->name }}</h5>
                <small class="text-muted">{{ $test->moduleSet->name }} | {{ $test->moduleSet->category->name }}</small>
            </div>
        </div>
        
        <div class="header-center timer-wrapper">
            <div class="timer d-flex align-items-center gap-2">
                <i class="far fa-clock"></i>
                <span id="test-timer" class="fw-bold fs-4">60:00</span>
            </div>
        </div>

        <div class="header-right d-flex align-items-center gap-3">
            <span class="user-badge px-3 py-1 bg-light rounded-pill border">
                <i class="fas fa-user-circle me-1"></i> {{ auth('student')->user()->name }}
            </span>
            <button class="btn btn-primary btn-sm px-4 fw-bold rounded-pill" onclick="submitTest()">Finish Test</button>
        </div>
    </header>

    <!-- Test Main Body -->
    <main class="test-main d-flex">
        <!-- Left: Passage / Shared Content -->
        <section class="test-passage p-4" id="passage-container">
            @foreach ($test->questionGroups as $g_index => $group)
                <div class="passage-group {{ $g_index === 0 ? '' : 'd-none' }}" id="passage-group-{{ $group->id }}">
                    <div class="passage-content bg-white p-4 shadow-sm rounded-4">
                        @if ($group->passage)
                            <div class="passage-text">
                                {!! nl2br($group->passage) !!}
                            </div>
                        @endif

                        @if ($group->audio_file)
                            <div class="audio-player mt-4 p-3 bg-light rounded-3 border">
                                <audio controls class="w-100">
                                    <source src="{{ asset('storage/' . $group->audio_file) }}" type="audio/mpeg">
                                </audio>
                            </div>
                        @endif

                        @if ($group->attachment)
                            <div class="attachment-preview mt-4">
                                <img src="{{ asset('storage/' . $group->attachment) }}" class="img-fluid rounded-3 border shadow-sm">
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </section>

        <!-- Resizer Divider -->
        <div class="test-resizer" id="test-divider">
            <div class="resizer-handle">
                <i class="fas fa-arrows-alt-h text-white"></i>
            </div>
        </div>

        <!-- Right: Questions -->
        <section class="test-questions p-4" id="questions-container">
            @foreach ($test->questionGroups as $g_idx => $group)
                <div class="question-group mb-5 {{ $g_idx === 0 ? '' : 'd-none' }}" data-group-id="{{ $group->id }}">
                    @php
                        // 1. Prepare Question Mapping
                        $renderedInstruction = $group->instruction;
                        $embeddedQIds = [];
                        
                        // We'll use a function-like approach to find and replace tags
                        // Pattern matches [q123], [Q123], [123], [ q123 ]
                        $pattern = '/\[\s*q?(\d+)\s*\]/i';
                        
                        $replaceCallback = function($matches) use ($group, &$embeddedQIds) {
                            $num = $matches[1];
                            
                            // Find question that matches this number exactly or is part of a range
                            $targetQ = $group->questions->filter(function($q) use ($num) {
                                if ($q->question_number == $num) return true;
                                // Handle range like 34-40
                                if (str_contains($q->question_number, '-')) {
                                    list($start, $end) = explode('-', $q->question_number);
                                    return (int)$num >= (int)$start && (int)$num <= (int)$end;
                                }
                                return false;
                            })->first();

                            if ($targetQ) {
                                $embeddedQIds[] = $targetQ->id;
                                // Create unique name for each blank: q_ID_NUM
                                return '<span class="d-inline-block mx-1" id="q-'.$targetQ->id.'-'.$num.'"><input type="text" name="q_'.$targetQ->id.'_'.$num.'" class="form-control form-control-sm d-inline-block text-center fw-bold smart-q-input" style="width: 100px; height: 32px; border: 1px solid #94a3b8; border-radius: 4px; background: #fff;" data-q-id="'.$targetQ->id.'" data-q-num="'.$num.'" placeholder="'.$num.'"></span>';
                            }
                            return $matches[0]; // Return original if no question found
                        };

                        $renderedInstruction = preg_replace_callback($pattern, $replaceCallback, $renderedInstruction);
                    @endphp

                    <div class="group-instruction mb-4 p-3 bg-warning-subtle rounded-3 border-start border-warning border-4">
                        <h6 class="fw-bold mb-1">{{ $group->title }}</h6>
                        <div class="mb-0 text-muted instruction-content" style="line-height: 2.5;">
                            {!! nl2br($renderedInstruction) !!}
                        </div>
                    </div>

                    <div class="questions-list">
                        @php $lastTitle = null; @endphp
                        @foreach ($group->questions as $index => $question)
                            @php
                                // Process question content for tags too
                                $qContent = preg_replace_callback($pattern, $replaceCallback, $question->content);
                                
                                // Hide the main question block if ANY of its numbers were embedded
                                if (in_array($question->id, $embeddedQIds)) {
                                    // If the entire content was just the tags, skip it
                                    // But we wrap it in a hidden div instead to keep the ID reachable via JS
                                    $isHidden = true;
                                } else {
                                    $isHidden = false;
                                }
                            @endphp

                            @if(!empty($question->title) && $question->title !== $lastTitle)
{{-- ... rest of question logic ... --}}
                                <div class="question-set-header mt-5 mb-4 p-4 rounded-4" style="background: rgba(59, 130, 246, 0.05); border-left: 5px solid #3b82f6;">
                                    <div class="d-flex flex-column gap-2">
                                        @php 
                                            $title = $question->title;
                                            $badgeText = '';
                                            if (preg_match('/^(Questions?\s\d+[-–]\d+)/i', $title, $matches)) {
                                                $badgeText = $matches[1];
                                                $title = trim(substr($title, strlen($badgeText)));
                                            }
                                        @endphp
                                        
                                        @if($badgeText)
                                            <div><span class="badge bg-primary px-3 py-2 rounded-2" style="font-size: 0.9rem;">{{ $badgeText }}</span></div>
                                        @endif
                                        
                                        @if($title)
                                            <h5 class="fw-bold mb-0 text-dark" style="line-height: 1.5;">{{ $title }}</h5>
                                        @endif
                                    </div>
                                </div>
                                @php $lastTitle = $question->title; @endphp
                            @endif

                            <div class="question-item mb-4 pb-4 border-bottom" id="q-{{ $question->id }}" data-q-id="{{ $question->id }}" data-q-type="{{ $question->question_type }}">
                                <div class="d-flex gap-3">
                                    <div class="q-body w-100">
                                        <div class="fw-semibold mb-3" style="line-height: 2.5;">
                                            {!! nl2br($qContent) !!}
                                        </div>

                                        {{-- Standard inputs (only show if tags didn't replace them) --}}
                                        @if ($question->question_type === 'match_heading')
                                            <div class="match-heading-wrapper d-flex flex-column gap-3">
                                                <div class="heading-drop-zone p-3 bg-light rounded-3 border-dashed border-2 text-center" ondrop="drop(event)" ondragover="allowDrop(event)">
                                                    <span class="text-muted small">Drag a heading here</span>
                                                </div>
                                                <div class="headings-pool d-flex flex-wrap gap-2 mt-2">
                                                    @foreach ($question->options as $opt_idx => $option)
                                                        <div class="heading-item px-3 py-2 bg-white border rounded shadow-sm cursor-grab" draggable="true" ondragstart="drag(event)" id="h-{{ $question->id }}-{{ $opt_idx }}">
                                                            {{ $option }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @elseif ($question->question_type === 'mcq' || $question->question_type === 'mcq_multi')
                                            <div class="mcq-options d-flex flex-column gap-2">
                                                @foreach ($question->options as $opt_idx => $option)
                                                    <label class="option-label d-flex align-items-center gap-3 p-3 bg-white border rounded-3 cursor-pointer hover-bg-light transition-all">
                                                        <input type="{{ $question->question_type === 'mcq' ? 'radio' : 'checkbox' }}" 
                                                               name="q_{{ $question->id }}" 
                                                               value="{{ chr(65 + $opt_idx) }}" 
                                                               class="form-check-input">
                                                        <span class="option-text">{{ $option }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @elseif ($question->question_type === 'tfng')
                                            <div class="tfng-options d-flex gap-3">
                                                @foreach (['TRUE', 'FALSE', 'NOT GIVEN'] as $val)
                                                    <label class="btn btn-outline-secondary px-3 py-2 rounded-pill flex-grow-1">
                                                        <input type="radio" name="q_{{ $question->id }}" value="{{ $val }}" class="d-none">
                                                        {{ $val }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        @elseif (($question->question_type === 'fill_blanks' || $question->question_type === 'short_answer'))
                                            <div class="fill-blanks-container {{ $isHidden ? 'd-none' : '' }}">
                                                <input type="text" name="q_{{ $question->id }}" class="form-control border-bottom border-top-0 border-start-0 border-end-0 bg-light px-3" placeholder="Enter word...">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </section>
    </main>

    <!-- Test Footer -->
    <footer class="test-footer bg-white border-top px-4 d-flex align-items-center justify-content-between">
        <div class="footer-left d-flex align-items-center gap-4 overflow-hidden" id="footer-nav-container">
            @foreach ($test->questionGroups as $g_index => $group)
                <div class="nav-part d-flex align-items-center gap-2 {{ $g_index === 0 ? 'active' : '' }}" id="nav-part-{{ $group->id }}" onclick="activatePart('{{ $group->id }}')">
                    <span class="part-label fw-bold text-nowrap">Part {{ $g_index + 1 }}</span>
                    
                    @php
                        $totalInGroup = 0;
                        foreach($group->questions as $q) {
                            if (str_contains($q->question_number, '-')) {
                                list($start, $end) = explode('-', $q->question_number);
                                if (is_numeric($start) && is_numeric($end)) {
                                    $totalInGroup += ((int)$end - (int)$start + 1);
                                } else {
                                    $totalInGroup += 1;
                                }
                            } else {
                                $totalInGroup += 1;
                            }
                        }
                    @endphp
                    <div class="part-summary text-muted small text-nowrap mx-2">
                        <span class="answered-count">0</span> of {{ $totalInGroup }}
                    </div>

                    <div class="part-questions d-flex gap-2 {{ $g_index === 0 ? '' : 'd-none' }}">
                        @foreach ($group->questions as $q_index => $q)
                            @php
                                $nums = [];
                                if (str_contains($q->question_number, '-')) {
                                    list($start, $end) = explode('-', $q->question_number);
                                    if (is_numeric($start) && is_numeric($end)) {
                                        for ($i = (int)$start; $i <= (int)$end; $i++) {
                                            $nums[] = $i;
                                        }
                                    } else {
                                        $nums[] = $q->question_number;
                                    }
                                } else {
                                    $nums[] = $q->question_number ?? ($q_index + 1);
                                }
                            @endphp
                            
                            @foreach ($nums as $displayNum)
                                <a href="javascript:void(0)" 
                                   class="question-nav-link q-nav-{{ $q->id }} text-decoration-none text-muted fw-semibold" 
                                   onclick="event.stopPropagation(); scrollToQuestion('q-{{ $q->id }}')">
                                    {{ $displayNum }}
                                </a>
                            @endforeach
                        @endforeach
                    </div>
                </div>
                @if(!$loop->last)
                    <div class="vr mx-1 opacity-25"></div>
                @endif
            @endforeach
        </div>
        <div class="footer-right flex-shrink-0 ms-3">
            <button class="btn btn-dark px-4 rounded-pill" onclick="submitTest()">
                <i class="fas fa-check-circle me-1"></i> Submit
            </button>
        </div>
    </footer>
</div>

<style>
    /* Premium Test UI Styles */
    :root {
        --header-height: 70px;
        --footer-height: 60px;
        --primary-gold: #ce9d3c;
        --main-dark: #0d1624;
    }

    body {
        margin: 0;
        padding: 0;
        overflow: hidden;
        background: #f8fafc;
        font-family: 'Inter', sans-serif;
    }

    .test-container {
        display: flex;
        flex-direction: column;
        height: 100vh;
    }

    .test-header {
        height: var(--header-height);
        background: #fff;
        border-bottom: 3px solid var(--primary-gold);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        z-index: 100;
    }

    .timer-wrapper {
        background: #f1f5f9;
        padding: 8px 24px;
        border-radius: 50px;
        color: var(--main-dark);
    }

    .test-main {
        flex: 1;
        overflow: hidden;
        display: flex;
    }

    .test-passage {
        width: 50%;
        overflow-y: auto;
        background: #f1f5f9;
        scrollbar-width: thin;
    }

    /* Draggable Resizer */
    .test-resizer {
        width: 10px;
        background: #cbd5e1;
        cursor: col-resize;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        position: relative;
        transition: background 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
    }

    .test-resizer:hover, .test-resizer.resizing {
        background: #3b82f6;
    }

    .resizer-handle {
        background: #3b82f6;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        pointer-events: none;
        border: 2px solid white;
    }

    .test-questions {
        width: 50%;
        overflow-y: auto;
        background: #fff;
        scrollbar-width: thin;
    }

    .test-footer {
        height: var(--footer-height);
        z-index: 100;
    }

    .passage-text {
        font-size: 1.15rem;
        line-height: 1.8;
        color: #1e293b;
    }

    .q-number {
        font-size: 0.85rem;
        background: var(--main-dark);
    }

    .option-label {
        cursor: pointer;
        transition: all 0.2s;
    }

    .option-label:hover {
        border-color: var(--primary-gold);
        background: #fffdf9;
    }

    .option-label input:checked + .option-text {
        color: var(--main-dark);
        font-weight: 700;
    }

    /* Footer Nav Segmented Styling */
    .nav-part {
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
        background: #f8fafc;
    }

    .nav-part:hover {
        background: #f1f5f9;
    }

    .nav-part.active {
        background: #fff;
        border-color: #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        flex-grow: 0;
    }

    .part-label {
        font-size: 0.9rem;
        color: var(--main-dark);
    }

    .nav-part.active .part-label {
        color: var(--primary-gold);
    }

    .nav-btn {
        border-color: #cbd5e1;
        background: #fff;
        color: #64748b;
        transition: all 0.2s;
    }

    .nav-btn.answered {
        background: #e2e8f0;
        border-color: #94a3b8;
        color: var(--main-dark);
    }

    .nav-btn.current {
        background: var(--main-dark) !important;
        border-color: var(--main-dark) !important;
        color: #fff !important;
    }

    .vr {
        width: 1px;
        height: 24px;
        background-color: #e2e8f0;
    }

    /* Smooth Transitions */
    .transition-all {
        transition: all 0.25s ease;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        .test-main {
            flex-direction: column;
        }
        .test-passage, .test-questions {
            width: 100%;
            height: 50%;
        }
    }
</style>

<script>
    // Timer Logic
    let timeInSeconds = {{ $attempt->time_left ?? 3600 }};
    const timerEl = document.getElementById('test-timer');

    function updateTimer() {
        const mins = Math.floor(timeInSeconds / 60);
        const secs = timeInSeconds % 60;
        timerEl.innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        
        if (timeInSeconds > 0) {
            timeInSeconds--;
            
            // Auto-save time to server every 30 seconds
            if (timeInSeconds % 30 === 0) {
                saveProgress();
            }
        } else {
            clearInterval(timerInterval);
            alert("Time's up!");
            submitTest();
        }
    }
    const timerInterval = setInterval(updateTimer, 1000);

    // Load existing answers
    function restoreAnswers() {
        const existingAnswers = @json($attempt->answers ?? []);
        if (!existingAnswers || Object.keys(existingAnswers).length === 0) return;

        Object.entries(existingAnswers).forEach(([qId, value]) => {
            const qEl = document.querySelector(`.question-item[data-q-id="${qId}"]`);
            if (!qEl) return;

            const type = qEl.dataset.qType;
            if (type === 'mcq' || type === 'tfng') {
                const input = qEl.querySelector(`input[value="${value}"]`);
                if (input) input.checked = true;
            } else if (type === 'mcq_multi' && Array.isArray(value)) {
                value.forEach(v => {
                    const input = qEl.querySelector(`input[value="${v}"]`);
                    if (input) input.checked = true;
                });
            } else if (type === 'fill_blanks') {
                const input = qEl.querySelector('input');
                if (input) input.value = value;
            } else if (type === 'match_heading') {
                const dropZone = qEl.querySelector('.heading-drop-zone');
                if (dropZone) {
                    dropZone.innerHTML = `<div class="p-2 bg-success-subtle border border-success rounded d-flex justify-content-between align-items-center">
                        <span class="fw-bold">${value}</span>
                        <i class="fas fa-times cursor-pointer" onclick="resetDropZone(this)"></i>
                        <input type="hidden" id="hidden_q_${qId}" name="q_${qId}" value="${value}">
                    </div>`;
                }
            }
        });
        updateProgress();
    }

    function saveProgress() {
        const answers = collectAnswers();
        fetch("{{ route('student.tests.save-progress', $test) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ answers: answers, time_left: timeInSeconds })
        });
    }

    function collectAnswers() {
        const answers = {};
        
        // 1. Collect from regular question items
        document.querySelectorAll('.question-item:not(.d-none)').forEach(q => {
            const qId = q.dataset.qId;
            const type = q.dataset.qType;
            
            if (type === 'mcq' || type === 'tfng') {
                const checked = q.querySelector('input:checked');
                answers[qId] = checked ? checked.value : null;
            } else if (type === 'mcq_multi') {
                const checked = Array.from(q.querySelectorAll('input:checked')).map(i => i.value);
                answers[qId] = checked;
            } else if (type === 'fill_blanks' || type === 'short_answer') {
                const input = q.querySelector('input');
                answers[qId] = input ? input.value : '';
            } else if (type === 'match_heading') {
                const hidden = document.getElementById(`hidden_q_${qId}`);
                answers[qId] = hidden ? hidden.value : null;
            }
        });

        // 2. Collect from smart inputs (can be multiple per Q-ID)
        const smartAnswers = {};
        document.querySelectorAll('.smart-q-input').forEach(input => {
            const qId = input.dataset.qId;
            const qNum = input.dataset.qNum;
            if (!smartAnswers[qId]) smartAnswers[qId] = {};
            smartAnswers[qId][qNum] = input.value;
        });

        // Merge smart answers into main answers (joining with commas or storing as object if preferred)
        Object.entries(smartAnswers).forEach(([qId, vals]) => {
            // Join all blank values. Filter out empties if you want, but better to keep positions
            answers[qId] = Object.values(vals).join(', ');
        });

        return answers;
    }

    // Call restoration on load
    window.addEventListener('DOMContentLoaded', restoreAnswers);

    // Navigation and Scrolling
    function scrollToQuestion(id) {
        // Handle smart inputs: search for q-ID-NUM or just q-ID
        let el = document.getElementById(id);
        if (!el) {
            // Try searching for a smart input container
            const qId = id.replace('q-', '');
            el = document.querySelector(`[data-q-id="${qId}"]`)?.closest('span');
        }

        if (el) {
            const group = el.closest('.question-group') || el.closest('.passage-group');
            let groupId = null;
            if (group) {
                groupId = group.dataset.groupId || group.id.replace('passage-group-', '');
            } else {
                const instrGroup = el.closest('.question-group') || el.closest('.instruction-content')?.closest('.question-group');
                if (instrGroup) groupId = instrGroup.dataset.groupId;
            }

            if (groupId) {
                activatePart(groupId, false);
            }

            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Highlight link in nav
            document.querySelectorAll('.question-nav-link').forEach(link => link.classList.remove('text-primary', 'fw-bold', 'border-bottom', 'border-primary'));
            const qIdOnly = id.replace('q-', '');
            const navLink = document.querySelector(`.q-nav-${qIdOnly}`);
            if (navLink) {
                navLink.classList.add('text-primary', 'fw-bold', 'border-bottom', 'border-primary');
            }
        }
    }

    function activatePart(groupId, scroll = true) {
        // Toggle Footer UI
        document.querySelectorAll('.nav-part').forEach(part => {
            const isTarget = part.id === `nav-part-${groupId}`;
            part.classList.toggle('active', isTarget);
            part.querySelector('.part-questions').classList.toggle('d-none', !isTarget);
        });

        // Toggle Passages
        document.querySelectorAll('.passage-group').forEach(p => {
            p.classList.toggle('d-none', p.id !== `passage-group-${groupId}`);
        });

        // Toggle Question Groups
        document.querySelectorAll('.question-group').forEach(q => {
            q.classList.toggle('d-none', q.dataset.groupId != groupId);
        });

        if (scroll) {
            const container = document.getElementById('questions-container');
            container.scrollTo({ top: 0, behavior: 'smooth' });
            document.getElementById('passage-container').scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    // Progress Tracking
    function updateProgress() {
        const currentAnswers = collectAnswers();
        
        // Track answered states per question
        Object.entries(currentAnswers).forEach(([qId, value]) => {
            // A question is answered if it has any non-empty value
            // (Note: Joined values like ", , " should be treated as empty)
            const cleanedValue = value ? value.toString().replace(/[, ]/g, '') : '';
            const isAnswered = cleanedValue.length > 0;
            
            const navLink = document.querySelector(`.q-nav-${qId}`);
            if (navLink) {
                navLink.classList.toggle('text-success', isAnswered);
                navLink.classList.toggle('text-muted', !isAnswered);
            }
        });

        // Update counts per group
        document.querySelectorAll('.nav-part').forEach(part => {
            const groupId = part.id.replace('nav-part-', '');
            const navLinks = part.querySelectorAll('.question-nav-link');
            let answeredCount = 0;
            navLinks.forEach(link => {
                if (link.classList.contains('text-success')) answeredCount++;
            });
            const summaryEl = part.querySelector('.answered-count');
            if (summaryEl) summaryEl.innerText = answeredCount;
        });
    }

    // Listen for changes to update progress
    document.addEventListener('change', updateProgress);
    document.addEventListener('input', updateProgress);

    // Initial progress check
    updateProgress();

    // Drag and Drop Logic (Native JS)
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
        ev.dataTransfer.setData("content", ev.target.innerText);
    }

    function drop(ev) {
        ev.preventDefault();
        const dataId = ev.dataTransfer.getData("text");
        const content = ev.dataTransfer.getData("content");
        const draggedEl = document.getElementById(dataId);
        
        // Find the drop zone (it might be the nested span or the zone itself)
        let dropZone = ev.target;
        if (!dropZone.classList.contains('heading-drop-zone')) {
            dropZone = dropZone.closest('.heading-drop-zone');
        }

        if (dropZone) {
            dropZone.innerHTML = `<div class="p-2 bg-success-subtle border border-success rounded d-flex justify-content-between align-items-center">
                <span class="fw-bold">${content}</span>
                <i class="fas fa-times cursor-pointer" onclick="resetDropZone(this)"></i>
            </div>`;
            // Store value in a hidden input (to be added)
            const qId = dropZone.closest('.question-item').dataset.qId;
            let hiddenInput = document.getElementById(`hidden_q_${qId}`);
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.id = `hidden_q_${qId}`;
                hiddenInput.name = `q_${qId}`;
                dropZone.appendChild(hiddenInput);
            }
            hiddenInput.value = content;
        }
    }

    function resetDropZone(el) {
        const zone = el.closest('.heading-drop-zone');
        zone.innerHTML = `<span class="text-muted small">Drag a heading here</span>`;
    }

    function submitTest() {
        if (confirm("Are you sure you want to finish and submit your answers?")) {
            // Collect answers and submit
            const answers = {};
            document.querySelectorAll('.question-item').forEach(q => {
                const qId = q.dataset.qId;
                const type = q.dataset.qType;
                
                if (type === 'mcq' || type === 'tfng') {
                    const checked = q.querySelector('input:checked');
                    answers[qId] = checked ? checked.value : null;
                } else if (type === 'mcq_multi') {
                    const checked = Array.from(q.querySelectorAll('input:checked')).map(i => i.value);
                    answers[qId] = checked;
                } else if (type === 'fill_blanks') {
                    answers[qId] = q.querySelector('input').value;
                } else if (type === 'match_heading') {
                    const hidden = document.getElementById(`hidden_q_${qId}`);
                    answers[qId] = hidden ? hidden.value : null;
                }
            });

            // Add CSRF token
            fetch("{{ route('student.tests.submit', $test) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ answers: answers })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = "{{ route('student.dashboard') }}";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("An error occurred while submitting the test.");
            });
        }
    }

    // Draggable Resizer Logic
    const resizer = document.getElementById('test-divider');
    const leftSide = resizer.previousElementSibling;
    const rightSide = resizer.nextElementSibling;

    let x = 0;
    let leftWidth = 0;

    const mouseMoveHandler = function (e) {
        const dx = e.clientX - x;
        const newLeftWidth = ((leftWidth + dx) * 100) / resizer.parentNode.getBoundingClientRect().width;
        
        // Boundaries (25% to 75%)
        if (newLeftWidth > 25 && newLeftWidth < 75) {
            leftSide.style.width = `${newLeftWidth}%`;
            rightSide.style.width = `${100 - newLeftWidth}%`;
        }
    };

    const mouseUpHandler = function () {
        resizer.classList.remove('resizing');
        document.removeEventListener('mousemove', mouseMoveHandler);
        document.removeEventListener('mouseup', mouseUpHandler);
        document.body.style.removeProperty('cursor');
        document.body.style.removeProperty('user-select');
    };

    const mouseDownHandler = function (e) {
        x = e.clientX;
        leftWidth = leftSide.getBoundingClientRect().width;
        resizer.classList.add('resizing');

        document.addEventListener('mousemove', mouseMoveHandler);
        document.addEventListener('mouseup', mouseUpHandler);
        
        document.body.style.cursor = 'col-resize';
        document.body.style.userSelect = 'none';
    };

    resizer.addEventListener('mousedown', mouseDownHandler);
</script>
@endsection
