@extends('layouts.app')

@section('sidebar')
    @include('magasin.partials.sidebar')
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64 bg-gray-50">
    <!-- En-tête avec effet de verre -->
    <div class="bg-white/80 backdrop-blur-md border-b border-gray-200/70 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tableau de Bord Magasin</h1>
                <p class="text-sm text-gray-600 mt-1">Gestion des ventes et du stock</p>
            </div>
            <div class="flex items-center space-x-2 bg-gradient-to-r from-blue-100 to-indigo-100 px-4 py-2 rounded-full shadow-inner">
                <i class="far fa-calendar-alt text-blue-600"></i>
                <span class="text-blue-800 font-medium">{{ now()->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Notification de bienvenue avec animation -->
        <div class="rounded-xl bg-gradient-to-r from-blue-100 to-indigo-100 p-4 mb-6 border border-blue-200/50 shadow-sm transform transition hover:scale-[1.005]">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-white p-2 rounded-full shadow-sm">
                    <i class="fas fa-box-open text-blue-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-800">
                        Bienvenue, <span class="font-bold">{{ Auth::user()->name }}</span>! Vous êtes connecté en tant que magasinier.
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistiques Principales - Cartes avec effet de profondeur -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Produits en stock -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transform transition hover:-translate-y-1 hover:shadow-xl">
                <div class="p-6 relative">
                    <div class="absolute top-6 right-6 -mt-4 -mr-4 bg-blue-500 text-white p-3 rounded-full shadow-lg">
                        <i class="fas fa-boxes text-xl"></i>
                    </div>
                    <div class="pt-2">
                        <p class="text-sm font-medium text-gray-500">Produits en stock</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_products'] }}</p>
                        <div class="mt-4 flex items-center text-sm text-blue-600">
                            <span class="inline-block bg-blue-100 rounded-full px-2 py-1">
                                <i class="fas fa-warehouse mr-1"></i> Stock
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clients -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transform transition hover:-translate-y-1 hover:shadow-xl">
                <div class="p-6 relative">
                    <div class="absolute top-6 right-6 -mt-4 -mr-4 bg-purple-500 text-white p-3 rounded-full shadow-lg">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="pt-2">
                        <p class="text-sm font-medium text-gray-500">Clients</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_clients'] }}</p>
                        <div class="mt-4 flex items-center text-sm text-purple-600">
                            <span class="inline-block bg-purple-100 rounded-full px-2 py-1">
                                <i class="fas fa-user-friends mr-1"></i> Relations
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Valeur Totale Ventes -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transform transition hover:-translate-y-1 hover:shadow-xl">
                <div class="p-6 relative">
                    <div class="absolute top-6 right-6 -mt-4 -mr-4 bg-green-500 text-white p-3 rounded-full shadow-lg">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </div>
                    <div class="pt-2">
                        <p class="text-sm font-medium text-gray-500">Valeur Totale Ventes</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_sales_value'], 2) }} MAD</p>
                        <div class="mt-4 flex items-center text-sm text-green-600">
                            <span class="inline-block bg-green-100 rounded-full px-2 py-1">
                                <i class="fas fa-chart-line mr-1"></i> Performance
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Ventes Produits -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transform transition hover:-translate-y-1 hover:shadow-xl">
                <div class="p-6 relative">
                    <div class="absolute top-6 right-6 -mt-4 -mr-4 bg-amber-500 text-white p-3 rounded-full shadow-lg">
                        <i class="fas fa-shopping-cart text-xl"></i>
                    </div>
                    <div class="pt-2">
                        <p class="text-sm font-medium text-gray-500">Total Ventes Produits</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_products_sold'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">unités vendues</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Clients et Produits - Design moderne -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Top 3 Clients -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-purple-50 to-white">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-crown text-purple-500 mr-2"></i> Top 3 Clients
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Meilleurs clients par dépenses</p>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($topClients as $client)
                    <div class="px-6 py-4 hover:bg-purple-50/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-100 p-2 rounded-lg">
                                    <i class="fas fa-user-tie text-purple-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $client->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-envelope mr-1"></i>{{ $client->email }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-purple-600">{{ number_format($client->total_spent, 2) }} MAD</p>
                                <p class="text-xs text-gray-500 mt-1">Dépensé</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-4 text-center text-gray-500">
                        Aucun client trouvé
                    </div>
                    @endforelse
                </div>
                <div class="px-6 py-3 bg-gray-50 text-center">
                    <a href="{{ route('clients.index') }}" class="text-sm font-medium text-purple-600 hover:text-purple-800">
                        Voir tous les clients <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Top 3 Produits Vendus -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-blue-50 to-white">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-trophy text-blue-500 mr-2"></i> Top 3 Produits Vendus
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Produits les plus populaires</p>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($topSoldProducts as $product)
                    <div class="px-6 py-4 hover:bg-blue-50/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="flex-shrink-0 bg-blue-100 text-blue-800 font-bold rounded-full w-8 h-8 flex items-center justify-center">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Ventes totales</p>
                                </div>
                            </div>
                            <div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $product->total_sold }} unités
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-4 text-center text-gray-500">
                        Aucun produit vendu
                    </div>
                    @endforelse
                </div>
                <div class="px-6 py-3 bg-gray-50 text-center">
                    <a href="{{ route('products.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                        Voir tous les produits <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Graphique des Ventes - Design moderne -->
        <div class="bg-white rounded-xl overflow-hidden shadow-lg mb-6">
            <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-green-50 to-white">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-chart-line text-green-500 mr-2"></i> Activité des Ventes
                </h3>
                <p class="mt-1 text-sm text-gray-500">30 derniers jours</p>
            </div>
            <div class="p-6">
                <div class="chart-container" style="position: relative; height:300px; width:100%">
                    <canvas id="salesChart" style="height:300px; width:100%"></canvas>
                </div>
            </div>
        </div>

        <!-- Ventes Aujourd'hui - Design moderne -->
        <div class="bg-white rounded-xl overflow-hidden shadow-lg">
            <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-amber-50 to-white">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-sun text-amber-500 mr-2"></i> Ventes Aujourd'hui
                </h3>
                <p class="mt-1 text-sm text-gray-500">{{ $stats['today_sales_count'] }} ventes • {{ number_format($stats['today_sales_amount'], 2) }} MAD</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($todaySalesDetails as $sale)
                <div class="px-6 py-4 hover:bg-amber-50/50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-amber-100 p-2 rounded-lg">
                                <i class="fas fa-receipt text-amber-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $sale->product->name ?? 'Produit inconnu' }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-user mr-1"></i>{{ $sale->client->name ?? 'Non spécifié' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-amber-600">{{ $sale->quantity }} × {{ number_format($sale->price, 2) }} MAD</p>
                            <p class="text-sm text-gray-900">{{ number_format($sale->quantity * $sale->price, 2) }} MAD</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="far fa-clock mr-1"></i>{{ $sale->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-4 text-center text-gray-500">
                    Aucune vente aujourd'hui
                </div>
                @endforelse
            </div>
            <div class="px-6 py-3 bg-gray-50 text-center">
                <a href="{{ route('sales.index') }}" class="text-sm font-medium text-amber-600 hover:text-amber-800">
                    Enregistrer une nouvelle vente <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($salesChart['labels']),
                datasets: [{
                    label: 'Montant des Ventes (MAD)',
                    data: @json($salesChart['data']),
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
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toFixed(2) + ' MAD';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' MAD';
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
