<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $test->name ?? 'IELTS Mock Test' }} | Test Mode</title>

    {{-- Apply saved theme IMMEDIATELY to prevent flash of wrong theme --}}
    <script>
        (function() {
            var saved = localStorage.getItem('ielts-theme');
            if (saved) document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ===== LIGHT THEME (default) ===== */
        :root,
        [data-theme="light"] {
            --body-bg: #f8fafc;
            --body-text: #0f172a;
            --header-bg: #ffffff;
            --header-border: #ce9d3c;
            --header-shadow: rgba(0, 0, 0, 0.05);
            --passage-bg: #f1f5f9;
            --passage-text: #1e293b;
            --questions-bg: #ffffff;
            --card-bg: #ffffff;
            --card-shadow: rgba(0, 0, 0, 0.03);
            --input-bg: #ffffff;
            --input-border: #e2e8f0;
            --input-text: #1e293b;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --divider: #e2e8f0;
            --footer-bg: #ffffff;
            --resizer-bg: #cbd5e1;
            --option-hover-bg: #fffdf9;
            --option-hover-border: #ce9d3c;
            --timer-bg: #f1f5f9;
            --dropdown-bg: #ffffff;
            --dropdown-text: #1e293b;
            --dropdown-hover: #f1f5f9;
            --modal-bg: #ffffff;
            --modal-text: #1e293b;
            --table-bg: #ffffff;
            --table-stripe: #f8fafc;
            --table-border: #e2e8f0;
            --code-bg: #f1f5f9;
            --nav-pill-bg: #e2e8f0;
            --nav-pill-active: #ce9d3c;
            --badge-bg: rgba(206, 157, 60, 0.1);
            --badge-text: #ce9d3c;
        }

        /* ===== DARK THEME ===== */
        [data-theme="dark"] {
            --body-bg: #0f172a;
            --body-text: #f1f5f9;
            --header-bg: #1e293b;
            --header-border: #ce9d3c;
            --header-shadow: rgba(0, 0, 0, 0.3);
            --passage-bg: #0f172a;
            --passage-text: #e2e8f0;
            --questions-bg: #1e293b;
            --card-bg: #1e293b;
            --card-shadow: rgba(0, 0, 0, 0.2);
            --input-bg: #0f172a;
            --input-border: #334155;
            --input-text: #e2e8f0;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --divider: #334155;
            --footer-bg: #1e293b;
            --resizer-bg: #475569;
            --option-hover-bg: #334155;
            --option-hover-border: #ce9d3c;
            --timer-bg: #334155;
            --dropdown-bg: #1e293b;
            --dropdown-text: #e2e8f0;
            --dropdown-hover: #334155;
            --modal-bg: #1e293b;
            --modal-text: #e2e8f0;
            --table-bg: #1e293b;
            --table-stripe: #0f172a;
            --table-border: #334155;
            --code-bg: #0f172a;
            --nav-pill-bg: #334155;
            --nav-pill-active: #ce9d3c;
            --badge-bg: rgba(206, 157, 60, 0.2);
            --badge-text: #f0c95c;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: var(--body-text);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        h1, h2, h3, h4, h5, h6, .fw-bold {
            font-family: 'Outfit', sans-serif;
        }
        .cursor-pointer { cursor: pointer; }
        .cursor-grab { cursor: grab; }
        .cursor-grabbing { cursor: grabbing; }

        /* ===== TEST-SPECIFIC DARK MODE OVERRIDES ===== */

        /* Test header */
        [data-theme="dark"] .test-header {
            background: var(--header-bg) !important;
            box-shadow: 0 4px 12px var(--header-shadow);
        }

        /* Passage panel */
        [data-theme="dark"] .test-passage {
            background: var(--passage-bg) !important;
        }
        [data-theme="dark"] .passage-text {
            color: var(--passage-text) !important;
        }

        /* Questions panel */
        [data-theme="dark"] .test-questions {
            background: var(--questions-bg) !important;
        }

        /* Resizer */
        [data-theme="dark"] .test-resizer {
            background: var(--resizer-bg) !important;
        }

        /* Footer */
        [data-theme="dark"] .test-footer {
            background: var(--footer-bg) !important;
            border-color: var(--divider) !important;
        }

        /* Timer */
        [data-theme="dark"] .timer-wrapper {
            background: var(--timer-bg) !important;
            color: var(--text-primary) !important;
        }

        /* Cards */
        [data-theme="dark"] .card {
            background: var(--card-bg) !important;
            color: var(--text-primary);
            border-color: var(--divider) !important;
        }

        /* Form controls */
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background-color: var(--input-bg);
            color: var(--input-text);
            border-color: var(--input-border);
        }
        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background-color: var(--input-bg);
            color: var(--input-text);
            border-color: #ce9d3c;
            box-shadow: 0 0 0 3px rgba(206, 157, 60, 0.15);
        }
        [data-theme="dark"] .form-control::placeholder,
        [data-theme="dark"] textarea::placeholder {
            color: #94a3b8;
            opacity: 1;
        }

        /* Option labels (MCQ) */
        [data-theme="dark"] .option-label {
            background: var(--card-bg) !important;
            border-color: var(--divider) !important;
            color: var(--text-primary);
        }
        [data-theme="dark"] .option-label:hover {
            border-color: var(--option-hover-border) !important;
            background: var(--option-hover-bg) !important;
        }

        /* Text overrides */
        [data-theme="dark"] .text-dark {
            color: var(--text-primary) !important;
        }
        [data-theme="dark"] .text-muted {
            color: var(--text-secondary) !important;
        }
        [data-theme="dark"] .text-body {
            color: var(--text-primary) !important;
        }

        /* Background overrides */
        [data-theme="dark"] .bg-white {
            background-color: var(--card-bg) !important;
        }
        [data-theme="dark"] .bg-light {
            background-color: var(--table-stripe) !important;
        }

        /* Borders */
        [data-theme="dark"] .border,
        [data-theme="dark"] .border-bottom,
        [data-theme="dark"] .border-top,
        [data-theme="dark"] .border-start,
        [data-theme="dark"] .border-end {
            border-color: var(--divider) !important;
        }

        /* Dropdowns */
        [data-theme="dark"] .dropdown-menu {
            background-color: var(--dropdown-bg);
            border-color: var(--divider);
        }
        [data-theme="dark"] .dropdown-item {
            color: var(--dropdown-text);
        }
        [data-theme="dark"] .dropdown-item:hover {
            background-color: var(--dropdown-hover);
        }

        /* Modals */
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
        [data-theme="dark"] .modal-header,
        [data-theme="dark"] .modal-footer {
            border-color: #334155;
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
        [data-theme="dark"] .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        [data-theme="dark"] .theme-toggle-label {
            color: #ffffff !important;
            font-weight: 700;
        }

        /* Tables */
        [data-theme="dark"] .table {
            color: var(--text-primary);
            --bs-table-bg: var(--table-bg);
            --bs-table-striped-bg: var(--table-stripe);
            border-color: var(--table-border);
        }
        [data-theme="dark"] .table thead th {
            background-color: var(--table-stripe);
            border-color: var(--table-border);
        }
        [data-theme="dark"] .table td,
        [data-theme="dark"] .table th {
            border-color: var(--table-border);
        }

        /* Badges */
        [data-theme="dark"] .badge.bg-light {
            background-color: var(--table-stripe) !important;
            color: var(--text-primary) !important;
        }

        /* User badge in header */
        [data-theme="dark"] .user-badge {
            background-color: var(--table-stripe) !important;
            color: var(--text-primary) !important;
            border-color: var(--divider) !important;
        }

        /* Question number circle */
        [data-theme="dark"] .q-number {
            background: var(--text-primary);
        }

        /* Question navigation pills in footer */
        [data-theme="dark"] .q-nav-btn {
            background: var(--nav-pill-bg) !important;
            color: var(--text-primary) !important;
            border-color: var(--divider) !important;
        }

        /* Tab / Nav links */
        [data-theme="dark"] .nav-tabs {
            border-color: var(--divider);
        }
        [data-theme="dark"] .nav-tabs .nav-link {
            color: var(--text-secondary);
        }
        [data-theme="dark"] .nav-tabs .nav-link.active {
            background-color: var(--card-bg);
            color: var(--text-primary);
            border-color: var(--divider) var(--divider) var(--card-bg);
        }

        /* Pagination */
        [data-theme="dark"] .page-link {
            background-color: var(--card-bg);
            color: var(--text-primary);
            border-color: var(--divider);
        }
        [data-theme="dark"] .page-link:hover {
            background-color: var(--dropdown-hover);
        }

        /* Alert overrides */
        [data-theme="dark"] .alert-success {
            background-color: #064e3b;
            color: #a7f3d0;
            border-color: rgba(16, 185, 129, 0.3);
        }
        [data-theme="dark"] .alert-danger {
            background-color: #7f1d1d;
            color: #fecaca;
            border-color: rgba(239, 68, 68, 0.3);
        }
        [data-theme="dark"] .alert-warning {
            background-color: #78350f;
            color: #fde68a;
            border-color: rgba(245, 158, 11, 0.3);
        }

        /* Audio player on dark bg is fine, but the section backgrounds */
        [data-theme="dark"] .audio-progress-bar {
            background-color: #475569 !important;
        }

        /* Code blocks */
        [data-theme="dark"] code {
            color: #f0abfc;
            background-color: var(--code-bg);
        }

        /* Accordion */
        [data-theme="dark"] .accordion-item {
            background-color: var(--card-bg);
            border-color: var(--divider);
            color: var(--text-primary);
        }
        [data-theme="dark"] .accordion-button {
            background-color: var(--card-bg);
            color: var(--text-primary);
        }

        /* ===== TEST VIEW DARK MODE COLOR OVERRIDES ===== */
        [data-theme="dark"] .bg-warning-subtle,
        [data-theme="dark"] .group-instruction {
            background-color: rgba(245, 158, 11, 0.15) !important;
            color: #fde047 !important;
            border-color: #f59e0b !important;
        }
        [data-theme="dark"] .group-instruction h6 {
            color: #fbbf24 !important;
        }
        [data-theme="dark"] .group-instruction .instruction-content,
        [data-theme="dark"] .group-instruction p,
        [data-theme="dark"] .group-instruction div {
            color: #e2e8f0 !important;
        }

        [data-theme="dark"] .question-set-header {
            background: rgba(59, 130, 246, 0.15) !important;
            border-left-color: #60a5fa !important;
        }
        [data-theme="dark"] .question-set-header h5 {
            color: #93c5fd !important;
        }
        [data-theme="dark"] .question-set-header div {
            color: #e2e8f0 !important;
        }

        [data-theme="dark"] .smart-q-input {
            background-color: #0f172a !important;
            color: #38bdf8 !important;
            border-color: #38bdf8 !important;
        }
        [data-theme="dark"] .fill-blanks-container input {
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
            border-bottom-color: #ce9d3c !important;
        }
        [data-theme="dark"] .fill-blanks-container input::placeholder {
            color: #64748b !important;
        }

        /* Report Issue Button Styling */
        .btn-report-issue {
            font-size: 0.78rem;
            transition: all 0.2s ease;
        }
        .btn-report-issue:hover {
            color: #dc2626 !important;
            background-color: rgba(220, 38, 38, 0.08);
        }
        [data-theme="dark"] .btn-report-issue {
            color: #f87171 !important;
        }
        [data-theme="dark"] .btn-report-issue:hover {
            background-color: rgba(239, 68, 68, 0.2) !important;
            color: #fca5a5 !important;
        }

        /* Inline Header Theme Toggle */
        .theme-toggle-inline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* List group */
        [data-theme="dark"] .list-group-item {
            background-color: var(--card-bg);
            color: var(--text-primary);
            border-color: var(--divider);
        }

        /* ===== FLOATING THEME TOGGLE ===== */
        .theme-toggle-float {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 9998;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .theme-toggle {
            position: relative;
            width: 64px;
            height: 32px;
            border-radius: 16px;
            border: 2px solid rgba(206, 157, 60, 0.3);
            background: linear-gradient(135deg, #87CEEB 0%, #E0F6FF 100%);
            cursor: pointer;
            padding: 0;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            flex-shrink: 0;
            box-shadow: inset 0 2px 6px rgba(0,0,0,0.1), 0 4px 12px rgba(135, 206, 235, 0.3);
        }

        [data-theme="dark"] .theme-toggle {
            background: linear-gradient(135deg, #0B132B 0%, #1C2541 100%);
            border-color: rgba(255, 255, 255, 0.1);
            box-shadow: inset 0 2px 6px rgba(0,0,0,0.4), 0 4px 12px rgba(28, 37, 65, 0.6);
        }

        .theme-toggle-thumb {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #FFD700;
            box-shadow: 0 2px 8px rgba(255, 215, 0, 0.6), inset 0 -2px 4px rgba(218, 165, 32, 0.6);
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        [data-theme="dark"] .theme-toggle-thumb {
            left: 34px;
            background: #F4F6F0;
            box-shadow: 0 2px 10px rgba(244, 246, 240, 0.7), inset 0 -2px 5px rgba(200, 200, 200, 0.8);
            transform: rotate(360deg);
        }

        .theme-toggle-icon {
            font-size: 12px;
            line-height: 1;
            transition: all 0.4s ease;
        }

        .theme-toggle .sun-icon {
            color: #D2691E;
            text-shadow: 0 0 4px rgba(255, 215, 0, 0.8);
        }

        .theme-toggle .moon-icon {
            display: none;
            color: #2C3E50;
        }

        [data-theme="dark"] .theme-toggle .sun-icon {
            display: none;
        }

        [data-theme="dark"] .theme-toggle .moon-icon {
            display: inline;
        }

        .theme-toggle:hover {
            transform: scale(1.08);
        }

        .theme-toggle:active {
            transform: scale(0.92);
        }

        /* Clouds in Light Mode */
        .theme-toggle::before {
            content: '';
            position: absolute;
            width: 14px;
            height: 6px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            top: 18px;
            left: 36px;
            transition: all 0.5s ease;
            box-shadow: 6px -4px 0 -1px rgba(255, 255, 255, 0.8), -4px -2px 0 1px rgba(255, 255, 255, 0.8);
            z-index: 1;
        }

        /* Stars decoration in dark mode */
        [data-theme="dark"] .theme-toggle::before {
            width: 2px;
            height: 2px;
            top: 10px;
            left: 14px;
            background: #FFF;
            box-shadow: 8px 12px 0 0.5px #FFF, 16px 2px 0 1px #FFF, 4px 6px 0 -0.5px #FFF;
            border-radius: 50%;
        }

        .theme-toggle-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            white-space: nowrap;
            user-select: none;
            transition: color 0.3s ease;
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Theme Toggle Container --}}
    <div class="theme-toggle-float" id="themeToggleWrapper">
        <span class="theme-toggle-label" id="themeLabel">Light</span>
        <button type="button" class="theme-toggle" id="themeToggle" title="Toggle dark mode" aria-label="Toggle dark mode">
            <span class="theme-toggle-thumb">
                <span class="theme-toggle-icon sun-icon"><i class="fas fa-sun"></i></span>
                <span class="theme-toggle-icon moon-icon"><i class="fas fa-moon"></i></span>
            </span>
        </button>
    </div>

    @yield('content')

    <!-- Report Question Issue Modal -->
    <div class="modal fade" id="reportQuestionModal" tabindex="-1" aria-labelledby="reportQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px;">
                            <i class="fas fa-exclamation-triangle text-danger fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold" id="reportQuestionModalLabel">Report Question Issue</h5>
                            <p class="text-muted small mb-0">Flag issue or suggest correction for <strong id="reportQNum">Question</strong></p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="reportQuestionForm" onsubmit="submitQuestionReport(event)">
                    <div class="modal-body py-4">
                        <input type="hidden" id="reportQId" name="question_id">
                        <input type="hidden" id="reportQNumberVal" name="question_number">
                        <input type="hidden" id="reportTestId" name="test_id">
                        <input type="hidden" id="reportCategory" name="category" value="reading">

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">WHAT IS THE ISSUE?</label>
                            <select class="form-select rounded-3 py-2" id="reportIssueType" name="issue_type" required>
                                <option value="" disabled selected>Select issue category...</option>
                                <option value="typo">Typo or Formatting Error</option>
                                <option value="incorrect_answer">Wrong Correct Answer / Answer Key Mistake</option>
                                <option value="confusing_options">Confusing or Missing Options</option>
                                <option value="media_problem">Audio or Image Loading Problem</option>
                                <option value="other">Other Content / Technical Issue</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">EXPLANATION / CORRECTION DETAILS</label>
                            <textarea class="form-control rounded-3" id="reportDescription" name="description" rows="3" placeholder="Describe the error or what should be corrected..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm" id="btnSubmitReport">
                            <i class="fas fa-paper-plane me-1"></i> Submit Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ===== THEME TOGGLE & HEADER ALIGNMENT =====
        document.addEventListener('DOMContentLoaded', function () {
            const themeToggleWrapper = document.getElementById('themeToggleWrapper');
            const headerRight = document.querySelector('.test-header .header-right');
            
            // Seamlessly integrate theme toggle into header right if available
            if (headerRight && themeToggleWrapper) {
                themeToggleWrapper.classList.remove('theme-toggle-float');
                themeToggleWrapper.classList.add('theme-toggle-inline');
                headerRight.insertBefore(themeToggleWrapper, headerRight.firstChild);
            }

            const themeToggle = document.getElementById('themeToggle');
            const themeLabel  = document.getElementById('themeLabel');
            const html        = document.documentElement;

            function setTheme(theme) {
                html.setAttribute('data-theme', theme);
                localStorage.setItem('ielts-theme', theme);
                if (themeLabel) {
                    themeLabel.textContent = theme === 'dark' ? 'Dark' : 'Light';
                }
            }

            const currentTheme = html.getAttribute('data-theme') || 'light';
            if (themeLabel) {
                themeLabel.textContent = currentTheme === 'dark' ? 'Dark' : 'Light';
            }

            if (themeToggle) {
                themeToggle.addEventListener('click', function () {
                    const current = html.getAttribute('data-theme') || 'light';
                    setTheme(current === 'dark' ? 'light' : 'dark');
                });
            }
        });

        // ===== REPORT QUESTION ISSUE MODAL =====
        function openReportModal(qId, qNum, testId, category) {
            document.getElementById('reportQId').value = qId || '';
            document.getElementById('reportQNumberVal').value = qNum || '';
            document.getElementById('reportQNum').textContent = qNum ? `Question #${qNum}` : 'Selected Question';
            document.getElementById('reportTestId').value = testId || '';
            document.getElementById('reportCategory').value = category || 'reading';
            document.getElementById('reportIssueType').value = '';
            document.getElementById('reportDescription').value = '';

            const modal = new bootstrap.Modal(document.getElementById('reportQuestionModal'));
            modal.show();
        }

        function submitQuestionReport(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitReport');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Submitting...';

            const payload = {
                question_id: document.getElementById('reportQId').value,
                question_number: document.getElementById('reportQNumberVal').value,
                test_id: document.getElementById('reportTestId').value,
                category: document.getElementById('reportCategory').value,
                issue_type: document.getElementById('reportIssueType').value,
                description: document.getElementById('reportDescription').value,
            };

            fetch("{{ route('student.question-reports.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                if (data.success) {
                    const modalEl = document.getElementById('reportQuestionModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    alert(data.message || 'Report submitted successfully!');
                } else {
                    alert('Failed to submit report: ' + (data.message || 'Error occurred'));
                }
            })
            .catch(err => {
                console.error(err);
                btn.disabled = false;
                btn.innerHTML = originalText;
                alert('An error occurred while submitting the report.');
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
