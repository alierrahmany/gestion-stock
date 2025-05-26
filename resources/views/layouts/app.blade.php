<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <!-- Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex justify-between items-center h-16 px-6">
                    <div class="flex items-center ml-10">  <!-- ml-4 pour déplacer vers la droite -->
                        <!-- Logo avec taille légèrement augmentée (h-12 au lieu de h-10) -->
                        <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="h-20">
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Notifications Dropdown - Only for Admin -->
                        @if(auth()->user()->role === 'admin')
                        <div class="relative">
                            <button id="notification-menu-button" type="button"
                                    class="relative inline-flex items-center p-2 text-gray-600 hover:text-gray-700 focus:outline-none">
                                <span class="sr-only">Notifications</span>
                                <i class="fas fa-bell text-xl"></i>
                                @php
                                    $unreadCount = \App\Models\Notification::where('read', false)->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="notification-badge absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Notifications Dropdown Panel -->
                            <div id="notification-menu"
                                class="hidden origin-top-right absolute right-0 mt-2 w-96 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50 max-h-[500px] overflow-y-auto"
                                role="menu"
                                aria-orientation="vertical"
                                aria-labelledby="notification-menu-button"
                                tabindex="-1">
                                <!-- Filter Buttons -->
                                <div class="sticky top-0 bg-white px-4 py-2 border-b flex space-x-2 overflow-x-auto">
                                    <button class="filter-btn active px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800" data-filter="all">Toutes</button>
                                    <button class="filter-btn px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800" data-filter="product">Produits</button>
                                    <button class="filter-btn px-3 py-1 text-xs rounded-full bg-green-100 text-green-800" data-filter="sale">Ventes</button>
                                    <button class="filter-btn px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-800" data-filter="purchase">Achats</button>
                                </div>

                                <!-- Notifications List -->
                                <div class="py-1" role="none">
                                    @php
                                        $notificationsQuery = \App\Models\Notification::with('actionUser')->latest();
                                    @endphp

                                    @forelse($notificationsQuery->take(20)->get() as $notification)
                                        <div class="px-4 py-3 hover:bg-gray-100 {{ $notification->read ? '' : 'bg-blue-50' }}"
                                             data-notification-id="{{ $notification->id }}"
                                             data-notification-type="{{ $notification->type }}">
                                            <div class="flex items-start">
                                                @if(!$notification->read)
                                                    <span class="mt-1 mr-2 h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                                                @else
                                                    <span class="mt-1 mr-2 h-2 w-2 rounded-full bg-gray-300 flex-shrink-0"></span>
                                                @endif
                                                <div class="flex-1">
                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <p class="text-sm {{ $notification->read ? 'text-gray-600' : 'text-gray-900 font-medium' }}">
                                                                @if($notification->type === 'product')
                                                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1">Produits</span>
                                                                @elseif($notification->type === 'sale')
                                                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded mr-1">Ventes</span>
                                                                @elseif($notification->type === 'purchase')
                                                                    <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded mr-1">Achats</span>
                                                                @else
                                                                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded mr-1">System</span>
                                                                @endif
                                                                {{ $notification->message }}
                                                            </p>
                                                            <div class="mt-1 flex items-center">
                                                                <span class="text-xs text-gray-500">
                                                                    {{ $notification->created_at->diffForHumans() }}
                                                                    @if($notification->actionUser)
                                                                        • By {{ $notification->actionUser->name }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @if(!$notification->read)
                                                            <button class="mark-as-read text-xs text-blue-600 hover:text-blue-800 ml-2"
                                                                    data-id="{{ $notification->id }}">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-4 py-3 text-sm text-gray-500">
                                            No notifications found
                                        </div>
                                    @endforelse
                                </div>
                                <div class="sticky bottom-0 bg-white border-t border-gray-200 px-4 py-2 text-center">
                                    <form action="{{ route('admin.notifications.mark-all-as-read') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-check-circle mr-1"></i> Mark all as read
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.notifications.index') }}" class="block text-xs text-blue-600 hover:text-blue-800 mt-1">
                                        <i class="fas fa-list mr-1"></i> View all notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- User Profile Dropdown -->
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
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Toggle user dropdown
        document.addEventListener('DOMContentLoaded', function() {
            // User dropdown
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');

            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', function() {
                    userMenu.classList.toggle('hidden');
                });

                // Close when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }

            // Notifications dropdown - Only if admin
            @if(auth()->user()->role === 'admin')
            const notificationButton = document.getElementById('notification-menu-button');
            const notificationMenu = document.getElementById('notification-menu');

            if (notificationButton && notificationMenu) {
                notificationButton.addEventListener('click', function() {
                    notificationMenu.classList.toggle('hidden');
                });

                // Close when clicking outside
                document.addEventListener('click', function(event) {
                    if (!notificationButton.contains(event.target) && !notificationMenu.contains(event.target)) {
                        notificationMenu.classList.add('hidden');
                    }
                });
            }

            // Mark notification as read
            document.querySelectorAll('.mark-as-read').forEach(button => {
                button.addEventListener('click', async function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const notificationId = this.getAttribute('data-id');
                    const url = `/admin/notifications/${notificationId}/mark-as-read`;  // Updated URL

                    try {
                        // Show loading indicator
                        const originalHtml = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Failed to mark as read');
                        }

                        if (data.success) {
                            // Update the UI
                            const notificationElement = this.closest('[data-notification-id]');
                            notificationElement.classList.remove('bg-blue-50');

                            // Update the unread indicator
                            const dot = notificationElement.querySelector('.bg-blue-500');
                            if (dot) {
                                dot.classList.replace('bg-blue-500', 'bg-gray-300');
                            }

                            // Remove the mark-as-read button
                            this.remove();

                            // Update the notification badge count
                            const badge = document.querySelector('.notification-badge');
                            if (badge) {
                                const currentCount = parseInt(badge.textContent);
                                if (currentCount > 1) {
                                    badge.textContent = currentCount - 1;
                                } else {
                                    badge.remove();
                                }
                            }

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Notification marked as read',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            throw new Error(data.message || 'Operation failed');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Failed to mark notification as read'
                        });

                        // Restore button state
                        this.innerHTML = originalHtml;
                    }
                });
            });

            // Mark all as read
            const markAllForm = document.querySelector('form[action="{{ route('admin.notifications.mark-all-as-read') }}"]');
            if (markAllForm) {
                markAllForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    }).then(response => response.json())
                      .then(data => {
                          if (data.success) {
                              // Update all notifications in UI
                              document.querySelectorAll('[data-notification-id]').forEach(item => {
                                  item.classList.remove('bg-blue-50');
                                  const dot = item.querySelector('.bg-blue-500');
                                  if (dot) {
                                      dot.classList.replace('bg-blue-500', 'bg-gray-300');
                                  }
                                  const markButton = item.querySelector('.mark-as-read');
                                  if (markButton) {
                                      markButton.remove();
                                  }
                              });

                              // Remove badge
                              const badge = document.querySelector('.notification-badge');
                              if (badge) {
                                  badge.remove();
                              }
                          }
                      });
                });
            }

            // Filter notifications
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Update active button
                    document.querySelectorAll('.filter-btn').forEach(b => {
                        b.classList.remove('active', 'bg-blue-100', 'text-blue-800');
                        b.classList.add('bg-gray-100', 'text-gray-800');
                    });

                    this.classList.remove('bg-gray-100', 'text-gray-800');
                    this.classList.add('active', 'bg-blue-100', 'text-blue-800');

                    const filter = this.dataset.filter;
                    document.querySelectorAll('[data-notification-id]').forEach(notification => {
                        if (filter === 'all' || notification.dataset.notificationType === filter) {
                            notification.classList.remove('hidden');
                        } else {
                            notification.classList.add('hidden');
                        }
                    });
                });
            });
            @endif
        });
    </script>

    @yield('scripts')
</body>
</html>
