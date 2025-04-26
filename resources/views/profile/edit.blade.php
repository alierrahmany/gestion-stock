@extends('layouts.app')
@section('header-title')
    <i class="fas fa-pencil-alt text-primary-600 mr-2"></i>Edit Profile
@endsection
@section('content')
<div class="min-h-screen bg-gray-50">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-6 sm:p-8">
                    <div class="mb-4">
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') :
                                    (auth()->user()->role === 'gestionnaire' ? route('gestionnaire.dashboard') :
                                    route('magasin.dashboard')) }}"
                           class="inline-flex items-center text-sm text-primary-600 hover:text-primary-800">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                        </a>
                    </div>
                    <div class="md:flex">
                        <div class="md:w-1/3 text-center mb-6 md:mb-0">
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
                                <label for="image" class="absolute bottom-0 right-0 bg-primary-600 rounded-full p-2 shadow-md cursor-pointer hover:bg-primary-700 transition-colors">
                                    <i class="fas fa-camera text-white"></i>
                                    <input type="file" class="hidden" id="image" name="image">
                                </label>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Click camera icon to change photo</p>
                        </div>

                        <div class="md:w-2/3 md:pl-8">
                            <div class="mb-6">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                            </div>

                            <div class="mb-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                            </div>

                            @if($user->isAdmin())
                            <div class="mb-6">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select id="status" name="status"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Password Change Section -->
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-key text-primary-600 mr-2"></i>Change Password (Optional)
                        </h3>

                        <div class="grid grid-cols-1 gap-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                <input type="password" id="current_password" name="current_password"
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input type="password" id="new_password" name="new_password"
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-between border-t border-gray-200 pt-6">
                        <a href="{{ route('profile.show') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                            <i class="fas fa-arrow-left mr-2"></i> Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                            <i class="fas fa-check-circle mr-2"></i> Update Profile
                        </button>
                    </div>
                </div>
            </form>
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
