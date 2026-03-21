@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Attempt Details</h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold">Student Info</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto bg-light rounded-circle d-flex align-items-center justify-content-center text-primary font-weight-bold mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ strtoupper(substr($attempt->student->name, 0, 1)) }}
                    </div>
                    <h5 class="mb-1 fw-bold">{{ $attempt->student->name }}</h5>
                    <p class="text-muted small mb-0">{{ $attempt->student->student_id }}</p>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="text-muted small d-block">Test Name</label>
                    <span class="fw-bold">{{ $attempt->test->name }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Module Set</label>
                    <span class="fw-bold">{{ $attempt->test->moduleSet->name ?? 'N/A' }}</span>
                </div>
                <div class="mb-0">
                    <label class="text-muted small d-block">Time Started</label>
                    <span class="fw-bold">{{ $attempt->started_at->format('d M Y, H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Submitted Answers</h6>
                @if($attempt->status === 'completed')
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Completed</span>
                @else
                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">In Progress</span>
                @endif
            </div>
            <div class="card-body">
                @if($attempt->answers)
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="100">Q. No</th>
                                    <th>Student Answer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attempt->answers as $qId => $answer)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $qId }}</td>
                                        <td>
                                            @if(is_array($answer))
                                                {{ implode(', ', $answer) }}
                                            @else
                                                {{ $answer }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-muted">No answers submitted yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
