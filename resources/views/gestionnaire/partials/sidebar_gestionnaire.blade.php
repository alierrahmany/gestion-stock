<div class="col-md-3 col-lg-2 d-md-block sidebar" style="background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4 py-3">
            <h4 class="text-white fw-bold mb-0">GESTIONNAIRE</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('gestionnaire.dashboard') ? 'active' : '' }}" 
                   href="{{ route('gestionnaire.dashboard') }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-speedometer2 me-3 fs-5"></i>
                        <span>Tableau de bord</span>
                    </div>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('categories.*') ? 'active' : '' }}" 
                   href="{{ route('categories.index') }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-tags me-3 fs-5"></i>
                        <span>Catégories</span>
                    </div>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" 
                   href="{{ route('suppliers.index') }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-truck me-3 fs-5"></i>
                        <span>Fournisseurs</span>
                    </div>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('products.*') ? 'active' : '' }}" 
                   href="{{ route('products.index') }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-box-seam me-3 fs-5"></i>
                        <span>Produits</span>
                    </div>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('purchases.*') ? 'active' : '' }}" 
                   href="{{ route('purchases.index') }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cart-plus me-3 fs-5"></i>
                        <span>Achats</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>