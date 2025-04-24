@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        @if(auth()->user()->role === 'admin')
            @include('admin.partials.admin-sidebar')
        @elseif(auth()->user()->role === 'gestionnaire')
            @include('gestionnaire.partials.sidebar_gestionnaire')
        @else
            @include('magasin.partials.sidebar')
        @endif
        <div class="col-md-10">
            <div class="card border-0 shadow">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-person-circle me-2"></i>Profile Details
                        </h4>
                    </div>
                </div>

                <div class="card-body px-4 py-4">
                    <div class="text-center mb-4">
                        @if($user->image && $user->image != 'no_image.jpg')
                            <div class="position-relative d-inline-block">
                                <img src="{{ asset('storage/profile_images/'.$user->image) }}" 
                                     alt="Profile" 
                                     class="rounded-circle border-3 border-primary shadow-lg" 
                                     width="160" 
                                     height="160"
                                     style="object-fit: cover; border-style: solid;">
                                <span class="position-absolute bottom-0 end-0 translate-middle-y badge rounded-pill bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'gestionnaire' ? 'warning' : 'primary') }} px-3 py-2 shadow">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        @else
                            <div class="position-relative d-inline-block">
                                <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-lg" 
                                     style="width: 160px; height: 160px;">
                                    <span class="text-white display-4 fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <span class="position-absolute bottom-0 end-0 translate-middle-y badge rounded-pill bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'gestionnaire' ? 'warning' : 'primary') }} px-3 py-2 shadow">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        @endif
                        <h3 class="mt-4 mb-1 fw-bold">{{ $user->name }}</h3>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-8 mx-auto">
                            <div class="card border-0 shadow">
                                <div class="card-header bg-light rounded-top py-3">
                                    <h5 class="mb-0 fw-semibold text-secondary">
                                        <i class="bi bi-person-badge-fill me-2"></i>Account Information
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small mb-1">Email Address</label>
                                            <div class="p-3 bg-light rounded-3 d-flex align-items-center">
                                                <i class="bi bi-envelope-fill text-primary me-3"></i>
                                                <span class="text-break fw-medium">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small mb-1">Account Status</label>
                                            <div class="p-3 bg-light rounded-3">
                                                <span class="badge bg-{{ $user->status ? 'success' : 'secondary' }} py-2 px-3 rounded-pill d-inline-flex align-items-center">
                                                    <i class="bi bi-{{ $user->status ? 'check-circle-fill' : 'x-circle-fill' }} me-2"></i>
                                                    {{ $user->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-4 mt-2">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small mb-1">Member Since</label>
                                            <div class="p-3 bg-light rounded-3 d-flex align-items-center">
                                                <i class="bi bi-calendar-check-fill text-primary me-3"></i>
                                                <span class="fw-medium">{{ $user->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small mb-1">Last Updated</label>
                                            <div class="p-3 bg-light rounded-3 d-flex align-items-center">
                                                <i class="bi bi-clock-history text-primary me-3"></i>
                                                <span class="fw-medium">{{ $user->updated_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
                                    <i class="bi bi-pencil-fill me-2"></i> Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection