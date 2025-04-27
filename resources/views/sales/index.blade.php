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
                <i class="fas fa-shopping-cart mr-2 text-blue-500"></i>Gestion des Ventes
            </h1>
            <a href="{{ route('sales.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                <i class="fas fa-plus-circle mr-2"></i> Nouvelle Vente
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 ml-10">
        <!-- Enhanced filters section -->
        <div class="mb-8 bg-white p-6 rounded-xl shadow-sm">
            <form id="filterForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="searchInput"
                               name="search" 
                               value="{{ request('search') }}" 
                               class="block w-full pl-10 pr-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Rechercher...">
                    </div>

                    <!-- Product Filter -->
                    <div>
                        <select id="productFilter" 
                                name="product" 
                                class="block w-full py-2 px-3 border-2 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les produits</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Client Filter -->
                    <div>
                        <select id="clientFilter" 
                                name="client" 
                                class="block w-full py-2 px-3 border-2 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="flex space-x-2">
                        <input type="date" 
                               id="dateStart" 
                               name="date_start" 
                               value="{{ request('date_start') }}"
                               class="block w-full py-2 px-3 border-2 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <input type="date" 
                               id="dateEnd" 
                               name="date_end" 
                               value="{{ request('date_end') }}"
                               class="block w-full py-2 px-3 border-2 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </form>
        </div>

        <!-- Sales Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $sale->date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sale->product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $sale->client->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $sale->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($sale->total_price, 2) }} DH
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('sales.edit', $sale) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Aucune vente trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    const inputs = form.querySelectorAll('input, select');
    let debounceTimer;

    // Function to update URL and reload data
    const updateFilters = (formData) => {
        const url = new URL(window.location);
        
        // Update URL parameters
        for (let [key, value] of formData.entries()) {
            if (value) {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
        }

        // Update URL without reloading
        window.history.pushState({}, '', url);

        // Reload data using fetch
        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.querySelector('table');
                const oldTable = document.querySelector('table');
                oldTable.innerHTML = newTable.innerHTML;
            });
    };

    // Handle input changes with debounce for text search
    inputs.forEach(input => {
        input.addEventListener('input', (e) => {
            if (input.type === 'text') {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const formData = new FormData(form);
                    updateFilters(formData);
                }, 300);
            }
        });

        // Immediate update for select and date inputs
        input.addEventListener('change', (e) => {
            if (input.type !== 'text') {
                const formData = new FormData(form);
                updateFilters(formData);
            }
        });
    });

    // Add reset functionality to clear icon
    const addClearButton = (input) => {
        const clearButton = document.createElement('button');
        clearButton.type = 'button';
        clearButton.className = 'absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600';
        clearButton.innerHTML = '<i class="fas fa-times-circle"></i>';
        clearButton.style.display = input.value ? 'block' : 'none';
        
        clearButton.addEventListener('click', () => {
            input.value = '';
            clearButton.style.display = 'none';
            input.dispatchEvent(new Event('change'));
        });

        input.parentElement.style.position = 'relative';
        input.parentElement.appendChild(clearButton);

        input.addEventListener('input', () => {
            clearButton.style.display = input.value ? 'block' : 'none';
        });
    };

    // Add clear buttons to all inputs
    inputs.forEach(input => {
        if (input.type !== 'submit' && input.type !== 'reset') {
            addClearButton(input);
        }
    });
});
</script>
@endsection
