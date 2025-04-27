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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-truck mr-2 text-blue-500"></i>{{ $title }}
            </h1>
            <a href="{{ route('suppliers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                <i class="fas fa-plus-circle mr-2"></i> New Supplier
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 ml-10">
        @if(session('success'))
            <div class="mb-6 rounded-md bg-green-50 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 mt-1"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Success</h3>
                        <div class="mt-1 text-sm text-green-700">
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($suppliers->isNotEmpty())
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($suppliers as $supplier)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($supplier->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $supplier->name }}</div>
                                            <div class="text-sm text-gray-500">Added {{ $supplier->created_at->format('m/d/Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $supplier->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $supplier->contact }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $supplier->address }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-3">
                                        <a href="{{ route('suppliers.edit', $supplier) }}"
                                            class="text-blue-600 hover:text-blue-900 transition-colors"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $suppliers->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-6">
                <p class="text-gray-500">No suppliers found.</p>
            </div>
        @endif
    </div>
</div>

<script>
// ...existing delete confirmation script...
</script>
@endsection
