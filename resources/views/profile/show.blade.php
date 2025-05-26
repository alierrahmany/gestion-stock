@extends('layouts.app')

@section('sidebar')
    @if(auth()->user()->role === 'admin')
        @include('admin.partials.admin-sidebar')
    @elseif(auth()->user()->role === 'gestionnaire')
        @include('gestionnaire.partials.sidebar_gestionnaire')
    @elseif(auth()->user()->role === 'magasin')
        @include('magasin.partials.sidebar')
    @endif
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64">
    <!-- En-tête -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="p-2 rounded-lg bg-white bg-opacity-20">
                    <i class="fas fa-user-circle text-white text-xl"></i>
                </div>
                <h1 class="text-xl font-bold text-white">Mon Profil</h1>
            </div>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-white border border-transparent rounded-md font-semibold text-blue-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all shadow-sm">
                <i class="fas fa-edit mr-2"></i> Modifier le Profil
            </a>
        </div>
    </div>

    <!-- Contenu du Profil -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-xl rounded-xl overflow-hidden">
            <!-- En-tête du Profil -->
            <div class="px-8 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                    <div class="relative">
                        @include('shared._avatar', [
                            'name' => auth()->user()->name,
                            'image' => auth()->user()->image,
                            'size' => '32',
                            'class' => 'border-4 border-white shadow-md'
                        ])
                    </div>
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                        <div class="mt-2 flex flex-wrap justify-center md:justify-start gap-2">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if(auth()->user()->role === 'admin') bg-purple-100 text-purple-800
                                @elseif(auth()->user()->role === 'gestionnaire') bg-amber-100 text-amber-800
                                @else bg-blue-100 text-blue-800 @endif">
                                <i class="fas fa-user-tag mr-1"></i>{{ ucfirst(auth()->user()->role) }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if(auth()->user()->status === 'active') bg-emerald-100 text-emerald-800
                                @else bg-red-100 text-red-800 @endif">
                                <i class="fas fa-circle mr-1 text-xs"></i>{{ ucfirst(auth()->user()->status) }}
                            </span>
                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-800 text-sm font-semibold">
                                <i class="fas fa-calendar-day mr-1"></i>Membre depuis {{ auth()->user()->created_at->format('M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails du Profil -->
            <div class="px-8 py-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i> Informations Personnelles
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Email -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-envelope text-gray-500 mr-2"></i>
                            <span class="text-sm font-medium text-gray-500">Adresse Email</span>
                        </div>
                        <p class="text-gray-900 font-medium">{{ auth()->user()->email }}</p>
                    </div>

                    <!-- Dernière Mise à Jour -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-clock text-gray-500 mr-2"></i>
                            <span class="text-sm font-medium text-gray-500">Dernière Mise à Jour</span>
                        </div>
                        <p class="text-gray-900 font-medium">{{ auth()->user()->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Sécurité du Compte -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-shield-alt text-blue-500 mr-2"></i> Sécurité du Compte
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mot de Passe -->
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center">
                                <i class="fas fa-lock text-gray-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-500">Mot de Passe</span>
                            </div>
                            <span class="text-xs font-medium text-gray-500">Modifié il y a 3 mois</span>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                            Changer le mot de passe <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
