@extends('layouts.app')

@section('sidebar')
    @include('admin.partials.admin-sidebar')
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64">
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <h1 class="text-2xl font-bold text-gray-800">Modifier l'Utilisateur</h1>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 ml-10">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-user-edit mr-2 text-blue-500"></i>Informations de l'Utilisateur
                </h3>
                <p class="mt-1 text-sm text-gray-500">Mettez à jour les détails de l'utilisateur ci-dessous</p>
            </div>

            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="px-6 py-5">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">
                        <!-- Nom Complet -->
                        <div class="sm:col-span-6">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user mr-1 text-gray-500"></i>Nom Complet
                            </label>
                            <input type="text" name="name" id="name" value="{{ $user->name }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        </div>

                        <!-- Email -->
                        <div class="sm:col-span-6">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-envelope mr-1 text-gray-500"></i>Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        </div>

                        <!-- Mot de Passe (optionnel) -->
                        <div class="sm:col-span-6">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-lock mr-1 text-gray-500"></i>Nouveau Mot de Passe (laisser vide pour garder l'actuel)
                            </label>
                            <div class="mt-1 relative">
                                <input type="password" name="password" id="password"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border pr-10">
                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500"
                                    onclick="togglePassword('password')">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Rôle -->
                        <div class="sm:col-span-3">
                            <label for="role" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user-tag mr-1 text-gray-500"></i>Rôle
                            </label>
                            <select id="role" name="role" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrateur</option>
                                <option value="gestionnaire" {{ $user->role === 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                                <option value="magasin" {{ $user->role === 'magasin' ? 'selected' : '' }}>Magasin</option>
                            </select>
                        </div>

                        <!-- Statut -->
                        <div class="sm:col-span-3">
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-circle mr-1 text-gray-500"></i>Statut
                            </label>
                            <select id="status" name="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Actif</option>
                                <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactif</option>
                            </select>
                        </div>

                        <!-- Photo de Profil -->
                        <div class="sm:col-span-6 mb-6">
                            <label for="image" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-image mr-1 text-gray-500"></i>Photo de Profil
                            </label>
                            <div class="mt-1 flex items-center space-x-4">
                                <div class="flex-shrink-0 h-24 w-24">
                                    <img id="image-preview" class="h-24 w-24 rounded-full object-cover border-4 border-gray-200"
                                        src="{{ $user->image && $user->image != 'no_image.jpg'
                                            ? asset('storage/profile_images/'.$user->image)
                                            : asset('storage/profile_images/no_image.jpg') }}"
                                        alt="Aperçu du profil">
                                </div>
                                <div class="relative">
                                    <input type="file" name="image" id="image" accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                        onchange="previewImage(this)">
                                    <button type="button"
                                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700">
                                        <i class="fas fa-camera mr-2"></i> Changer la Photo
                                    </button>
                                </div>
                            </div>
                            @if ($errors->has('image'))
                                <span class="text-red-500 text-sm">{{ $errors->first('image') }}</span>
                            @endif
                            <p class="mt-2 text-sm text-gray-500">Taille maximale : 2MB. Format recommandé : image carrée.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Annuler
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>Mettre à Jour
                    </button>
                </div>
            </form>
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

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('image-preview').src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
