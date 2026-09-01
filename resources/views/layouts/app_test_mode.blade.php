<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $test->name ?? 'IELTS Mock Test' }} | Test Mode</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --accent-gold: #ce9d3c;
            --header-bg: #ffffff;
        }

        html[data-theme="dark"] {
            --bg-main: #0b1120;
            --bg-card: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --header-bg: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .test-header {
            background-color: var(--header-bg);
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        
        .bg-white { background-color: var(--bg-card) !important; }
        .bg-light { background-color: var(--bg-main) !important; }
        .text-dark { color: var(--text-main) !important; }
        
        .test-passage, .passage-content, .panel { 
            background-color: var(--bg-main); 
            color: var(--text-main);
        }
        
        /* Premium minimal toggle */
        .theme-toggle-btn {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .theme-toggle-btn:hover {
            background: var(--border-color);
        }

        h1, h2, h3, h4, h5, h6, .fw-bold {
            font-family: 'Outfit', sans-serif;
            color: var(--text-main);
        }
        
        .text-muted { color: var(--text-muted) !important; }
        .border { border-color: var(--border-color) !important; }
        
        .cursor-pointer { cursor: pointer; }
        .cursor-grab { cursor: grab; }
        .cursor-grabbing { cursor: grabbing; }
        
        /* --- Font Size Overrides --- */
        html[data-theme="dark"] .bg-warning-subtle { background-color: rgba(206, 157, 60, 0.15) !important; color: var(--text-main) !important; }
        html[data-theme="dark"] .bg-primary-subtle { background-color: rgba(59, 130, 246, 0.15) !important; }
        html[data-theme="dark"] .bg-danger-subtle { background-color: rgba(239, 68, 68, 0.15) !important; }
        html[data-theme="dark"] .bg-success-subtle { background-color: rgba(34, 197, 94, 0.15) !important; }
        
        html[data-theme="dark"] .form-control, html[data-theme="dark"] .form-select { 
            background-color: var(--bg-main); 
            color: var(--text-main); 
            border-color: var(--border-color); 
        }
        html[data-theme="dark"] .form-control:focus, html[data-theme="dark"] .form-select:focus { 
            background-color: var(--bg-card); 
            color: var(--text-main); 
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 0.25rem rgba(206, 157, 60, 0.25);
        }
        html[data-theme="dark"] .form-control::placeholder { color: var(--text-muted); }
        
        /* --- Font Size Overrides --- */
        html[data-font-size="large"] { font-size: 1.15rem; }
        html[data-font-size="extra-large"] { font-size: 1.3rem; }
        
        html[data-font-size="large"] .passage-text { font-size: 1.25rem; line-height: 1.9; }
        html[data-font-size="extra-large"] .passage-text { font-size: 1.4rem; line-height: 2.0; }
    </style>
    <script>
        (function() {
            var savedFont = localStorage.getItem('ielts-font-size');
            if (savedFont) document.documentElement.setAttribute('data-font-size', savedFont);
            
            var savedTheme = localStorage.getItem('ielts-theme');
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
    @stack('styles')
</head>
<body>
    @yield('content')

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggles = document.querySelectorAll('.theme-toggle-btn');
            const html = document.documentElement;

            function updateToggleIcons() {
                const isDark = html.getAttribute('data-theme') === 'dark';
                themeToggles.forEach(btn => {
                    btn.innerHTML = isDark ? '<i class="fas fa-sun text-warning"></i>' : '<i class="fas fa-moon"></i>';
                });
            }

            themeToggles.forEach(btn => {
                btn.addEventListener('click', () => {
                    const isDark = html.getAttribute('data-theme') === 'dark';
                    if (isDark) {
                        html.removeAttribute('data-theme');
                        localStorage.setItem('ielts-theme', 'light');
                    } else {
                        html.setAttribute('data-theme', 'dark');
                        localStorage.setItem('ielts-theme', 'dark');
                    }
                    updateToggleIcons();
                });
            });

            // Initialize icons
            updateToggleIcons();
        });
    </script>

    @stack('scripts')
</body>
</html>
