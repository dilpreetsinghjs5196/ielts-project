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
        }

        #sidebar.active {
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
            width: calc(100% - 260px);
            min-height: 100vh;
            transition: all 0.3s;
            position: absolute;
            top: 0;
            right: 0;
        }

        #content.active {
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

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -260px;
                z-index: 100;
            }

            #sidebar.active {
                margin-left: 0;
            }

            .sidebar-overlay.active {
                display: block;
                animation: fadeIn 0.3s ease-in-out;
            }

            #content {
                width: 100%;
            }

            #content.active {
                width: 100%;
            }

            /* Mobile Close Arrow Button */
            #sidebarClose {
                position: absolute;
                top: 20px;
                right: -17px;
                width: 30px;
                height: 30px;
                background: var(--sidebar-active-bg);
                color: white;
                border: none;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
                z-index: 101;
                cursor: pointer;
                transition: all 0.3s;
                opacity: 0;
                pointer-events: none;
            }

            #sidebarClose:hover {
                transform: scale(1.1);
            }

            @media (max-width: 768px) {
                #sidebar.active #sidebarClose {
                    opacity: 1;
                    pointer-events: auto;
                }
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="wrapper d-flex">

        <!-- Sidebar -->
        <nav id="sidebar">
            <!-- Premium Mobile Close Arrow -->
            <button type="button" id="sidebarClose" class="d-md-none">
                <i class="fas fa-chevron-left" style="font-size: 1.1rem;"></i>
            </button>

            <div class="sidebar-header position-relative">
                <div class="sidebar-logo-wrapper">
                    <img src="{{ asset('images/opera-dark-logo.webp') }}" alt="IELTS System Logo">
                </div>
            </div>

            <ul class="list-unstyled components">
                <p class="px-3 text-uppercase mb-2 mt-2"
                    style="font-size: 0.75rem; font-weight: 700; color: #ce9d3c; letter-spacing: 1px;">Main Navigation
                </p>
                <li class="{{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-home"></i> Dashboard</a>
                </li>

                <p class="px-3 text-uppercase mb-2 mt-4"
                    style="font-size: 0.75rem; font-weight: 700; color: #ce9d3c; letter-spacing: 1px;">Test Management
                </p>
                <li class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}"><i class="fas fa-cubes"></i> Modules</a>
                </li>
                <li class="{{ request()->is('admin/types*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-tags"></i> Test Types</a>
                </li>
                <li class="{{ request()->is('admin/levels*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-layer-group"></i> Levels</a>
                </li>
                <li class="{{ request()->is('admin/tests*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-file-alt"></i> Tests</a>
                </li>
                <li class="{{ request()->is('admin/questions*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-question-circle"></i> Question Bank</a>
                </li>

                <p class="px-3 text-uppercase mb-2 mt-4"
                    style="font-size: 0.75rem; font-weight: 700; color: #ce9d3c; letter-spacing: 1px;">User Management
                </p>
                <li class="{{ request()->is('admin/students*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-users"></i> Students</a>
                </li>
                <li class="{{ request()->is('admin/results*') ? 'active' : '' }}">
                    <a href="#"><i class="fas fa-chart-bar"></i> Results & Performance</a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs px-3 mt-4">
                <li>
                    <a href="#" class="btn btn-outline-light w-100 text-start"
                        style="border-color: rgba(255,255,255,0.2);">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
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
                            <span style="font-weight: 500;">Admin User</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                            <li><a class="dropdown-item" href="#"><i
                                        class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i
                                        class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#"><i
                                        class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
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
        document.addEventListener("DOMContentLoaded", function (event) {
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');

            // Open/Close from hamburger menu
            sidebarCollapse.addEventListener('click', function () {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
                content.classList.toggle('active');
            });

            // Close from inside sidebar (mobile X button)
            sidebarClose.addEventListener('click', function () {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                content.classList.remove('active');
            });

            // Close when clicking the dark overlay outside the sidebar
            sidebarOverlay.addEventListener('click', function () {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                content.classList.remove('active');
            });
        });
    </script>
    @stack('scripts')
</body>

</html>