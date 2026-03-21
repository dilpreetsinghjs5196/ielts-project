<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - IELTS Test Management System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-bg: #f4f6f9;
            --sidebar-bg: #0d1624;
            /* Deep Navy matching logo background */
            --sidebar-hover: #16243b;
            --sidebar-color: #f8fafc;
            /* Crisp white for better legibility */
            --sidebar-active: #ffffff;
            --sidebar-active-bg: #ce9d3c;
            /* Golden accent matching logo */
            --brand-color: #ffffff;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--primary-bg);
            overflow-x: hidden;
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
            background-color: var(--primary-bg);
            position: relative;
            margin-left: 260px;
            z-index: 10;
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
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #e2e8f0;
        }

        .navbar-btn {
            background: transparent;
            border: none;
            font-size: 1.2rem;
            color: #64748b;
            cursor: pointer;
            transition: color 0.3s;
        }

        .navbar-btn:hover {
            color: #3b82f6;
        }

        .main-content {
            padding: 30px;
        }

        /* Utility */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
            margin-bottom: 24px;
            position: relative;
            background: #fff;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #ce9d3c;
            box-shadow: 0 0 0 3px rgba(206, 157, 60, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
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
                <p class="px-3 text-uppercase mb-2 mt-2"
                    style="font-size: 0.75rem; font-weight: 700; color: #ce9d3c; letter-spacing: 1px;">Main Navigation
                </p>
                @if (auth('web')->check())
                <li class="{{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                </li>
                @elseif (auth('student')->check())
                <li class="{{ request()->is('student/dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('student.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                </li>
                <li class="{{ request()->is('student/take-test*') ? 'active' : '' }}">
                    <a href="{{ route('student.tests.take') }}"><i class="fas fa-play-circle"></i> Take Test</a>
                </li>
                <li class="{{ request()->is('student/my-tests*') ? 'active' : '' }}">
                    <a href="{{ route('student.tests.index') }}"><i class="fas fa-file-alt"></i> My Tests</a>
                </li>
                @endif

                @if (auth('web')->check())
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
                    <a href="{{ route('admin.module-sets.index') }}"><i class="fas fa-archive"></i> Module Portfolios</a>
                </li>
                <li class="{{ request()->is('admin/tests*') ? 'active' : '' }}">
                    <a href="{{ route('admin.tests.index') }}"><i class="fas fa-vial"></i> Mock Tests</a>
                </li>
                <li class="{{ request()->is('admin/question-groups*') ? 'active' : '' }}">
                    <a href="#questionSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->is('admin/question-groups*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <i class="fas fa-question-circle"></i> Question Bank
                    </a>
                    <ul class="collapse list-unstyled ps-4 {{ request()->is('admin/question-groups*') ? 'show' : '' }}" id="questionSubmenu">
                        <li>
                            <a href="{{ route('admin.question-groups.index', ['category' => 'listening']) }}" style="font-size: 0.9rem; padding: 8px 20px;">
                                <i class="fas fa-headphones me-2"></i> Listening
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.question-groups.index', ['category' => 'reading']) }}" style="font-size: 0.9rem; padding: 8px 20px;">
                                <i class="fas fa-book-open me-2"></i> Reading
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.question-groups.index', ['category' => 'writing']) }}" style="font-size: 0.9rem; padding: 8px 20px;">
                                <i class="fas fa-pen-nib me-2"></i> Writing
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.question-groups.index', ['category' => 'speaking']) }}" style="font-size: 0.9rem; padding: 8px 20px;">
                                <i class="fas fa-comment-dots me-2"></i> Speaking
                            </a>
                        </li>
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
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                            id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                            <span style="font-weight: 500;">
                                @if (auth('web')->check())
                                    {{ auth('web')->user()->name }}
                                @elseif (auth('student')->check())
                                    {{ auth('student')->user()->name }}
                                @else
                                    User
                                @endif
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                            <li>
                                @if (auth('web')->check())
                                    <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                                    </a>
                                @elseif (auth('student')->check())
                                    <a class="dropdown-item" href="{{ route('student.profile') }}">
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
        });
    </script>
    @stack('scripts')
</body>

</html>

