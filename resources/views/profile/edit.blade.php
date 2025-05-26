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
                    <i class="fas fa-user-edit text-white text-xl"></i>
                </div>
                <h1 class="text-xl font-bold text-white">Modifier le Profil</h1>
            </div>
            <a href="{{ route('profile.show') }}" class="inline-flex items-center px-4 py-2 bg-white border border-transparent rounded-md font-semibold text-blue-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Retour au Profil
            </a>
        </div>
    </div>

    <!-- Formulaire de Modification -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-xl rounded-xl overflow-hidden">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="divide-y divide-gray-200">
                @csrf
                @method('PUT')

                <!-- Section Photo de Profil -->
                <div class="px-8 py-6">
                    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                        <div class="relative group">
                            @include('shared._avatar', [
                                'name' => auth()->user()->name,
                                'image' => auth()->user()->image,
                                'size' => '32',
                                'class' => 'border-4 border-white shadow-md group-hover:opacity-75 transition-opacity'
                            ])
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <label for="image" class="cursor-pointer p-3 bg-blue-600 bg-opacity-90 rounded-full text-white shadow-lg">
                                    <i class="fas fa-camera"></i>
                                    <input type="file" class="hidden" id="image" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Photo de Profil</h3>
                            <p class="mt-1 text-sm text-gray-500">Taille recommandée : 200x200 pixels</p>
                        </div>
                    </div>
                </div>

                <!-- Section Informations Personnelles -->
                <div class="px-8 py-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-user-circle text-blue-500 mr-2"></i> Informations Personnelles
                    </h3>

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Il y a {{ $errors->count() }} erreurs dans votre soumission</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                        <!-- Nom -->
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-user text-gray-500 mr-2"></i> Nom Complet
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        </div>

                        <!-- Email -->
                        <div class="sm:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-envelope text-gray-500 mr-2"></i> Adresse Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        </div>
                    </div>
                </div>

                <!-- Section Mot de Passe -->
                <div class="px-8 py-6 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-lock text-blue-500 mr-2"></i> Modification du Mot de Passe
                    </h3>
                    <p class="text-sm text-gray-500 mb-6">Laissez ces champs vides si vous ne souhaitez pas changer votre mot de passe.</p>

                    <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                        <!-- Mot de Passe Actuel -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-key text-gray-500 mr-2"></i> Mot de Passe Actuel
                            </label>
                            <input type="password" name="current_password" id="current_password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        </div>

                        <!-- Nouveau Mot de Passe -->
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-key text-gray-500 mr-2"></i> Nouveau Mot de Passe
                            </label>
                            <input type="password" name="new_password" id="new_password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        </div>

                        <!-- Confirmation du Mot de Passe -->
                        <div class="sm:col-span-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-check-circle text-gray-500 mr-2"></i> Confirmer le Nouveau Mot de Passe
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        </div>
                    </div>
                </div>

                <!-- Actions du Formulaire -->
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('profile.show') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Annuler
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-save mr-2"></i> Enregistrer les Modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
