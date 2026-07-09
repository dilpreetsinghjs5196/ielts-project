@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tests.index', ['category' => 'listening']) }}">Listening Tests</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Listening Test</li>
            </ol>
        </nav>
        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Edit Listening Test: {{ $listeningTest->name }}</h2>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 10px;">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li><i class="fas fa-exclamation-circle me-2"></i> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px;">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="row">
    <div class="col-md-4">
        <!-- General Settings -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold"><i class="fas fa-cog me-2 text-primary"></i> General Settings</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.listening-tests.update', $listeningTest) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Test Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $listeningTest->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="level_id" class="form-label fw-bold">Test Level</label>
                        <select name="level_id" id="level_id" class="form-select" required>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ $listeningTest->level_id == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="test_type_id" class="form-label fw-bold">Test Type</label>
                        <select name="test_type_id" id="test_type_id" class="form-select" required>
                            @foreach($testTypes as $type)
                                <option value="{{ $type->id }}" {{ $listeningTest->test_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ $listeningTest->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $listeningTest->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="audio_file" class="form-label fw-bold">Main Audio File (Optional)</label>
                        <input type="file" name="audio_file" id="audio_file" class="form-control" accept="audio/*">
                        @if($listeningTest->audio_file)
                            <div class="mt-2">
                                <audio controls class="w-100">
                                    <source src="{{ asset('storage/' . $listeningTest->audio_file) }}" type="audio/mpeg">
                                </audio>
                                <div class="form-check mt-2 text-start">
                                    <input class="form-check-input" type="checkbox" name="remove_audio" id="remove_audio_test" value="1">
                                    <label class="form-check-label text-danger" for="remove_audio_test">
                                        Remove Current Audio
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 10px;">Update Test</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Test Parts -->
        <div class="accordion" id="partsAccordion">
            @foreach($listeningTest->parts as $part)
            <div class="card border-0 shadow-sm mb-3" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-white border-0 py-3 px-4" id="heading{{ $part->id }}">
                    <h5 class="mb-0 d-flex justify-content-between align-items-center">
                        <button class="btn btn-link text-decoration-none text-dark fw-bold p-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $part->id }}">
                            <i class="fas fa-headphones me-2 text-primary"></i> Part {{ $part->part_number }}: {{ $part->title }}
                        </button>
                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $part->questions->count() }} Questions</span>
                    </h5>
                </div>

                <div id="collapse{{ $part->id }}" class="collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#partsAccordion">
                    <div class="card-body p-4 border-top">
                        <!-- Part Settings Form -->
                        <form action="{{ route('admin.listening-parts.update', $part) }}" method="POST" enctype="multipart/form-data" class="mb-4 pb-4 border-bottom">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted small uppercase">Part Title</label>
                                    <input type="text" name="title" class="form-control" value="{{ $part->title }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted small uppercase">Part Instruction</label>
                                    <input type="text" name="instruction" class="form-control" value="{{ $part->instruction }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold text-muted small uppercase">Part Transcript / Passage</label>
                                    <textarea name="passage" class="form-control" rows="4">{{ $part->passage }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted small uppercase">Part Audio File</label>
                                    <input type="file" name="audio_file" class="form-control" accept="audio/*">
                                    @if($part->audio_file)
                                        <audio controls class="w-100 mt-2">
                                            <source src="{{ asset('storage/' . $part->audio_file) }}" type="audio/mpeg">
                                        </audio>
                                        <div class="form-check mt-2 text-start">
                                            <input class="form-check-input" type="checkbox" name="remove_audio" id="remove_audio_part_{{ $part->id }}" value="1">
                                            <label class="form-check-label text-danger" for="remove_audio_part_{{ $part->id }}">
                                                Remove Current Audio
                                            </label>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted small uppercase">Part Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    @if($part->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $part->image) }}" class="img-thumbnail" style="max-height: 100px;">
                                            <div class="form-check mt-2 text-start">
                                                <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image_part_{{ $part->id }}" value="1">
                                                <label class="form-check-label text-danger" for="remove_image_part_{{ $part->id }}">
                                                    Remove Current Image
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-sm btn-outline-primary fw-bold px-4" style="border-radius: 8px;">Update Part Info</button>
                                </div>
                            </div>
                        </form>

                        <!-- Questions List -->
                        <h6 class="fw-bold mb-3"><i class="fas fa-question-circle me-2 text-primary"></i> Questions</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Question Text / Type</th>
                                        <th width="150">Image</th>
                                        <th width="100" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($part->questions as $question)
                                    <tr>
                                        <td class="fw-bold text-primary">{{ $question->question_number }}</td>
                                        <td>
                                            <div class="fw-bold text-truncate" style="max-width: 300px;" title="{{ $question->title }}">{{ $question->title }}</div>
                                            <small class="badge bg-light text-secondary">{{ strtoupper($question->question_type) }}</small>
                                        </td>
                                        <td>
                                            @if($question->image)
                                                <img src="{{ asset('storage/' . $question->image) }}" class="img-thumbnail" style="max-height: 40px;">
                                            @else
                                                <span class="text-muted small">No image</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-white border shadow-sm" data-bs-toggle="modal" data-bs-target="#editQuestion{{ $question->id }}" style="border-radius: 8px;">
                                                <i class="fas fa-edit text-primary"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Question Edit Modal -->
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
                                                                    <div class="mt-2">
                                                                        <img src="{{ asset('storage/' . $question->image) }}" class="img-thumbnail" style="max-height: 120px;">
                                                                        <div class="form-check mt-2 text-start">
                                                                            <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image_question_{{ $question->id }}" value="1">
                                                                            <label class="form-check-label text-danger" for="remove_image_question_{{ $question->id }}">
                                                                                Remove Current Image
                                                                            </label>
                                                                        </div>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
