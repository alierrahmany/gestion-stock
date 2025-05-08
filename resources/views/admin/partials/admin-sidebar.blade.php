<div class="hidden md:flex md:w-64 flex-col bg-gradient-to-b from-primary-800 to-primary-900 text-white h-screen fixed">
    <div class="flex items-center justify-center h-16 px-4 border-b border-primary-700">
        <div class="flex items-center">
            <i class="fas fa-box-open text-xl mr-2 text-primary-200"></i>
            <span class="text-xl font-bold">STOCK MANAGER</span>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto py-4">
        <nav class="px-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="@if(request()->routeIs('admin.dashboard')) bg-primary-700 text-white @else text-primary-200 hover:bg-primary-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-tachometer-alt mr-3 text-primary-300"></i>
                Tableau de bord
            </a>

            <a href="{{ route('users.index') }}" class="@if(request()->routeIs('users.*')) bg-primary-700 text-white @else text-primary-200 hover:bg-primary-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-users mr-3 text-primary-300"></i>
                Gestion Utilisateurs
            </a>

            <a href="{{ route('categories.index') }}" class="@if(request()->routeIs('categories.*')) bg-primary-700 text-white @else text-primary-200 hover:bg-primary-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-tags mr-3 text-primary-300"></i>
                Catégories
            </a>

            <a href="{{ route('suppliers.index') }}" class="@if(request()->routeIs('suppliers.*')) bg-primary-700 text-white @else text-primary-200 hover:bg-primary-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-truck mr-3 text-primary-300"></i>
                Fournisseurs
            </a>

            <a href="{{ route('products.index') }}" class="@if(request()->routeIs('products.*')) bg-primary-700 text-white @else text-primary-200 hover:bg-primary-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-boxes mr-3 text-primary-300"></i>
                Produits
            </a>

            <a href="{{ route('sales.index') }}" class="@if(request()->routeIs('sales.*')) bg-primary-700 text-white @else text-primary-200 hover:bg-primary-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-shopping-cart mr-3 text-primary-300"></i>
                Ventes
            </a>

            <a href="{{ route('purchases.index') }}" class="@if(request()->routeIs('purchases.*')) bg-primary-700 text-white @else text-primary-200 hover:bg-primary-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-shopping-basket mr-3 text-primary-300"></i>
                Achats
            </a>

            <!-- Fixed Invoice Route -->
            <a href="{{ route('invoices.index') }}" class="@if(request()->routeIs('invoices.*')) bg-primary-700 text-white @else text-primary-200 hover:bg-primary-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-file-invoice mr-3 text-primary-300"></i>
                Factures
            </a>

            <a href="{{ route('reports.index') }}" class="@if(request()->routeIs('reports.*')) bg-primary-700 text-white @else text-primary-200 hover:bg-primary-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-chart-line mr-3 text-primary-300"></i>
                Rapports
            </a>

            <a href="{{ route('documents.index') }}" class="@if(request()->routeIs('documents.*')) bg-primary-700 text-white @else text-primary-200 hover:bg-primary-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-chart-document mr-3 text-primary-300"></i>
                Documents
            </a>
        </nav>
    </div>

    <div class="p-4 border-t border-primary-700">
        <div class="flex items-center">
            
        </div>
    </div>
</div>
