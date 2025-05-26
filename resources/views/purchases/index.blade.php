@extends('layouts.app')

@section('sidebar')
    @include(auth()->user()->role === 'admin' ? 'admin.partials.admin-sidebar' : 'gestionnaire.partials.sidebar_gestionnaire')
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64">
    <!-- Flash Message -->
    @if(session()->has('success') || session()->has('error'))
    <div id="flash-message" class="fixed bottom-4 right-4 z-50 transition-all duration-300 transform translate-y-6 opacity-0">
        <div class="px-6 py-4 rounded-lg shadow-lg text-white font-medium flex items-center
            {{ session()->has('success') ? 'bg-green-500' : 'bg-red-500' }}">
            @if(session()->has('success'))
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            @else
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @endif
            {{ session('success') ?? session('error') }}
        </div>
    </div>
    @endif

    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-shopping-basket mr-2 text-blue-500"></i>Gestion des Achats
            </h1>
            <a href="{{ route('purchases.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                <i class="fas fa-plus-circle mr-2"></i> Nouvel Achat
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Filters -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Produit</label>
                    <select name="product" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Tous les Produits</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fournisseur</label>
                    <select name="supplier" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Tous les Fournisseurs</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Filtrer
                    </button>
                    <a href="{{ route('purchases.index') }}" class="ml-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Purchases Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Achat ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fournisseur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix Unitaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($purchases as $purchase)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <i class="fas fa-shopping-cart mr-1 text-blue-600 hover:text-blue-800"></i>
                            {{ $purchase->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $purchase->formatted_date }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $purchase->supplier->name ?? 'Non spécifié' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $purchase->product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $purchase->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($purchase->price, 2) }} MAD
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($purchase->price * $purchase->quantity, 2) }} MAD
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('purchases.edit', $purchase->id) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" onclick="showDeleteModal('{{ $purchase->id }}', '{{ $purchase->product->name }}')" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            Aucun achat trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $purchases->links() }}
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Confirmer la suppression</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer l'achat de <span id="productName" class="font-semibold"></span> ? Cette action est irréversible.</p>
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
document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.classList.remove('translate-y-6', 'opacity-0');
            flashMessage.classList.add('translate-y-0', 'opacity-100');
        }, 100);

        setTimeout(() => {
            flashMessage.classList.remove('translate-y-0', 'opacity-100');
            flashMessage.classList.add('translate-y-6', 'opacity-0');
            setTimeout(() => flashMessage.remove(), 300);
        }, 2000);
    }

    // Delete Modal Functions
    window.showDeleteModal = function(purchaseId, productName) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const nameSpan = document.getElementById('productName');

        nameSpan.textContent = productName;
        form.action = `/purchases/${purchaseId}`;
        modal.classList.remove('hidden');
    };

    window.hideDeleteModal = function() {
        document.getElementById('deleteModal').classList.add('hidden');
    };

    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target == modal) {
            hideDeleteModal();
        }
    };
});
</script>
@endsection
