@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <!-- Part Header Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #0d1624, #1a2a44); color: white;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-primary mb-2">Listening | {{ $part->test->testType->name }}</span>
                            <h2 class="mb-1" style="font-weight: 700;">PART {{ $part->part_number }}: {{ $part->title }}</h2>
                            <p class="mb-0 opacity-75">{{ $part->instruction }}</p>
                        </div>
                        <div class="d-flex">
                            <a href="{{ route('admin.listening-tests.edit', $part->listening_test_id) }}" class="btn btn-light btn-sm me-2" style="border-radius: 8px;">Edit Test Details</a>
                            <a href="{{ route('admin.listening-tests.index', ['test' => $part->listening_test_id]) }}" class="btn btn-outline-light btn-sm" style="border-radius: 8px;">Back to Question Bank</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Part Media Preview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 font-weight-bold">Media & Content for this part</h5>
                </div>
                <div class="card-body">
                    @if ($part->audio_file)
                        <div class="bg-light p-3 rounded mb-3" style="border: 1px solid #e2e8f0;">
                            <label class="small fw-bold text-muted d-block mb-2">Part Audio</label>
                            <audio controls class="w-100">
                                <source src="{{ asset('storage/' . $part->audio_file) }}" type="audio/mpeg">
                            </audio>
                        </div>
                    @endif

                    @if ($part->image)
                        <div class="mb-3">
                            <label class="small fw-bold text-muted d-block mb-2">Part Image</label>
                            <img src="{{ asset('storage/' . $part->image) }}" class="img-fluid rounded" style="max-height: 400px; border: 1px solid #e2e8f0;">
                        </div>
                    @endif

                    @if ($part->passage)
                        <div class="mt-3">
                            <label class="small fw-bold text-muted d-block mb-2">Transcript / Passage</label>
                            <div class="segment-passage-box">
                                {!! nl2br(e($part->passage)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Questions Header -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="font-weight-bold mb-0">Questions in this segment</h4>
            <a href="{{ route('admin.listening-questions.create', ['part_id' => $part->id]) }}" class="btn btn-success shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-plus me-2"></i> Add Question
            </a>
        </div>
    </div>

    <!-- Individual Questions List -->
    <div class="row">
        <div class="col-12">
            @if ($part->questions->count() > 0)
                @foreach ($part->questions as $question)
                <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="me-3 d-flex align-items-center justify-content-center bg-primary text-white" style="width: auto; min-width: 32px; height: 32px; padding: 0 8px; border-radius: 8px; font-weight: bold;">
                                    {{ $question->question_number }}
                                </div>
                                <div>
                                    <p class="mb-0 font-weight-bold text-dark">{!! Str::limit(htmlspecialchars_decode($question->title, ENT_QUOTES), 150) !!}</p>
                                    <div class="mt-1 d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.7rem; border-radius: 6px;">
                                            {{ str_replace('_', ' ', strtoupper($question->question_type)) }}
                                        </span>
                                        <span class="text-muted" style="font-size: 0.75rem;">
                                            <i class="fas fa-star me-1 text-warning" style="font-size: 0.65rem;"></i> {{ $question->marks }} {{ Str::plural('Mark', $question->marks) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link text-secondary mb-0 outline-none shadow-none" type="button" data-bs-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v text-xs"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px;">
                                    <li><a class="dropdown-item py-2" href="{{ route('admin.listening-questions.edit', $question) }}"><i class="fas fa-edit me-2 text-primary"></i> Edit Question</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.listening-questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item py-2 text-danger">
                                                <i class="fas fa-trash-alt me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <p class="text-muted mb-0">No questions added yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
