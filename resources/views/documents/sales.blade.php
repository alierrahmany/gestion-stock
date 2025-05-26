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
                <i class="fas fa-truck mr-2 text-blue-500"></i>Bons de Livraison
            </h1>
        </div>
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700" value="{{ request('date_from') }}">Date de début</label>
                    <input type="date" id="date_from" name="date_from" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700" value="{{ request('date_to') }}">Date de fin</label>
                    <input type="date" id="date_to" name="date_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <button type="button" onclick="filterDocuments()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-filter mr-2"></i> Filtrer
                    </button>
                </div>
                <div class="text-right">
                    <button type="button" onclick="printAllSales()"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-print mr-2"></i> Imprimer tout
                    </button>
                </div>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Bon</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sales as $sale)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                BL-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $sale->date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sale->client->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sale->product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $sale->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($sale->price, 2) }} DH
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($sale->quantity * $sale->price, 2) }} DH
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex space-x-2">
                                    <a href="{{ route('documents.delivery-note.download', $sale) }}"
                                       class="text-blue-600 hover:text-blue-900"
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
                        Affichage de {{ $sales->firstItem() }} à {{ $sales->lastItem() }} sur {{ $sales->total() }} bons de livraison
                    </div>
                    <div>
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function filterDocuments() {
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;

    let url = window.location.href.split('?')[0];
    const params = new URLSearchParams();

    if (dateFrom) params.append('date_from', dateFrom);
    if (dateTo) params.append('date_to', dateTo);

    window.location.href = url + '?' + params.toString();
}


    function printAllSales() {
        const urlParams = new URLSearchParams(window.location.search);
        const dateFrom = urlParams.get('date_from') || '';
        const dateTo = urlParams.get('date_to') || '';

        let printUrl = "{{ route('documents.sales.print-all') }}";
        if (dateFrom || dateTo) {
            printUrl += '?' + new URLSearchParams({
                date_from: dateFrom,
                date_to: dateTo
            }).toString();
        }

        window.open(printUrl, '_blank');
    }
</script>
@endsection
