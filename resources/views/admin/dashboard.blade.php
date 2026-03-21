@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Admin Dashboard</h2>
        <p class="text-muted">Welcome to the IELTS Test Management System Admin Panel.</p>
    </div>
</div>

<!-- Quick Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card bg-primary text-white h-100 p-4" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #3b82f6, #60a5fa);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 text-white-50 font-weight-bold" style="font-size: 0.8rem; letter-spacing: 1px;">Total Students</h6>
                    <h2 class="mb-0 font-weight-bold">{{ number_format($stats['total_students']) }}</h2>
                </div>
                <div>
                    <i class="fas fa-users fa-2x text-white-50"></i>
                </div>
            </div>
            <a href="{{ route('admin.students.index') }}" class="text-white text-decoration-none mt-3 d-inline-block" style="font-size: 0.85rem;">View Details <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-white h-100 p-4" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #10b981, #34d399);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 text-white-50 font-weight-bold" style="font-size: 0.8rem; letter-spacing: 1px;">Tests Created</h6>
                    <h2 class="mb-0 font-weight-bold">{{ number_format($stats['total_tests']) }}</h2>
                </div>
                <div>
                    <i class="fas fa-file-alt fa-2x text-white-50"></i>
                </div>
            </div>
            <a href="{{ route('admin.tests.index') }}" class="text-white text-decoration-none mt-3 d-inline-block" style="font-size: 0.85rem;">Manage Tests <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-white h-100 p-4" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #f59e0b, #fbbf24);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 text-white-50 font-weight-bold" style="font-size: 0.8rem; letter-spacing: 1px;">Questions Bank</h6>
                    <h2 class="mb-0 font-weight-bold">{{ number_format($stats['total_questions']) }}</h2>
                </div>
                <div>
                    <i class="fas fa-question-circle fa-2x text-white-50"></i>
                </div>
            </div>
            <a href="{{ route('admin.questions.index') }}" class="text-white text-decoration-none mt-3 d-inline-block" style="font-size: 0.85rem;">Add Questions <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-white h-100 p-4" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 text-white-50 font-weight-bold" style="font-size: 0.8rem; letter-spacing: 1px;">Recent Attempts</h6>
                    <h2 class="mb-0 font-weight-bold">{{ number_format($stats['recent_attempts']) }}</h2>
                </div>
                <div>
                    <i class="fas fa-chart-line fa-2x text-white-50"></i>
                </div>
            </div>
            <a href="{{ route('admin.results.index') }}" class="text-white text-decoration-none mt-3 d-inline-block" style="font-size: 0.85rem;">View Results <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>
</div>

<!-- Recent Activity / Tests List Overview -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card h-100" style="border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.03);">
            <div class="card-header bg-white d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #e2e8f0; padding: 20px;">
                <h5 class="m-0 font-weight-bold text-dark" style="font-size: 1.1rem;">Recent Test Submissions</h5>
                <a href="{{ route('admin.results.index') }}" class="btn btn-sm btn-outline-primary" style="font-size: 0.8rem; border-radius: 20px;">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">Student</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Test Module</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Score</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentSubmissions as $submission)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white me-3" style="width: 40px; height: 40px; font-weight: 600;">
                                            {{ strtoupper(substr($submission->student->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-0 text-dark text-sm" style="font-weight: 600;">{{ $submission->student->name ?? 'N/A' }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $submission->student->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0 text-dark">{{ $submission->test->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-secondary mb-0">{{ $submission->test->moduleSet->name ?? 'N/A' }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="font-weight-bold text-dark">{{ $submission->score ?? '--' }}</span> / 9.0
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @if($submission->status === 'completed')
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2" style="border-radius: 20px;">Evaluated</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2" style="border-radius: 20px;">In Progress</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $submission->created_at->format('M d, Y') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-muted">No recent submissions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview Snippet -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100" style="border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.03);">
            <div class="card-header bg-white" style="border-bottom: 1px solid #e2e8f0; padding: 20px;">
                <h5 class="m-0 font-weight-bold text-dark" style="font-size: 1.1rem;">Quick Actions</h5>
            </div>
            <div class="card-body p-4 d-flex flex-column gap-3">
                <a href="{{ route('admin.tests.create') }}" class="btn btn-primary d-flex align-items-center gap-3 p-3 text-start" style="border-radius: 10px;">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-plus"></i></div>
                    <div>
                        <h6 class="mb-0 text-white font-weight-bold">Create New Test</h6>
                        <small class="text-white-50">Setup L, R, W, or S test</small>
                    </div>
                </a>
                
                <a href="{{ route('admin.questions.create') }}" class="btn btn-outline-secondary d-flex align-items-center gap-3 p-3 text-start" style="border-radius: 10px;">
                    <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-question"></i></div>
                    <div>
                        <h6 class="mb-0 font-weight-bold text-dark">Add Questions</h6>
                        <small class="text-muted">Fill banks with new questions</small>
                    </div>
                </a>
                
                <a href="{{ route('admin.students.create') }}" class="btn btn-outline-secondary d-flex align-items-center gap-3 p-3 text-start" style="border-radius: 10px;">
                    <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-user-plus"></i></div>
                    <div>
                        <h6 class="mb-0 font-weight-bold text-dark">Register Student</h6>
                        <small class="text-muted">Add new user manually</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

