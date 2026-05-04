@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0 text-gray-800" style="font-weight: 700;">
                {{ $student ? $student->name . "'s " : "All " }} Test Results
            </h2>
            <p class="text-muted">Monitor student progress and completed mock tests.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-uppercase text-secondary font-weight-bolder opacity-7" style="font-size: 0.75rem;">Student</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7" style="font-size: 0.75rem;">Test Name</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7 text-center" style="font-size: 0.75rem;">Module</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7 text-center" style="font-size: 0.75rem;">Status</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7 text-center" style="font-size: 0.75rem;">Score</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7 text-center" style="font-size: 0.75rem;">Date</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7 text-center" style="font-size: 0.75rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attempts as $attempt)
                            @php
                                $isWriting = $attempt->attempt_type === 'writing';
                                $isListening = $attempt->attempt_type === 'listening';
                                $test = $isWriting ? $attempt->writingTest : ($isListening ? $attempt->listeningTest : $attempt->test);
                            @endphp
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3 bg-light rounded-circle d-flex align-items-center justify-content-center text-primary font-weight-bold" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($attempt->student->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 font-weight-bold" style="font-size: 0.9rem;">{{ $attempt->student->name ?? 'Deleted Student' }}</h6>
                                            <small class="text-muted">{{ $attempt->student->student_id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="mb-0 font-weight-bold" style="font-size: 0.9rem;">{{ $test->name ?? 'Deleted Test' }}</h6>
                                    <small class="text-muted">
                                        @if($isWriting || $isListening)
                                            {{ $test->level->name ?? ($isWriting ? 'Writing' : 'Listening') . ' Module' }}
                                        @else
                                            {{ $test->moduleSet->name ?? 'N/A' }}
                                        @endif
                                    </small>
                                </td>
                                <td class="text-center">
                                    @php
                                        $badgeClass = 'bg-primary-subtle text-primary';
                                        $moduleName = 'Standard';
                                        if($isWriting) { $badgeClass = 'bg-info-subtle text-info'; $moduleName = 'Writing'; }
                                        elseif($isListening) { $badgeClass = 'bg-warning-subtle text-warning'; $moduleName = 'Listening'; }
                                        else { $moduleName = $test->category->name ?? 'Standard'; }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2" style="font-size: 0.75rem;">
                                        {{ $moduleName }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($attempt->status === 'pending')
                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2" style="font-size: 0.75rem;">In Progress</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2" style="font-size: 0.75rem;">Completed</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <h6 class="mb-0 font-weight-bold" style="color: {{ ($isWriting ? $attempt->score >= 5 : ($isListening ? $attempt->score >= 20 : $attempt->score >= 20)) ? '#10b981' : '#ef4444' }};">
                                        {{ $attempt->score ?? 'N/A' }} {{ $isWriting ? '' : '/ 40' }}
                                    </h6>
                                    @if($isWriting && $attempt->score === null)
                                        <small class="text-muted">Not Graded</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="text-muted small">
                                        {{ $attempt->created_at ? $attempt->created_at->format('d M Y, H:i') : 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.results.review', ['attempt' => $attempt->id, 'type' => $attempt->attempt_type]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Review</a>
                                        <form action="{{ route('admin.results.destroy', ['result' => $attempt->id, 'type' => $attempt->attempt_type]) }}" method="POST" onsubmit="return confirm('Delete this attempt?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center">
                                    <h5 class="text-muted">No Test Results Found</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
