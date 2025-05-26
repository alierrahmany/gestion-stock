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
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Gestion des Documents</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Show Delivery Notes for magasin and admin -->
                @if(auth()->user()->role === 'magasin' || auth()->user()->role === 'admin')
                <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-truck mr-2 text-blue-500"></i>Bons de Livraison
                        </h2>
                        <a href="{{ route('documents.sales') }}"
                           class="text-sm text-blue-600 hover:text-blue-800">
                            Voir tout <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <p class="text-gray-600 mb-4">Gérez tous les bons de livraison pour les commandes des clients.</p>
                    <div class="flex space-x-2">
                        <a href="{{ route('documents.sales') }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                            Voir les Bons de Livraison
                        </a>
                    </div>
                </div>
                @endif

                <!-- Show Purchase Orders for gestionnaire and admin -->
                @if(auth()->user()->role === 'gestionnaire' || auth()->user()->role === 'admin')
                <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-shopping-cart mr-2 text-green-500"></i>Bons d'Achat
                        </h2>
                        <a href="{{ route('documents.purchases') }}"
                           class="text-sm text-blue-600 hover:text-blue-800">
                            Voir tout <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <p class="text-gray-600 mb-4">Gérez tous les bons d'achat auprès des fournisseurs.</p>
                    <div class="flex space-x-2">
                        <a href="{{ route('documents.purchases') }}"
                           class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                            Voir les Bons d'Achat
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
