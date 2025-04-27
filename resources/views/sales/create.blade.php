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
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-plus-circle mr-2 text-blue-500"></i>New Sale
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('sales.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                        <select name="product_id" id="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                        <select name="client_id" id="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-span-6 sm:col-span-2">
                        <label for="qty" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="qty" id="qty" value="{{ old('qty') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('qty')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-span-6 sm:col-span-2">
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('price')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-span-6 sm:col-span-2">
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('date')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('sales.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Save Sale
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
