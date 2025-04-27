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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Supplier</h3>
                <form action="{{ route('suppliers.update', $supplier) }}" method="POST" class="mt-5">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $supplier->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $supplier->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
                            <input type="text" name="contact" id="contact" value="{{ old('contact', $supplier->contact) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('contact')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-span-6">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('address', $supplier->address) }}</textarea>
                            @error('address')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Update Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
