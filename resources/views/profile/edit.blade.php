@extends('layouts.app')

@section('sidebar')
    @include('admin.partials.admin-sidebar')
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64">
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-user-edit mr-2 text-blue-500"></i>Edit Profile
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 ml-10">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="px-6 py-5">
                @csrf
                @method('PUT')

                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-24 w-24 relative">
                            @if(auth()->user()->image && auth()->user()->image != 'no_image.jpg')
                                <img class="h-24 w-24 rounded-full object-cover border-4 border-blue-100" 
                                    src="{{ asset('storage/profile_images/'.auth()->user()->image) }}" 
                                    alt="{{ auth()->user()->name }}">
                            @else
                                <div class="h-24 w-24 rounded-full bg-blue-600 flex items-center justify-center text-white text-3xl font-bold border-4 border-blue-100">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <label for="image" class="absolute bottom-0 right-0 bg-blue-600 rounded-full p-2 cursor-pointer hover:bg-blue-700 transition-colors">
                                <i class="fas fa-camera text-white"></i>
                                <input type="file" class="hidden" id="image" name="image" accept="image/*">
                            </label>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-medium text-gray-900">Update Profile Information</h3>
                            <p class="mt-1 text-sm text-gray-500">Update your account's profile information and email address.</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5 space-y-6">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user mr-1 text-gray-500"></i>Full Name
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        <div class="col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-envelope mr-1 text-gray-500"></i>Email Address
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-lock mr-1 text-gray-500"></i>Current Password
                            </label>
                            <input type="password" name="current_password" id="current_password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-key mr-1 text-gray-500"></i>New Password
                            </label>
                            <input type="password" name="new_password" id="new_password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        <div class="col-span-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-check-circle mr-1 text-gray-500"></i>Confirm New Password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('profile.show') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
