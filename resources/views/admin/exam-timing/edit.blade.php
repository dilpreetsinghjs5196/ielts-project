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

                        <!-- Listening Audio Speed Setting (1.5x Toggle) -->
                        <div class="mb-4 p-4 rounded-3 border" style="background: #fdfaf3; border-color: rgba(206, 157, 60, 0.3) !important; border-radius: 12px;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="pe-3">
                                    <label for="listening_speed_1_5x" class="form-label fw-bold text-dark mb-1 d-flex align-items-center gap-2" style="font-family: 'Outfit', sans-serif; cursor: pointer; font-size: 1.05rem;">
                                        <i class="fas fa-headphones text-warning" style="color: #ce9d3c !important;"></i>
                                        <span>Increase Listening Test Audio Speed to 1.5x</span>
                                        <span class="badge {{ old('listening_speed_1_5x', $timing->listening_speed_1_5x) ? 'bg-warning text-dark' : 'bg-secondary' }}" id="speedBadge" style="font-size: 0.75rem; transition: all 0.2s;">
                                            {{ old('listening_speed_1_5x', $timing->listening_speed_1_5x) ? '1.5x Enabled' : '1.0x Default' }}
                                        </span>
                                    </label>
                                    <p class="text-muted small mb-0" style="line-height: 1.5;">
                                        When toggle is <strong>ON</strong>, all student listening tests will play audio at <strong>1.5x speed</strong>. When <strong>OFF</strong>, audio will play at the normal default speed (1.0x).
                                    </p>
                                </div>
                                <div class="form-check form-switch m-0 fs-3">
                                    <input class="form-check-input custom-speed-switch" 
                                           type="checkbox" 
                                           name="listening_speed_1_5x" 
                                           id="listening_speed_1_5x" 
                                           value="1" 
                                           {{ old('listening_speed_1_5x', $timing->listening_speed_1_5x) ? 'checked' : '' }}
                                           onchange="updateSpeedBadge(this)"
                                           style="cursor: pointer; width: 2.8em; height: 1.5em;">
                                </div>
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
    .custom-speed-switch:checked {
        background-color: #ce9d3c !important;
        border-color: #ce9d3c !important;
    }
    .custom-speed-switch:focus {
        border-color: #ce9d3c !important;
        box-shadow: 0 0 0 0.25rem rgba(206, 157, 60, 0.25) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function updateSpeedBadge(checkbox) {
        const badge = document.getElementById('speedBadge');
        if (!badge) return;
        if (checkbox.checked) {
            badge.className = 'badge bg-warning text-dark';
            badge.innerText = '1.5x Enabled';
        } else {
            badge.className = 'badge bg-secondary';
            badge.innerText = '1.0x Default';
        }
    }
</script>
@endpush
@endsection
