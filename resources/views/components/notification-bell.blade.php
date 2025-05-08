@php
    $unreadCount = auth()->user()->notifications()->where('read', false)->count();
@endphp

<div class="relative">
    <button id="notificationButton" class="p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
        <i class="fas fa-bell text-xl"></i>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50">
        <div class="py-1">
            @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 notification-item {{ $notification->read ? '' : 'bg-blue-50' }}"
                   data-id="{{ $notification->id }}">
                    {{ $notification->message }}
                    <div class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                </a>
            @empty
                <div class="px-4 py-2 text-sm text-gray-500">No notifications</div>
            @endforelse
            @if(auth()->user()->notifications()->count() > 5)
                <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-sm text-center text-blue-600 hover:bg-gray-100">
                    View all notifications
                </a>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const button = document.getElementById('notificationButton');
    const dropdown = document.getElementById('notificationDropdown');
    const notificationItems = document.querySelectorAll('.notification-item');

    // Toggle dropdown
    button.addEventListener('click', function() {
        dropdown.classList.toggle('hidden');
    });

    // Mark as read when clicked
    notificationItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const notificationId = this.getAttribute('data-id');

            fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    this.classList.remove('bg-blue-50');
                    // Update unread count
                    const badge = button.querySelector('span');
                    if (badge) {
                        const count = parseInt(badge.textContent) - 1;
                        if (count > 0) {
                            badge.textContent = count;
                        } else {
                            badge.remove();
                        }
                    }
                }
            });
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!button.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});
</script>
