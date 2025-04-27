<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Stock Management</title>
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📦</text></svg>">

    <!-- Fonts and Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Add SweetAlert2 -->
    @yield('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="flex-shrink-0">
            @yield('sidebar')
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header aligned with sidebar -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex justify-between items-center h-16 px-6">
                    <h1 class="text-xl font-bold text-gray-800">
                        @yield('header-title', 'Dashboard')
                    </h1>
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center space-x-2 focus:outline-none">
                            <div class="flex-shrink-0">
                                @if(auth()->user()->image && auth()->user()->image != 'no_image.jpg')
                                    <img class="h-8 w-8 rounded-full" src="{{ asset('storage/profile_images/'.auth()->user()->image) }}" alt="Profile">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-circle mr-2 text-primary-600"></i>Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2 text-red-500"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <nav class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Notifications Dropdown -->
                        <div class="ml-3 relative">
                            <div class="relative inline-block text-left">
                                <button id="notification-menu-button" type="button" 
                                        class="relative inline-flex items-center p-2 text-gray-600 hover:text-gray-700 focus:outline-none">
                                    <span class="sr-only">Notifications</span>
                                    <i class="fas fa-bell text-xl"></i>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </button>

                                <!-- Notifications Dropdown Panel -->
                                <div id="notification-menu" 
                                     class="hidden origin-top-right absolute right-0 mt-2 w-96 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none"
                                     role="menu" 
                                     aria-orientation="vertical" 
                                     aria-labelledby="notification-menu-button"
                                     tabindex="-1">
                                    <div class="py-1" role="none">
                                        @forelse(auth()->user()->unreadNotifications as $notification)
                                            <div class="px-4 py-3 hover:bg-gray-100">
                                                <p class="text-sm text-red-600">
                                                    {{ $notification->data['message'] }}
                                                </p>
                                                <div class="mt-1 flex justify-between items-center">
                                                    <span class="text-xs text-gray-500">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </span>
                                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                                                            Marquer comme lu
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="px-4 py-3 text-sm text-gray-500">
                                                Aucune notification
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Toggle user dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');

            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', function() {
                    userMenu.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('notification-menu-button');
        const menu = document.getElementById('notification-menu');

        button.addEventListener('click', function() {
            menu.classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    });
    </script>
    @endpush

    @yield('scripts')
</body>
</html>
