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
                            <div style="background: #ffffff; padding: 30px; border-radius: 12px; max-height: 500px; overflow-y: auto; border: 1px solid #e2e8f0; font-size: 1.15rem; line-height: 1.8; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
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
                                    <p class="mb-0 font-weight-bold text-dark">{{ Str::limit($question->title, 120) }}</p>
                                    <small class="text-muted">{{ strtoupper($question->question_type) }} | Marks: {{ $question->marks }}</small>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link text-secondary mb-0 outline-none shadow-none" type="button" data-bs-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v text-xs"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px;">
                                    <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#editQuestion{{ $question->id }}"><i class="fas fa-edit me-2 text-primary"></i> Edit Question</a></li>
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

                <!-- Question Edit Modal (reused from edit view logic) -->
                <div class="modal fade" id="editQuestion{{ $question->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                            <form action="{{ route('admin.listening-questions.update', $question) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header border-0 pt-4 px-4">
                                    <h5 class="modal-title fw-bold">Edit Question {{ $question->question_number }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label fw-bold text-muted small uppercase">Number</label>
                                            <input type="text" name="question_number" class="form-control" value="{{ $question->question_number }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted small uppercase">Type</label>
                                            <select name="question_type" class="form-select" required>
                                                <option value="mcq" {{ $question->question_type == 'mcq' ? 'selected' : '' }}>MCQ</option>
                                                <option value="fill_blanks" {{ $question->question_type == 'fill_blanks' ? 'selected' : '' }}>Fill Blanks</option>
                                                <option value="short_answer" {{ $question->question_type == 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                                                <option value="tfng" {{ $question->question_type == 'tfng' ? 'selected' : '' }}>TFNG</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label fw-bold text-muted small uppercase">Marks</label>
                                            <input type="number" name="marks" class="form-control" value="{{ $question->marks }}" required>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold text-muted small uppercase">Question Title / Body</label>
                                            <textarea name="title" class="form-control" rows="3">{{ $question->title }}</textarea>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold text-muted small uppercase">Correct Answer</label>
                                            <input type="text" name="correct_answer" class="form-control" value="{{ $question->correct_answer }}">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold text-muted small uppercase">Question Image</label>
                                            <input type="file" name="image" class="form-control" accept="image/*">
                                            @if($question->image)
                                                <div class="mt-2 text-center">
                                                    <img src="{{ asset('storage/' . $question->image) }}" class="img-thumbnail" style="max-height: 150px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pb-4 px-4">
                                    <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Close</button>
                                    <button type="submit" class="btn btn-primary fw-bold px-4" style="border-radius: 10px;">Save Changes</button>
                                </div>
                            </form>
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
