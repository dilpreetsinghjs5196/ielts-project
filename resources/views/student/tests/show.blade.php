@extends('layouts.app_test_mode') {{-- Special lightweight layout for tests --}}

@section('content')
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

        <!-- Right: Questions -->
        <section class="test-questions p-4" id="questions-container">
            @foreach ($test->questionGroups as $group)
                <div class="question-group mb-5" data-group-id="{{ $group->id }}">
                    <div class="group-instruction mb-4 p-3 bg-warning-subtle rounded-3 border-start border-warning border-4">
                        <h6 class="fw-bold mb-1">{{ $group->title }}</h6>
                        <p class="mb-0 text-muted">{{ $group->instruction }}</p>
                    </div>

                    <div class="questions-list">
                        @foreach ($group->questions as $index => $question)
                            <div class="question-item mb-4 pb-4 border-bottom" id="q-{{ $question->id }}" data-q-id="{{ $question->id }}" data-q-type="{{ $question->question_type }}">
                                <div class="d-flex gap-3">
                                    <div class="q-number bg-dark text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; flex-shrink: 0;">
                                        {{ $question->question_number ?? ($index + 1) }}
                                    </div>
                                    <div class="q-body w-100">
                                        <p class="fw-semibold mb-3">{{ $question->content }}</p>

                                        {{-- Match Heading Component --}}
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

                                        {{-- MCQ Single/Multi Component --}}
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

                                        {{-- TFNG Component --}}
                                        @elseif ($question->question_type === 'tfng')
                                            <div class="tfng-options d-flex gap-3">
                                                @foreach (['TRUE', 'FALSE', 'NOT GIVEN'] as $val)
                                                    <label class="btn btn-outline-secondary px-3 py-2 rounded-pill flex-grow-1">
                                                        <input type="radio" name="q_{{ $question->id }}" value="{{ $val }}" class="d-none">
                                                        {{ $val }}
                                                    </label>
                                                @endforeach
                                            </div>

                                        {{-- Fill Blanks Component --}}
                                        @elseif ($question->question_type === 'fill_blanks')
                                            <div class="fill-blanks-container">
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
        <div class="footer-left d-flex align-items-center gap-2 overflow-hidden" id="footer-nav-container">
            @foreach ($test->questionGroups as $g_index => $group)
                <div class="nav-part d-flex align-items-center gap-3 {{ $g_index === 0 ? 'active' : '' }}" id="nav-part-{{ $group->id }}" onclick="activatePart('{{ $group->id }}')">
                    <span class="part-label fw-bold text-nowrap">Part {{ $g_index + 1 }}</span>
                    
                    <div class="part-questions d-flex gap-1 {{ $g_index === 0 ? '' : 'd-none' }}">
                        @foreach ($group->questions as $q_index => $q)
                            <button class="nav-btn btn btn-sm rounded-circle fw-bold q-nav-{{ $q->id }}" 
                                    style="width: 28px; height: 28px; font-size: 0.75rem;" 
                                    onclick="event.stopPropagation(); scrollToQuestion('q-{{ $q->id }}')">
                                {{ $q->question_number ?? ($q_index + 1) }}
                            </button>
                        @endforeach
                    </div>

                    <div class="part-summary text-muted small text-nowrap {{ $g_index === 0 ? 'd-none' : '' }}">
                        <span class="answered-count">0</span> of {{ $group->questions->count() }}
                    </div>
                </div>
                @if(!$loop->last)
                    <div class="vr mx-2 opacity-50"></div>
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
        border-right: 1px solid #e2e8f0;
        background: #f1f5f9;
        scrollbar-width: thin;
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
    let timeInSeconds = 3600;
    const timerEl = document.getElementById('test-timer');

    function updateTimer() {
        const mins = Math.floor(timeInSeconds / 60);
        const secs = timeInSeconds % 60;
        timerEl.innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        
        if (timeInSeconds > 0) {
            timeInSeconds--;
        } else {
            clearInterval(timerInterval);
            alert("Time's up!");
            submitTest();
        }
    }
    const timerInterval = setInterval(updateTimer, 1000);

    // Navigation and Scrolling
    function scrollToQuestion(id) {
        const el = document.getElementById(id);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Mark as current in nav
            document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('current'));
            const qId = id.replace('q-', '');
            const navBtn = document.querySelector(`.q-nav-${qId}`);
            if (navBtn) navBtn.classList.add('current');

            // Auto-activate the part in footer
            const group = el.closest('.question-group');
            if (group) activatePart(group.dataset.groupId, false);
        }
    }

    function activatePart(groupId, scroll = true) {
        // Toggle Footer UI
        document.querySelectorAll('.nav-part').forEach(part => {
            const isTarget = part.id === `nav-part-${groupId}`;
            part.classList.toggle('active', isTarget);
            part.querySelector('.part-questions').classList.toggle('d-none', !isTarget);
            part.querySelector('.part-summary').classList.toggle('d-none', isTarget);
        });

        // Toggle Passages
        document.querySelectorAll('.passage-group').forEach(p => {
            p.classList.toggle('d-none', p.id !== `passage-group-${groupId}`);
        });

        if (scroll) {
            const groupEl = document.querySelector(`.question-group[data-group-id="${groupId}"]`);
            if (groupEl) {
                // Scroll the questions container
                const container = document.getElementById('questions-container');
                const topPos = groupEl.offsetTop - container.offsetTop;
                container.scrollTo({ top: topPos, behavior: 'smooth' });
            }
            // Also reset passage scroll to top
            document.getElementById('passage-container').scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    // Progress Tracking
    function updateProgress() {
        document.querySelectorAll('.question-group').forEach(group => {
            const groupId = group.dataset.groupId;
            const questions = group.querySelectorAll('.question-item');
            let answeredCount = 0;

            questions.forEach(q => {
                const qId = q.dataset.qId;
                const type = q.dataset.qType;
                let isAnswered = false;

                if (type === 'mcq' || type === 'tfng') {
                    isAnswered = !!q.querySelector('input:checked');
                } else if (type === 'mcq_multi') {
                    isAnswered = q.querySelectorAll('input:checked').length > 0;
                } else if (type === 'fill_blanks') {
                    isAnswered = q.querySelector('input').value.trim() !== '';
                } else if (type === 'match_heading') {
                    isAnswered = !!document.getElementById(`hidden_q_${qId}`)?.value;
                }

                const navBtn = document.querySelector(`.q-nav-${qId}`);
                if (navBtn) navBtn.classList.toggle('answered', isAnswered);
                if (isAnswered) answeredCount++;
            });

            const summaryEl = document.querySelector(`#nav-part-${groupId} .answered-count`);
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
</script>
@endsection
