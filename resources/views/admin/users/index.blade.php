@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('admin.sidebar.admin-sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-light">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 text-gray-800">Gérer les Utilisateurs</h1>
                <a href="{{ route('users.create') }}" class="btn btn-outline-dark">
                    <i class="bi bi-plus-circle"></i> Ajouter un utilisateur
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success bg-success text-white border-0">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-gray-700 text-white">
                        <tr>
                            <th class="py-3">Nom</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Rôle</th>
                            <th class="py-3">Statut</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="bg-white">
                            <td class="py-3 align-middle">{{ $user->name }}</td>
                            <td class="py-3 align-middle">{{ $user->email }}</td>
                            <td class="py-3 align-middle">
                                <span class="badge bg-{{ $user->role === 'admin' ? 'gray-600' : ($user->role === 'manager' ? 'gray-500' : 'gray-400') }} text-white">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-3 align-middle">
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="py-3 align-middle">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-gray-600">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
@endsection

<style>
    .bg-gray-700 {
        background-color: #374151;
    }
    .bg-gray-600 {
        background-color: #4b5563;
    }
    .bg-gray-500 {
        background-color: #6b7280;
    }
    .bg-gray-400 {
        background-color: #9ca3af;
    }
    .btn-outline-gray-600 {
        border-color: #4b5563;
        color: #4b5563;
    }
    .btn-outline-gray-600:hover {
        background-color: #4b5563;
        color: white;
    }
    .bg-light {
        background-color: #f3f4f6;
    }
    .text-gray-800 {
        color: #1f2937;
    }
    .table-hover tbody tr:hover {
        background-color: #f9fafb;
    }
    .py-3 {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    .align-middle {
        vertical-align: middle;
    }
</style>