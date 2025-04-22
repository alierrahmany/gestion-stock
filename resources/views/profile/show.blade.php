@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary fw-bold">
                            <i class="bi bi-person-circle me-2"></i>Profile Details
                        </h4>
                       
                        
                    </div>
                </div>

                <div class="card-body px-4 py-3">
                    <div class="text-center mb-4">
                        @if($user->image && $user->image != 'no_image.jpg')
                            <img src="{{ asset('storage/profile_images/'.$user->image) }}" 
                                 alt="Profile" 
                                 class="rounded-circle border border-4 border-primary shadow" 
                                 width="150" 
                                 height="150"
                                 style="object-fit: cover;">
                        @else
                            <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center mx-auto shadow" 
                                 style="width: 150px; height: 150px;">
                                <span class="text-white display-4 fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <h3 class="mt-3 mb-1 fw-bold">{{ $user->name }}</h3>
                        
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-8 mx-auto">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light bg-gradient rounded-top py-3">
                                    <h5 class="mb-0 text-secondary fw-semibold">
                                        <i class="bi bi-person-badge-fill me-2"></i>Account Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small mb-1">Email Address</label>
                                            <div class="p-2 bg-light rounded d-flex align-items-center">
                                                <i class="bi bi-envelope-fill text-primary me-2"></i>
                                                <span class="text-break">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small mb-1">Account Status</label>
                                            <div>
                                                <span class="badge bg-{{ $user->status ? 'success' : 'secondary' }} py-2 px-3 rounded-pill d-inline-flex align-items-center">
                                                    <i class="bi bi-{{ $user->status ? 'check-circle-fill' : 'x-circle-fill' }} me-1"></i>
                                                    {{ $user->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small mb-1">Member Since</label>
                                            <div class="p-2 bg-light rounded d-flex align-items-center">
                                                <i class="bi bi-calendar-check me-2 text-primary"></i>
                                                {{ $user->created_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small mb-1">Last Updated</label>
                                            <div class="p-2 bg-light rounded d-flex align-items-center">
                                                <i class="bi bi-clock-history me-2 text-primary"></i>
                                                {{ $user->updated_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-2">
                                <i class="bi bi-pencil-fill me-1"></i> Edit Profile
                            </a>
                            
                            
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
</div>
@endsection