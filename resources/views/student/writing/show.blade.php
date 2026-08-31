<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Writing Mock Test - {{ $test->name }}</title>
    <script>(function(){var s=localStorage.getItem('ielts-theme');if(s)document.documentElement.setAttribute('data-theme',s);})()</script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    
    <style>
        .btn-report-issue {
            font-size: 0.78rem;
            color: #ef4444 !important;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            transition: all 0.2s ease;
            text-decoration: none !important;
        }
        .btn-report-issue:hover {
            background: #ef4444;
            color: #ffffff !important;
            border-color: #ef4444;
        }

        :root,
        [data-theme="light"] {
            --ielts-header: #f4f4f4;
            --ielts-border: #d1d1d1;
            --ielts-active: #ececec;
            --ielts-red: #e31837;
            --ielts-text: #222;
            --ielts-body-bg: #fff;
            --ielts-panel-bg: #fff;
            --ielts-main-bg: #fff;
            --ielts-prompt-bg: #fff;
            --ielts-part-bg: #f8f8f8;
            --ielts-footer-bg: #fdfdfd;
            --ielts-tab-active-bg: #fff;
            --ielts-tab-hover-bg: #f1f1f1;
            --ielts-nav-btn-bg: #e5e5e5;
            --ielts-btn-check-bg: #f4f4f4;
            --ielts-popup-bg: white;
            --ielts-popup-text: #222;
            --ielts-resume-bg: #fff;
            --ielts-resume-text: #111;
            --ielts-resume-text2: #666;
            --ielts-icon-color: #555;
            --ielts-input-border: #e2e8f0;
            --ielts-text-secondary: #64748b;
            --ielts-writing-area-bg: #fff;
            --ielts-writing-area-text: #222;
            --ielts-timer-bg: #f1f5f9;
            --ielts-word-counter: #444;
            --ielts-hr-color: #ddd;
            --ielts-divider-border: 8px solid #bbb;
        }

        [data-theme="dark"] {
            --ielts-header: #1e293b;
            --ielts-border: #334155;
            --ielts-active: #334155;
            --ielts-red: #f87171;
            --ielts-text: #f1f5f9;
            --ielts-body-bg: #0f172a;
            --ielts-panel-bg: #1e293b;
            --ielts-main-bg: #0f172a;
            --ielts-prompt-bg: #1e293b;
            --ielts-part-bg: #334155;
            --ielts-footer-bg: #1e293b;
            --ielts-tab-active-bg: #334155;
            --ielts-tab-hover-bg: #334155;
            --ielts-nav-btn-bg: #334155;
            --ielts-btn-check-bg: #334155;
            --ielts-popup-bg: #1e293b;
            --ielts-popup-text: #f1f5f9;
            --ielts-resume-bg: #1e293b;
            --ielts-resume-text: #f1f5f9;
            --ielts-resume-text2: #94a3b8;
            --ielts-icon-color: #94a3b8;
            --ielts-input-border: #334155;
            --ielts-text-secondary: #94a3b8;
            --ielts-writing-area-bg: #0f172a;
            --ielts-writing-area-text: #e2e8f0;
            --ielts-timer-bg: #334155;
            --ielts-word-counter: #94a3b8;
            --ielts-hr-color: #334155;
            --ielts-divider-border: 8px solid #475569;
        }

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Inter', sans-serif;
            color: var(--ielts-text);
            overflow: hidden;
            background-color: var(--ielts-body-bg);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* --- Header --- */
        header {
            height: 50px;
            background-color: var(--ielts-header);
            border-bottom: 1px solid var(--ielts-border);
            display: flex;
            align-items: center;
            padding: 0 20px;
            justify-content: space-between;
        }

        .timer-wrapper {
            display: none !important;
            background: var(--ielts-timer-bg);
            padding: 4px 18px;
            border-radius: 50px;
            color: var(--ielts-text);
            min-width: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid var(--ielts-border);
        }

        .test-info {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .ielts-logo {
            font-weight: 800;
            color: var(--ielts-red);
            font-size: 1.5rem;
            letter-spacing: -1px;
        }

        .header-icons {
            display: flex;
            gap: 20px;
            color: var(--ielts-icon-color);
        }

        /* --- Main Layout --- */
        .main-container {
            display: flex;
            height: calc(100vh - 100px); /* Subtract header and footer */
            width: 100%;
            background: var(--ielts-main-bg);
        }

        /* --- Panels --- */
        .panel {
            padding: 30px;
            overflow-y: auto;
            position: relative;
        }

        .left-panel {
            flex: 1;
            border-right: var(--ielts-divider-border);
            background-color: var(--ielts-panel-bg);
        }

        .right-panel {
            flex: 1;
            background-color: var(--ielts-panel-bg);
        }

        /* --- Instructions Area --- */
        .part-header {
            background: var(--ielts-part-bg);
            border: 1px solid var(--ielts-border);
            padding: 10px 15px;
            margin-bottom: 25px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .prompt-text {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .chart-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #eee;
            margin: 20px 0;
        }

        /* --- Input Area --- */
        .writing-area {
            width: 100%;
            height: 80%;
            border: 1px solid #4a90e2;
            border-radius: 4px;
            padding: 15px;
            font-size: 1rem;
            line-height: 1.6;
            resize: none;
            box-sizing: border-box;
            outline: none;
            background-color: var(--ielts-writing-area-bg);
            color: var(--ielts-writing-area-text);
        }

        .word-counter {
            text-align: right;
            margin-top: 10px;
            font-size: 0.9rem;
            color: var(--ielts-word-counter);
            font-weight: 500;
        }

        /* --- Footer Tabs --- */
        footer {
            height: 50px;
            background: var(--ielts-footer-bg);
            border-top: 1px solid var(--ielts-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 10px;
        }

        .tabs-container {
            display: flex;
            height: 100%;
        }

        .tab-btn {
            padding: 0 25px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            border-right: 1px solid var(--ielts-border);
            transition: background 0.2s;
            color: var(--ielts-text);
        }

        .tab-btn.active {
            background-color: var(--ielts-tab-active-bg);
            border-bottom: 3px solid var(--ielts-border);
        }

        .tab-btn:hover {
            background-color: var(--ielts-tab-hover-bg);
        }

        .submit-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-btn {
            background-color: var(--ielts-nav-btn-bg);
            border: none;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            cursor: pointer;
            color: var(--ielts-text);
        }

        .nav-btn.black {
            background-color: #000;
            color: #fff;
        }

        .btn-check {
            background-color: var(--ielts-btn-check-bg);
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            color: var(--ielts-text);
        }

        /* --- Hidden Task Logic --- */
        .task-content {
            display: none;
        }
        .task-content.active {
            display: block;
        }

        hr {
            border: 0;
            border-top: 1px solid var(--ielts-hr-color);
            margin: 20px 0;
        }

        /* --- Success Popup --- */
        #submission-popup {
            display: none; 
            position: fixed !important; 
            top: 0 !important; 
            left: 0 !important; 
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important; 
            height: 100% !important; 
            background: rgba(0,0,0,0.85) !important; 
            z-index: 999999 !important; 
            align-items: center !important; 
            justify-content: center !important;
            backdrop-filter: blur(5px) !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .popup-content {
            background: var(--ielts-popup-bg); 
            color: var(--ielts-popup-text);
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
        .btn-admin { background: #e31837; color: white; }

        /* --- Resume Overlay styling --- */
        #resume-confirm-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: rgba(13, 22, 36, 0.95) !important;
            backdrop-filter: blur(10px) !important;
            z-index: 999999 !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .resume-card {
            background: var(--ielts-resume-bg);
            padding: 40px;
            border-radius: 24px;
            text-align: center;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        .resume-card h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--ielts-resume-text);
            margin-top: 0;
            margin-bottom: 15px;
        }

        .resume-card p {
            color: var(--ielts-resume-text2);
            line-height: 1.6;
            margin-bottom: 25px;
            font-size: 0.95rem;
        }

        .resume-btn-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .resume-btn {
            padding: 15px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            display: block;
            text-align: center;
            font-size: 1rem;
            cursor: pointer;
            box-sizing: border-box;
            transition: all 0.2s;
        }

        .resume-btn-primary {
            background: #ce9d3c;
            color: #fff;
            border: none;
            width: 100%;
        }

        .resume-btn-primary:hover {
            background: #b8882f;
            transform: translateY(-2px);
        }

        .resume-btn-secondary {
            background: var(--ielts-panel-bg);
            color: var(--ielts-text);
            border: 1px solid var(--ielts-border);
        }

        .resume-btn-secondary:hover {
            background: var(--ielts-part-bg);
            transform: translateY(-2px);
        }

        .resume-btn-link {
            color: var(--ielts-text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            text-align: center;
            margin-top: 10px;
            display: block;
        }

        .resume-btn-link:hover {
            color: var(--ielts-text);
            text-decoration: underline;
        }

        /* ===== THEME TOGGLE ===== */
        .theme-toggle-inline { display:inline-flex; align-items:center; gap:8px; }
        .theme-toggle { position:relative; width:48px; height:26px; border-radius:13px; border:2px solid var(--ielts-input-border); background:linear-gradient(135deg,#e0f2fe,#bae6fd); cursor:pointer; padding:0; overflow:hidden; transition:all .4s cubic-bezier(.4,0,.2,1); flex-shrink:0; }
        [data-theme="dark"] .theme-toggle { background:linear-gradient(135deg,#1e293b,#0f172a); border-color:#475569; }
        .theme-toggle-thumb { position:absolute; top:2px; left:2px; width:18px; height:18px; border-radius:50%; background:#fbbf24; box-shadow:0 2px 8px rgba(251,191,36,.4); transition:all .4s cubic-bezier(.4,0,.2,1); display:flex; align-items:center; justify-content:center; }
        [data-theme="dark"] .theme-toggle-thumb { left:24px; background:#e2e8f0; box-shadow:0 2px 8px rgba(148,163,184,.4); }
        .theme-toggle-icon { font-size:9px; line-height:1; }
        .theme-toggle .sun-icon { color:#92400e; }
        .theme-toggle .moon-icon { display:none; color:#475569; }
        [data-theme="dark"] .theme-toggle .sun-icon { display:none; }
        [data-theme="dark"] .theme-toggle .moon-icon { display:inline; }
        .theme-toggle:hover { transform:scale(1.05); box-shadow:0 0 12px rgba(206,157,60,.25); }
        .theme-toggle-label { font-size:.7rem; font-weight:700; color:var(--ielts-text-secondary); text-transform:uppercase; letter-spacing:.05em; user-select:none; }
        [data-theme="dark"] .theme-toggle-label { color:#ffffff !important; font-weight:700; }

        /* Dark Mode Modal & Label Text Overrides */
        [data-theme="dark"] .modal-content {
            background-color: #1e293b !important;
            color: #ffffff !important;
            border: 1px solid #334155 !important;
        }
        [data-theme="dark"] .modal-content .text-muted,
        [data-theme="dark"] .modal-content p,
        [data-theme="dark"] .modal-content label {
            color: #f1f5f9 !important;
        }
        [data-theme="dark"] .modal-content .form-control,
        [data-theme="dark"] .modal-content .form-select {
            background-color: #0f172a !important;
            color: #ffffff !important;
            border: 1px solid #475569 !important;
        }
        [data-theme="dark"] .modal-content .form-control:read-only {
            background-color: #0b1120 !important;
            color: #93c5fd !important;
            border: 1px solid #334155 !important;
        }
        [data-theme="dark"] .modal-content .form-control::placeholder {
            color: #94a3b8 !important;
        }
        [data-theme="dark"] .modal-content .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
    </style>
</head>
<body>
@if($attempt && ($attempt->status === 'completed' || !$attempt->wasRecentlyCreated))
<div id="resume-confirm-overlay">
    <div class="resume-card">
        <div style="margin-bottom: 20px;">
            <div style="width: 80px; height: 80px; background: rgba(206, 157, 60, 0.1); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                <i class="fas fa-history" style="color: #ce9d3c; font-size: 2.5rem;"></i>
            </div>
            <h2>{{ $attempt->status === 'completed' ? 'Test Already Given' : 'Resume Test?' }}</h2>
            <p>
                @if($attempt->status === 'completed')
                    You have already submitted an attempt for this Writing test. What would you like to do?
                @else
                    You have an ongoing attempt for this Writing test. Would you like to pick up where you left off?
                @endif
            </p>
        </div>
        <div class="resume-btn-group">
            @if($attempt->status === 'completed')
                <a href="{{ route('student.tests.review', ['id' => $test->id, 'category' => 'writing']) }}" class="resume-btn resume-btn-secondary">
                    <i class="fas fa-eye me-2"></i> Review Old Test
                </a>
                <a href="{{ route('student.tests.restart', ['id' => $test->id, 'category' => 'writing']) }}" class="resume-btn resume-btn-primary" onclick="return confirm('Are you sure you want to retry? This will delete your previous attempt.')">
                    <i class="fas fa-redo me-2"></i> Retry Test Again
                </a>
            @else
                <button class="resume-btn resume-btn-primary" onclick="document.getElementById('resume-confirm-overlay').remove(); startTimer();">
                    <i class="fas fa-play me-2"></i> Resume Old One Test
                </button>
                <a href="{{ route('student.tests.restart', ['id' => $test->id, 'category' => 'writing']) }}" class="resume-btn resume-btn-secondary" onclick="return confirm('Starting fresh will permanently delete your current progress. Are you sure?')">
                    <i class="fas fa-redo me-2"></i> Restart from Beginning
                </a>
            @endif
            <a href="{{ route('student.dashboard') }}" class="resume-btn-link">Back to Dashboard</a>
        </div>
    </div>
</div>
@endif

    <header>
        <div class="ielts-logo">IELTS</div>
        <div class="test-info">
            Candidate Name: {{ auth('student')->user()->name ?? 'Guest User' }}
        </div>
        <div class="header-center timer-wrapper">
            <div class="timer" style="display: flex; align-items: center; gap: 8px;">
                <i class="far fa-clock"></i>
                <span id="test-timer" style="font-weight: 700; font-size: 1.1rem;">60:00</span>
            </div>
        </div>
        <div class="header-icons d-flex align-items-center gap-3">
            <div class="theme-toggle-inline me-2">
                <span class="theme-toggle-label" id="themeLabel">Light</span>
                <button type="button" class="theme-toggle" id="themeToggle" title="Toggle dark mode" aria-label="Toggle dark mode">
                    <span class="theme-toggle-thumb">
                        <span class="theme-toggle-icon sun-icon"><i class="fas fa-sun"></i></span>
                        <span class="theme-toggle-icon moon-icon"><i class="fas fa-moon"></i></span>
                    </span>
                </button>
            </div>
            <i class="fas fa-wifi"></i>
            <i class="fas fa-bell"></i>
            <i class="fas fa-bars"></i>
        </div>
    </header>

    <div class="main-container">
        <!-- Left Panel: The Question -->
        <div class="panel left-panel">
            @foreach($test->tasks as $index => $task)
                <div id="task-info-{{ $index + 1 }}" class="task-content @if($index == 0) active @endif">
                    <div class="part-header d-flex justify-content-between align-items-center">
                        <div>
                            Part {{ $task->task_number }}<br>
                            <span style="font-weight: 400; font-size: 0.85rem;">{{ $task->instruction ?: 'You should spend about ' . ($task->task_number == 1 ? '20' : '40') . ' minutes on this task. Write at least ' . ($task->task_number == 1 ? '150' : '250') . ' words.' }}</span>
                        </div>
                        <button type="button" class="btn btn-link btn-sm text-decoration-none btn-report-issue rounded-pill px-2 py-1 ms-3 flex-shrink-0" onclick="openReportModal('{{ $task->id }}', 'Part {{ $task->task_number }}', '{{ $test->id }}', 'writing')" title="Report issue with Task {{ $task->task_number }}">
                            <i class="far fa-flag me-1"></i> Report Issue
                        </button>
                    </div>

                    <div class="prompt-text">
                        {!! nl2br(e($task->question_text)) !!}
                    </div>

                    @if($task->image)
                        <img src="{{ asset('storage/' . $task->image) }}" class="chart-image" alt="Task Image">
                    @endif
                    
                    <p class="mt-4 small text-muted">Part {{ $task->task_number }}</p>
                </div>
            @endforeach
        </div>

        <!-- Right Panel: The Input -->
        <div class="panel right-panel">
            @foreach($test->tasks as $index => $task)
                <div id="task-input-{{ $index + 1 }}" class="task-content @if($index == 0) active @endif" style="height: 100%; display: flex; flex-direction: column;">
                    <textarea 
                        id="writing-textarea-{{ $index + 1 }}" 
                        class="writing-area" 
                        placeholder="Type your answer here..."
                        oninput="updateWordCount({{ $index + 1 }})"
                        onchange="saveProgress()"
                        style="flex: 1;"
                    ></textarea>
                    
                    <div class="word-counter d-flex justify-content-between align-items-center mt-2 px-1" style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="word-warning" id="word-warning-{{ $index + 1 }}" style="display: none; color: var(--ielts-red, #e31837); font-weight: 600; font-size: 0.85rem;">
                            <i class="fas fa-exclamation-triangle me-1"></i> Maximum word limit reached!
                        </span>
                        <div class="ms-auto" style="margin-left: auto;">
                            Words: <strong id="word-count-{{ $index + 1 }}">0</strong>
                            @if($task->max_words)
                                / <span style="opacity: 0.75;">Max {{ $task->max_words }}</span>
                            @elseif($task->min_words || $task->task_number)
                                / <span style="opacity: 0.75;">Min {{ $task->min_words ?? ($task->task_number == 1 ? 150 : 250) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <footer>
        <div class="tabs-container">
            @foreach($test->tasks as $index => $task)
                <button class="tab-btn @if($index == 0) active @endif" id="tab-btn-{{ $index + 1 }}" onclick="switchTask({{ $index + 1 }})">
                    Part {{ $task->task_number }}
                </button>
            @endforeach
        </div>

        <div class="submit-container">
            <span class="small text-muted" id="progress-text">1 of {{ $test->tasks->count() }}</span>
            <button class="nav-btn" onclick="prevTask()"><i class="fas fa-chevron-left"></i></button>
            <button class="nav-btn black" onclick="nextTask()"><i class="fas fa-chevron-right"></i></button>
            <button class="btn-check" onclick="submitTest()"><i class="fas fa-check"></i></button>
        </div>
    </footer>

    <!-- Submission Success Popup -->
    <div id="submission-popup">
        <div class="popup-content">
            <div class="popup-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="margin-bottom: 12px; font-weight: 800; color: #111;">Test Submitted!</h2>
            <p style="color: #666; font-size: 1.1rem; line-height: 1.6;">Your writing test has been submitted successfully. What would you like to do next?</p>
            <div class="btn-group">
                <a href="{{ route('student.dashboard') }}" class="popup-btn btn-home">Dashboard</a>
                <a href="{{ route('student.tests.review', ['id' => $test->id, 'category' => 'writing']) }}" class="popup-btn btn-admin">View Answers</a>
            </div>
        </div>
    </div>

    <script>
        let currentTask = 1;
        const totalTasks = {{ $test->tasks->count() }};
        let timeInSeconds = {{ $attempt->time_left ?? $examDurationInSeconds }};

        function switchTask(taskNum) {
            currentTask = taskNum;

            // Update UI elements
            document.querySelectorAll('.task-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

            document.getElementById(`task-info-${taskNum}`).classList.add('active');
            document.getElementById(`task-input-${taskNum}`).classList.add('active');
            document.getElementById(`tab-btn-${taskNum}`).classList.add('active');

            document.getElementById('progress-text').innerText = `${taskNum} of ${totalTasks}`;
        }

        function prevTask() {
            if (currentTask > 1) {
                switchTask(currentTask - 1);
            }
        }

        function nextTask() {
            if (currentTask < totalTasks) {
                switchTask(currentTask + 1);
            } else {
                if(confirm("Submit your writing test?")) {
                    submitTest();
                }
            }
        }

        const taskLimits = {
            @foreach($test->tasks as $index => $task)
                {{ $index + 1 }}: {
                    maxWords: {{ $task->max_words ? (int)$task->max_words : 'null' }},
                    minWords: {{ $task->min_words ? (int)$task->min_words : ($task->task_number == 1 ? 150 : 250) }}
                },
            @endforeach
        };

        function updateWordCount(taskNum) {
            const textarea = document.getElementById(`writing-textarea-${taskNum}`);
            if (!textarea) return;

            let text = textarea.value;
            const limitInfo = taskLimits[taskNum] || {};
            const maxWords = limitInfo.maxWords;

            // Split into words while handling whitespace
            const words = text.trim() ? text.trim().split(/\s+/) : [];
            let count = words.length;

            // Enforce max word count limit if set by Admin
            if (maxWords && count > maxWords) {
                // Truncate text to maxWords words
                const allowedText = words.slice(0, maxWords).join(' ');
                textarea.value = allowedText;
                count = maxWords;

                const warningEl = document.getElementById(`word-warning-${taskNum}`);
                if (warningEl) {
                    warningEl.style.display = 'inline-block';
                    warningEl.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i> Maximum word limit reached (${maxWords} words max)!`;
                }
            } else {
                const warningEl = document.getElementById(`word-warning-${taskNum}`);
                if (warningEl) warningEl.style.display = 'none';
            }

            const countEl = document.getElementById(`word-count-${taskNum}`);
            if (countEl) {
                countEl.innerText = count;
                if (maxWords && count >= maxWords) {
                    countEl.style.color = 'var(--ielts-red, #e31837)';
                } else if (limitInfo.minWords && count >= limitInfo.minWords) {
                    countEl.style.color = '#10b981';
                } else {
                    countEl.style.color = 'inherit';
                }
            }
        }

        function collectAnswers() {
            const answers = {};
            for(let i=1; i<=totalTasks; i++) {
                answers[i] = document.getElementById(`writing-textarea-${i}`).value;
            }
            return answers;
        }

        function saveProgress() {
            const answers = collectAnswers();
            fetch("{{ route('student.tests.save-progress', $test->id) }}?category=writing", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ answers: answers, time_left: timeInSeconds })
            });
        }

        function restoreAnswers() {
            const existingAnswers = @json($attempt->answers ?? []);
            if (!existingAnswers || Object.keys(existingAnswers).length === 0) return;

            Object.entries(existingAnswers).forEach(([partNum, value]) => {
                const textarea = document.getElementById(`writing-textarea-${partNum}`);
                if (textarea) {
                    textarea.value = value;
                    updateWordCount(partNum);
                }
            });
        }

        const timerEl = document.getElementById('test-timer');
        let timerInterval;

        function updateTimer() {
            const mins = Math.floor(timeInSeconds / 60);
            const secs = timeInSeconds % 60;
            if (timerEl) {
                timerEl.innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }

            if (timeInSeconds > 0) {
                timeInSeconds--;
                if (timeInSeconds % 30 === 0) {
                    saveProgress();
                }
            } else {
                if (timerInterval) clearInterval(timerInterval);
                alert("Time's up!");
                submitTest(true);
            }
        }

        function startTimer() {
            if (!timerInterval && timeInSeconds > 0) {
                timerInterval = setInterval(updateTimer, 1000);
            }
        }

        const isOverlayShowing = {{ ($attempt && ($attempt->status === 'completed' || !$attempt->wasRecentlyCreated)) ? 'true' : 'false' }};

        window.addEventListener('DOMContentLoaded', () => {
            restoreAnswers();
            if (!isOverlayShowing) {
                startTimer();
            }
        });

        function submitTest(isAuto = false) {
            if (!isAuto && !confirm("Are you sure you want to submit your writing test?")) return;

            // Collecting answers
            const answers = collectAnswers();

            // CSRF Token
            const csrfToken = '{{ csrf_token() }}';

            // Show loading state
            const submitBtn = document.querySelector('.btn-check');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                submitBtn.disabled = true;
            }

            fetch("{{ route('student.writing-tests.submit', $test->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ answers: answers })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success popup
                    document.getElementById('submission-popup').style.display = 'flex';
                } else {
                    alert("Submission failed. Please try again.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("An error occurred during submission.");
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-check"></i>';
                    submitBtn.disabled = false;
                }
            });
        }
    </script>
    <!-- Report Question Issue Modal -->
    <div class="modal fade" id="reportQuestionModal" tabindex="-1" aria-labelledby="reportQuestionModalLabel" aria-hidden="true" style="z-index: 10050;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; background: var(--ielts-panel-bg); color: var(--ielts-text);">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(239, 68, 68, 0.12); display: flex; align-items: center; justify-content: center; color: #ef4444;">
                            <i class="fas fa-flag"></i>
                        </div>
                        <h5 class="modal-title fw-bold mb-0" id="reportQuestionModalLabel" style="font-size: 1.15rem;">Report an Issue</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <p class="text-muted small mb-3">Notice an error or mistake with this writing task or prompt? Let our academic team know so we can review and correct it immediately.</p>
                    
                    <form id="reportQuestionForm" onsubmit="submitQuestionReport(event)">
                        <input type="hidden" id="report_question_id" name="question_id" value="">
                        <input type="hidden" id="report_question_number" name="question_number" value="">
                        <input type="hidden" id="report_test_id" name="test_id" value="{{ $test->id ?? '' }}">
                        <input type="hidden" id="report_category" name="category" value="writing">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted mb-1">Target</label>
                            <input type="text" id="report_target_label" class="form-control form-control-sm fw-bold bg-light" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted mb-1">Type of Issue <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="report_issue_type" name="issue_type" required>
                                <option value="Typo / Spelling / Grammar Error">Typo / Spelling / Grammar Error</option>
                                <option value="Unclear / Ambiguous Task Prompt">Unclear / Ambiguous Task Prompt</option>
                                <option value="Chart / Image Display Bug">Chart / Image Display Bug</option>
                                <option value="Incorrect Word Count Limit">Incorrect Word Count Limit</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted mb-1">Description / Details (Optional)</label>
                            <textarea class="form-control form-control-sm" id="report_description" name="description" rows="3" placeholder="Please describe what seems incorrect or needs correction..."></textarea>
                        </div>

                        <div id="reportFormFeedback" class="alert alert-success d-none py-2 small mb-0"></div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-sm btn-light border px-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="reportSubmitBtn" class="btn btn-sm btn-danger px-4 fw-bold">
                                <i class="fas fa-paper-plane me-1"></i> Submit Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function openReportModal(questionId, questionNumber, testId, category) {
            document.getElementById('report_question_id').value = questionId || '';
            document.getElementById('report_question_number').value = questionNumber || '';
            document.getElementById('report_test_id').value = testId || '{{ $test->id ?? '' }}';
            document.getElementById('report_category').value = category || 'writing';
            
            let label = (category ? category.toUpperCase() + ' - ' : '') + (questionNumber ? questionNumber : 'Task');
            document.getElementById('report_target_label').value = label;
            document.getElementById('report_description').value = '';
            document.getElementById('report_issue_type').selectedIndex = 0;
            
            const feedback = document.getElementById('reportFormFeedback');
            feedback.classList.add('d-none');
            feedback.textContent = '';
            
            const modalEl = document.getElementById('reportQuestionModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        }

        function submitQuestionReport(e) {
            e.preventDefault();
            const btn = document.getElementById('reportSubmitBtn');
            const feedback = document.getElementById('reportFormFeedback');
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';
            feedback.classList.add('d-none');

            const payload = {
                question_id: document.getElementById('report_question_id').value,
                question_number: document.getElementById('report_question_number').value,
                test_id: document.getElementById('report_test_id').value,
                category: document.getElementById('report_category').value,
                issue_type: document.getElementById('report_issue_type').value,
                description: document.getElementById('report_description').value
            };

            fetch('{{ route("student.question-reports.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Submit Report';
                if (data.success) {
                    feedback.className = 'alert alert-success py-2 small mb-0 mt-3';
                    feedback.textContent = data.message || 'Report submitted successfully! Thank you.';
                    feedback.classList.remove('d-none');
                    setTimeout(() => {
                        const modalEl = document.getElementById('reportQuestionModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    }, 1500);
                } else {
                    feedback.className = 'alert alert-danger py-2 small mb-0 mt-3';
                    feedback.textContent = data.message || 'Error submitting report. Please try again.';
                    feedback.classList.remove('d-none');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Submit Report';
                feedback.className = 'alert alert-danger py-2 small mb-0 mt-3';
                feedback.textContent = 'Server error. Please try again.';
                feedback.classList.remove('d-none');
            });
        }
    </script>
    <script>
        (function(){
            var t=document.getElementById('themeToggle'),l=document.getElementById('themeLabel'),h=document.documentElement;
            function set(v){h.setAttribute('data-theme',v);localStorage.setItem('ielts-theme',v);if(l)l.textContent=v==='dark'?'Dark':'Light';}
            var c=h.getAttribute('data-theme')||'light';if(l)l.textContent=c==='dark'?'Dark':'Light';
            if(t)t.addEventListener('click',function(){set((h.getAttribute('data-theme')||'light')==='dark'?'light':'dark');});
        })();
    </script>
</body>
</html>
