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
                <i class="fas fa-chart-bar mr-2 text-blue-500"></i>Sales Report
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Report Filters -->
        <div class="bg-white shadow rounded-lg mb-6 p-4">
            <form action="{{ route('reports.generate') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date" 
                           value="{{ request('start_date', now()->subMonth()->format('Y-m-d')) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date" 
                           value="{{ request('end_date', now()->format('Y-m-d')) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                    <select name="product_id" id="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Report Results -->
        @if(isset($sales) && $sales->count() > 0)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-700">Total Sales</h3>
                        <p class="text-2xl font-bold text-blue-900">{{ $sales->count() }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-700">Total Revenue</h3>
                        <p class="text-2xl font-bold text-green-900">${{ number_format($sales->sum(function($sale) { return $sale->qty * $sale->price; }), 2) }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-purple-700">Total Items Sold</h3>
                        <p class="text-2xl font-bold text-purple-900">{{ $sales->sum('qty') }}</p>
                    </div>
                </div>

                <!-- Detailed Table -->
                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sales as $sale)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sale->date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $sale->product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sale->client->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sale->qty }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($sale->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($sale->qty * $sale->price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-6 text-center text-gray-500">
                No sales data found for the selected criteria.
            </div>
        @endif
    </div>
</div>
@endsection
