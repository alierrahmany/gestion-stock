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
                <i class="fas fa-chart-pie mr-2 text-blue-500"></i>Reports Dashboard
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Report Selection and Filters -->
        <div class="bg-white shadow rounded-lg mb-6 p-6">
            <form action="{{ route('reports.generate') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="report_type" class="block text-sm font-medium text-gray-700">Report Type</label>
                        <select id="report_type" name="report_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            @if(auth()->user()->role === 'admin')
                                <option value="sales" {{ request('report_type') == 'sales' ? 'selected' : '' }}>Sales Report</option>
                                <option value="purchases" {{ request('report_type') == 'purchases' ? 'selected' : '' }}>Purchases Report</option>
                            @elseif(auth()->user()->role === 'gestionnaire')
                                <option value="purchases" {{ request('report_type') == 'purchases' ? 'selected' : '' }}>Purchases Report</option>
                            @elseif(auth()->user()->role === 'magasin')
                                <option value="sales" {{ request('report_type') == 'sales' ? 'selected' : '' }}>Sales Report</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" id="start_date"
                               value="{{ request('start_date', now()->subMonth()->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" id="end_date"
                               value="{{ request('end_date', now()->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                        <select name="product_id" id="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Products</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-search mr-2"></i> Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Report Results -->
        @if(request()->has('report_type'))
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <!-- Export Buttons -->
                <div class="flex justify-end p-4 space-x-4">
                    <form action="{{ route('reports.export', ['format' => 'csv']) }}" method="GET" class="inline">
                        @foreach(request()->query() as $key => $value)
                            @if($key !== 'page')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-file-csv mr-2 text-green-600"></i> Export to CSV
                        </button>
                    </form>
                    <form action="{{ route('reports.export', ['format' => 'pdf']) }}" method="GET" class="inline">
                        @foreach(request()->query() as $key => $value)
                            @if($key !== 'page')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-file-pdf mr-2 text-red-600"></i> Export to PDF
                        </button>
                    </form>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4">
                    @if(request('report_type') == 'sales')
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-700">Total Sales</h3>
                            <p class="text-2xl font-bold text-blue-900">{{ $transactions->total() }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-700">Total Revenue</h3>
                            <p class="text-2xl font-bold text-green-900">{{ number_format($transactions->sum(function($t) { return $t->quantity * $t->price; }), 2) }} DH</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-700">Items Sold</h3>
                            <p class="text-2xl font-bold text-purple-900">{{ $transactions->sum('quantity') }}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-yellow-700">Avg. Sale</h3>
                            <p class="text-2xl font-bold text-yellow-900">{{ $transactions->count() > 0 ? number_format($transactions->avg(function($t) { return $t->quantity * $t->price; }), 2) : 0 }} DH</p>
                        </div>
                    @else
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-700">Total Purchases</h3>
                            <p class="text-2xl font-bold text-blue-900">{{ $transactions->total() }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-700">Total Cost</h3>
                            <p class="text-2xl font-bold text-green-900">{{ number_format($transactions->sum(function($t) { return $t->quantity * $t->price; }), 2) }} DH</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-700">Items Purchased</h3>
                            <p class="text-2xl font-bold text-purple-900">{{ $transactions->sum('quantity') }}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-yellow-700">Avg. Cost</h3>
                            <p class="text-2xl font-bold text-yellow-900">{{ $transactions->count() > 0 ? number_format($transactions->avg(function($t) { return $t->quantity * $t->price; }), 2) : 0 }} DH</p>
                        </div>
                    @endif
                </div>

                <!-- Detailed Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                @if(request('report_type') == 'sales')
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                @else
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                @endif
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->date ? $transaction->date->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $transaction->reference ?? 'N/A' }}
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
                                        No {{ request('report_type') == 'sales' ? 'sales' : 'purchases' }} found for the selected criteria.
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

            <!-- Trend Chart -->
    @if(isset($chartData) && count($chartData['labels']) > 0)
    <div class="bg-white shadow rounded-lg overflow-hidden mt-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                {{ request('report_type') == 'sales' ? 'Sales' : 'Purchases' }} Trend
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-md font-medium text-gray-700 mb-2">Amount (DH)</h3>
                    <canvas id="amountTrendChart" height="350"></canvas>
                </div>
                <div>
                    <h3 class="text-md font-medium text-gray-700 mb-2">Quantity</h3>
                    <canvas id="quantityTrendChart" height="350"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Top Products Section -->
    @if(isset($topProducts) && count($topProducts) > 0)
    <div class="bg-white shadow rounded-lg overflow-hidden mt-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Top {{ request('report_type') == 'sales' ? 'Sold' : 'Purchased' }} Products
                ({{ $filters['start_date'] }} to {{ $filters['end_date'] }})
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <canvas id="topProductsChart" height="350"></canvas>
                </div>

                <!-- Table with Details -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% of Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $totalQuantity = $topProducts->sum('quantity');
                                $totalAmount = $topProducts->sum('amount');
                            @endphp
                            @foreach($topProducts as $product)
                            @if($product['quantity'] > 0 && $product['amount'] > 0)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product['name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product['quantity'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($product['amount'], 2) }} DH</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $totalAmount > 0 ? number_format(($product['amount'] / $totalAmount) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            <tr class="bg-gray-50 font-semibold">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Total</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $totalQuantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($totalAmount, 2) }} DH</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">100%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
        @else
            <div class="bg-white shadow rounded-lg p-6 text-center text-gray-500">
                <i class="fas fa-chart-bar text-4xl mb-4 text-gray-300"></i>
                <p class="text-lg">Select report type and filters to generate a report</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Amount Trend Chart
    @if(isset($chartData) && count($chartData['labels']) > 0)
    try {
        const amountCtx = document.getElementById('amountTrendChart').getContext('2d');
        const amountChart = new Chart(amountCtx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: '{{ request('report_type') == 'sales' ? 'Sales' : 'Purchases' }} Amount (DH)',
                    data: @json($chartData['data']),
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw.toFixed(2) + ' DH';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' DH';
                            }
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error rendering amount trend chart:', error);
    }

    // Quantity Trend Chart
    try {
        const quantityCtx = document.getElementById('quantityTrendChart').getContext('2d');
        const quantityChart = new Chart(quantityCtx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: '{{ request('report_type') == 'sales' ? 'Sales' : 'Purchases' }} Quantity',
                    data: @json($chartData['quantities']),
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value;
                            }
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error rendering quantity trend chart:', error);
    }
    @endif

    // Top Products Chart
    @if(isset($topProducts) && count($topProducts) > 0)
    try {
        const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        const topProductsChart = new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: @json(array_column($topProducts->toArray(), 'name')),
                datasets: [
                    {
                        label: 'Quantity',
                        data: @json(array_column($topProducts->toArray(), 'quantity')),
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Amount (DH)',
                        data: @json(array_column($topProducts->toArray(), 'amount')),
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1,
                        type: 'line',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.dataset.yAxisID === 'y1') {
                                    label += context.raw.toFixed(2) + ' DH';
                                } else {
                                    label += context.raw;
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Quantity'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Amount (DH)'
                        },
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error rendering top products chart:', error);
    }
    @endif
});
</script>
@endpush
@endsection
