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
        
        <div class="header-center timer-wrapper">
            <div class="timer d-flex align-items-center gap-2">
                <i class="far fa-clock"></i>
                <span id="test-timer" class="fw-bold fs-4">40:00</span>
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
                    
                    <div class="group-instruction mb-4 p-3 bg-warning-subtle rounded-3 border-start border-warning border-4">
                        <h6 class="fw-bold mb-1">Instructions</h6>
                        <div class="mb-0 text-muted instruction-content">
                            {!! nl2br($part->instruction) !!}
                        </div>
                    </div>

                    <div class="questions-list">
                        @foreach ($part->questions as $question)
                            @php
                                $qContent = $question->title;
                                $pattern = '/\[\s*q?(\d+)\s*\]/i';
                                $isEmbedded = preg_match($pattern, $qContent);
                                
                                if ($isEmbedded) {
                                    $qContent = preg_replace_callback($pattern, function($m) use ($question) {
                                        $num = $m[1];
                                        return '<input type="text" name="q_'.$question->id.'_'.$num.'" class="form-control form-control-sm d-inline-block text-center smart-q-input" style="width: 80px; border-bottom: 2px solid #ce9d3c; border-top:0; border-left:0; border-right:0; border-radius:0;" data-q-id="'.$question->id.'" data-q-num="'.$num.'" placeholder="'.$num.'">';
                                    }, $qContent);
                                }
                            @endphp

                            <div class="question-item mb-4 pb-4 border-bottom" 
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
                                        @elseif ($question->question_type === 'fill_blanks' || $question->question_type === 'short_answer')
                                            @if(!$isEmbedded)
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
        <div class="footer-left d-flex align-items-center gap-4">
            @foreach ($test->parts as $p_index => $part)
                <div class="nav-part d-flex align-items-center gap-2 {{ $p_index === 0 ? 'active' : '' }}" id="nav-part-{{ $part->id }}" onclick="activatePart('{{ $part->id }}')">
                    <span class="part-label fw-bold">Part {{ $p_index + 1 }}</span>
                    <div class="part-questions d-flex gap-2 {{ $p_index === 0 ? '' : 'd-none' }}">
                        @foreach ($part->questions as $q)
                            <a href="javascript:void(0)" class="question-nav-link q-nav-{{ $q->id }} text-decoration-none text-muted" onclick="scrollToQuestion('q-{{ $q->id }}')">
                                {{ $q->question_number }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div class="footer-right">
            <button class="btn btn-dark px-4 rounded-pill" onclick="submitTest()">Submit Test</button>
        </div>
    </footer>
</div>

<style>
    :root {
        --header-height: 70px;
        --footer-height: 60px;
        --primary-gold: #ce9d3c;
    }
    body { overflow: hidden; background: #f8fafc; }
    .test-container { display: flex; flex-direction: column; height: 100vh; }
    .test-header { height: var(--header-height); background: #fff; border-bottom: 3px solid var(--primary-gold); z-index: 100; }
    .timer-wrapper { background: #f1f5f9; padding: 8px 24px; border-radius: 50px; }
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

    function scrollToQuestion(id) {
        const el = document.getElementById(id);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            document.querySelectorAll('.question-nav-link').forEach(l => l.classList.remove('active'));
            document.querySelector(`.q-nav-${id.split('-')[1]}`).classList.add('active');
        }
    }

    function activatePart(groupId) {
        document.querySelectorAll('.nav-part').forEach(p => {
            const isTarget = p.id === `nav-part-${groupId}`;
            p.classList.toggle('active', isTarget);
            p.querySelector('.part-questions').classList.toggle('d-none', !isTarget);
        });
        document.querySelectorAll('.passage-group').forEach(p => p.classList.toggle('d-none', p.id !== `passage-group-${groupId}`));
        document.querySelectorAll('.question-group').forEach(q => q.classList.toggle('d-none', q.dataset.groupId != groupId));
    }

    function submitTest() {
        if(!confirm('Are you sure you want to finish the test?')) return;
        const answers = {};
        document.querySelectorAll('.question-item').forEach(q => {
            const id = q.dataset.qId;
            const type = q.dataset.qType;
            if (type === 'mcq') {
                const checked = q.querySelector('input:checked');
                answers[id] = checked ? checked.value : null;
            } else {
                const input = q.querySelector('input[type="text"]');
                answers[id] = input ? input.value : '';
            }
        });

        fetch("{{ route('student.tests.submit', $test->id) }}?category=listening", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ answers })
        }).then(r => r.json()).then(data => {
            if(data.success) {
                window.location.href = "{{ route('student.tests.thank-you', $test->id) }}?category=listening";
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
