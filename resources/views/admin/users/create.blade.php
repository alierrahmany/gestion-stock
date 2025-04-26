@extends('layouts.app')

@section('sidebar')
    @include('admin.partials.admin-sidebar')
@endsection

@section('content')
<div class="flex-1 overflow-auto">
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <h1 class="text-2xl font-bold text-gray-800">Créer un nouvel utilisateur</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Informations de l'utilisateur</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" required class="py-2 px-3 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" required class="py-2 px-3 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                            <div class="mt-1 relative">
                                <input type="password" name="password" id="password" required class="py-2 px-3 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500" onclick="togglePassword('password')">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                            <div class="mt-1 relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" required class="py-2 px-3 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500" onclick="togglePassword('password_confirmation')">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                            <div class="mt-1">
                                <select id="role" name="role" required class="py-2 px-3 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                    <option value="admin">Administrateur</option>
                                    <option value="manager">Manager</option>
                                    <option value="user" selected>Utilisateur</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                            <div class="mt-1">
                                <select id="status" name="status" required class="py-2 px-3 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                    <option value="active" selected>Actif</option>
                                    <option value="inactive">Inactif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('users.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Annuler
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = input.nextElementSibling.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endsection
