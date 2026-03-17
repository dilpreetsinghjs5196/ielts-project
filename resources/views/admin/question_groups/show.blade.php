@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <!-- Group Header Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #0d1624, #1a2a44); color: white;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-primary mb-2">{{ $questionGroup->category->name }} | {{ $questionGroup->testType->name }}</span>
                            <h2 class="mb-1" style="font-weight: 700;">{{ $questionGroup->title }}</h2>
                            <p class="mb-0 opacity-75">{{ $questionGroup->instruction }}</p>
                        </div>
                        <div class="d-flex">
                            <a href="{{ route('admin.question-groups.edit', $questionGroup) }}" class="btn btn-light btn-sm me-2" style="border-radius: 8px;">Edit Segment</a>
                            <a href="{{ route('admin.question-groups.index', ['category' => $questionGroup->category->slug]) }}" class="btn btn-outline-light btn-sm" style="border-radius: 8px;">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Segment Media Preview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 font-weight-bold">Shared content for this segment</h5>
                </div>
                <div class="card-body">
                    @if($questionGroup->passage)
                        <div style="background: #ffffff; padding: 30px; border-radius: 12px; max-height: 500px; overflow-y: auto; border: 1px solid #e2e8f0; font-size: 1.15rem; line-height: 1.8; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                            {!! nl2br(e($questionGroup->passage)) !!}
                        </div>
                    @endif

                    @if($questionGroup->audio_file)
                        <div class="bg-light p-3 rounded" style="border: 1px solid #e2e8f0;">
                            <audio controls class="w-100">
                                <source src="{{ asset('storage/' . $questionGroup->audio_file) }}" type="audio/mpeg">
                            </audio>
                        </div>
                    @endif

                    @if($questionGroup->attachment)
                        <img src="{{ asset('storage/' . $questionGroup->attachment) }}" class="img-fluid rounded" style="max-height: 400px; border: 1px solid #e2e8f0;">
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Questions Header -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="font-weight-bold mb-0">Questions in this segment</h4>
            <a href="{{ route('admin.questions.create', ['group_id' => $questionGroup->id]) }}" class="btn btn-success shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-plus me-2"></i> Add Question
            </a>
        </div>
    </div>

    <!-- Individual Questions List -->
    <div class="row">
        <div class="col-12">
            @forelse($questionGroup->questions as $index => $question)
            <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="me-3 d-flex align-items-center justify-content-center bg-primary text-white" style="width: 32px; height: 32px; border-radius: 8px; font-weight: bold;">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="mb-0 font-weight-bold text-dark">{{ Str::limit($question->content, 120) }}</p>
                                <small class="text-muted">{{ ucfirst($question->question_type) }} | Marks: {{ $question->marks }}</small>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this question?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <p class="text-muted mb-0">No questions added yet. Click "Add Question" to start adding questions for this segment.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
