@extends('layouts.app')

@section('sidebar')
    @if(auth()->user()->role === 'admin')
        @include('admin.partials.admin-sidebar')
    @elseif(auth()->user()->role === 'gestionnaire')
        @include('gestionnaire.partials.sidebar_gestionnaire')
    @else
        @include('magasin.partials.sidebar')
    @endif
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64">
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-bell mr-2 text-blue-500"></i>All Notifications
                </h1>
                <div class="flex space-x-2">
                    <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Mark All as Read
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Filter Buttons -->
        <div class="mb-4 flex space-x-2 overflow-x-auto pb-2">
            <a href="{{ route('notifications.index', ['filter' => 'all']) }}"
               class="px-3 py-1 text-xs rounded-full {{ request('filter') === 'all' || !request('filter') ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                All
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'product']) }}"
               class="px-3 py-1 text-xs rounded-full {{ request('filter') === 'product' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                Products
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'sale']) }}"
               class="px-3 py-1 text-xs rounded-full {{ request('filter') === 'sale' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                Sales
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'purchase']) }}"
               class="px-3 py-1 text-xs rounded-full {{ request('filter') === 'purchase' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                Purchases
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
               class="px-3 py-1 text-xs rounded-full {{ request('filter') === 'unread' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                Unread Only
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <ul class="divide-y divide-gray-200">
                @forelse($notifications as $notification)
                    <li class="px-6 py-4 hover:bg-gray-50 {{ $notification->read ? '' : 'bg-blue-50' }}" id="notification-{{ $notification->id }}">
                        <div class="flex justify-between items-start">
                            <div class="flex items-start w-full">
                                @if(!$notification->read)
                                    <span class="mt-1 mr-3 h-2 w-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                                @endif
                                <div class="flex-grow">
                                    <div class="flex items-center">
                                        @if($notification->type === 'product')
                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-2">Product</span>
                                        @elseif($notification->type === 'sale')
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded mr-2">Sale</span>
                                        @elseif($notification->type === 'purchase')
                                            <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded mr-2">Purchase</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded mr-2">System</span>
                                        @endif
                                        <p class="text-sm font-medium {{ $notification->read ? 'text-gray-600' : 'text-gray-900' }}">
                                            {{ $notification->message }}
                                        </p>
                                    </div>
                                    <div class="mt-1">
                                        <span class="text-xs text-gray-500">
                                            {{ $notification->created_at->format('M j, Y \a\t g:i a') }}
                                            @if($notification->actionUser)
                                                • By {{ $notification->actionUser->name }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                @if(!$notification->read)
                                    <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                                            Mark as read
                                        </button>
                                    </form>
                                @endif
                                <button onclick="showDeleteModal({{ $notification->id }})"
                                        class="text-xs text-red-600 hover:text-red-800">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-6 py-4 text-center text-gray-500">
                        No notifications found
                    </li>
                @endforelse
            </ul>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex flex-col items-center">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Delete Notification</h3>
            <div class="mt-2 text-center">
                <p class="text-sm text-gray-500">Are you sure you want to delete this notification? This action cannot be undone.</p>
            </div>
            <div class="mt-6 flex justify-center space-x-4">
                <button type="button" onclick="hideDeleteModal()" class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="button" id="confirmDeleteBtn" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });
    });
</script>
@endif

<script>
    let currentNotificationId = null;

    function showDeleteModal(notificationId) {
        currentNotificationId = notificationId;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function hideDeleteModal() {
        currentNotificationId = null;
        document.getElementById('deleteModal').classList.add('hidden');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!currentNotificationId) return;

        fetch(`/notifications/${currentNotificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove notification from DOM
                const notificationElement = document.getElementById(`notification-${currentNotificationId}`);
                if (notificationElement) {
                    notificationElement.remove();
                }

                // Show success message
                Swal.fire({
                    title: 'Deleted!',
                    text: 'The notification has been deleted.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });
            }
            hideDeleteModal();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while deleting the notification.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
            hideDeleteModal();
        });
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            hideDeleteModal();
        }
    });
</script>
@endsection
