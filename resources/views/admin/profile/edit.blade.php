@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row pt-4">
        <!-- Profile Form Card -->
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <!-- Header -->
                <div class="card-header bg-white border-bottom" style="padding: 1.5rem; text-align: center;">
                    <div style="width: 80px; height: 80px; background: #ce9d3c; color: #fff; font-size: 2.5rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 0 auto 15px auto;">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h3 class="font-weight-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">{{ auth()->user()->name }}'s Profile</h3>
                    <p class="text-muted small mt-1">Manage your account details and password</p>
                </div>

                <!-- Body -->
                <div class="card-body" style="padding: 2rem;">
                    <form method="POST" action="{{ route('admin.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-4 text-primary font-weight-bold"><i class="fas fa-info-circle me-2"></i> Account Information</h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label font-weight-bold text-dark">Full Name <span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" style="border-radius: 8px;">
                                @error('name')
                                    <span class="text-danger small mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label font-weight-bold text-dark">Email Address <span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email" style="border-radius: 8px;">
                                @error('email')
                                    <span class="text-danger small mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4" style="opacity: 0.1;">

                        <h5 class="mb-4 text-danger font-weight-bold"><i class="fas fa-lock me-2"></i> Update Password</h5>
                        <p class="small text-muted mb-4">Leave the password fields blank if you do not want to change your password.</p>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label font-weight-bold text-dark">New Password</label>
                                <input id="password" type="password" class="form-control" name="password" autocomplete="new-password" style="border-radius: 8px;">
                                @error('password')
                                    <span class="text-danger small mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password-confirm" class="form-label font-weight-bold text-dark">Confirm Password</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" style="border-radius: 8px;">
                            </div>
                        </div>

                        <!-- Footer/Submit -->
                        <div class="d-flex justify-content-end mt-5 border-top pt-4">
                            <button type="submit" class="btn text-white px-5 py-2 font-weight-bold" style="background-color: #ce9d3c; border-radius: 8px; transition: 0.3s; border: none; box-shadow: 0 4px 10px rgba(206,157,60,0.3);">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
