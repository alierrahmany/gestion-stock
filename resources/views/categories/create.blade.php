@extends('layouts.app')

@section('sidebar')
    @if(auth()->user()->role === 'admin')
        @include('admin.partials.admin-sidebar')
    @else
        @include('gestionnaire.partials.sidebar_gestionnaire')
    @endif
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
