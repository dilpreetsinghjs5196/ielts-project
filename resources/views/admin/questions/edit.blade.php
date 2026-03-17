@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Question</li>
                </ol>
            </nav>
            <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Edit Question: #{{ $question->id }}</h2>
        </div>
    </div>

<form action="{{ route('admin.questions.update', $question) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    
                    <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius: 12px; background: #eef2ff;">
                        <i class="fas fa-info-circle me-2"></i> Editing a question for the <strong id="selectedModuleText">{{ $question->category->name }}</strong> module.
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label font-weight-bold">Question Heading / Title</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Section 1 - Note Completion" value="{{ old('title', $question->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Reading Specific: Passage -->
                    <div class="mb-3 {{ $question->category->slug != 'reading' ? 'd-none' : '' }}" id="passage_section">
                        <label for="q_passage" class="form-label font-weight-bold">Reading Passage / Text</label>
                        <textarea name="passage" id="q_passage" rows="10" class="form-control @error('passage') is-invalid @enderror" placeholder="Paste the reading paragraph/passage here...">{{ old('passage', $question->passage) }}</textarea>
                        @error('passage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Listening Specific: Audio -->
                    <div class="mb-3 {{ $question->category->slug != 'listening' ? 'd-none' : '' }}" id="audio_section">
                        <label for="audio_file" class="form-label font-weight-bold">Listening Audio File</label>
                        @if($question->audio_file)
                            <div class="mb-2">
                                <audio controls class="w-100">
                                    <source src="{{ asset('storage/' . $question->audio_file) }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        @endif
                        <input type="file" name="audio_file" id="audio_file" class="form-control @error('audio_file') is-invalid @enderror" accept="audio/*">
                        <small class="text-muted">Upload new MP3 or WAV file to replace existing (Max 10MB)</small>
                        @error('audio_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Writing Specific: Image/Attachment -->
                    <div class="mb-3 {{ $question->category->slug != 'writing' ? 'd-none' : '' }}" id="writing_section">
                        <label for="attachment" class="form-label font-weight-bold">Writing Task Image (Chart/Graph)</label>
                        @if($question->attachment)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $question->attachment) }}" alt="Attachment" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        @endif
                        <input type="file" name="attachment" id="attachment" class="form-control @error('attachment') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Upload new image to replace (For Task 1 charts/graphs).</small>
                        @error('attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="q_content" class="form-label font-weight-bold" id="contentLabel">Question Content</label>
                        <textarea name="content" id="q_content" rows="4" class="form-control @error('content') is-invalid @enderror" placeholder="Type the specific question prompt here..." required>{{ old('content', $question->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="mcq_options_section" style="{{ $question->question_type == 'mcq' ? 'display: block;' : 'display: none;' }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label font-weight-bold mb-0">Options (For MCQs)</label>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addOption()">
                                <i class="fas fa-plus me-1"></i> Add Option
                            </button>
                        </div>
                        <div id="options_container">
                            @if($question->options && is_array($question->options))
                                @foreach($question->options as $index => $option)
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-light">{{ chr(65 + $index) }}</span>
                                    <input type="text" name="options[]" class="form-control" placeholder="Enter option text" value="{{ $option }}">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)"><i class="fas fa-times"></i></button>
                                </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-light">A</span>
                                    <input type="text" name="options[]" class="form-control" placeholder="Enter option text">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-light">B</span>
                                    <input type="text" name="options[]" class="form-control" placeholder="Enter option text">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)"><i class="fas fa-times"></i></button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="correct_answer" class="form-label font-weight-bold">Correct Answer</label>
                        <input type="text" name="correct_answer" id="correct_answer" class="form-control @error('correct_answer') is-invalid @enderror" placeholder="Type the exact correct answer" value="{{ old('correct_answer', $question->correct_answer) }}" required>
                        @error('correct_answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="explanation" class="form-label font-weight-bold">Explanation / Sample Answer</label>
                        <textarea name="explanation" id="explanation" rows="3" class="form-control @error('explanation') is-invalid @enderror" placeholder="Explain the answer or provide a sample writing response...">{{ old('explanation', $question->explanation) }}</textarea>
                        @error('explanation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold mb-3 text-uppercase small text-secondary">Categorization</h6>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label small font-weight-bold text-muted">Module</label>
                        <select name="category_id" id="category_id" class="form-select" onchange="handleModuleChange(this)" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-slug="{{ $category->slug }}" {{ $question->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="test_type_id" class="form-label small font-weight-bold text-muted">Test Type</label>
                        <select name="test_type_id" id="test_type_id" class="form-select" required>
                            @foreach($testTypes as $type)
                                <option value="{{ $type->id }}" {{ $question->test_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="level_id" class="form-label small font-weight-bold text-muted">Level / Batch</label>
                        <select name="level_id" id="level_id" class="form-select" required>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ $question->level_id == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label for="question_type" class="form-label small font-weight-bold text-muted">Question Format</label>
                        <select name="question_type" id="question_type" class="form-select" onchange="toggleOptions(this.value)" required>
                            <option value="short_answer" {{ $question->question_type == 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                            <option value="mcq" {{ $question->question_type == 'mcq' ? 'selected' : '' }}>Multiple Choice (MCQ)</option>
                            <option value="fill_blanks" {{ $question->question_type == 'fill_blanks' ? 'selected' : '' }}>Fill in the Blanks</option>
                            <option value="tfng" {{ $question->question_type == 'tfng' ? 'selected' : '' }}>True / False / Not Given</option>
                            <option value="match_heading" {{ $question->question_type == 'match_heading' ? 'selected' : '' }}>Heading Matching</option>
                            <option value="essay" {{ $question->question_type == 'essay' ? 'selected' : '' }}>Essay / Long Answer</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="marks" class="form-label small font-weight-bold text-muted">Marks</label>
                        <input type="number" name="marks" id="marks" class="form-control" value="{{ $question->marks }}" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label small font-weight-bold text-muted">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ $question->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $question->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 mb-2" style="border-radius: 10px;">Update Question</button>
                        <a href="{{ route('admin.questions.index') }}" class="btn btn-light w-100" style="border-radius: 10px;">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function handleModuleChange(select) {
        const slug = select.options[select.selectedIndex].getAttribute('data-slug');
        const text = select.options[select.selectedIndex].text;
        
        document.getElementById('selectedModuleText').innerText = text;
        
        // Hide all special sections first
        document.getElementById('passage_section').classList.add('d-none');
        document.getElementById('audio_section').classList.add('d-none');
        document.getElementById('writing_section').classList.add('d-none');
        
        // Show relevant section
        if (slug === 'reading') {
            document.getElementById('passage_section').classList.remove('d-none');
            document.getElementById('contentLabel').innerText = "Questions (e.g. 1-13)";
        } else if (slug === 'listening') {
            document.getElementById('audio_section').classList.remove('d-none');
             document.getElementById('contentLabel').innerText = "Questions (e.g. 1-10)";
        } else if (slug === 'writing') {
            document.getElementById('writing_section').classList.remove('d-none');
            document.getElementById('contentLabel').innerText = "Writing Prompt / Question Text";
        } else {
            document.getElementById('contentLabel').innerText = "Question / Content";
        }
    }

    function toggleOptions(type) {
        const mcqSection = document.getElementById('mcq_options_section');
        if (type === 'mcq') {
            mcqSection.style.display = 'block';
        } else {
            mcqSection.style.display = 'none';
        }
    }

    function addOption() {
        const container = document.getElementById('options_container');
        const optionCount = container.children.length;
        const letter = String.fromCharCode(65 + optionCount); // A, B, C...
        
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <span class="input-group-text bg-light">${letter}</span>
            <input type="text" name="options[]" class="form-control" placeholder="Enter option text">
            <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)"><i class="fas fa-times"></i></button>
        `;
        container.appendChild(div);
    }

    function removeOption(btn) {
        const container = document.getElementById('options_container');
        if (container.children.length > 2) {
            btn.parentElement.remove();
            // Re-label letters
            Array.from(container.children).forEach((child, index) => {
                child.querySelector('.input-group-text').innerText = String.fromCharCode(65 + index);
            });
        } else {
            alert("At least two options are required.");
        }
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        // Initial setup for existing data
        const select = document.getElementById('category_id');
        handleModuleChange(select);
    });
</script>
</div>
@endsection
