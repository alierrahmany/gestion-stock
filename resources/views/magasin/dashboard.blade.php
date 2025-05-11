@extends('layouts.app')

@section('sidebar')
    @include('magasin.partials.sidebar')
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64">
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Tableau de Bord Magasin</h1>
            <div class="flex items-center space-x-2 bg-primary-50 px-4 py-2 rounded-lg">
                <i class="far fa-calendar-alt text-primary-600"></i>
                <span class="text-primary-800">{{ now()->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="rounded-md bg-blue-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-box-open text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-800">
                        Bienvenue, {{ Auth::user()->name }}! Vous êtes connecté en tant que magasinier.
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Products in stock -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-l-4 border-blue-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-boxes text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Produits en stock</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_products'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clients -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-l-4 border-purple-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Clients</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_clients'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Sales Value -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-l-4 border-green-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 p-3 rounded-full">
                            <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Valeur Totale Ventes</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_sales_value'], 2) }} MAD</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products Sold -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-l-4 border-amber-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-amber-100 p-3 rounded-full">
                            <i class="fas fa-shopping-cart text-amber-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Ventes Produits</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_products_sold'] }}</div>
                                    <span class="ml-2 text-sm text-gray-500">unités</span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Clients and Products -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Top 3 Clients -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4 bg-purple-50">
                    <h3 class="text-lg font-medium text-gray-900">Top 3 Clients</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($topClients as $client)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-100 p-2 rounded-full">
                                    <i class="fas fa-user text-purple-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $client->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $client->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ number_format($client->total_spent, 2) }} MAD</p>
                                <p class="text-xs text-gray-500">Dépensé</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-4 text-center text-gray-500">
                        Aucun client trouvé
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Top 3 Sold Products -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4 bg-blue-50">
                    <h3 class="text-lg font-medium text-gray-900">Top 3 Produits Vendus</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($topSoldProducts as $product)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 p-2 rounded-full">
                                    <i class="fas fa-star text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500">Ventes totales</p>
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
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
            <div class="border-b border-gray-200 px-6 py-4 bg-green-50">
                <h3 class="text-lg font-medium text-gray-900">Activité des Ventes (30 derniers jours)</h3>
            </div>
            <div class="p-6">
                <div class="chart-container" style="position: relative; height:300px; width:100%">
                    <canvas id="salesChart" style="height:300px; width:100%"></canvas>
                </div>
            </div>
        </div>

        <!-- Today's Sales -->
        <div class="bg-white shadow rounded-lg overflow-hidden mt-6">
            <div class="border-b border-gray-200 px-6 py-4 bg-amber-50">
                <h3 class="text-lg font-medium text-gray-900">Ventes Aujourd'hui</h3>
                <p class="mt-1 text-sm text-gray-500">{{ $stats['today_sales_count'] }} ventes pour {{ number_format($stats['today_sales_amount'], 2) }} MAD</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($todaySalesDetails as $sale)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-amber-100 p-2 rounded-full">
                                <i class="fas fa-shopping-cart text-amber-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $sale->product->name ?? 'Produit inconnu' }}</p>
                                <p class="text-sm text-gray-500">
                                    Client: {{ $sale->client->name ?? 'Non spécifié' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">{{ $sale->quantity }} × {{ number_format($sale->price, 2) }} MAD</p>
                            <p class="text-sm text-gray-900">{{ number_format($sale->quantity * $sale->price, 2) }} MAD</p>
                            <p class="text-xs text-gray-500">{{ $sale->created_at->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-4 text-center text-gray-500">
                    Aucune vente aujourd'hui
                </div>
                @endforelse
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
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
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
                    }
                }
            }
        });
    });
</script>
@endsection
