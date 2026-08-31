<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - IELTS Test Management System</title>

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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ===== LIGHT THEME (default) ===== */
        :root,
        [data-theme="light"] {
            --primary-bg: #f4f6f9;
            --sidebar-bg: #0d1624;
            --sidebar-hover: #16243b;
            --sidebar-color: #f8fafc;
            --sidebar-active: #ffffff;
            --sidebar-active-bg: #ce9d3c;
            --brand-color: #ffffff;

            --body-bg: #f4f6f9;
            --navbar-bg: #ffffff;
            --navbar-shadow: rgba(0, 0, 0, 0.05);
            --navbar-border: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --card-bg: #ffffff;
            --card-shadow: rgba(0, 0, 0, 0.03);
            --input-bg: #ffffff;
            --input-border: #e2e8f0;
            --input-text: #1e293b;
            --dropdown-bg: #ffffff;
            --dropdown-text: #1e293b;
            --dropdown-hover: #f1f5f9;
            --table-bg: #ffffff;
            --table-stripe: #f8fafc;
            --table-border: #e2e8f0;
            --table-text: #1e293b;
            --modal-bg: #ffffff;
            --modal-text: #1e293b;
            --alert-success-bg: #d1fae5;
            --alert-success-text: #065f46;
            --alert-danger-bg: #fee2e2;
            --alert-danger-text: #991b1b;
            --badge-bg: rgba(206, 157, 60, 0.1);
            --badge-text: #ce9d3c;
            --btn-outline-border: #e2e8f0;
            --divider: #e2e8f0;
            --code-bg: #f1f5f9;
        }

        /* ===== DARK THEME ===== */
        [data-theme="dark"] {
            --primary-bg: #0f172a;
            --sidebar-bg: #020617;
            --sidebar-hover: #0f172a;
            --sidebar-color: #e2e8f0;
            --sidebar-active: #ffffff;
            --sidebar-active-bg: #ce9d3c;
            --brand-color: #ffffff;

            --body-bg: #0f172a;
            --navbar-bg: #1e293b;
            --navbar-shadow: rgba(0, 0, 0, 0.3);
            --navbar-border: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --card-bg: #1e293b;
            --card-shadow: rgba(0, 0, 0, 0.2);
            --input-bg: #0f172a;
            --input-border: #334155;
            --input-text: #e2e8f0;
            --dropdown-bg: #1e293b;
            --dropdown-text: #e2e8f0;
            --dropdown-hover: #334155;
            --table-bg: #1e293b;
            --table-stripe: #0f172a;
            --table-border: #334155;
            --table-text: #e2e8f0;
            --modal-bg: #1e293b;
            --modal-text: #e2e8f0;
            --alert-success-bg: #064e3b;
            --alert-success-text: #a7f3d0;
            --alert-danger-bg: #7f1d1d;
            --alert-danger-text: #fecaca;
            --badge-bg: rgba(206, 157, 60, 0.2);
            --badge-text: #f0c95c;
            --btn-outline-border: #334155;
            --divider: #334155;
            --code-bg: #0f172a;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-primary);
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Sidebar Styling */
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            background: var(--sidebar-bg);
            color: var(--sidebar-color);
            transition: all 0.3s;
            height: 100vh;
            position: fixed;
            z-index: 100;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.2) transparent;
        }

        #sidebar::-webkit-scrollbar {
            width: 5px;
        }

        #sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }

        #sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.2);
        }

        /* Desktop collapse: sidebar slides off-screen left */
        #sidebar.sidebar-collapsed {
            margin-left: -260px;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.15);
            /* Slightly darker navy header */
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-logo-wrapper {
            background: #fdfbf5;
            /* Cream base to perfectly melt the image */
            border-radius: 12px;
            padding: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #sidebar .sidebar-header img {
            max-height: 70px;
            width: 100%;
            object-fit: contain;
            mix-blend-mode: multiply;
            /* Blends logo smoothly into the cream wrapper */
        }

        #sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 1rem;
            display: block;
            color: var(--sidebar-color);
            text-decoration: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #sidebar ul li a:hover {
            color: var(--sidebar-active);
            background: var(--sidebar-hover);
        }

        #sidebar ul li.active>a {
            color: var(--sidebar-active);
            background: var(--sidebar-active-bg);
            border-left: 4px solid #fff;
        }

        #sidebar ul li a i {
            width: 25px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Content Styling */
        #content {
            flex: 1;
            min-height: 100vh;
            transition: all 0.3s;
            background-color: var(--body-bg);
            position: relative;
            margin-left: 260px;
            /* Removed z-index: 10 to prevent stacking context issues with modals */
            overflow-x: hidden;
            max-width: calc(100% - 260px);
        }

        /* Desktop: content expands when sidebar is collapsed */
        #content.content-expanded {
            margin-left: 0;
            max-width: 100%;
            width: 100%;
        }

        /* Navbar */
        .navbar {
            padding: 15px 20px;
            background: var(--navbar-bg);
            box-shadow: 0 2px 10px var(--navbar-shadow);
            border-bottom: 1px solid var(--navbar-border);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        .navbar-btn {
            background: transparent;
            border: none;
            font-size: 1.2rem;
            color: var(--text-secondary);
            cursor: pointer;
            transition: color 0.3s;
        }

        .navbar-btn:hover {
            color: #ce9d3c;
        }

        .main-content {
            padding: 30px;
        }

        /* Utility */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px var(--card-shadow);
            margin-bottom: 24px;
            position: relative;
            background: var(--card-bg);
            color: var(--text-primary);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid var(--input-border);
            padding: 10px 15px;
            transition: all 0.3s;
            background-color: var(--input-bg);
            color: var(--input-text);
        }

        .form-control:focus, .form-select:focus {
            border-color: #ce9d3c;
            box-shadow: 0 0 0 3px rgba(206, 157, 60, 0.15);
            background-color: var(--input-bg);
            color: var(--input-text);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* Placeholder text */
        [data-theme="dark"] .form-control::placeholder,
        [data-theme="dark"] textarea::placeholder,
        [data-theme="dark"] .form-select::placeholder {
            color: #94a3b8;
            opacity: 1;
        }
        [data-theme="dark"] .form-control:-ms-input-placeholder,
        [data-theme="dark"] textarea:-ms-input-placeholder {
            color: #94a3b8;
        }

        /* Form helper text & code blocks */
        [data-theme="dark"] .form-text {
            color: #cbd5e1 !important;
        }
        [data-theme="dark"] .form-text strong {
            color: #f1f5f9;
        }
        [data-theme="dark"] code {
            color: #f0abfc;
            background-color: var(--code-bg);
        }
        [data-theme="dark"] .form-text code,
        [data-theme="dark"] code.bg-white {
            background-color: var(--code-bg) !important;
            color: #fbbf24;
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.6);
            z-index: 90;
            top: 0;
            left: 0;
            cursor: pointer;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.show {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* Sidebar close tab  right edge of sidebar */
        #sidebarClose {
            position: fixed;
            top: 50%;
            left: 260px;               /* right edge of sidebar */
            transform: translateY(-50%);
            width: 22px;
            height: 56px;
            background: var(--sidebar-active-bg);
            color: white;
            border: none;
            border-radius: 0 8px 8px 0;
            display: none;             /* hidden by default */
            align-items: center;
            justify-content: center;
            box-shadow: 3px 0 12px rgba(0,0,0,0.25);
            z-index: 101;
            cursor: pointer;
            transition: left 0.3s, background 0.2s;
        }

        #sidebarClose:hover {
            background: #b8882f;
        }

        /* Show tab on desktop when sidebar is visible */
        @media (min-width: 769px) {
            #sidebarClose {
                display: flex;
            }
            /* When sidebar collapses, move tab to left edge */
            #sidebar.sidebar-collapsed + * + #content #sidebarClose,
            #sidebar.sidebar-collapsed ~ #sidebarClose {
                left: 0;
            }
        }

        /*  MOBILE (768px)  */
        @media (max-width: 768px) {
            /* sidebar always hidden off-screen by default on mobile */
            #sidebar {
                margin-left: -260px;
                position: fixed;
            }
            /* sidebar-open class slides it BACK on screen */
            #sidebar.sidebar-open {
                margin-left: 0;
            }
            /* show the close tab on mobile when sidebar is open */
            #sidebar.sidebar-open ~ #sidebarClose,
            #sidebar.sidebar-open #sidebarClose {
                display: flex;
            }
            /* content always full width on mobile */
            #content {
                margin-left: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }
        /* Fix for Bootstrap Modals Stacking Context */
        .modal {
            z-index: 2000 !important;
        }
        .modal-backdrop {
            z-index: 1900 !important;
        }

        /* ===== DARK MODE OVERRIDES ===== */

        /* Dropdown menus */
        [data-theme="dark"] .dropdown-menu {
            background-color: var(--dropdown-bg);
            border-color: var(--divider);
        }
        [data-theme="dark"] .dropdown-item {
            color: var(--dropdown-text);
        }
        [data-theme="dark"] .dropdown-item:hover,
        [data-theme="dark"] .dropdown-item:focus {
            background-color: var(--dropdown-hover);
            color: var(--text-primary);
        }
        [data-theme="dark"] .dropdown-divider {
            border-color: var(--divider);
        }

        /* Text overrides */
        [data-theme="dark"] .text-dark {
            color: var(--text-primary) !important;
        }
        [data-theme="dark"] .text-muted,
        [data-theme="dark"] .text-secondary {
            color: var(--text-secondary) !important;
        }
        [data-theme="dark"] .text-body {
            color: var(--text-primary) !important;
        }
        [data-theme="dark"] a.text-dark {
            color: var(--text-primary) !important;
        }
        [data-theme="dark"] a.text-dark:hover {
            color: #ce9d3c !important;
        }
        [data-theme="dark"] .text-decoration-none {
            color: var(--text-primary);
        }

        /* Tables */
        [data-theme="dark"] .table {
            color: var(--table-text);
            --bs-table-bg: var(--table-bg);
            --bs-table-striped-bg: var(--table-stripe);
            --bs-table-hover-bg: var(--dropdown-hover);
            border-color: var(--table-border);
        }
        [data-theme="dark"] .table thead th {
            background-color: var(--table-stripe);
            color: var(--text-secondary);
            border-color: var(--table-border);
        }
        [data-theme="dark"] .table td,
        [data-theme="dark"] .table th {
            border-color: var(--table-border);
        }

        /* Modals */
        [data-theme="dark"] .modal-content {
            background-color: var(--modal-bg);
            color: var(--modal-text);
            border-color: var(--divider);
        }
        [data-theme="dark"] .modal-header {
            border-color: var(--divider);
        }
        [data-theme="dark"] .modal-footer {
            border-color: var(--divider);
        }
        [data-theme="dark"] .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* Alerts */
        [data-theme="dark"] .alert-success {
            background-color: var(--alert-success-bg);
            color: var(--alert-success-text);
            border-color: rgba(16, 185, 129, 0.3);
        }
        [data-theme="dark"] .alert-danger {
            background-color: var(--alert-danger-bg);
            color: var(--alert-danger-text);
            border-color: rgba(239, 68, 68, 0.3);
        }

        /* Cards & misc backgrounds */
        [data-theme="dark"] .bg-white {
            background-color: var(--card-bg) !important;
        }
        [data-theme="dark"] .bg-light {
            background-color: var(--table-stripe) !important;
            color: var(--text-primary) !important;
            border-color: var(--divider) !important;
        }
        [data-theme="dark"] .card-header {
            background-color: var(--table-stripe);
            border-bottom-color: var(--divider);
            color: var(--text-primary);
        }
        [data-theme="dark"] .card-footer {
            background-color: var(--table-stripe);
            border-top-color: var(--divider);
        }
        [data-theme="dark"] .list-group-item {
            background-color: var(--card-bg);
            color: var(--text-primary);
            border-color: var(--divider);
        }

        /* Text Overrides for Dark Mode */
        [data-theme="dark"] .text-dark {
            color: #f1f5f9 !important;
        }
        [data-theme="dark"] .text-muted {
            color: #94a3b8 !important;
        }
        [data-theme="dark"] .fw-semibold {
            color: #f1f5f9 !important;
        }

        /* Table & Table Cell Dark Mode Overrides */
        [data-theme="dark"] .table {
            color: #f1f5f9 !important;
            --bs-table-color: #f1f5f9 !important;
            --bs-table-bg: transparent !important;
            --bs-table-hover-color: #ffffff !important;
            --bs-table-hover-bg: rgba(255, 255, 255, 0.05) !important;
            border-color: var(--divider) !important;
        }
        [data-theme="dark"] .table td,
        [data-theme="dark"] .table th {
            color: #f1f5f9 !important;
            border-color: var(--divider) !important;
        }

        /* Segment Passage & Shared Content Dark Mode Box */
        .segment-passage-box {
            background: #ffffff;
            color: #1e293b;
            padding: 30px;
            border-radius: 12px;
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            font-size: 1.15rem;
            line-height: 1.8;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }
        [data-theme="dark"] .segment-passage-box {
            background: #0f172a !important;
            color: #f1f5f9 !important;
            border-color: #334155 !important;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.3) !important;
        }

        /* Welcome Banner Styling for Student Dashboard */
        .welcome-banner {
            background: linear-gradient(135deg, #0d1624 0%, #1a2a44 100%);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
        }
        [data-theme="dark"] .welcome-banner {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
            color: #f1f5f9 !important;
            border: 1px solid #334155 !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4) !important;
        }
        .avatar-box {
            width: 65px;
            height: 65px;
            background: #ce9d3c;
            color: #0f172a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            box-shadow: 0 4px 14px rgba(206, 157, 60, 0.4);
        }

        /* Sidebar Logo Wrapper Dark Mode Fix */
        [data-theme="dark"] .sidebar-logo-wrapper {
            background: #1e293b !important;
            border: 1px solid #334155;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        }
        [data-theme="dark"] #sidebar .sidebar-header img {
            mix-blend-mode: normal !important;
            filter: brightness(1.1);
        }

        /* Borders */
        [data-theme="dark"] .border,
        [data-theme="dark"] .border-bottom,
        [data-theme="dark"] .border-top,
        [data-theme="dark"] .border-start,
        [data-theme="dark"] .border-end {
            border-color: var(--divider) !important;
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
        [data-theme="dark"] .page-item.active .page-link {
            background-color: #ce9d3c;
            border-color: #ce9d3c;
            color: #fff;
        }
        [data-theme="dark"] .page-item.disabled .page-link {
            background-color: var(--table-stripe);
            color: var(--text-secondary);
        }

        /* Breadcrumb */
        [data-theme="dark"] .breadcrumb {
            color: var(--text-secondary);
        }
        [data-theme="dark"] .breadcrumb-item a {
            color: var(--text-secondary);
        }
        [data-theme="dark"] .breadcrumb-item.active {
            color: var(--text-primary);
        }

        /* Nav tabs / pills */
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

        /* Badge overrides */
        [data-theme="dark"] .badge.bg-light {
            background-color: var(--table-stripe) !important;
            color: var(--text-primary) !important;
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
        [data-theme="dark"] .accordion-button:not(.collapsed) {
            background-color: var(--table-stripe);
            color: #ce9d3c;
        }
        [data-theme="dark"] .accordion-body {
            background-color: var(--card-bg);
        }

        /* ===== THEME TOGGLE BUTTON ===== */
        .theme-toggle {
            position: relative;
            width: 52px;
            height: 28px;
            border-radius: 14px;
            border: 2px solid var(--input-border);
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            cursor: pointer;
            padding: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
        }

        [data-theme="dark"] .theme-toggle {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-color: #475569;
        }

        .theme-toggle-thumb {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #fbbf24;
            box-shadow: 0 2px 8px rgba(251, 191, 36, 0.4);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        [data-theme="dark"] .theme-toggle-thumb {
            left: 26px;
            background: #e2e8f0;
            box-shadow: 0 2px 8px rgba(148, 163, 184, 0.4);
        }

        .theme-toggle-icon {
            font-size: 10px;
            line-height: 1;
            transition: all 0.3s ease;
        }

        .theme-toggle .sun-icon {
            color: #92400e;
        }

        .theme-toggle .moon-icon {
            display: none;
            color: #475569;
        }

        [data-theme="dark"] .theme-toggle .sun-icon {
            display: none;
        }

        [data-theme="dark"] .theme-toggle .moon-icon {
            display: inline;
        }

        .theme-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 0 12px rgba(206, 157, 60, 0.25);
        }

        .theme-toggle:active {
            transform: scale(0.95);
        }

        /* Stars decoration in dark mode */
        .theme-toggle::before,
        .theme-toggle::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: transparent;
            transition: all 0.4s ease;
        }

        [data-theme="dark"] .theme-toggle::before {
            width: 3px;
            height: 3px;
            top: 5px;
            left: 8px;
            background: #fbbf24;
            box-shadow: 6px 8px 0 0.5px #fbbf24, 12px 2px 0 0.5px #fbbf24;
        }

        .theme-toggle-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
            user-select: none;
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar Close Tab (fixed to right edge of sidebar) -->
    <button type="button" id="sidebarClose" title="Close sidebar" style="display: none;">
        <i class="fas fa-chevron-left" style="font-size: 0.9rem;"></i>
    </button>

    <div class="wrapper d-flex">

        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header position-relative">
                <div class="sidebar-logo-wrapper">
                    <img src="{{ asset('images/opera-dark-logo.webp') }}" alt="IELTS System Logo">
                </div>
            </div>

            <ul class="list-unstyled components">
                <p class="px-3 text-uppercase mb-1 mt-3"
                    style="font-size: 1.2rem; font-weight: 900; color: #e63946; letter-spacing: 1.5px; border-left: 4px solid #e63946; padding-left: 12px; margin-left: 15px;">IELTS Portal
                </p>
                <p class="px-3 text-uppercase mb-2 mt-3"
                    style="font-size: 0.75rem; font-weight: 700; color: #ce9d3c; letter-spacing: 1px;">Main Navigation
                </p>
                @if (auth('student')->check())
                    {{-- Student Specific Navigation --}}
                    <li class="{{ request()->is('student/dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('student.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li class="{{ request()->is('student/take-test*') ? 'active' : '' }}">
                        <a href="{{ route('student.tests.take') }}"><i class="fas fa-play-circle"></i> Take Test</a>
                    </li>
                    <li class="{{ request()->is('student/my-tests*') ? 'active' : '' }}">
                        <a href="{{ route('student.tests.index') }}"><i class="fas fa-file-alt"></i> My Tests</a>
                    </li>
                @elseif (auth('web')->check())
                    {{-- Admin Specific Navigation --}}
                    <li class="{{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                    </li>

                    <p class="px-3 text-uppercase mb-2 mt-4"
                        style="font-size: 0.75rem; font-weight: 700; color: #ce9d3c; letter-spacing: 1px;">Test Management
                    </p>
                    <li class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
                        <a href="{{ route('admin.categories.index') }}"><i class="fas fa-cubes"></i> Modules</a>
                    </li>
                    <li class="{{ request()->is('admin/test-types*') ? 'active' : '' }}">
                        <a href="{{ route('admin.test-types.index') }}"><i class="fas fa-tags"></i> Test Types</a>
                    </li>
                    <li class="{{ request()->is('admin/levels*') ? 'active' : '' }}">
                        <a href="{{ route('admin.levels.index') }}"><i class="fas fa-layer-group"></i> Levels</a>
                    </li>
                    <li class="{{ request()->is('admin/module-sets*') ? 'active' : '' }}">
                        <a href="{{ route('admin.module-sets.index') }}">
                            <i class="fas fa-archive"></i> Module Portfolios 
                            <span class="badge bg-info bg-opacity-10 text-info ms-auto" style="font-size: 0.65rem;">Exam Batch</span>
                        </a>
                    </li>
                    @php
                        $isQuestionBankActive = request()->is('admin/question-groups*') || 
                                               request()->is('admin/listening-tests*') || 
                                               request()->is('admin/listening-parts*') || 
                                               request()->is('admin/listening-questions*') || 
                                               request()->is('admin/speaking-tests*') || 
                                               (request()->is('admin/tests*') && request()->query('category') === 'speaking');
                        
                        $isListeningActive = request()->is('admin/listening-tests*') || 
                                             request()->is('admin/listening-parts*') || 
                                             request()->is('admin/listening-questions*') || 
                                             (request()->is('admin/question-groups*') && request()->query('category') === 'listening');
                        
                        $isReadingActive = request()->is('admin/question-groups*') && request()->query('category') === 'reading';
                        $isWritingActive = request()->is('admin/question-groups*') && request()->query('category') === 'writing';
                        
                        $isSpeakingActive = request()->is('admin/speaking-tests*') || 
                                            (request()->is('admin/tests*') && request()->query('category') === 'speaking') || 
                                            (request()->is('admin/question-groups*') && request()->query('category') === 'speaking');
                    @endphp
                    <li class="{{ request()->is('admin/tests*') && request()->query('category') !== 'speaking' ? 'active' : '' }}">
                        <a href="{{ route('admin.tests.index') }}"><i class="fas fa-vial"></i> Mock Tests</a>
                    </li>
                    <li class="{{ request()->is('admin/import*') ? 'active' : '' }}">
                        <a href="{{ route('admin.import.create') }}"><i class="fas fa-file-import"></i> Import Test (Auto)</a>
                    </li>
                    <li class="{{ request()->is('admin/exam-timing*') ? 'active' : '' }}">
                        <a href="{{ route('admin.exam-timing.edit') }}"><i class="fas fa-clock"></i> Exam Timing</a>
                    </li>
                    
                    <li class="{{ $isQuestionBankActive ? 'active' : '' }}">
                        <a href="#questionSubmenu" data-bs-toggle="collapse" aria-expanded="{{ $isQuestionBankActive ? 'true' : 'false' }}" class="dropdown-toggle">
                            <i class="fas fa-question-circle"></i> Question Bank
                        </a>
                        <ul class="collapse list-unstyled ps-4 {{ $isQuestionBankActive ? 'show' : '' }}" id="questionSubmenu">
                            <li class="{{ $isListeningActive ? 'active' : '' }}"><a href="{{ route('admin.question-groups.index', ['category' => 'listening']) }}"><i class="fas fa-headphones me-2"></i> Listening</a></li>
                            <li class="{{ $isReadingActive ? 'active' : '' }}"><a href="{{ route('admin.question-groups.index', ['category' => 'reading']) }}"><i class="fas fa-book-open me-2"></i> Reading</a></li>
                            <li class="{{ $isWritingActive ? 'active' : '' }}"><a href="{{ route('admin.question-groups.index', ['category' => 'writing']) }}"><i class="fas fa-pen-nib me-2"></i> Writing</a></li>
                            <li class="{{ $isSpeakingActive ? 'active' : '' }}"><a href="{{ route('admin.question-groups.index', ['category' => 'speaking']) }}"><i class="fas fa-comment-dots me-2"></i> Speaking</a></li>
                        </ul>
                    </li>

                    <p class="px-3 text-uppercase mb-2 mt-4"
                        style="font-size: 0.75rem; font-weight: 700; color: #ce9d3c; letter-spacing: 1px;">User Management
                    </p>
                    <li class="{{ request()->is('admin/students*') ? 'active' : '' }}">
                        <a href="{{ route('admin.students.index') }}"><i class="fas fa-users"></i> Students</a>
                    </li>
                    <li class="{{ request()->is('admin/results*') ? 'active' : '' }}">
                        <a href="{{ route('admin.results.index') }}"><i class="fas fa-chart-bar"></i> Results & Performance</a>
                    </li>
                    <li class="{{ request()->is('admin/question-reports*') ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-flag"></i> Question Reports</a>
                    </li>
                @endif
            </ul>

            <ul class="list-unstyled CTAs px-3 mt-4">
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-light w-100 text-start"
                        style="border-color: rgba(255,255,255,0.2);">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">

            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg d-flex justify-content-between align-items-center">
                <button type="button" id="sidebarCollapse" class="navbar-btn">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="d-flex align-items-center gap-3">
                    {{-- Theme Toggle --}}
                    <div class="d-flex align-items-center gap-2">
                        <span class="theme-toggle-label" id="themeLabel">Light</span>
                        <button type="button" class="theme-toggle" id="themeToggle" title="Toggle dark mode" aria-label="Toggle dark mode">
                            <span class="theme-toggle-thumb">
                                <span class="theme-toggle-icon sun-icon"><i class="fas fa-sun"></i></span>
                                <span class="theme-toggle-icon moon-icon"><i class="fas fa-moon"></i></span>
                            </span>
                        </button>
                    </div>

                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                            id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                            <span style="font-weight: 500;">
                                @if (auth('student')->check())
                                    {{ auth('student')->user()->name }}
                                @elseif (auth('web')->check())
                                    {{ auth('web')->user()->name }}
                                @else
                                    User
                                @endif
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                            <li>
                                @if (auth('student')->check())
                                    <a class="dropdown-item" href="{{ route('student.profile') }}">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                                    </a>
                                @elseif (auth('web')->check())
                                    <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                                    </a>
                                @endif
                            </li>
                            <li><a class="dropdown-item" href="#"><i
                                        class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                        class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="main-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btn      = document.getElementById('sidebarCollapse');
            const closeBtn = document.getElementById('sidebarClose');
            const overlay  = document.getElementById('sidebarOverlay');
            const sidebar  = document.getElementById('sidebar');
            const content  = document.getElementById('content');
            const isMobile = () => window.innerWidth <= 768;

            //  DESKTOP 
            function desktopCollapse() {
                sidebar.classList.add('sidebar-collapsed');
                content.classList.add('content-expanded');
                // Move tab to left edge so user can re-open
                if (closeBtn) closeBtn.style.left = '0px';
            }
            function desktopExpand() {
                sidebar.classList.remove('sidebar-collapsed');
                content.classList.remove('content-expanded');
                // Move tab back to right edge of sidebar
                if (closeBtn) closeBtn.style.left = '260px';
            }

            //  MOBILE 
            function mobileOpen() {
                sidebar.classList.add('sidebar-open');
                overlay.classList.add('show');
            }
            function mobileClose() {
                sidebar.classList.remove('sidebar-open');
                overlay.classList.remove('show');
            }

            //  Hamburger 
            btn.addEventListener('click', function () {
                if (isMobile()) {
                    sidebar.classList.contains('sidebar-open') ? mobileClose() : mobileOpen();
                } else {
                    sidebar.classList.contains('sidebar-collapsed') ? desktopExpand() : desktopCollapse();
                }
            });

            //  Sidebar close tab 
            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    if (isMobile()) {
                        mobileClose();   // on mobile: close the overlay
                    } else {
                        // on desktop: tab acts as a toggle
                        sidebar.classList.contains('sidebar-collapsed') ? desktopExpand() : desktopCollapse();
                    }
                });
            }

            //  Overlay click 
            overlay.addEventListener('click', mobileClose);

            //  Resize cleanup 
            window.addEventListener('resize', function () {
                if (!isMobile()) {
                    sidebar.classList.remove('sidebar-open');
                    overlay.classList.remove('show');
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    content.classList.remove('content-expanded');
                    if (closeBtn) closeBtn.style.left = '';
                }
            });

            // ===== THEME TOGGLE =====
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

            // Apply saved theme (already set via inline script in <head>, but update label)
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
    </script>
    @stack('scripts')
</body>

</html>

