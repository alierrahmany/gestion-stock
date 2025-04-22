@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
             
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h4 class="mb-0 text-primary fw-bold">
                        <i class="bi bi-pencil-square me-2"></i>Edit Profile
                    </h4>
                </div>

                <div class="card-body px-4">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                <div class="position-relative mb-3">
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
                                    <label for="image" class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 shadow cursor-pointer">
                                        <i class="bi bi-camera-fill text-white"></i>
                                        <input type="file" class="d-none" id="image" name="image">
                                    </label>
                                </div>
                                <small class="text-muted">Click camera icon to change photo</small>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-medium">Name</label>
                                    <input type="text" class="form-control rounded-3 py-2" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="form-label fw-medium">Email</label>
                                    <input type="email" class="form-control rounded-3 py-2" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>

                                @if($user->isAdmin())
                                <div class="mb-4">
                                    <label for="status" class="form-label fw-medium">Status</label>
                                    <select class="form-select rounded-3 py-2" id="status" name="status">
                                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-light bg-gradient rounded-top py-3">
                                <h5 class="mb-0 text-secondary fw-semibold">
                                    <i class="bi bi-key-fill me-2"></i>Change Password (Optional)
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label fw-medium">Current Password</label>
                                    <input type="password" class="form-control rounded-3 py-2" id="current_password" name="current_password">
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label fw-medium">New Password</label>
                                    <input type="password" class="form-control rounded-3 py-2" id="new_password" name="new_password">
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-medium">Confirm New Password</label>
                                    <input type="password" class="form-control rounded-3 py-2" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between border-top pt-4 mb-2">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-check-circle me-1"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection