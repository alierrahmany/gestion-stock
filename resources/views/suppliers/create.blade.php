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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Ajouter un nouveau fournisseur</h3>
                <form action="{{ route('suppliers.store') }}" method="POST" class="mt-5">
                    @csrf
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="contact" class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input type="text" name="contact" id="contact" value="{{ old('contact') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            @error('contact')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-span-6">
                            <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                            <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('address') }}</textarea>
                            @error('address')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <a href="{{ route('suppliers.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Annuler
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Enregistrer le fournisseur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
