@extends('layouts.app_test_mode')

@section('content')

@if($attempt && ($attempt->status === 'completed' || !$attempt->wasRecentlyCreated))
<div id="resume-confirm-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="z-index: 9999; background: rgba(13, 22, 36, 0.95); backdrop-filter: blur(10px);">
    <div class="card border-0 shadow-lg text-center p-5" style="max-width: 500px; border-radius: 24px;">
        <div class="mb-4">
            <div class="rounded-circle bg-warning-subtle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="fas fa-history text-warning fs-1"></i>
            </div>
            <h2 class="fw-bold mb-3">{{ $attempt->status === 'completed' ? 'Test Already Given' : 'Resume Test?' }}</h2>
            <p class="text-muted">
                @if($attempt->status === 'completed')
                    You have already submitted an attempt for <strong>{{ $test->name }}</strong>. What would you like to do?
                @else
                    You have an ongoing attempt for <strong>{{ $test->name }}</strong>. Would you like to pick up where you left off?
                @endif
            </p>
        </div>
        <div class="d-grid gap-3">
            @if($attempt->status === 'completed')
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-dark py-3 rounded-pill fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                </a>
                <a href="{{ route('student.tests.restart', ['id' => $test->id, 'category' => 'listening']) }}" class="btn btn-warning py-3 rounded-pill fw-bold shadow-sm" onclick="return confirm('Are you sure you want to retry? This will delete your previous attempt.')">
                    <i class="fas fa-redo me-2"></i> Retry Test Again
                </a>
            @else
                <button class="btn btn-warning py-3 rounded-pill fw-bold shadow-sm" onclick="document.getElementById('resume-confirm-overlay').remove()">
                    <i class="fas fa-play me-2"></i> Resume Test
                </button>
                <a href="{{ route('student.tests.restart', ['id' => $test->id, 'category' => 'listening']) }}" class="btn btn-outline-secondary py-3 rounded-pill fw-bold" onclick="return confirm('Starting fresh will permanently delete your current progress. Are you sure?')">
                    <i class="fas fa-redo me-2"></i> Restart from Beginning
                </a>
            @endif
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
                <small class="text-muted">{{ $test->level->name }} | Listening</small>
            </div>
        </div>
        
        <div class="header-center d-flex align-items-center gap-4">
            @if($test->audio_file)
                <div class="main-audio-player d-flex align-items-center bg-dark rounded-pill px-4 py-2 shadow-sm" style="min-width: 380px;">
                    <div class="audio-controls d-flex align-items-center gap-2">
                        <div class="p-2 cursor-pointer skip-btn" onclick="window.skipAudio(-10)" title="Back 10s">
                            <i class="fas fa-undo text-light opacity-75" style="pointer-events: none;"></i>
                        </div>
                        <i class="fas fa-play-circle text-warning fs-3 cursor-pointer" onclick="toggleMainAudio()" id="main-audio-icon"></i>
                        <div class="p-2 cursor-pointer skip-btn" onclick="window.skipAudio(10)" title="Forward 10s">
                            <i class="fas fa-redo text-light opacity-75" style="pointer-events: none;"></i>
                        </div>
                    </div>
                    
                    <audio id="main-test-audio" class="d-none">
                        <source src="{{ asset('storage/' . $test->audio_file) }}" type="audio/mpeg">
                    </audio>
                    
                    <div class="audio-progress-container flex-grow-1 mx-3" style="height: 6px; background: #334155; border-radius: 3px; cursor: pointer;" onclick="seekAudio(event)">
                        <div id="audio-progress-bar" style="width: 0%; height: 100%; background: #ce9d3c; border-radius: 3px; transition: width 0.1s linear;"></div>
                    </div>
                    
                    <span id="audio-time" class="text-white small fw-bold mono" style="min-width: 45px;">0:00</span>
                </div>
            @endif

            <div class="timer-wrapper">
                <div class="timer d-flex align-items-center gap-2">
                    <i class="far fa-clock"></i>
                    <span id="test-timer" class="fw-bold fs-4">40:00</span>
                </div>
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
            @foreach ($test->parts as $p_index => $part)
                <div class="passage-group {{ $p_index === 0 ? '' : 'd-none' }}" id="passage-group-{{ $part->id }}">
                    <div class="passage-content bg-white p-4 shadow-sm rounded-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold mb-0 text-primary">Part {{ $p_index + 1 }}</h4>
                            @if($part->audio_file)
                                <span class="badge bg-danger pulse-badge"><i class="fas fa-volume-up me-1"></i> Audio Playing</span>
                            @endif
                        </div>

                        @if ($part->passage)
                            <div class="passage-text mb-4">
                                {!! nl2br($part->passage) !!}
                            </div>
                        @endif

                        @if ($part->audio_file)
                            <div class="audio-player p-3 bg-light rounded-4 border shadow-sm">
                                <audio controls class="w-100" id="audio-part-{{ $part->id }}">
                                    <source src="{{ asset('storage/' . $part->audio_file) }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        @endif

                        @if ($part->image)
                            <div class="segment-image mt-4 text-center">
                                <img src="{{ asset('storage/' . $part->image) }}" class="img-fluid rounded-4 border shadow-sm" style="max-height: 500px;">
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
            @foreach ($test->parts as $p_idx => $part)
                <div class="question-group mb-5 {{ $p_idx === 0 ? '' : 'd-none' }}" data-group-id="{{ $part->id }}">
                    
                    @php
                        // 1. Prepare Question Mapping
                        $renderedInstruction = $part->instruction;
                        $embeddedQIds = [];
                        $pattern = '/\[\s*q?(\d+)\s*\]/i';
                        
                        // Process Title tags in all questions of this part
                        foreach ($part->questions as $q) {
                            $qContentForEmbedding = $q->content ?: $q->title;
                            if (preg_match_all($pattern, $qContentForEmbedding, $matches)) {
                                foreach ($matches[1] as $num) {
                                    $targetQ = $part->questions->filter(fn($pq) => $pq->question_number == $num)->first();
                                    if ($targetQ && $targetQ->id != $q->id) {
                                        $embeddedQIds[] = $targetQ->id;
                                    }
                                }
                            }
                        }
                    @endphp

                    <div class="group-instruction mb-4 p-3 bg-warning-subtle rounded-3 border-start border-warning border-4">
                        <h6 class="fw-bold mb-1">Instructions</h6>
                        <div class="mb-0 text-muted instruction-content" style="line-height: 1.8;">
                            {!! nl2br($renderedInstruction) !!}
                        </div>
                    </div>

                    <div class="questions-list">
                        @php $lastTitle = null; @endphp
                        @foreach ($part->questions as $question)
                            @php
                                $qContent = $question->content ?: $question->title;
                                $isEmbeddedInBody = preg_match($pattern, $qContent);
                                
                                if ($isEmbeddedInBody) {
                                    $qContent = preg_replace_callback($pattern, function($m) use ($question, $part) {
                                        $num = $m[1];
                                        return '<input type="text" name="q_'.$question->id.'_'.$num.'" class="form-control form-control-sm d-inline-block text-center smart-q-input" style="width: 80px; border-bottom: 2px solid #ce9d3c; border-top:0; border-left:0; border-right:0; border-radius:0;" data-q-id="'.$question->id.'" data-q-num="'.$num.'" placeholder="'.$num.'">';
                                    }, $qContent);
                                }
                                
                                // Logic to hide cards that are embedded elsewhere
                                $isHidden = in_array($question->id, $embeddedQIds);
                            @endphp

                            @if(!empty($question->content) && !empty($question->title) && $question->title !== $lastTitle)
                                <div class="question-set-header mt-4 mb-3 p-3 rounded" style="background: rgba(59, 130, 246, 0.05); border-left: 5px solid #3b82f6;">
                                    <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.1rem; line-height: 1.5;">{{ $question->title }}</h5>
                                </div>
                                @php $lastTitle = $question->title; @endphp
                            @endif

                            <div class="question-item mb-4 pb-4 border-bottom {{ $isHidden ? 'd-none' : '' }}" 
                                 id="q-{{ $question->id }}" 
                                 data-q-id="{{ $question->id }}" 
                                 data-q-type="{{ $question->question_type }}">
                                
                                <div class="d-flex gap-3">
                                    <div class="q-number-box">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 32px; height: 32px; font-size: 0.85rem; flex-shrink: 0;">
                                            {{ $question->question_number }}
                                        </div>
                                    </div>
                                    <div class="q-body w-100">
                                        <div class="fw-semibold mb-3" style="line-height: 2;">
                                            {!! nl2br($qContent) !!}
                                        </div>

                                        @if ($question->image)
                                            <div class="question-image mb-3">
                                                <img src="{{ asset('storage/' . $question->image) }}" class="img-fluid rounded-3 border shadow-sm" style="max-height: 400px;">
                                            </div>
                                        @endif

                                        @if ($question->question_type === 'mcq')
                                            <div class="mcq-options d-flex flex-column gap-2">
                                                @foreach ($question->options as $opt_idx => $option)
                                                    <label class="option-label d-flex align-items-center gap-3 p-3 bg-white border rounded-3 cursor-pointer">
                                                        <input type="radio" name="q_{{ $question->id }}" value="{{ $opt_idx }}" class="form-check-input">
                                                        <span class="option-text">{{ $opt_idx }}. {{ $option }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @elseif ($question->question_type === 'mcq_multi')
                                            <div class="mcq-options d-flex flex-column gap-2">
                                                @foreach ($question->options as $opt_idx => $option)
                                                    <label class="option-label d-flex align-items-center gap-3 p-3 bg-white border rounded-3 cursor-pointer">
                                                        <input type="checkbox" name="q_{{ $question->id }}[]" value="{{ $opt_idx }}" class="form-check-input">
                                                        <span class="option-text">{{ $opt_idx }}. {{ $option }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @elseif ($question->question_type === 'fill_blanks' || $question->question_type === 'short_answer')
                                            @if(!$isEmbeddedInBody)
                                                <div class="fill-blanks-container mt-3">
                                                    <input type="text" name="q_{{ $question->id }}" class="form-control border-bottom border-top-0 border-start-0 border-end-0 bg-light px-3" placeholder="Enter answer..." style="height: 45px; border-radius: 8px;">
                                                </div>
                                            @endif
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
            @foreach ($test->parts as $p_index => $part)
                <div class="nav-part d-flex align-items-center gap-2 {{ $p_index === 0 ? 'active' : '' }}" id="nav-part-{{ $part->id }}" onclick="activatePart('{{ $part->id }}')">
                    <span class="part-label fw-bold text-nowrap">Part {{ $p_index + 1 }}</span>
                    
                    @php
                        $allQuestionNums = [];
                        foreach($part->questions as $q) {
                            if (str_contains($q->question_number, '-')) {
                                list($start, $end) = explode('-', $q->question_number);
                                if (is_numeric($start) && is_numeric($end)) {
                                    for ($i = (int)$start; $i <= (int)$end; $i++) {
                                        $allQuestionNums[] = $i;
                                    }
                                } else {
                                    $allQuestionNums[] = $q->question_number;
                                }
                            } else {
                                $allQuestionNums[] = $q->question_number;
                            }
                        }
                        $uniqueNums = array_unique($allQuestionNums);
                        $totalInPart = count($uniqueNums);
                    @endphp
                    <div class="part-summary text-muted small text-nowrap mx-2">
                        <span class="answered-count">0</span> of {{ $totalInPart }}
                    </div>

                    <div class="part-questions d-flex gap-2 {{ $p_index === 0 ? '' : 'd-none' }}">
                        @php
                            $displayedNums = [];
                        @endphp
                        @foreach ($part->questions as $q_index => $q)
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
                                    $nums[] = $q->question_number;
                                }
                            @endphp
                            
                            @foreach ($nums as $displayNum)
                                @if(!in_array($displayNum, $displayedNums))
                                    @php $displayedNums[] = $displayNum; @endphp
                                    <a href="javascript:void(0)" 
                                       class="question-nav-link q-nav-{{ $q->id }} text-decoration-none text-muted fw-semibold" 
                                       onclick="event.stopPropagation(); scrollToQuestion('q-{{ $q->id }}')">
                                        {{ $displayNum }}
                                    </a>
                                @endif
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
                <i class="fas fa-check-circle me-1"></i> Finish Test
            </button>
        </div>
    </footer>

    <!-- Submission Success Popup -->
    <div id="submission-popup">
        <div class="popup-content">
            <div class="popup-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="margin-bottom: 12px; font-weight: 800; color: #111;">Test Submitted!</h2>
            <p style="color: #666; font-size: 1.1rem; line-height: 1.6;">Your listening test has been submitted successfully. What would you like to do next?</p>
            <div class="btn-group">
                <a href="{{ route('student.dashboard') }}" class="popup-btn btn-home">Dashboard</a>
                <a href="{{ route('student.tests.thank-you', $test->id) }}?category=listening" class="popup-btn btn-admin">View Results</a>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --header-height: 70px;
        --footer-height: 70px;
        --primary-gold: #ce9d3c;
        --main-dark: #0d1624;
    }
    body { overflow: hidden; background: #f8fafc; font-family: 'Inter', sans-serif; }
    .test-container { display: flex; flex-direction: column; height: 100vh; }
    .test-header { height: var(--header-height); background: #fff; border-bottom: 3px solid var(--primary-gold); z-index: 100; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .timer-wrapper { display: none !important; /* Temporarily hidden as requested */ background: #f1f5f9; padding: 8px 24px; border-radius: 50px; min-width: 150px; }
    .skip-btn { display: none !important; }
    .audio-progress-container { pointer-events: none; cursor: default !important; }

    .test-footer { height: var(--footer-height); z-index: 100; box-shadow: 0 -4px 12px rgba(0,0,0,0.05); }
    
    .nav-part {
        padding: 8px 16px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        background: #f8fafc;
    }
    .nav-part:hover { background: #f1f5f9; }
    .nav-part.active { background: #fff; border-color: #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .nav-part.active .part-label { color: var(--primary-gold); }

    .question-nav-link {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 0.85rem;
        background: #fff;
        transition: all 0.2s;
    }
    .question-nav-link:hover { background: #f1f5f9; color: var(--primary-gold); border-color: var(--primary-gold); }
    .question-nav-link.answered { background: #f1f5f9; border-color: #cbd5e1; }
    .question-nav-link.current { background: var(--main-dark) !important; color: #fff !important; border-color: var(--main-dark) !important; }

    .vr { width: 1px; height: 24px; background-color: #e2e8f0; }
    
    /* --- Success Popup --- */
    #submission-popup {
        display: none; 
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        background: rgba(0,0,0,0.85); 
        z-index: 10000; 
        align-items: center; 
        justify-content: center;
        backdrop-filter: blur(5px);
    }
    .popup-content {
        background: white; 
        padding: 40px; 
        border-radius: 16px; 
        text-align: center; 
        max-width: 450px; 
        width: 90%; 
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        animation: slideUp 0.4s ease-out;
    }
    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .popup-icon {
        color: #10b981; 
        font-size: 60px; 
        margin-bottom: 20px;
    }
    .btn-group {
        display: flex; 
        gap: 15px; 
        margin-top: 30px;
    }
    .popup-btn {
        flex: 1; 
        padding: 14px; 
        border-radius: 8px; 
        font-weight: 700; 
        text-decoration: none;
        transition: transform 0.2s;
    }
    .popup-btn:hover {
        transform: translateY(-2px);
    }
    .btn-home { background: #0d1624; color: white; }
    .btn-admin { background: #ce9d3c; color: white; }
    
    .test-main { flex: 1; overflow: hidden; }
    .test-passage { width: 50%; overflow-y: auto; background: #f1f5f9; }
    .test-questions { width: 50%; overflow-y: auto; background: #fff; }
    .test-footer { height: var(--footer-height); z-index: 100; }
    .test-resizer { width: 8px; background: #cbd5e1; cursor: col-resize; position: relative; }
    .resizer-handle { background: #3b82f6; width: 30px; height: 30px; border-radius: 50%; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); display: flex; align-items: center; justify-content: center; }
    .nav-part { padding: 5px 15px; border-radius: 20px; cursor: pointer; transition: 0.3s; }
    .nav-part.active { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .nav-part.active .part-label { color: var(--primary-gold); }
    .question-nav-link { font-size: 0.8rem; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 4px; border: 1px solid #e2e8f0; }
    .question-nav-link.active { background: var(--primary-gold); color: #fff !important; }
    .pulse-badge { animation: pulse 2s infinite; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
</style>

<script>
    let timeInSeconds = {{ $attempt->time_left ?? 2400 }};
    const timerEl = document.getElementById('test-timer');
    
    function updateTimer() {
        const mins = Math.floor(timeInSeconds / 60);
        const secs = timeInSeconds % 60;
        timerEl.innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        if (timeInSeconds > 0) timeInSeconds--;
        else submitTest();
    }
    setInterval(updateTimer, 1000);

    function activatePart(partId) {
        // Toggle Passage Visibility
        document.querySelectorAll('.passage-group').forEach(p => p.classList.toggle('d-none', p.id != `passage-group-${partId}`));
        
        // Toggle Question Group Visibility
        document.querySelectorAll('.question-group').forEach(q => q.classList.toggle('d-none', q.dataset.groupId != partId));

        // Update Footer Nav
        document.querySelectorAll('.nav-part').forEach(p => p.classList.remove('active'));
        const activeNav = document.getElementById(`nav-part-${partId}`);
        if (activeNav) {
            activeNav.classList.add('active');
            document.querySelectorAll('.part-questions').forEach(pq => pq.classList.add('d-none'));
            const partQuestions = activeNav.querySelector('.part-questions');
            if (partQuestions) partQuestions.classList.remove('d-none');
        }
    }

    function scrollToQuestion(qId) {
        const el = document.getElementById(qId);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Highlight the question temporarily
            el.style.backgroundColor = '#fffbeb';
            setTimeout(() => { el.style.backgroundColor = ''; }, 2000);

            // Update nav active state
            document.querySelectorAll('.question-nav-link').forEach(l => l.classList.remove('current'));
            const navLink = document.querySelector(`.q-nav-${qId.replace('q-', '')}`);
            if (navLink) navLink.classList.add('current');
        }
    }

    // Main Audio Player Logic
    const mainAudio = document.getElementById('main-test-audio');
    const audioIcon = document.getElementById('main-audio-icon');
    const progressBar = document.getElementById('audio-progress-bar');
    const timeDisplay = document.getElementById('audio-time');

    const attemptId = "{{ $attempt->id }}";
    const finishedKey = `audio_finished_${attemptId}`;
    const startedKey = `audio_started_${attemptId}`;
    const timeKey = `audio_time_${attemptId}`;

    let isAudioFinished = localStorage.getItem(finishedKey) === 'true';
    let isAudioStarted = localStorage.getItem(startedKey) === 'true';

    // Initialize player state on page load
    if (mainAudio) {
        if (isAudioFinished) {
            disableAudioUI();
        } else if (isAudioStarted) {
            const savedTime = parseFloat(localStorage.getItem(timeKey) || '0');
            if (savedTime > 0) {
                mainAudio.currentTime = savedTime;
            }
        }
    }

    function disableAudioUI() {
        if (audioIcon) {
            audioIcon.classList.remove('fa-play-circle', 'fa-pause-circle');
            audioIcon.classList.add('fa-ban');
            audioIcon.style.opacity = '0.5';
            audioIcon.style.cursor = 'not-allowed';
            audioIcon.style.pointerEvents = 'none';
        }
        document.querySelectorAll('.skip-btn').forEach(btn => btn.style.display = 'none');
        const progressContainer = document.querySelector('.audio-progress-container');
        if (progressContainer) progressContainer.style.pointerEvents = 'none';
    }

    function toggleMainAudio() {
        if (!mainAudio) return;
        
        if (localStorage.getItem(finishedKey) === 'true') {
            alert('This audio has already been played once and cannot be replayed.');
            return;
        }

        if (mainAudio.paused) {
            mainAudio.play().then(() => {
                localStorage.setItem(startedKey, 'true');
                audioIcon.classList.replace('fa-play-circle', 'fa-pause-circle');
            }).catch(e => {
                console.error("Audio playback failed:", e);
            });
        } else {
            mainAudio.pause();
            audioIcon.classList.replace('fa-pause-circle', 'fa-play-circle');
        }
    }

    if (mainAudio) {
        mainAudio.ontimeupdate = function() {
            if (localStorage.getItem(finishedKey) === 'true') {
                mainAudio.pause();
                return;
            }
            
            const pct = (mainAudio.currentTime / mainAudio.duration) * 100;
            progressBar.style.width = pct + '%';
            
            const mins = Math.floor(mainAudio.currentTime / 60);
            const secs = Math.floor(mainAudio.currentTime % 60);
            timeDisplay.innerText = `${mins}:${secs.toString().padStart(2, '0')}`;
            
            localStorage.setItem(timeKey, mainAudio.currentTime);
        };

        mainAudio.onended = function() {
            localStorage.setItem(finishedKey, 'true');
            disableAudioUI();
        };

        // Prevent seeking via keyboard or custom player controls
        mainAudio.onseeking = function() {
            const savedTime = parseFloat(localStorage.getItem(timeKey) || '0');
            if (Math.abs(mainAudio.currentTime - savedTime) > 1.5) {
                mainAudio.currentTime = savedTime;
            }
        };
    }

    // Disable skip & seek click functions completely
    window.seekAudio = function(e) {
        console.log("Seeking is disabled for this test.");
    };

    window.skipAudio = function(seconds) {
        console.log("Skipping is disabled for this test.");
    };

    // Part Audios Logic (for tests with individual part audios instead of a main audio)
    document.querySelectorAll('audio').forEach(audio => {
        if (audio.id === 'main-test-audio') return; // Handled by main audio logic

        const audioId = audio.id || `part-audio-${Math.random().toString(36).substr(2, 9)}`;
        const pFinishedKey = `audio_finished_${attemptId}_${audioId}`;
        const pStartedKey = `audio_started_${attemptId}_${audioId}`;
        const pTimeKey = `audio_time_${attemptId}_${audioId}`;

        let isFinished = localStorage.getItem(pFinishedKey) === 'true';
        let isStarted = localStorage.getItem(pStartedKey) === 'true';

        if (isFinished) {
            audio.style.display = 'none';
            const container = audio.closest('.audio-player');
            if (container) {
                container.innerHTML = '<div class="text-danger fw-bold"><i class="fas fa-info-circle me-1"></i> Audio played once and is now locked.</div>';
            }
        } else {
            if (isStarted) {
                const savedTime = parseFloat(localStorage.getItem(pTimeKey) || '0');
                if (savedTime > 0) {
                    audio.currentTime = savedTime;
                }
            }

            let lastTime = audio.currentTime;
            audio.addEventListener('timeupdate', () => {
                if (!audio.seeking) {
                    lastTime = audio.currentTime;
                    localStorage.setItem(pTimeKey, lastTime);
                }
            });

            audio.addEventListener('seeking', () => {
                const delta = audio.currentTime - lastTime;
                if (Math.abs(delta) > 0.01) {
                    audio.currentTime = lastTime;
                }
            });

            audio.addEventListener('play', () => {
                localStorage.setItem(pStartedKey, 'true');
            });

            audio.addEventListener('ended', () => {
                localStorage.setItem(pFinishedKey, 'true');
                audio.style.display = 'none';
                const container = audio.closest('.audio-player');
                if (container) {
                    container.innerHTML = '<div class="text-danger fw-bold"><i class="fas fa-info-circle me-1"></i> Audio played once and is now locked.</div>';
                }
            });
        }
    });

    // Submit Logic
    function submitTest() {
        if(!confirm('Are you sure you want to finish the test?')) return;
        const answers = {};
        document.querySelectorAll('.question-item').forEach(q => {
            const id = q.dataset.qId;
            const type = q.dataset.qType;
            if (type === 'mcq') {
                const checked = q.querySelector('input:checked');
                answers[id] = checked ? checked.value : null;
            } else if (type === 'mcq_multi') {
                const checked = q.querySelectorAll('input:checked');
                answers[id] = Array.from(checked).map(c => c.value).join(', ');
            } else {
                const input = q.querySelector('input[type="text"]');
                if (input) {
                    // Collect all smart inputs for this question if it's a merged one
                    const allInputs = q.querySelectorAll('.smart-q-input');
                    if (allInputs.length > 1) {
                        answers[id] = Array.from(allInputs).map(i => i.value).join(', ');
                    } else {
                        answers[id] = input.value;
                    }
                }
            }
        });

        fetch("{{ route('student.tests.submit', $test->id) }}?category=listening", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ answers })
        }).then(r => r.json()).then(data => {
            if(data.success) {
                document.getElementById('submission-popup').style.display = 'flex';
            }
        });
    }

    // Basic Resizer
    const divider = document.getElementById('test-divider');
    const left = document.getElementById('passage-container');
    const right = document.getElementById('questions-container');
    let isResizing = false;

    divider.addEventListener('mousedown', () => isResizing = true);
    document.addEventListener('mousemove', (e) => {
        if (!isResizing) return;
        const width = (e.clientX / window.innerWidth) * 100;
        left.style.width = width + '%';
        right.style.width = (100 - width) + '%';
    });
    document.addEventListener('mouseup', () => isResizing = false);
</script>

@endsection
