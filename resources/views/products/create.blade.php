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
                <i class="fas fa-box-open mr-2 text-blue-500"></i>Ajouter un Nouveau Produit
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom du Produit *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="categorie_id" class="block text-sm font-medium text-gray-700">Catégorie *</label>
                        <select name="categorie_id" id="categorie_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Sélectionner une Catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('categorie_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-span-6">
                        <label for="file_name" class="block text-sm font-medium text-gray-700">Image du Produit</label>
                        <div class="mt-1 flex items-center">
                            <div class="mr-4 flex-shrink-0">
                                <img id="image-preview" src="{{ isset($product) ? $product->image_url : asset('images/default-product.png') }}"
                                    class="h-32 w-32 rounded-md object-cover border">
                            </div>
                            <div>
                                <input type="file" name="image" id="image" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    onchange="previewImage(this)">
                                <p class="mt-1 text-xs text-gray-500">JPEG, PNG, JPG, GIF (Max 2MB)</p>
                                @error('image')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('image-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
