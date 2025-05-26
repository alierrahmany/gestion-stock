@extends('layouts.app')

@section('sidebar')
    @if(auth()->user()->role === 'admin')
        @include('admin.partials.admin-sidebar')
    @elseif(auth()->user()->role === 'gestionnaire')
        @include('gestionnaire.partials.sidebar_gestionnaire')
    @elseif(auth()->user()->role === 'magasin')
        @include('magasin.partials.sidebar')
    @endif
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64">
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-boxes mr-2 text-blue-500"></i>Gestion des Produits
            </h1>
            @if(auth()->user()->role !== 'magasin')
                <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                    <i class="fas fa-plus-circle mr-2"></i> Nouveau Produit
                </a>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 ml-10">
        <!-- Search and Filter Section -->
<div class="mb-8">
    <form method="GET" action="{{ route('products.index') }}" class="flex gap-4 items-center" id="searchForm">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-lg"></i>
            </div>
            <input type="text"
                   name="search"
                   id="searchInput"
                   value="{{ request('search') }}"
                   class="block w-full pl-12 pr-4 py-3 text-lg border-2 border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                   placeholder="Rechercher des produits..."
                   autocomplete="off">
        </div>

        <div class="w-72">
            <select name="category" id="categoryFilter"
                    class="block w-full py-3 pl-3 pr-10 text-lg border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                <option value="">Toutes les Catégories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
            <i class="fas fa-filter mr-2"></i> Filtrer
        </button>

        @if(request('search') || request('category'))
            <a href="{{ route('products.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600">
                <i class="fas fa-times mr-2"></i> Réinitialiser
            </a>
        @endif
    </form>
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'ajout</th>
                            @if(auth()->user()->role !== 'magasin')
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->file_name !== 'no_image.jpg')
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $product->category->name ?? 'Aucune catégorie' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 py-1 rounded-full {{ $product->current_stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->current_stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->created_at->format('d/m/Y') }}</td>
                            @if(auth()->user()->role !== 'magasin')
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-3">
                                        <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" onclick="showDeleteModal('{{ $product->id }}', '{{ $product->name }}')" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role !== 'magasin' ? '6' : '5' }}" class="px-6 py-4 text-center text-gray-500">
                                Aucun produit trouvé
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Confirmer la suppression</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer <span id="productName" class="font-semibold"></span> ? Cette action est irréversible.</p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Supprimer
                    </button>
                    <button type="button" onclick="hideDeleteModal()" class="ml-3 px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Annuler
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showDeleteModal(productId, productName) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const nameSpan = document.getElementById('productName');

    nameSpan.textContent = productName;
    form.action = `/products/${productId}`;
    modal.classList.remove('hidden');
}

function hideDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target == modal) {
        hideDeleteModal();
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');

    // Live search implementation
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            if (this.value.length > 2 || this.value.length === 0) {
                searchForm.submit();
            }
        });
    }

    // Category filter change
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            searchForm.submit();
        });
    }
});
</script>
@endsection
