@extends('layouts.app')
@section('header-title')
    <i class="fas fa-user-circle text-primary-600 mr-2"></i>Profile Details
@endsection
@section('content')
<div class="min-h-screen bg-gray-50">


    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6 sm:p-8">
                <div class="mb-4">
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') :
                                (auth()->user()->role === 'gestionnaire' ? route('gestionnaire.dashboard') :
                                route('magasin.dashboard')) }}"
                       class="inline-flex items-center text-sm text-primary-600 hover:text-primary-800">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>
                <div class="text-center mb-8">
                    <div class="relative mx-auto" style="width: 150px; height: 150px;">
                        @if($user->image && $user->image != 'no_image.jpg')
                            <img src="{{ asset('storage/profile_images/'.$user->image) }}"
                                 alt="Profile"
                                 class="rounded-full border-4 border-primary-200 shadow-md w-full h-full object-cover">
                        @else
                            <div class="rounded-full bg-primary-600 border-4 border-primary-200 shadow-md w-full h-full flex items-center justify-center">
                                <span class="text-white text-5xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    <h2 class="mt-4 text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-gray-500">{{ ucfirst($user->role) }}</p>
                </div>

                <!-- Account Information -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-id-card text-primary-600 mr-2"></i>Account Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
                            <div class="flex items-center p-3 bg-gray-100 rounded-lg">
                                <i class="fas fa-envelope text-primary-600 mr-3"></i>
                                <span class="text-gray-700">{{ $user->email }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Account Status</label>
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($user->status) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                                <i class="fas @if($user->status) fa-check-circle @else fa-times-circle @endif mr-2"></i>
                                {{ $user->status ? 'Active' : 'Inactive' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Member Since</label>
                            <div class="flex items-center p-3 bg-gray-100 rounded-lg">
                                <i class="fas fa-calendar-alt text-primary-600 mr-3"></i>
                                <span class="text-gray-700">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                            <div class="flex items-center p-3 bg-gray-100 rounded-lg">
                                <i class="fas fa-clock text-primary-600 mr-3"></i>
                                <span class="text-gray-700">{{ $user->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                        <i class="fas fa-edit mr-2"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Toggle user dropdown
    document.getElementById('user-menu-button').addEventListener('click', function() {
        document.getElementById('user-menu').classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('user-menu');
        const button = document.getElementById('user-menu-button');
        if (!button.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
@endsection
