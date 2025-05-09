<div class="hidden md:flex md:w-64 flex-col bg-gradient-to-b from-teal-800 to-teal-900 text-white h-screen fixed">
    <div class="flex items-center justify-center h-16 px-4 border-b border-teal-700">
        <div class="flex items-center">
            <i class="fas fa-store text-xl mr-2 text-teal-200"></i>
            <span class="text-xl font-bold">MAGASIN</span>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto py-4">
        <nav class="px-4 space-y-1">
            <a href="{{ route('magasin.dashboard') }}" class="@if(request()->routeIs('magasin.dashboard')) bg-teal-700 text-white @else text-teal-200 hover:bg-teal-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-tachometer-alt mr-3 text-teal-300"></i>
                Tableau de bord
            </a>

            <a href="{{ route('products.index') }}" class="@if(request()->routeIs('products.*')) bg-teal-700 text-white @else text-teal-200 hover:bg-teal-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-boxes mr-3 text-teal-300"></i>
                Produits
            </a>

            <a href="{{ route('clients.index') }}" class="@if(request()->routeIs('settings.*')) bg-primary-700 text-white @else text-primary-200 hover:bg-primary-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-users mr-3 text-primary-300"></i>
                Clients
            </a>

            <a href="{{ route('sales.index') }}" class="@if(request()->routeIs('sales.index')) bg-teal-700 text-white @else text-teal-200 hover:bg-teal-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-list-check mr-3 text-teal-300"></i>
                Ventes
            </a>

            <a href="{{ route('reports.index') }}" class="@if(request()->routeIs('reports.*')) bg-teal-700 text-white @else text-teal-200 hover:bg-teal-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-chart-line mr-3 text-teal-300"></i>
                Rapports
            </a>

            <a href="{{ route('documents.index') }}" class="@if(request()->routeIs('documents.*')) bg-primary-700 text-white @else text-primary-200 hover:bg-primary-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-file-invoice mr-3 text-primary-300"></i>
                Factures
            </a>
        </nav>
    </div>

    <div class="p-4 border-t border-teal-700">
        <div class="flex items-center">
        </div>
    </div>
</div>
