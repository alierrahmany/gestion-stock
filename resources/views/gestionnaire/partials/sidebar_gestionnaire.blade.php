<div class="hidden md:flex md:w-64 flex-col bg-gradient-to-b from-blue-800 to-blue-900 text-white h-screen fixed">
    <div class="flex items-center justify-center h-16 px-4 border-b border-blue-700">
        <div class="flex items-center">
            <i class="fas fa-user-cog text-xl mr-2 text-blue-200"></i>
            <span class="text-xl font-bold">GESTIONNAIRE</span>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto py-4">
        <nav class="px-4 space-y-1">
            <a href="{{ route('gestionnaire.dashboard') }}" class="@if(request()->routeIs('gestionnaire.dashboard')) bg-blue-700 text-white @else text-blue-200 hover:bg-blue-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-tachometer-alt mr-3 text-blue-300"></i>
                Tableau de bord
            </a>

            <a href="{{ route('categories.index') }}" class="@if(request()->routeIs('categories.*')) bg-blue-700 text-white @else text-blue-200 hover:bg-blue-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-tags mr-3 text-blue-300"></i>
                Catégories
            </a>

            <a href="{{ route('suppliers.index') }}" class="@if(request()->routeIs('suppliers.*')) bg-blue-700 text-white @else text-blue-200 hover:bg-blue-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-truck mr-3 text-blue-300"></i>
                Fournisseurs
            </a>

            <a href="{{ route('products.index') }}" class="@if(request()->routeIs('products.*')) bg-blue-700 text-white @else text-blue-200 hover:bg-blue-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-boxes mr-3 text-blue-300"></i>
                Produits
            </a>

            <a href="{{ route('purchases.index') }}" class="@if(request()->routeIs('purchases.*')) bg-blue-700 text-white @else text-blue-200 hover:bg-blue-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-cart-plus mr-3 text-blue-300"></i>
                Achats
            </a>

            <a href="{{ route('documents.index') }}" class="@if(request()->routeIs('documents.*')) bg-primary-700 text-white @else text-primary-200 hover:bg-primary-700 hover:text-white @endif group flex items-center px-4 py-3 rounded-lg transition-all duration-200">
                <i class="fas fa-file-invoice mr-3 text-primary-300"></i>
                Factures
            </a>
        </nav>
    </div>

    <div class="p-4 border-t border-blue-700">
        <div class="flex items-center">
            
        </div>
    </div>
</div>
