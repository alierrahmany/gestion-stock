<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Gestion de Stock | {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📦</text></svg>">
    
    <!-- Fonts and Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #eef2ff;
            --secondary-color: #2b2d42;
            --accent-color: #ef476f;
            --success-color: #06d6a0;
            --warning-color: #ffd166;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --border-radius: 10px;
            --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
            --transition: all 0.25s ease-in-out;
            
            /* Sidebar variables */
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 80px;
            --sidebar-bg: linear-gradient(135deg, #2b3244 0%, #1e293b 100%);
            --sidebar-hover: rgba(255, 255, 255, 0.05);
            --sidebar-active: rgba(67, 97, 238, 0.2);
            --sidebar-icon-bg: rgba(255, 255, 255, 0.08);
            --sidebar-text: rgba(255, 255, 255, 0.85);
            --sidebar-text-dim: rgba(255, 255, 255, 0.6);
            --sidebar-accent: #4361ee;
            --transition-speed: 0.3s;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Navbar styling */
        .navbar {
            box-shadow: var(--box-shadow);
            background: var(--secondary-color);
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            z-index: 1020;
            position: relative;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
            letter-spacing: -0.5px;
        }
        
        .navbar-brand::before {
            content: "📦";
            margin-right: 12px;
            font-size: 1.8rem;
        }
        
        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: var(--transition);
            border-radius: 6px;
            margin: 0 4px;
        }
        
        .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
            padding: 0.75rem 0;
            min-width: 200px;
            margin-top: 10px;
        }
        
        .dropdown-item {
            padding: 0.6rem 1.5rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
        }
        
        .dropdown-item:hover {
            background-color: var(--primary-light);
            color: var(--primary-color) !important;
            transform: translateX(5px);
        }
        
        .dropdown-item i {
            margin-right: 10px;
            font-size: 1.05rem;
        }
        
        .dropdown-divider {
            margin: 0.5rem 0;
            opacity: 0.1;
        }
        
        .badge {
            font-size: 0.65rem;
            font-weight: 600;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Main container */
        .main-container {
            display: flex;
            flex: 1;
            margin-top: 56px; /* Navbar height */
        }
        
        /* Sidebar styling */
        .sidebar {
            position: fixed;
            top: 56px; /* Below navbar */
            left: 0;
            height: calc(100vh - 56px);
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            transition: all var(--transition-speed) ease;
            z-index: 1010;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar-header {
            padding: 1.5rem 1rem 0.5rem;
        }
        
        .sidebar-logo {
            min-width: 40px;
            height: 40px;
            background: linear-gradient(45deg, var(--sidebar-accent), #3a0ca3);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }
        
        .sidebar-logo i {
            font-size: 20px;
            color: white;
        }
        
        .sidebar-title {
            color: var(--sidebar-text);
            font-weight: 600;
            letter-spacing: 1px;
            font-size: 0.85rem;
            margin-bottom: 0;
            white-space: nowrap;
        }
        
        .sidebar-divider {
            height: 2px;
            background: linear-gradient(90deg, var(--sidebar-accent), transparent);
            border-radius: 2px;
        }
        
        .sidebar-nav {
            padding: 1rem 0.5rem;
            flex-grow: 1;
        }
        
        .sidebar-item {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-link {
            color: var(--sidebar-text-dim) !important;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all var(--transition-speed) ease;
            white-space: nowrap;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .sidebar-icon {
            min-width: 36px;
            height: 36px;
            background: var(--sidebar-icon-bg);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            transition: all var(--transition-speed) ease;
        }
        
        .sidebar-icon i {
            font-size: 18px;
            color: var(--sidebar-text);
            transition: all var(--transition-speed) ease;
        }
        
        .sidebar-link.active {
            background: var(--sidebar-active);
            color: white !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-link.active .sidebar-icon {
            background: var(--sidebar-accent);
        }
        
        .sidebar-link.active .sidebar-icon i {
            color: white;
        }
        
        .sidebar-link:hover:not(.active) {
            background: var(--sidebar-hover);
            transform: translateX(5px);
        }
        
        .sidebar-link:hover .sidebar-icon {
            transform: scale(1.1);
        }
        
        /* Collapsed state styles */
        .sidebar-collapsed .menu-text, 
        .sidebar-collapsed .sidebar-title {
            display: none;
        }
        
        .sidebar-collapsed .sidebar-icon {
            margin-right: 0;
        }
        
        .sidebar-collapsed .sidebar-link {
            padding: 0.75rem 0;
            display: flex;
            justify-content: center;
        }
        
        .sidebar-collapsed .sidebar-header {
            display: flex;
            justify-content: center;
            padding-bottom: 1rem;
        }
        
        .sidebar-collapsed .sidebar-divider {
            width: 40%;
            margin: 0 auto;
        }
        
        /* Sidebar footer */
        .sidebar-footer {
            padding: 1rem;
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .sidebar-collapse-btn {
            cursor: pointer;
            color: var(--sidebar-text-dim);
            padding: 0.5rem;
            border-radius: 8px;
            transition: all var(--transition-speed) ease;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .sidebar-collapse-btn:hover {
            background: var(--sidebar-hover);
            color: var(--sidebar-text);
        }
        
        .sidebar-collapsed .sidebar-collapse-btn .bi-chevron-left {
            transform: rotate(180deg);
        }
        
        /* Main content area */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin var(--transition-speed) ease;
            padding: 1.5rem;
            background-color: #f9fafb;
            min-height: calc(100vh - 56px);
        }
        
        .sidebar-collapsed .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        /* Alert styling */
        .alert {
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .alert i {
            font-size: 1.2rem;
            margin-right: 10px;
        }
        
        /* Animation for alerts */
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert {
            animation: slideIn 0.4s ease-out;
        }
        
        /* Card styling */
        .card {
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            border: none;
            margin-bottom: 1.5rem;
        }
        
        .card:hover {
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            border-top-left-radius: var(--border-radius) !important;
            border-top-right-radius: var(--border-radius) !important;
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.5rem;
        }
        
        .card-title {
            font-weight: 600;
            margin-bottom: 0;
        }
        
        /* Button styling */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: var(--transition);
            border-radius: var(--border-radius);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: #3a56d4;
            border-color: #3a56d4;
        }
        
        /* Profile styling */
        .profile-initial {
            font-weight: bold;
        }
        
        /* Profile picture container */
        .profile-picture {
            position: relative;
            display: inline-flex;
        }
        
        /* Mobile toggle */
        .mobile-menu-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--sidebar-accent);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1020;
            display: none;
        }
        
        .mobile-menu-toggle i {
            font-size: 24px;
        }
        
        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1005;
            display: none;
        }
        
        .toggle-btn {
            background: transparent;
            border: none;
            color: var(--sidebar-text);
            font-size: 1.25rem;
            padding: 0;
        }
        
        /* Media queries */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1030;
                top: 0;
                height: 100vh;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            body.sidebar-mobile-open .sidebar {
                transform: translateX(0);
            }
            
            body.sidebar-mobile-open .sidebar-overlay {
                display: block;
            }
            
            .mobile-menu-toggle {
                display: flex;
            }
        }
        
        /* Table styling */
        .table {
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .table th {
            background-color: var(--primary-light);
            font-weight: 600;
            border-top: none;
        }
        
        /* Form styling */
        .form-control, .form-select {
            border-radius: var(--border-radius);
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        /* Badge styling */
        .badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gestion de Stock</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <!-- Profile Image with Role Badge -->
                                <div class="position-relative me-2">
                                    @if(Auth::user()->image && Auth::user()->image != 'no_image.jpg')
                                        <img src="{{ asset('storage/profile_images/'.Auth::user()->image) }}" 
                                             alt="Profile" 
                                             class="rounded-circle border border-2 border-white" 
                                             width="36" 
                                             height="36"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 36px; height: 36px;">
                                            <span class="text-white small profile-initial">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <span class="position-absolute bottom-0 end-0 translate-middle badge rounded-pill bg-{{ Auth::user()->role === 'admin' ? 'danger' : (Auth::user()->role === 'gestionnaire' ? 'warning' : 'primary') }}">
                                        {{ substr(Auth::user()->role, 0, 1) }}
                                    </span>
                                </div>
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="bi bi-person"></i> View Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="sidebar-logo">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h5 class="sidebar-title ms-3">ADMINISTRATION</h5>
                    </div>
                    <button id="sidebarToggler" class="btn toggle-btn d-block d-md-none">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="sidebar-divider mt-3"></div>
            </div>
            
            <div class="sidebar-nav">
                <div class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <div class="sidebar-icon">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <span class="menu-text">Tableau de bord</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}" 
                       href="{{ route('users.index') }}">
                        <div class="sidebar-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <span class="menu-text">Gestion Utilisateurs</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" 
                       href="{{ route('categories.index') }}">
                        <div class="sidebar-icon">
                            <i class="bi bi-tags-fill"></i>
                        </div>
                        <span class="menu-text">Catégories</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" 
                       href="{{ route('suppliers.index') }}">
                        <div class="sidebar-icon">
                            <i class="bi bi-truck-fill"></i>
                        </div>
                        <span class="menu-text">Fournisseurs</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}" 
                       href="{{ route('products.index') }}">
                        <div class="sidebar-icon">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <span class="menu-text">Produits</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" 
                       href="{{ route('sales.index') }}">
                        <div class="sidebar-icon">
                            <i class="bi bi-cart-check-fill"></i>
                        </div>
                        <span class="menu-text">Ventes</span>
                    </a>
                </div>
                <div class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" 
                       href="{{ route('reports.index') }}">
                        <div class="sidebar-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <span class="menu-text">Rapports</span>
                    </a>
                </div>
            </div>
            
            <div class="sidebar-footer">
                <a href="#" class="sidebar-collapse-btn" id="sidebarCollapseBtn">
                    <div class="sidebar-icon">
                        <i class="bi bi-chevron-left"></i>
                    </div>
                    <span class="menu-text">Réduire</span>
                </a>
            </div>
        </div>
        
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <button class="mobile-menu-toggle d-md-none" id="mobileMenuToggle">
            <i class="bi bi-list"></i>
        </button>

        <!-- Main content -->
        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sidebar collapse functionality
        const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
        const body = document.body;
        
        sidebarCollapseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            body.classList.toggle('sidebar-collapsed');
            
            // Store state in localStorage
            if (body.classList.contains('sidebar-collapsed')) {
                localStorage.setItem('sidebar-collapsed', 'true');
            } else {
                localStorage.setItem('sidebar-collapsed', 'false');
            }
        });
        
        // Check localStorage on page load
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            body.classList.add('sidebar-collapsed');
        }
        
        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggler = document.getElementById('sidebarToggler');
        
        mobileMenuToggle.addEventListener('click', function() {
            body.classList.add('sidebar-mobile-open');
        });
        
        sidebarOverlay.addEventListener('click', function() {
            body.classList.remove('sidebar-mobile-open');
        });
        
        if (sidebarToggler) {
            sidebarToggler.addEventListener('click', function() {
                body.classList.remove('sidebar-mobile-open');
            });
        }
        
        // Close sidebar when clicking on a link in mobile view
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    body.classList.remove('sidebar-mobile-open');
                }
            });
        });
    });
    </script>
</body>
</html>