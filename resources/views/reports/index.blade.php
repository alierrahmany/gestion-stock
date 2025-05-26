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
                <i class="fas fa-chart-pie mr-2 text-blue-500"></i>Tableau de Bord des Rapports
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Sélection et Filtres du Rapport -->
        <div class="bg-white shadow rounded-lg mb-6 p-6">
            <form action="{{ route('reports.generate') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="report_type" class="block text-sm font-medium text-gray-700">Type de Rapport</label>
                        <select id="report_type" name="report_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            @if(auth()->user()->role === 'admin')
                                <option value="sales" {{ request('report_type') == 'sales' ? 'selected' : '' }}>Rapport des Ventes</option>
                                <option value="purchases" {{ request('report_type') == 'purchases' ? 'selected' : '' }}>Rapport des Achats</option>
                            @elseif(auth()->user()->role === 'gestionnaire')
                                <option value="purchases" {{ request('report_type') == 'purchases' ? 'selected' : '' }}>Rapport des Achats</option>
                            @elseif(auth()->user()->role === 'magasin')
                                <option value="sales" {{ request('report_type') == 'sales' ? 'selected' : '' }}>Rapport des Ventes</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Date de Début</label>
                        <input type="date" name="start_date" id="start_date"
                               value="{{ request('start_date', now()->subMonth()->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Date de Fin</label>
                        <input type="date" name="end_date" id="end_date"
                               value="{{ request('end_date', now()->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700">Produit</label>
                        <select name="product_id" id="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Tous les Produits</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-search mr-2"></i> Générer le Rapport
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Résultats du Rapport -->
        @if(request()->has('report_type'))
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <!-- Boutons d'Exportation -->
                <div class="flex justify-end p-4 space-x-4">
                    <form action="{{ route('reports.export', ['format' => 'pdf']) }}" method="GET" class="inline">
                        @foreach(request()->query() as $key => $value)
                            @if($key !== 'page')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-file-pdf mr-2 text-red-600"></i> Exporter en PDF
                        </button>
                    </form>
                </div>

                <!-- Cartes de Résumé -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4">
                    @if(request('report_type') == 'sales')
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-700">Ventes Totales</h3>
                            <p class="text-2xl font-bold text-blue-900">{{ $transactions->total() }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-700">Revenu Total</h3>
                            <p class="text-2xl font-bold text-green-900">{{ number_format($transactions->sum(function($t) { return $t->quantity * $t->price; }), 2) }} DH</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-700">Articles Vendus</h3>
                            <p class="text-2xl font-bold text-purple-900">{{ $transactions->sum('quantity') }}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-yellow-700">Vente Moyenne</h3>
                            <p class="text-2xl font-bold text-yellow-900">{{ $transactions->count() > 0 ? number_format($transactions->avg(function($t) { return $t->quantity * $t->price; }), 2) : 0 }} DH</p>
                        </div>
                    @else
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-700">Achats Totaux</h3>
                            <p class="text-2xl font-bold text-blue-900">{{ $transactions->total() }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-700">Coût Total</h3>
                            <p class="text-2xl font-bold text-green-900">{{ number_format($transactions->sum(function($t) { return $t->quantity * $t->price; }), 2) }} DH</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-700">Articles Achetés</h3>
                            <p class="text-2xl font-bold text-purple-900">{{ $transactions->sum('quantity') }}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-yellow-700">Coût Moyen</h3>
                            <p class="text-2xl font-bold text-yellow-900">{{ $transactions->count() > 0 ? number_format($transactions->avg(function($t) { return $t->quantity * $t->price; }), 2) : 0 }} DH</p>
                        </div>
                    @endif
                </div>

                <!-- Tableau Détaillé -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                @if(request('report_type') == 'sales')
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                @else
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fournisseur</th>
                                @endif
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qté</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix Unitaire</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->date ? $transaction->date->format('d/m/Y') : 'N/A' }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                        RP-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->product->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if(request('report_type') == 'sales')
                                            {{ $transaction->client->name ?? 'N/A' }}
                                        @else
                                            {{ $transaction->supplier->name ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($transaction->price, 2) }} DH
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($transaction->quantity * $transaction->price, 2) }} DH
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Aucune {{ request('report_type') == 'sales' ? 'vente' : 'achat' }} trouvée pour les critères sélectionnés.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $transactions->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

            <!-- Points Forts -->
            @if(isset($topProductsSummary))
            <div class="bg-white shadow rounded-lg overflow-hidden mt-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        Points Forts des {{ request('report_type') == 'sales' ? 'Ventes' : 'Achats' }}
                    </h2>

                    @if($topProductsSummary['topByQuantity'] || $topProductsSummary['topByAmount'])
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Produit le Plus par Quantité -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-700 mb-2">Produit le Plus {{ request('report_type') == 'sales' ? 'Vendu' : 'Acheté' }} (Quantité)</h3>
                            @if($topProductsSummary['topByQuantity'])
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-medium">Produit:</span> {{ $topProductsSummary['topByQuantity']['name'] }}</p>
                                <p class="text-sm"><span class="font-medium">Quantité:</span> {{ $topProductsSummary['topByQuantity']['quantity'] }}</p>
                                <p class="text-sm"><span class="font-medium">Montant Total:</span> {{ number_format($topProductsSummary['topByQuantity']['amount'], 2) }} DH</p>
                            </div>
                            @else
                            <p class="text-gray-500">Aucune donnée disponible</p>
                            @endif
                        </div>

                        <!-- Produit le Plus par Montant -->
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-700 mb-2">Produit le Plus {{ request('report_type') == 'sales' ? 'Vendu' : 'Acheté' }} (Valeur)</h3>
                            @if($topProductsSummary['topByAmount'])
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-medium">Produit:</span> {{ $topProductsSummary['topByAmount']['name'] }}</p>
                                <p class="text-sm"><span class="font-medium">Quantité:</span> {{ $topProductsSummary['topByAmount']['quantity'] }}</p>
                                <p class="text-sm"><span class="font-medium">Montant Total:</span> {{ number_format($topProductsSummary['topByAmount']['amount'], 2) }} DH</p>
                            </div>
                            @else
                            <p class="text-gray-500">Aucune donnée disponible</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        @else
            <div class="bg-white shadow rounded-lg p-6 text-center text-gray-500">
                <i class="fas fa-chart-bar text-4xl mb-4 text-gray-300"></i>
                <p class="text-lg">Sélectionnez un type de rapport et des filtres pour générer un rapport</p>
            </div>
        @endif
    </div>
</div>
@endsection
