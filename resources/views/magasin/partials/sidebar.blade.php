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

            <a href="{{ route('sales.index') }}" class="@if(request()->routeIs('sales.index')) bg-teal-700 text-white @else text-teal-200 hover:bg-teal-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-list-check mr-3 text-teal-300"></i>
                Ventes
            </a>

            <a href="{{ route('reports.index') }}" class="@if(request()->routeIs('reports.*')) bg-teal-700 text-white @else text-teal-200 hover:bg-teal-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-chart-line mr-3 text-teal-300"></i>
                Rapports
            </a>

            <a href="{{ route('invoices.index') }}" class="@if(request()->routeIs('invoices.*')) bg-teal-700 text-white @else text-teal-200 hover:bg-teal-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-receipt mr-3 text-teal-300"></i>
                Factures
            </a>
        </nav>
    </div>

    <div class="p-4 border-t border-teal-700">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=0ea5e9&color=fff" alt="User avatar">
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs font-medium text-teal-200">{{ ucfirst(auth()->user()->role) }}</p>
            </div>
        </div>
    </div>
</div>
