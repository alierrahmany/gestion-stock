@extends('layouts.app')

@section('sidebar')
    @include('admin.partials.admin-sidebar')
@endsection


@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h2 fw-bold text-dark">Tableau de Bord Administrateur</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-calendar me-1"></i> {{ now()->format('d/m/Y') }}
                </button>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
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

        <div class="col-xl-4 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Magasins</div>
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
</div>
@endsection