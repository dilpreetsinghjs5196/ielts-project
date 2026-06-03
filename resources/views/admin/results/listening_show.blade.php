@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Listening Attempt Details</h2>
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
                        {{ strtoupper(substr($attempt->student->name ?? '?', 0, 1)) }}
                    </div>
                    <h5 class="mb-1 fw-bold">{{ $attempt->student->name ?? 'Deleted Student' }}</h5>
                    <p class="text-muted small mb-0">{{ $attempt->student->student_id ?? 'N/A' }}</p>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="text-muted small d-block">Test Name</label>
                    <span class="fw-bold">{{ $attempt->listeningTest->name ?? 'Deleted Test' }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Score</label>
                    <span class="fw-bold text-success" style="font-size: 1.2rem;">{{ $attempt->score ?? 0 }} / 40</span>
                </div>
                <div class="mb-0">
                    <label class="text-muted small d-block">Time Started</label>
                    <span class="fw-bold">{{ $attempt->started_at ? $attempt->started_at->format('d M Y, H:i:s') : 'N/A' }}</span>
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
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="100" class="px-4">Q. No</th>
                                <th>Correct Answer</th>
                                <th>Student Answer</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($attempt->answers)
                                @foreach($attempt->listeningTest->parts as $part)
                                    @foreach($part->questions as $question)
                                        @php 
                                            $studentAns = $attempt->answers[$question->id] ?? null;
                                            $isCorrect = false;
                                            $correct = trim(strtolower($question->correct_answer));
                                            if($studentAns !== null) {
                                                $ans = trim(strtolower(is_array($studentAns) ? implode(', ', $studentAns) : (string)$studentAns));
                                                
                                                if ($question->question_type === 'mcq_multi') {
                                                    $correctArray = preg_split('/[,]| and |;/', $correct);
                                                    $correctArray = array_map('trim', $correctArray);
                                                    $studentArray = is_array($studentAns) ? $studentAns : explode(',', $studentAns);
                                                    $studentArray = array_map('trim', array_map('strtolower', $studentArray));
                                                    sort($correctArray);
                                                    sort($studentArray);
                                                    if ($correctArray == $studentArray) {
                                                        $isCorrect = true;
                                                    }
                                                } else {
                                                    $alternatives = preg_split('/\s*(?:\/|\||\bor\b)\s*/i', $correct);
                                                    
                                                    // Exact match against alternative singular/plural options
                                                    foreach ($alternatives as $alt) {
                                                        $alt = trim($alt);
                                                        $singular = preg_replace('/\s*\(.*?\)\s*/', '', $alt);
                                                        $plural = str_replace(['(', ')'], '', $alt);
                                                        if ($ans === $singular || $ans === $plural) {
                                                            $isCorrect = true;
                                                            break;
                                                        }
                                                    }
                                                    
                                                    // Partial match fallback for admin insights
                                                    if (!$isCorrect && !empty($ans)) {
                                                        foreach ($alternatives as $alt) {
                                                            $alt = trim($alt);
                                                            if (strpos($alt, $ans) !== false) {
                                                                $isCorrect = 'partial';
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td class="px-4 fw-bold text-secondary">{{ $question->question_number }}</td>
                                            <td class="text-success small fw-bold">{{ $question->correct_answer }}</td>
                                            <td>
                                                @if($studentAns === null)
                                                    <span class="text-muted fst-italic small">No Answer</span>
                                                @else
                                                    <span class="{{ $isCorrect === true ? 'text-success' : ($isCorrect === 'partial' ? 'text-warning' : 'text-danger') }} fw-bold">
                                                        {{ is_array($studentAns) ? implode(', ', $studentAns) : $studentAns }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($isCorrect === true)
                                                    <i class="fas fa-check-circle text-success"></i>
                                                @elseif($isCorrect === 'partial')
                                                    <i class="fas fa-dot-circle text-warning"></i>
                                                @else
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">No answers found in this attempt.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
