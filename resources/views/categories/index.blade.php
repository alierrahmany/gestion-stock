@extends('layouts.app')

@section('sidebar')
    @if(auth()->user()->role === 'admin')
        @include('admin.partials.admin-sidebar')
    @else
        @include('gestionnaire.partials.sidebar_gestionnaire')
    @endif
@endsection

@section('content')
<div class="flex-1 overflow-auto ml-64">
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-tags mr-2 text-blue-500"></i>Gestion des Catégories
            </h1>
            <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                <i class="fas fa-plus-circle mr-2"></i> Nouvelle Catégorie
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 ml-10">
        @if(session('success'))
            <div class="mb-6 rounded-md bg-green-50 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 mt-1"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Succès</h3>
                        <div class="mt-1 text-sm text-green-700">
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($categories->isNotEmpty())
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($categories as $category)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->description ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-3">
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                            class="text-blue-600 hover:text-blue-900 transition-colors"
                                            title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $categories->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-6">
                <p class="text-gray-500">Aucune catégorie trouvée.</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('.delete-form');
    
    if (deleteForms && deleteForms.length > 0) {
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Êtes-vous sûr?',
                        text: "Cette action est irréversible!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Oui, supprimer!',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie?')) {
                        form.submit();
                    }
                }
            });
        });
    }
});
</script>
@endsection
