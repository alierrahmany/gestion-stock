<div class="col-md-3 col-lg-2 d-md-block sidebar">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4 py-3">
            <h4 class="text-white fw-bold mb-0">ADMINISTRATION</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                   href="{{ route('admin.dashboard') }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-speedometer2 me-3 fs-5"></i>
                        <span>Tableau de bord</span>
                    </div>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->routeIs('users.*') ? 'active' : '' }}" 
                   href="{{ route('users.index') }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-people-fill me-3 fs-5"></i>
                        <span>Gestion Utilisateurs</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    .sidebar {
        min-height: calc(100vh - 56px);
        background: linear-gradient(180deg, #2c3e50 0%, #1a1a2e 100%);
        box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
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
    .sidebar hr {
        border-color: rgba(255, 255, 255, 0.1);
    }
</style>