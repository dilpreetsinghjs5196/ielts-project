@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">My Tests</h2>
        <p class="text-muted">A complete list of all mock tests assigned to you.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between" style="border-radius: 15px 15px 0 0; padding: 1.2rem 1.5rem;">
                <h6 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2 text-primary"></i>All Mock Tests</h6>
            </div>
            <div class="card-body p-0">
                @if(isset($tests) && $tests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-uppercase text-secondary fw-bold" style="font-size: 0.75rem;">
                                <tr>
                                    <th class="px-4 py-3">Test Name</th>
                                    <th class="py-3">Module</th>
                                    <th class="py-3">Set</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tests as $test)
                                    @php $attempt = $test->attempts->first(); @endphp
                                    <tr>
                                        <td class="px-4 py-4">
                                            <div class="fw-bold text-dark">{{ $test->name }}</div>
                                        </td>
                                        <td class="py-4">
                                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">{{ $test->category->name ?? 'N/A' }}</span>
                                        </td>
                                        <td class="py-4 text-muted small">
                                            {{ $test->moduleSet->name ?? 'N/A' }}
                                        </td>
                                        <td class="py-4">
                                            @if(!$attempt)
                                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Not Started</span>
                                            @elseif($attempt->status === 'pending')
                                                <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">In Progress</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Completed</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-center">
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
                    <div class="d-flex align-items-center justify-content-center" style="min-height: 300px;">
                        <div class="text-center text-muted">
                            <i class="fas fa-clipboard-list mb-3" style="font-size: 4rem; color: #e2e8f0;"></i>
                            <h5 class="mb-1">No tests assigned</h5>
                            <p class="small">When your instructor assigns a test, it will appear here.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
