<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Speaking Mock Test - {{ $test->name }}</title>
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
            --ielts-main-bg: #fafafa;
            --ielts-prompt-bg: #f4fbff;
            --ielts-part-bg: #f8f8f8;
            --ielts-question-bg: #fff;
            --ielts-question-border: #e1e1e1;
            --ielts-footer-bg: #fdfdfd;
            --ielts-tab-active-bg: #fff;
            --ielts-tab-hover-bg: #f1f1f1;
            --ielts-nav-btn-bg: #e5e5e5;
            --ielts-popup-bg: white;
            --ielts-popup-text: #222;
            --ielts-icon-color: #555;
            --ielts-input-border: #e2e8f0;
            --ielts-text-secondary: #64748b;
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
            --ielts-question-bg: #1e293b;
            --ielts-question-border: #334155;
            --ielts-footer-bg: #1e293b;
            --ielts-tab-active-bg: #334155;
            --ielts-tab-hover-bg: #334155;
            --ielts-nav-btn-bg: #334155;
            --ielts-popup-bg: #1e293b;
            --ielts-popup-text: #f1f5f9;
            --ielts-icon-color: #94a3b8;
            --ielts-input-border: #334155;
            --ielts-text-secondary: #94a3b8;
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
            align-items: center;
            gap: 15px;
            color: var(--ielts-icon-color);
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

    /* --- Main Layout --- */
        .main-container {
            display: flex;
            justify-content: center;
            height: calc(100vh - 100px); 
            width: 100%;
            background-color: var(--ielts-main-bg);
        }

        /* --- Panels --- */
        .panel {
            padding: 40px;
            overflow-y: auto;
            position: relative;
            background-color: var(--ielts-panel-bg);
            max-width: 800px;
            width: 100%;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }

        /* --- Instructions Area --- */
        .part-header {
            background: var(--ielts-part-bg);
            border: 1px solid var(--ielts-border);
            padding: 10px 15px;
            margin-bottom: 25px;
            font-size: 1rem;
            font-weight: 700;
            text-align: center;
        }

        .prompt-text {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
            font-weight: 500;
            padding: 15px;
            background-color: var(--ielts-prompt-bg);
            border-left: 4px solid #4a90e2;
        }

        .question-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .question-list li {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 15px;
            padding: 15px 20px;
            background: var(--ielts-question-bg);
            border: 1px solid var(--ielts-question-border);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: transform 0.2s;
        }

        .question-list li:hover {
            transform: translateY(-2px);
            border-color: #4a90e2;
        }

        .question-icon {
            color: #4a90e2;
            margin-right: 15px;
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

        /* --- Hidden Task Logic --- */
        .task-content {
            display: none;
        }
        .task-content.active {
            display: block;
            animation: fadeIn 0.4s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- Exit / Success Popup --- */
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
            cursor: pointer;
        }
        .popup-btn:hover {
            transform: translateY(-2px);
        }
        .btn-home { background: #0d1624; color: white; border: none; }
        .btn-admin { background: #e31837; color: white; border: none; }

    </style>
</head>
<body>
    <header>
        <div class="ielts-logo">IELTS</div>
        <div class="test-info">
            Candidate Name: {{ auth('student')->user()->name ?? 'Guest User' }}
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
        <!-- Single Center Panel for Speaking -->
        <div class="panel">
            @foreach($test->parts as $index => $part)
                <div id="part-info-{{ $index + 1 }}" class="task-content @if($index == 0) active @endif">
                    <div class="part-header">
                        IELTS Speaking Part {{ $part->part_number }}<br>
                        <span class="text-muted small" style="font-size: 0.8rem; font-weight: 400;">{{ $part->title }}</span>
                    </div>

                    @if($part->passage)
                    <div class="prompt-text">
                        {!! nl2br(e($part->passage)) !!}
                    </div>
                    @endif

                    <ul class="question-list">
                        @foreach($part->questions as $question)
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-comment-dots question-icon mt-1"></i>
                                <span>{{ $question->question_text }}</span>
                            </div>
                            <button type="button" class="btn btn-link btn-sm text-decoration-none btn-report-issue rounded-pill px-2 py-1 ms-3 flex-shrink-0" onclick="openReportModal('{{ $question->id }}', 'Question {{ $loop->iteration }}', '{{ $test->id }}', 'speaking')" title="Report issue with this question">
                                <i class="far fa-flag me-1"></i> Report Issue
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <footer>
        <div class="tabs-container">
            @foreach($test->parts as $index => $part)
                <button class="tab-btn @if($index == 0) active @endif" id="tab-btn-{{ $index + 1 }}" onclick="switchTask({{ $index + 1 }})">
                    Part {{ $part->part_number }}
                </button>
            @endforeach
        </div>

        <div class="submit-container">
            <span class="small text-muted" id="progress-text">1 of {{ $test->parts->count() }}</span>
            <button class="nav-btn"><i class="fas fa-chevron-left"></i></button>
            <button class="nav-btn black" onclick="nextTask()"><i class="fas fa-chevron-right"></i></button>
            <button onclick="showExitPopup()" class="nav-btn bg-danger text-white ms-3 px-4" style="width: auto; text-decoration: none; font-weight: bold;">Exit Test</button>
        </div>
    </footer>

    <!-- Exit Settings / Success Popup -->
    <div id="submission-popup">
        <div class="popup-content">
            <div class="popup-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="margin-bottom: 12px; font-weight: 800; color: #111;">Test Completed!</h2>
            <p style="color: #666; font-size: 1.1rem; line-height: 1.6;">You have successfully completed this interactive speaking practice. What would you like to do next?</p>
            <div class="btn-group">
                <a href="{{ route('student.dashboard') }}" class="popup-btn btn-home">Dashboard</a>
                <a href="{{ route('student.dashboard') }}" class="popup-btn btn-admin">Back to Dashboard</a>
            </div>
        </div>
    </div>

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
                    <p class="text-muted small mb-3">Notice an error or mistake with this question/instruction? Let our academic team know so we can review and correct it immediately.</p>
                    
                    <form id="reportQuestionForm" onsubmit="submitQuestionReport(event)">
                        <input type="hidden" id="report_question_id" name="question_id" value="">
                        <input type="hidden" id="report_question_number" name="question_number" value="">
                        <input type="hidden" id="report_test_id" name="test_id" value="{{ $test->id ?? '' }}">
                        <input type="hidden" id="report_category" name="category" value="speaking">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted mb-1">Target</label>
                            <input type="text" id="report_target_label" class="form-control form-control-sm fw-bold bg-light" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted mb-1">Type of Issue <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="report_issue_type" name="issue_type" required>
                                <option value="Wrong / Incorrect Answer Key">Wrong / Incorrect Answer Key</option>
                                <option value="Typo / Spelling / Grammar Error">Typo / Spelling / Grammar Error</option>
                                <option value="Audio / Media Issue">Audio / Media Issue</option>
                                <option value="Ambiguous / Unclear Question">Ambiguous / Unclear Question</option>
                                <option value="Formatting / Display Bug">Formatting / Display Bug</option>
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
        let currentTask = 1;
        const totalTasks = {{ $test->parts->count() }};

        function switchTask(taskNum) {
            currentTask = taskNum;

            // Update UI elements
            document.querySelectorAll('.task-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

            document.getElementById(`part-info-${taskNum}`).classList.add('active');
            document.getElementById(`tab-btn-${taskNum}`).classList.add('active');

            document.getElementById('progress-text').innerText = `${taskNum} of ${totalTasks}`;
        }

        function nextTask() {
            if (currentTask < totalTasks) {
                switchTask(currentTask + 1);
            }
        }

        function showExitPopup() {
            if(confirm("Are you sure you want to finish this Speaking Exam?")) {
                document.getElementById('submission-popup').style.display = 'flex';
            }
        }

        function openReportModal(questionId, questionNumber, testId, category) {
            document.getElementById('report_question_id').value = questionId || '';
            document.getElementById('report_question_number').value = questionNumber || '';
            document.getElementById('report_test_id').value = testId || '{{ $test->id ?? '' }}';
            document.getElementById('report_category').value = category || 'speaking';
            
            let label = (category ? category.toUpperCase() + ' - ' : '') + (questionNumber ? questionNumber : 'Question');
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
