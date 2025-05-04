@extends('layouts.app')

@section('sidebar')
    @include(auth()->user()->role === 'admin' ? 'admin.partials.admin-sidebar' : 'gestionnaire.partials.sidebar_gestionnaire')
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64">
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-shopping-cart mr-2 text-blue-500"></i>Edit Sale
            </h1>
            <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <form action="{{ route('sales.update', $sale->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2">
                        <!-- Client -->
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Client *</label>
                            <select id="client_id" name="client_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ $sale->client_id == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Product -->
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Product *</label>
                            <select id="product_id" name="product_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $sale->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity *</label>
                            <input type="number" id="quantity" name="quantity" min="1" value="{{ old('quantity', $sale->quantity) }}" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <p id="stock-info" class="mt-1 text-sm text-gray-600">Available stock: <span id="available-stock">{{ $availableStock }}</span> (including {{ $sale->quantity }} from this sale)</p>
                            <p id="stock-warning" class="mt-1 text-sm text-red-600 hidden">Insufficient stock!</p>
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (MAD) *</label>
                            <input type="number" step="0.01" id="price" name="price" min="0" value="{{ old('price', $sale->price) }}" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Date *</label>
                            <input type="date" id="date" name="date" value="{{ old('date', $sale->date->format('Y-m-d')) }}" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" id="submit-btn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price');
    const availableStockSpan = document.getElementById('available-stock');
    const stockWarning = document.getElementById('stock-warning');
    const submitBtn = document.getElementById('submit-btn');
    const originalQuantity = {{ $sale->quantity }};

    function checkStock() {
        if (productSelect.value) {
            const availableStock = parseInt(availableStockSpan.textContent);
            const quantity = parseInt(quantityInput.value) || 0;
            const quantityDiff = quantity - originalQuantity;

            if (quantityDiff > (availableStock - originalQuantity)) {
                stockWarning.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                stockWarning.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
    }

    // When product changes, fetch available stock
    productSelect.addEventListener('change', function() {
        if (this.value) {
            fetch(`/sales/stock/${this.value}`)
                .then(response => response.json())
                .then(data => {
                    // Add the current sale's quantity back to available stock for editing
                    const adjustedStock = data.stock + (this.value == "{{ $sale->product_id }}" ? originalQuantity : 0);
                    availableStockSpan.textContent = adjustedStock;
                    checkStock();
                });
        }
    });

    // When quantity changes, check stock
    quantityInput.addEventListener('input', checkStock);

    // Initialize check
    checkStock();
});
</script>
@endsection
