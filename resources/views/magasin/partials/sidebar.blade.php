<div class="col-md-3 col-lg-2 d-md-block sidebar bg-primary">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4 py-3 border-bottom">
            <h4 class="text-white fw-bold mb-0">MAGASIN</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('magasin.dashboard') ? 'active' : '' }}" 
                   href="{{ route('magasin.dashboard') }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-speedometer2 me-3 fs-5"></i>
                        <span>Tableau de bord</span>
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
                <a class="nav-link text-white {{ request()->routeIs('sales.index') ? 'active' : '' }}" 
                   href="{{ route('sales.index') }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-list-check me-3 fs-5"></i>
                        <span> Ventes</span>
                    </div>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('reports.*') ? 'active' : '' }}" 
                   href="{{ route('reports.index') }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-graph-up me-3 fs-5"></i>
                        <span>Rapports</span>
                    </div>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('invoices.*') ? 'active' : '' }}" 
                   href="{{ route('invoices.index') }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-receipt me-3 fs-5"></i>
                        <span>Factures</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    .sidebar {
        background: linear-gradient(180deg, #1a2980 0%, #26d0ce 100%) !important;
    }
    .nav-link {
        padding: 12px 20px;
        border-radius: 6px;
        margin: 0 10px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .nav-link.active {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px);
        border-left: 4px solid #4e73df;
    }
    .nav-link:hover:not(.active) {
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }
</style>