@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-2">
    <!-- Header Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold" style="letter-spacing: -0.5px;">Exam Timing Settings</h1>
            <p class="text-muted mb-0 small">Configure global duration limits for mock tests</p>
        </div>
        <div class="breadcrumb-wrapper">
            <span class="badge bg-light text-dark p-2 border">
                <i class="fas fa-cog text-muted me-1"></i> System Configuration
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-9 mx-auto">
            <!-- Settings Card -->
            <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 15px;">
                <!-- Card Header with Golden Accent Line -->
                <div style="height: 4px; background: #ce9d3c;"></div>
                
                <div class="card-body p-4 p-md-5">
                    <!-- Info Alert Box -->
                    <div class="alert border-0 d-flex align-items-start gap-3 mb-4 shadow-sm" style="background-color: rgba(206, 157, 60, 0.08); border-left: 4px solid #ce9d3c !important; border-radius: 10px; padding: 1.25rem;">
                        <div class="alert-icon bg-white text-warning rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; color: #ce9d3c !important; flex-shrink: 0;">
                            <i class="fas fa-info-circle fs-5" style="color: #ce9d3c;"></i>
                        </div>
                        <div class="alert-content flex-grow-1">
                            <h6 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Global Exam Timing</h6>
                            <p class="mb-0 text-muted small" style="line-height: 1.6; color: #555 !important;">
                                This setting defines the duration limit (in minutes) for all student test modules, including <strong>Listening</strong>, <strong>Reading</strong>, and <strong>Writing</strong>. 
                                The timer will run seamlessly in the background on the frontend test interface without showing a visible countdown, and will <strong>automatically finish and submit</strong> the student's answers once the configured time has elapsed.
                            </p>
                        </div>
                    </div>

                    <!-- Config Form -->
                    <form action="{{ route('admin.exam-timing.update') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="exam_time" class="form-label fw-bold text-dark mb-2" style="font-family: 'Outfit', sans-serif;">
                                <i class="fas fa-hourglass-half text-muted me-1"></i> Exam Duration Limit (Minutes)
                            </label>
                            <div class="input-group input-group-lg shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                <input type="number" 
                                       name="exam_time" 
                                       id="exam_time" 
                                       value="{{ old('exam_time', $timing->exam_time) }}" 
                                       class="form-control border-end-0 @error('exam_time') is-invalid @enderror" 
                                       placeholder="e.g. 60" 
                                       min="1" 
                                       max="1440" 
                                       required
                                       style="border-radius: 10px 0 0 10px; font-weight: 500; font-family: 'Outfit', sans-serif;">
                                <span class="input-group-text bg-light border-start-0 text-muted fw-bold" style="border-radius: 0 10px 10px 0; font-family: 'Outfit', sans-serif;">
                                    minutes
                                </span>
                                @error('exam_time')
                                    <div class="invalid-feedback px-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-text mt-2 text-muted small">
                                Enter a duration between 1 minute and 1440 minutes (24 hours). The default recommended IELTS time limit is 60 minutes.
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex align-items-center justify-content-end gap-3 mt-5 pt-4 border-top">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light px-4 py-2 fw-bold" style="border-radius: 8px; border: 1px solid #ddd; transition: 0.3s;">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn text-white px-5 py-2 fw-bold" style="background-color: #ce9d3c; border-radius: 8px; border: none; transition: 0.3s; box-shadow: 0 4px 10px rgba(206,157,60,0.3);">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .input-group-text {
        background-color: #f8fafc;
        border-color: #e2e8f0;
    }
    .form-control:focus {
        border-color: #ce9d3c !important;
        box-shadow: 0 0 0 3px rgba(206, 157, 60, 0.1) !important;
    }
</style>
@endpush
@endsection
