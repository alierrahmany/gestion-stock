@extends('layouts.app')

@section('content')
<div class="flex-1 overflow-auto ml-64">
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-bell mr-2 text-blue-500"></i>Notifications
                </h1>
                <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Mark All as Read
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <ul class="divide-y divide-gray-200">
                @forelse($notifications as $notification)
                    <li class="px-6 py-4 hover:bg-gray-50 {{ $notification->read ? '' : 'bg-blue-50' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$notification->read)
                                <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                                        Mark as read
                                    </button>
                                </form>
                            @endif
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
@endsection
