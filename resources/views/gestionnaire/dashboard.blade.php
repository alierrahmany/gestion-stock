@extends('layouts.app')

@section('sidebar')
@include('gestionnaire.partials.sidebar_gestionnaire')
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h2 fw-bold text-dark">Tableau de Bord Gestionnaire</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-calendar me-1"></i> {{ now()->format('d/m/Y') }}
                </button>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill me-2"></i> Bienvenue, {{ Auth::user()->name }}! Vous êtes connecté en tant que gestionnaire.
    </div>
    
    <!-- Add manager-specific content here -->
</div>
@endsection