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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-shopping-cart mr-2 text-green-500"></i>Bons d'Achat
            </h1>
        </div>
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <form id="filterForm" method="GET" action="{{ route('documents.purchases') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                            <i class="fas fa-filter mr-2"></i> Filtrer
                        </button>
                        <button type="button" onclick="printAllPurchases()"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700">
                            <i class="fas fa-print mr-2"></i> Imprimer tout
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Bon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fournisseur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix Unitaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($purchases as $purchase)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                BC-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $purchase->date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $purchase->supplier->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $purchase->product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $purchase->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($purchase->price, 2) }} DH
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($purchase->quantity * $purchase->price, 2) }} DH
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex space-x-2">
                                    <a href="{{ route('documents.purchase-order.download', $purchase) }}"
                                       class="text-green-600 hover:text-green-900"
                                       title="Télécharger PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Affichage de {{ $purchases->firstItem() }} à {{ $purchases->lastItem() }} sur {{ $purchases->total() }} bons d'achat
                    </div>
                    <div>
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function printAllPurchases() {
    // Get all current filter values
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    const supplier = "{{ request('supplier') }}";
    const product = "{{ request('product') }}";

    // Build URL with all current filters
    let url = "{{ route('documents.purchases.print-all') }}";
    const params = new URLSearchParams();
    
    if (dateFrom) params.append('date_from', dateFrom);
    if (dateTo) params.append('date_to', dateTo);
    if (supplier) params.append('supplier', supplier);
    if (product) params.append('product', product);

    // Open the URL with all filters
    window.open(url + '?' + params.toString(), '_blank');
}

        function filterPurchases() {
    document.getElementById('filterForm').submit();
}
</script>
@endsection
