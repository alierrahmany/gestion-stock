@extends('layouts.app')

@section('sidebar')
    @include('admin.partials.admin-sidebar')
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64">
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-user-circle mr-2 text-blue-500"></i>Profile
            </h1>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                <i class="fas fa-edit mr-2"></i> Edit Profile
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 ml-10">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-24 w-24">
                        @include('shared._avatar', [
                            'name' => auth()->user()->name,
                            'image' => auth()->user()->image,
                            'size' => '24',
                            'class' => 'border-4 border-blue-100'
                        ])
                    </div>
                    <div class="ml-6">
                        <h3 class="text-2xl font-bold text-gray-900">
                    </div>
                    <div class="ml-6">
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ auth()->user()->name }}
                        </h3>
                        <div class="mt-1 flex items-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if(auth()->user()->role === 'admin') bg-purple-100 text-purple-800
                                @elseif(auth()->user()->role === 'gestionnaire') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if(auth()->user()->status === 'active') bg-emerald-100 text-emerald-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(auth()->user()->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-5">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            <i class="fas fa-envelope mr-1 text-gray-400"></i> Email
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ auth()->user()->email }}</dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            <i class="fas fa-calendar mr-1 text-gray-400"></i> Joined Date
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ auth()->user()->created_at->format('M d, Y') }}
                        </dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">
                            <i class="fas fa-clock mr-1 text-gray-400"></i> Last Updated
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ auth()->user()->updated_at->diffForHumans() }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
