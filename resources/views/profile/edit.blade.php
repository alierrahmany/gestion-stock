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
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h4 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Edit Profile
                    </h4>
                </div>

                <div class="card-body px-4 py-4">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4 align-items-center">
                            <div class="col-md-4 text-center">
                                <div class="position-relative mb-3 d-inline-block">
                                    @if($user->image && $user->image != 'no_image.jpg')
                                        <img src="{{ asset('storage/profile_images/'.$user->image) }}" 
                                             alt="Profile" 
                                             class="rounded-circle border-3 border-primary shadow-lg" 
                                             width="150" 
                                             height="150"
                                             style="object-fit: cover; border-style: solid;">
                                    @else
                                        <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-lg" 
                                             style="width: 150px; height: 150px;">
                                            <span class="text-white display-4 fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <label for="image" class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 shadow-lg cursor-pointer" style="cursor: pointer; border: 2px solid white;">
                                        <i class="bi bi-camera-fill text-white fs-5"></i>
                                        <input type="file" class="d-none" id="image" name="image">
                                    </label>
                                </div>
                                <small class="text-muted fst-italic">Click camera icon to change photo</small>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-medium">Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-person-fill"></i></span>
                                        <input type="text" class="form-control rounded-end py-2" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="form-label fw-medium">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-envelope-fill"></i></span>
                                        <input type="email" class="form-control rounded-end py-2" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                </div>

                                @if($user->isAdmin())
                                <div class="mb-4">
                                    <label for="status" class="form-label fw-medium">Status</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-toggle-on"></i></span>
                                        <select class="form-select rounded-end py-2" id="status" name="status">
                                            <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="card mb-4 border-0 shadow">
                            <div class="card-header bg-light rounded-top py-3">
                                <h5 class="mb-0 fw-semibold text-secondary">
                                    <i class="bi bi-key-fill me-2"></i>Change Password (Optional)
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label fw-medium">Current Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" class="form-control rounded-end py-2" id="current_password" name="current_password">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label fw-medium">New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-shield-lock-fill"></i></span>
                                        <input type="password" class="form-control rounded-end py-2" id="new_password" name="new_password">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-medium">Confirm New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-check-circle-fill"></i></span>
                                        <input type="password" class="form-control rounded-end py-2" id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between border-top pt-4 mb-2">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                <i class="bi bi-arrow-left me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
                                <i class="bi bi-check-circle me-2"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection