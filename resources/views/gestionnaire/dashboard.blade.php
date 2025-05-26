@extends('layouts.app')

@section('sidebar')
    @include('gestionnaire.partials.sidebar_gestionnaire')
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64 bg-gray-50">
    <!-- En-tête avec effet de verre -->
    <div class="bg-white/80 backdrop-blur-md border-b border-gray-200/70 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tableau de Bord Gestionnaire</h1>
                <p class="text-sm text-gray-600 mt-1">Gestion des stocks et des fournisseurs</p>
            </div>
            <div class="flex items-center space-x-2 bg-gradient-to-r from-blue-100 to-indigo-100 px-4 py-2 rounded-full shadow-inner">
                <i class="far fa-calendar-alt text-blue-600"></i>
                <span class="text-blue-800 font-medium">{{ now()->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Notification de bienvenue avec animation -->
        <div class="rounded-xl bg-gradient-to-r from-green-100 to-blue-100 p-4 mb-6 border border-blue-200/50 shadow-sm transform transition hover:scale-[1.005]">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-white p-2 rounded-full shadow-sm">
                    <i class="fas fa-info-circle text-blue-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-800">
                        Bienvenue, <span class="font-bold">{{ Auth::user()->name }}</span>! Vous êtes connecté en tant que gestionnaire.
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistiques Rapides - Cartes avec effet de profondeur -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Catégories -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transform transition hover:-translate-y-1 hover:shadow-xl">
                <div class="p-6 relative">
                    <div class="absolute top-6 right-6 -mt-4 -mr-4 bg-blue-500 text-white p-3 rounded-full shadow-lg">
                        <i class="fas fa-tags text-xl"></i>
                    </div>
                    <div class="pt-2">
                        <p class="text-sm font-medium text-gray-500">Catégories</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_categories'] }}</p>
                        <div class="mt-4 flex items-center text-sm text-blue-600">
                            <span class="inline-block bg-blue-100 rounded-full px-2 py-1">
                                <i class="fas fa-list mr-1"></i> Gestion
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produits -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transform transition hover:-translate-y-1 hover:shadow-xl">
                <div class="p-6 relative">
                    <div class="absolute top-6 right-6 -mt-4 -mr-4 bg-green-500 text-white p-3 rounded-full shadow-lg">
                        <i class="fas fa-boxes text-xl"></i>
                    </div>
                    <div class="pt-2">
                        <p class="text-sm font-medium text-gray-500">Produits</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_products'] }}</p>
                        <div class="mt-4 flex items-center text-sm text-green-600">
                            <span class="inline-block bg-green-100 rounded-full px-2 py-1">
                                <i class="fas fa-cubes mr-1"></i> Stock
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fournisseurs -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transform transition hover:-translate-y-1 hover:shadow-xl">
                <div class="p-6 relative">
                    <div class="absolute top-6 right-6 -mt-4 -mr-4 bg-amber-500 text-white p-3 rounded-full shadow-lg">
                        <i class="fas fa-truck text-xl"></i>
                    </div>
                    <div class="pt-2">
                        <p class="text-sm font-medium text-gray-500">Fournisseurs</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_suppliers'] }}</p>
                        <div class="mt-4 flex items-center text-sm text-amber-600">
                            <span class="inline-block bg-amber-100 rounded-full px-2 py-1">
                                <i class="fas fa-handshake mr-1"></i> Partenaires
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques Stock - Design moderne -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- En Stock -->
            <div class="bg-gradient-to-br from-white to-green-50 rounded-xl overflow-hidden shadow-lg border border-green-100 transform transition hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Produits en Stock</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['in_stock_products'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">sur {{ $stats['total_products'] }} produits</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($stats['in_stock_products']/$stats['total_products'])*100 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Stock Faible -->
            <div class="bg-gradient-to-br from-white to-yellow-50 rounded-xl overflow-hidden shadow-lg border border-yellow-100 transform transition hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Stock Faible</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['low_stock_products'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">nécessitent réapprovisionnement</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-full">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ ($stats['low_stock_products']/$stats['total_products'])*100 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Stock Épuisé -->
            <div class="bg-gradient-to-br from-white to-red-50 rounded-xl overflow-hidden shadow-lg border border-red-100 transform transition hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Stock Épuisé</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['out_of_stock_products'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">en rupture de stock</p>
                        </div>
                        <div class="bg-red-100 p-4 rounded-full">
                            <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ ($stats['out_of_stock_products']/$stats['total_products'])*100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques Achats - Design moderne -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Total Achats Produits -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transform transition hover:rotate-1 hover:shadow-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="bg-indigo-100 p-3 rounded-lg">
                            <i class="fas fa-shopping-cart text-indigo-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Achats Produits</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_products_purchased']) }}</p>
                            <p class="text-xs text-gray-500">unités achetées</p>
                        </div>
                    </div>
                    <div class="mt-4 border-t border-gray-100 pt-3">
                        <a href="{{ route('purchases.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center">
                            Voir les achats <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Valeur Totale Achats -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transform transition hover:rotate-1 hover:shadow-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-money-bill-wave text-purple-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Valeur Totale Achats</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_products_purchases_amount'], 2) }} MAD</p>
                            <p class="text-xs text-gray-500">dépensés</p>
                        </div>
                    </div>
                    <div class="mt-4 border-t border-gray-100 pt-3">
                        <a href="{{ route('reports.index') }}" class="text-sm font-medium text-purple-600 hover:text-purple-800 flex items-center">
                            Voir les dépenses <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achats Récents - Design moderne -->
        <div class="grid grid-cols-1 gap-6 mb-6">
            <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-green-50 to-white">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-truck-loading text-green-500 mr-2"></i> Achats Récents (30 jours)
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $stats['recent_purchases_count'] }} achats • {{ number_format($stats['recent_purchases_total'], 2) }} MAD</p>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($recentPurchases as $purchase)
                    <div class="px-6 py-4 hover:bg-green-50/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 p-2 rounded-lg">
                                    <i class="fas fa-box-open text-green-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $purchase->product->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-truck mr-1"></i>{{ $purchase->supplier->name ?? 'Non spécifié' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-green-600">{{ number_format($purchase->total_amount, 2) }} MAD</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="far fa-clock mr-1"></i>{{ $purchase->date->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="px-6 py-3 bg-gray-50 text-center">
                    <a href="{{ route('purchases.index') }}" class="text-sm font-medium text-green-600 hover:text-green-800">
                        Voir tous les achats <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Produits les Plus Achetés - Design moderne -->
        <div class="grid grid-cols-1 gap-6 mb-6">
            <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-green-50 to-white">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-trophy text-yellow-500 mr-2"></i> Top 3 Produits Achetés
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Tous le temps</p>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($topPurchasedProducts as $product)
                    <div class="px-6 py-4 hover:bg-green-50/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="flex-shrink-0 bg-yellow-100 text-yellow-800 font-bold rounded-full w-8 h-8 flex items-center justify-center">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $product->product->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-shopping-cart mr-1"></i>{{ $product->total_purchased }} unités achetées
                                    </p>
                                </div>
                            </div>
                            <div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-star mr-1"></i> Top
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-4 text-center text-gray-500">
                        Aucun achat enregistré
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Fournisseurs Actifs - Design moderne -->
        <div class="grid grid-cols-1 gap-6 mb-6">
            <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-purple-50 to-white">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-star text-purple-500 mr-2"></i> Top 3 Fournisseurs Actifs
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Tous le temps</p>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($topSuppliers as $supplier)
                    <div class="px-6 py-4 hover:bg-purple-50/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="flex-shrink-0 bg-purple-100 text-purple-800 font-bold rounded-full w-8 h-8 flex items-center justify-center">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $supplier->supplier->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-truck mr-1"></i>{{ $supplier->total_delivered }} produits livrés
                                    </p>
                                </div>
                            </div>
                            <div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    <i class="fas fa-check mr-1"></i> Fiable
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-4 text-center text-gray-500">
                        Aucun fournisseur enregistré
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Graphique des Achats - Design moderne -->
        <div class="grid grid-cols-1 gap-6 mb-6">
            <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-green-50 to-white">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-chart-bar text-green-500 mr-2"></i> Activité des Achats
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">12 derniers mois</p>
                </div>
                <div class="p-6">
                    <div class="chart-container" style="position: relative; height:300px; width:100%">
                        <canvas id="purchasesChart" style="height:300px; width:100%"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes Stock - Design moderne -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Ruptures de Stock -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-red-50 to-white">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-times-circle text-red-500 mr-2"></i> Produits en Rupture
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $stats['out_of_stock_products'] }} produits actuellement en rupture</p>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($outOfStockProducts as $item)
                    <div class="px-6 py-4 hover:bg-red-50/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-100 p-2 rounded-lg">
                                    <i class="fas fa-boxes text-red-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-tag mr-1"></i>{{ $item->product->categorie->name ?? 'Non classé' }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i> Rupture
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-4 text-center text-gray-500">
                        Aucun produit en rupture de stock
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Stock Faible -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-yellow-50 to-white">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i> Alertes Stock Faible
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $lowStockProducts->count() }} produits nécessitent attention</p>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($lowStockProducts as $item)
                    <div class="px-6 py-4 hover:bg-yellow-50/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-100 p-2 rounded-lg">
                                    <i class="fas fa-box-open text-yellow-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-boxes mr-1"></i>Stock actuel: {{ $item->quantity }}
                                        @if($item->product->min_stock_level)
                                            <span class="ml-2"><i class="fas fa-arrow-down mr-1"></i>Seuil: {{ $item->product->min_stock_level }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation mr-1"></i> Attention
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-4 text-center text-gray-500">
                        Aucun produit en stock faible
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Purchases Chart
    document.addEventListener('DOMContentLoaded', function() {
        const purchasesCtx = document.getElementById('purchasesChart').getContext('2d');
        const purchasesChart = new Chart(purchasesCtx, {
            type: 'line',
            data: {
                labels: @json($purchasesChart['labels']),
                datasets: [{
                    label: 'Nombre d\'Achats',
                    data: @json($purchasesChart['data']),
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' achats';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
