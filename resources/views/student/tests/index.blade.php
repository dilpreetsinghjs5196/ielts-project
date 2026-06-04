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
                                    <th class="py-3">Score</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $allTests = $tests->map(function($t) { 
                                        $t->view_category = 'reading'; 
                                        return $t; 
                                    })->concat($writingTests->map(function($t) { 
                                        $t->view_category = 'writing'; 
                                        return $t; 
                                    }))->concat(isset($listeningTests) ? $listeningTests->map(function($t) { 
                                        $t->view_category = 'listening'; 
                                        return $t; 
                                    }) : collect())->concat(isset($speakingTests) ? $speakingTests->map(function($t) { 
                                        $t->view_category = 'speaking'; 
                                        return $t; 
                                    }) : collect());
                                @endphp

                                @foreach($allTests as $test)
                                    @php $attempt = method_exists($test, 'attempts') ? $test->attempts->first() : null; @endphp
                                    <tr>
                                        <td class="px-4 py-4">
                                            <div class="fw-bold text-dark">{{ $test->name }}</div>
                                        </td>
                                        <td class="py-4">
                                            <span class="badge bg-{{ in_array($test->view_category, ['writing', 'speaking', 'listening']) ? 'info' : 'primary' }}-subtle text-{{ in_array($test->view_category, ['writing', 'speaking', 'listening']) ? 'info' : 'primary' }} px-3 py-2 rounded-pill">
                                                {{ ucfirst($test->view_category) }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-muted small">
                                            {{ $test->moduleSet->name ?? ($test->level->name ?? 'N/A') }}
                                        </td>
                                        <td class="py-4 fw-bold">
                                            @if($attempt && ($attempt->status === 'completed' || ($attempt->status ?? '') === 'graded'))
                                                @if(in_array($test->view_category, ['writing', 'speaking']))
                                                    <span class="text-primary">{{ $attempt->score ?? 'Pending' }}</span>
                                                    @if($attempt->score) <small class="text-muted">/ 9.0</small> @endif
                                                @elseif($test->view_category === 'listening')
                                                    <span class="text-primary">{{ $attempt->score ?? 0 }}</span>
                                                    <small class="text-muted">/ 40</small>
                                                @else
                                                    <span class="text-primary">{{ $attempt->score ?? 0 }}</span>
                                                    @php $totalMarks = $test->questionGroups->sum(fn($g) => $g->questions->sum('marks')); @endphp
                                                    <small class="text-muted">/ {{ $totalMarks > 0 ? $totalMarks : 40 }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            @if(!$attempt)
                                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Not Started</span>
                                            @elseif($attempt->status === 'pending')
                                                <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">In Progress</span>
                                            @elseif(in_array($test->view_category, ['writing', 'speaking']))
                                                @if($attempt->score !== null)
                                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Graded</span>
                                                @else
                                                    <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">Submitted</span>
                                                @endif
                                            @else
                                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Completed</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-center">
                                            @php
                                                $categoryParam = in_array($test->view_category, ['writing', 'speaking', 'listening']) ? '?category=' . $test->view_category : '';
                                            @endphp
                                            @if(!$attempt)
                                                <a href="{{ route('student.tests.show', $test->id) }}{{ $categoryParam }}" class="btn btn-sm btn-primary rounded-pill px-4">Take Test</a>
                                            @elseif($attempt->status === 'pending')
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <a href="{{ route('student.tests.show', $test->id) }}{{ $categoryParam }}" class="btn btn-sm btn-info text-white rounded-pill px-3">Resume</a>
                                                    <a href="{{ route('student.tests.restart', ['id' => $test->id, 'category' => $test->view_category]) }}" onclick="return confirm('Restart test and lose current progress?')" class="btn btn-sm btn-outline-danger rounded-pill px-3">Restart</a>
                                                </div>
                                            @elseif(in_array($test->view_category, ['writing', 'speaking']) && $attempt->score === null)
                                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-4" disabled>Awaiting Grade</button>
                                            @else
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <a href="{{ route('student.tests.review', ['id' => $test->id, 'category' => $test->view_category]) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">Review Result</a>
                                                    <a href="{{ route('student.tests.restart', ['id' => $test->id, 'category' => $test->view_category]) }}" onclick="return confirm('Retrying will delete your current score. Are you sure?')" class="btn btn-sm btn-outline-warning rounded-pill px-3">Retry</a>
                                                </div>
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
