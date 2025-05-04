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
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-boxes mr-2 text-blue-500"></i>Gestion des Produits
            </h1>
            <a href="{{ route('gestionnaire.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                <i class="fas fa-plus-circle mr-2"></i> Nouveau Produit
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 ml-10">
        <!-- Enhanced search form with category filter -->
        <div class="mb-8">
            <div class="flex gap-4 items-center">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-lg"></i>
                    </div>
                    <input type="text"
                           id="searchInput"
                           name="search"
                           value="{{ request('search') }}"
                           class="block w-full pl-12 pr-4 py-3 text-lg border-2 border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-200 ease-in-out"
                           placeholder="Rechercher un produit..."
                           autocomplete="off">
                </div>

                <!-- Category Filter Dropdown -->
                <div class="w-72">
                    <select id="categoryFilter"
                            class="block w-full py-3 pl-3 pr-10 text-lg border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-200 ease-in-out">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(request('search') || request('category'))
                    <button onclick="clearFilters()"
                            class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 ease-in-out text-base">
                        <i class="fas fa-times mr-2"></i>
                        Réinitialiser
                    </button>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody" class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                        @php
                            // Calculate total purchased quantity
                            $totalPurchased = $product->purchases->sum('quantity');
                            // Calculate total sold quantity
                            $totalSold = $product->sales->sum('quantity');
                            // Calculate current stock
                            $currentStock = $totalPurchased - $totalSold;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors product-row" data-category-id="{{ $product->categorie_id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->file_name)
                                    <img src="{{ Storage::url($product->file_name) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 py-1 rounded-full {{ $currentStock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $currentStock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
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
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete form handling
    const deleteForms = document.querySelectorAll('.delete-form');
    if (deleteForms) {
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Êtes-vous sûr de vouloir supprimer ce produit?')) {
                    form.submit();
                }
            });
        });
    }

    // Live Search and Category Filter Implementation
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const tableBody = document.getElementById('productsTableBody');
    const rows = tableBody.getElementsByClassName('product-row');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;

        Array.from(rows).forEach(row => {
            const name = row.getElementsByTagName('td')[1].textContent.toLowerCase();
            const category = row.getElementsByTagName('td')[2].textContent.toLowerCase();
            const quantity = row.getElementsByTagName('td')[3].textContent.toLowerCase();
            const categoryId = row.getAttribute('data-category-id');

            const matchesSearch = searchTerm === '' ||
                                name.includes(searchTerm) ||
                                category.includes(searchTerm) ||
                                quantity.includes(searchTerm);

            // Convert to string for comparison
            const matchesCategory = selectedCategory === '' || categoryId === selectedCategory.toString();

            row.style.display = (matchesSearch && matchesCategory) ? '' : 'none';
        });

        updateNoResultsMessage(searchTerm, selectedCategory);
    }

    function updateNoResultsMessage(searchTerm, selectedCategory) {
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        const noResultsMessage = document.getElementById('noResultsMessage');

        if (visibleRows.length === 0) {
            if (!noResultsMessage) {
                const message = document.createElement('tr');
                message.id = 'noResultsMessage';
                let messageText = 'Aucun produit trouvé';

                if (searchTerm) {
                    messageText += ` pour "${searchTerm}"`;
                }
                if (selectedCategory) {
                    const categoryName = categoryFilter.options[categoryFilter.selectedIndex].text;
                    messageText += ` dans la catégorie "${categoryName}"`;
                }

                message.innerHTML = `
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        ${messageText}
                    </td>
                `;
                tableBody.appendChild(message);
            }
        } else if (noResultsMessage) {
            noResultsMessage.remove();
        }
    }

    searchInput.addEventListener('input', filterTable);
    categoryFilter.addEventListener('change', filterTable);

    // Update the clear filters function
    window.clearFilters = function() {
        searchInput.value = '';
        categoryFilter.value = '';
        filterTable();
        history.replaceState({}, '', window.location.pathname);
    };
});
</script>
@endsection
