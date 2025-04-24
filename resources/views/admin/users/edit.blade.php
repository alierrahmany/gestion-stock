@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('admin.partials.admin-sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Modifier l'utilisateur</h1>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrateur</option>
                                <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Utilisateur</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactif</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection