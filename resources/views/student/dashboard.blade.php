@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- Welcome Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 rounded-3 text-white d-flex align-items-center gap-4"
                style="background: linear-gradient(135deg, #0d1624 0%, #1a2a44 100%); border-radius: 16px !important;">
                <div style="width: 70px; height: 70px; background: #ce9d3c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0;">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <h3 class="mb-1 fw-bold" style="font-family: 'Outfit', sans-serif;">
                        Welcome, {{ auth('student')->user()->name }}!
                    </h3>
                    <p class="mb-0 text-white-50">
                        <i class="fas fa-id-badge me-2"></i>{{ auth('student')->user()->student_id }}
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <i class="fas fa-envelope me-2"></i>{{ auth('student')->user()->email }}
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <span class="badge" style="background-color: #ce9d3c; font-size: 0.75rem;">
                            {{ ucfirst(auth('student')->user()->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #ce9d3c !important;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div style="width: 50px; height: 50px; background: rgba(206,157,60,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-file-alt" style="color: #ce9d3c; font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Tests Assigned</p>
                        <h4 class="fw-bold mb-0">0</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div style="width: 50px; height: 50px; background: rgba(16,185,129,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Tests Completed</p>
                        <h4 class="fw-bold mb-0">0</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #3b82f6 !important;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div style="width: 50px; height: 50px; background: rgba(59,130,246,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-chart-bar" style="color: #3b82f6; font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">Average Score</p>
                        <h4 class="fw-bold mb-0">N/A</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- My Profile --}}
        <div class="col-md-5 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between" style="border-radius: 12px 12px 0 0; padding: 1.2rem 1.5rem;">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user me-2" style="color: #ce9d3c;"></i>My Profile</h5>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    @php $student = auth('student')->user(); @endphp
                    <table class="table table-borderless mb-0" style="font-size: 0.95rem;">
                        <tr>
                            <td class="text-muted" style="width: 40%;">Student ID</td>
                            <td class="fw-semibold">{{ $student->student_id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Full Name</td>
                            <td class="fw-semibold">{{ $student->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td class="fw-semibold">{{ $student->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Phone</td>
                            <td class="fw-semibold">{{ $student->phone ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Address</td>
                            <td class="fw-semibold">{{ $student->address ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>
                                <span class="badge" style="background-color: {{ $student->status === 'active' ? '#10b981' : '#ef4444' }};">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Member Since</td>
                            <td class="fw-semibold">{{ $student->created_at->format('d M Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- My Tests --}}
        <div class="col-md-7 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between" style="border-radius: 12px 12px 0 0; padding: 1.2rem 1.5rem;">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2" style="color: #3b82f6;"></i>My Tests & Results</h5>
                </div>
                <div class="card-body p-0">
                    @if(isset($tests) && $tests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-uppercase text-secondary fw-bold" style="font-size: 0.75rem;">
                                    <tr>
                                        <th class="px-4 py-3">Test Name</th>
                                        <th class="py-3">Module Set</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tests as $test)
                                        @php $attempt = $test->attempts->first(); @endphp
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="fw-bold text-dark">{{ $test->name }}</div>
                                            </td>
                                            <td class="py-3 text-muted small">
                                                {{ $test->moduleSet->name ?? 'N/A' }}
                                            </td>
                                            <td class="py-3">
                                                @if(!$attempt)
                                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Not Started</span>
                                                @elseif($attempt->status === 'pending')
                                                    <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">In Progress</span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Completed</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-center">
                                                @if(!$attempt)
                                                    <a href="{{ route('student.tests.show', $test) }}" class="btn btn-sm btn-primary rounded-pill px-4">Take Test</a>
                                                @elseif($attempt->status === 'pending')
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <a href="{{ route('student.tests.show', $test) }}" class="btn btn-sm btn-info text-white rounded-pill px-3">Resume</a>
                                                        <a href="{{ route('student.tests.restart', $test) }}" onclick="return confirm('Restart test and lose current progress?')" class="btn btn-sm btn-outline-danger rounded-pill px-3">Restart</a>
                                                    </div>
                                                @else
                                                    <a href="#" class="btn btn-sm btn-outline-success rounded-pill px-4 disabled">Completed</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-center" style="min-height: 200px;">
                            <div class="text-center text-muted">
                                <i class="fas fa-clipboard-list mb-3" style="font-size: 3rem; color: #e2e8f0;"></i>
                                <p class="mb-0">No tests have been assigned yet.</p>
                                <p class="small">Please contact your administrator.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection


