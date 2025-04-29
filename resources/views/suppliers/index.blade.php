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
                <i class="fas fa-truck mr-2 text-blue-500"></i>Gestion des Fournisseurs
            </h1>
            <a href="{{ route('suppliers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                <i class="fas fa-plus-circle mr-2"></i> Nouveau Fournisseur
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 ml-10">
        <!-- Enhanced search form -->
        <div class="mb-8">
            <div class="flex gap-4 items-center">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-lg"></i>
                    </div>
                    <input type="text" 
                           id="searchInput"
                           name="search" 
                           value="{{ request('search') }}" 
                           class="block w-full pl-12 pr-4 py-3 text-lg border-2 border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-200 ease-in-out"
                           placeholder="Rechercher un fournisseur par nom, email ou téléphone..."
                           autocomplete="off">
                </div>
                @if(request('search'))
                    <button onclick="clearSearch()" 
                            class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 ease-in-out text-base">
                        <i class="fas fa-times mr-2"></i>
                        Réinitialiser
                    </button>
                @endif
            </div>
        </div>

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

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="suppliersTableBody" class="bg-white divide-y divide-gray-200">
                        @foreach($suppliers as $supplier)
                        <tr class="hover:bg-gray-50 transition-colors supplier-row">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $supplier->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $supplier->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $supplier->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $supplier->contact }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
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
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete form handling
    const deleteForms = document.querySelectorAll('.delete-form');
    if (deleteForms) {
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur?')) {
                    form.submit();
                }
            });
        });
    }

    // Live Search Implementation
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('suppliersTableBody');
    const rows = tableBody.getElementsByClassName('supplier-row');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();

        Array.from(rows).forEach(row => {
            const name = row.getElementsByTagName('td')[1].textContent.toLowerCase();
            const email = row.getElementsByTagName('td')[2].textContent.toLowerCase();
            const phone = row.getElementsByTagName('td')[3].textContent.toLowerCase();
            
            const matches = name.includes(searchTerm) || 
                          email.includes(searchTerm) || 
                          phone.includes(searchTerm);
            row.style.display = matches ? '' : 'none';
        });

        updateNoResultsMessage(searchTerm);
    });

    function updateNoResultsMessage(searchTerm) {
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        const noResultsMessage = document.getElementById('noResultsMessage');
        
        if (visibleRows.length === 0) {
            if (!noResultsMessage) {
                const message = document.createElement('tr');
                message.id = 'noResultsMessage';
                message.innerHTML = `
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Aucun fournisseur trouvé pour "${searchTerm}"
                    </td>
                `;
                tableBody.appendChild(message);
            }
        } else if (noResultsMessage) {
            noResultsMessage.remove();
        }
    }

    window.clearSearch = function() {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
        history.replaceState({}, '', window.location.pathname);
    };
});
</script>
@endsection
