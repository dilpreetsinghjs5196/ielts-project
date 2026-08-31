@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="fas fa-flag text-danger me-2"></i> Question Issues & Reports</h3>
            <p class="text-muted small mb-0">Review student reported errors, typos, or correction requests on test questions.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.question-reports.index', ['status' => 'all']) }}" class="btn btn-sm {{ request('status') == 'all' || !request('status') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">All</a>
            <a href="{{ route('admin.question-reports.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-secondary' }} rounded-pill">Pending</a>
            <a href="{{ route('admin.question-reports.index', ['status' => 'resolved']) }}" class="btn btn-sm {{ request('status') == 'resolved' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill">Resolved</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Student</th>
                            <th>Category & Test</th>
                            <th>Question #</th>
                            <th>Issue Type</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td class="ps-4 fw-bold">#{{ $report->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($report->student->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $report->student->name ?? 'Unknown Student' }}</div>
                                            <div class="small text-muted">{{ $report->student->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $catColor = match(strtolower($report->category ?? 'reading')) {
                                            'listening' => 'bg-info text-dark',
                                            'reading' => 'bg-success text-white',
                                            'writing' => 'bg-warning text-dark',
                                            'speaking' => 'bg-primary text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                        $catIcon = match(strtolower($report->category ?? 'reading')) {
                                            'listening' => 'fa-headphones',
                                            'reading' => 'fa-book-open',
                                            'writing' => 'fa-pen-nib',
                                            'speaking' => 'fa-comment-dots',
                                            default => 'fa-question-circle'
                                        };
                                    @endphp
                                    <span class="badge {{ $catColor }} text-uppercase px-2 py-1 mb-1">
                                        <i class="fas {{ $catIcon }} me-1"></i> {{ $report->category }}
                                    </span>
                                    <div class="small text-muted">Test ID: #{{ $report->test_id ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-dark px-3 py-1 rounded-pill">
                                        {{ $report->question_number ? $report->question_number : ($report->question_id ? '#' . $report->question_id : 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $typeBadge = match($report->issue_type) {
                                            'typo' => 'bg-info text-dark',
                                            'incorrect_answer' => 'bg-danger',
                                            'confusing_options' => 'bg-warning text-dark',
                                            'media_problem' => 'bg-dark',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $typeBadge }} text-capitalize px-3 py-1 rounded-pill">
                                        {{ str_replace('_', ' ', $report->issue_type) }}
                                    </span>
                                </td>
                                <td style="max-width: 300px;">
                                    <div class="text-truncate" title="{{ $report->description }}">
                                        {{ $report->description ?: 'No details provided' }}
                                    </div>
                                </td>
                                <td>
                                    @if($report->status === 'resolved')
                                        <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill fw-bold">
                                            <i class="fas fa-check-circle me-1"></i> Resolved
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning px-3 py-1 rounded-pill fw-bold">
                                            <i class="fas fa-clock me-1"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="small text-muted">
                                    {{ $report->created_at ? $report->created_at->diffForHumans() : 'N/A' }}
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                        @if($report->status !== 'resolved')
                                            <form action="{{ route('admin.question-reports.update', $report->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="resolved">
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3" title="Mark as Resolved">
                                                    <i class="fas fa-check me-1"></i> Resolve
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.question-reports.update', $report->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill px-3" title="Reopen Report">
                                                    <i class="fas fa-undo me-1"></i> Reopen
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.question-reports.destroy', $report->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this report?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Delete Report" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fas fa-flag-checkered fs-1 mb-3 opacity-50"></i>
                                    <p class="mb-0 fw-semibold">No question reports found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($reports, 'hasPages') && $reports->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
