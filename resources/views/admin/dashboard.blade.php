@extends('layouts.app')

@section('sidebar')
    @include('admin.partials.admin-sidebar')
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Tableau de Bord Admin</h1>
            <div class="flex items-center space-x-2 bg-primary-50 px-4 py-2 rounded-lg">
                <i class="far fa-calendar-alt text-primary-600"></i>
                <span class="text-primary-800">{{ now()->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Welcome Alert -->
        <div class="rounded-md bg-blue-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-800">
                        Bienvenue, {{ Auth::user()->name }}! Vous êtes connecté en tant qu'administrateur.
                    </p>
                </div>
            </div>
        </div>

        <!-- User Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total Users -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-l-4 border-blue-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Utilisateurs Totals</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admins -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-l-4 border-purple-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Administrateurs</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['admin_count'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestionnaires -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-l-4 border-green-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 p-3 rounded-full">
                            <i class="fas fa-user-tie text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Gestionnaires</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['gestionnaire_count'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Magasiniers -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-l-4 border-amber-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-amber-100 p-3 rounded-full">
                            <i class="fas fa-warehouse text-amber-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Magasiniers</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['magasin_count'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- In Stock -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-l-4 border-green-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 p-3 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Produits en Stock</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['in_stock_products'] }}</div>
                                    <span class="ml-2 text-sm text-gray-500">/ {{ $stats['total_products'] }}</span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-l-4 border-yellow-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Stock Faible</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['low_stock_products'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Out of Stock -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-l-4 border-red-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 p-3 rounded-full">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Stock Épuisé</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['out_of_stock_products'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Recent Sales -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900">Ventes Récentes (30 jours)</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $stats['recent_sales_count'] }} ventes pour {{ number_format($stats['recent_sales_total'], 2) }} MAD</p>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($recentSales as $sale)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 p-2 rounded-full">
                                    <i class="fas fa-shopping-cart text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $sale->product->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">Client: {{ $sale->client->name ?? 'Non spécifié' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ number_format($sale->total_amount, 2) }} MAD</p>
                                <p class="text-xs text-gray-500">{{ $sale->date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Purchases -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900">Achats Récents (30 jours)</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $stats['recent_purchases_count'] }} achats pour {{ number_format($stats['recent_purchases_total'], 2) }} MAD</p>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($recentPurchases as $purchase)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 p-2 rounded-full">
                                    <i class="fas fa-truck-loading text-green-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $purchase->product->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">Fournisseur: {{ $purchase->supplier->name ?? 'Non spécifié' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ number_format($purchase->total_amount, 2) }} MAD</p>
                                <p class="text-xs text-gray-500">{{ $purchase->date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Alerts and Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Low Stock Alerts -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4 bg-yellow-50">
                    <h3 class="text-lg font-medium text-gray-900">Alertes Stock Faible</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($lowStockProducts as $item)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-100 p-2 rounded-full">
                                    <i class="fas fa-exclamation text-yellow-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-500">Stock actuel: {{ $item->quantity }}</p>
                                </div>
                            </div>
                            <div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Attention
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

            <!-- Sales Chart - Fixed Size Container -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4 bg-blue-50">
                    <h3 class="text-lg font-medium text-gray-900">Activité des Ventes (30 jours)</h3>
                </div>
                <div class="p-6">
                    <div class="chart-container" style="position: relative; height:300px; width:100%">
                        <canvas id="salesChart" style="height:300px; width:100%"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart with fixed dimensions
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($salesChart['labels']),
                datasets: [{
                    label: 'Nombre de Ventes',
                    data: @json($salesChart['data']),
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
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
                                return context.parsed.y + ' ventes';
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