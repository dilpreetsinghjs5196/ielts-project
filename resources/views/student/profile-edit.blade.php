@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 text-white d-flex align-items-center justify-content-between gap-4"
                style="background: linear-gradient(135deg, #0d1624 0%, #1a2a44 100%); border-radius: 16px;">
                <div class="d-flex align-items-center gap-4">
                    <div style="width: 70px; height: 70px; background: #ce9d3c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0;">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold" style="font-family: 'Outfit', sans-serif;">Edit My Profile</h3>
                        <p class="mb-0 text-white-50">Update your personal information and password</p>
                    </div>
                </div>
                <a href="{{ route('student.profile') }}" class="btn btn-outline-light" style="border-radius: 8px;">
                    <i class="fas fa-arrow-left me-2"></i>Back to Profile
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">

                <div class="card-body" style="padding: 2.5rem;">
                    <form method="POST" action="{{ route('student.profile.update') }}">
                        @csrf
                        @method('PUT')

                        {{-- Personal Information Section --}}
                        <h5 class="fw-bold mb-4" style="color: #0d1624;">
                            <i class="fas fa-user me-2" style="color: #ce9d3c;"></i> Personal Information
                        </h5>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold text-dark">Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $student->name) }}" required style="border-radius: 8px;">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold text-dark">Email Address <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $student->email) }}" required style="border-radius: 8px;">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold text-dark">Phone Number</label>
                                <input type="text" id="phone" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $student->phone) }}" placeholder="e.g. +91 9876543210" style="border-radius: 8px;">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-dark">Student ID</label>
                                <input type="text" class="form-control bg-light" value="{{ $student->student_id }}" disabled style="border-radius: 8px;">
                                <div class="form-text">Student ID cannot be changed.</div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="address" class="form-label fw-bold text-dark">Address</label>
                                <textarea id="address" name="address" rows="3"
                                    class="form-control @error('address') is-invalid @enderror"
                                    placeholder="Your address..." style="border-radius: 8px;">{{ old('address', $student->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr style="opacity: 0.1;">

                        {{-- Password Section --}}
                        <h5 class="fw-bold mb-1 mt-4" style="color: #0d1624;">
                            <i class="fas fa-lock me-2" style="color: #3b82f6;"></i> Change Password
                        </h5>
                        <p class="text-muted small mb-4">Leave blank to keep your current password.</p>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold text-dark">New Password</label>
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Min. 8 characters" style="border-radius: 8px;">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-bold text-dark">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="Repeat new password" style="border-radius: 8px;">
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex justify-content-end gap-3 mt-4 border-top pt-4">
                            <a href="{{ route('student.profile') }}" class="btn btn-light px-4" style="border-radius: 8px;">
                                Cancel
                            </a>
                            <button type="submit" class="btn text-white px-5 py-2 fw-bold"
                                style="background-color: #ce9d3c; border-radius: 8px; border: none; box-shadow: 0 4px 10px rgba(206,157,60,0.3);">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

