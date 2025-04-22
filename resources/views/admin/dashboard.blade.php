@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('admin.sidebar.admin-sidebar')

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h2 fw-bold text-dark">Tableau de Bord</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-calendar me-1"></i> {{ now()->format('d/m/Y') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-4 mb-4">
                <!-- Admin Card -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Administrateurs</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userCounts['admin'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-shield-lock fs-1 text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Manager Card -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Gestionnaires</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userCounts['gestionnaire'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-person-gear fs-1 text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Card -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Magasin</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userCounts['magasin'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-people fs-1 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
        </main>
    </div>
</div>

<style>
    .card {
        border-radius: 10px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .border-left-danger {
        border-left: 4px solid #e74a3b;
    }
    .border-left-warning {
        border-left: 4px solid #f6c23e;
    }
    .border-left-primary {
        border-left: 4px solid #4e73df;
    }
    .shadow {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    }
    .text-gray-800 {
        color: #5a5c69;
    }
</style>
@endsection