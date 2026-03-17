@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Question</li>
                </ol>
            </nav>
            <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Add Question</h2>
        </div>
    </div>

<form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    
                    <!-- Dynamic Module Header -->
                    @if(isset($selectedGroup))
                        <div class="alert alert-primary border-0 shadow-sm mb-4" style="border-radius: 12px;">
                            <i class="fas fa-layer-group me-2"></i> Adding question to segment: <strong>{{ $selectedGroup->title }}</strong>
                            <input type="hidden" name="question_group_id" value="{{ $selectedGroup->id }}">
                            <input type="hidden" name="category_id" value="{{ $selectedGroup->category_id }}">
                            <input type="hidden" name="test_type_id" value="{{ $selectedGroup->test_type_id }}">
                            <input type="hidden" name="level_id" value="{{ $selectedGroup->level_id }}">
                        </div>
                    @else
                        <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius: 12px; background: #eef2ff;">
                            <i class="fas fa-info-circle me-2"></i> You are adding a question to the <strong id="selectedModuleText">...</strong> module.
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="title" class="form-label font-weight-bold">Question Heading / Title</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Section 1 - Note Completion" value="{{ old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(!isset($selectedGroup))
                        <!-- Reading Specific: Passage -->
                        <div class="mb-3 d-none" id="passage_section">
                            <label for="q_passage" class="form-label font-weight-bold">Reading Passage / Text</label>
                            <textarea name="passage" id="q_passage" rows="10" class="form-control @error('passage') is-invalid @enderror" placeholder="Paste the reading paragraph/passage here...">{{ old('passage') }}</textarea>
                            @error('passage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Listening Specific: Audio -->
                        <div class="mb-3 d-none" id="audio_section">
                            <label for="audio_file" class="form-label font-weight-bold">Listening Audio File</label>
                            <input type="file" name="audio_file" id="audio_file" class="form-control @error('audio_file') is-invalid @enderror" accept="audio/*">
                            <small class="text-muted">Upload MP3 or WAV file (Max 10MB)</small>
                            @error('audio_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Writing Specific: Image/Attachment -->
                        <div class="mb-3 d-none" id="writing_section">
                            <label for="attachment" class="form-label font-weight-bold">Writing Task Image (Chart/Graph)</label>
                            <input type="file" name="attachment" id="attachment" class="form-control @error('attachment') is-invalid @enderror" accept="image/*">
                            <small class="text-muted">For Task 1: Upload Chart, Table, or Graph image.</small>
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="q_content" class="form-label font-weight-bold" id="contentLabel">Question Instruction</label>
                        <textarea name="content" id="q_content" rows="4" class="form-control @error('content') is-invalid @enderror" placeholder="Type the specific question prompt here..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="mcq_options_section" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label font-weight-bold mb-0">Options (For MCQs)</label>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addOption()">
                                <i class="fas fa-plus me-1"></i> Add Option
                            </button>
                        </div>
                        <div id="options_container">
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
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="correct_answer" class="form-label font-weight-bold">Correct Answer</label>
                        <input type="text" name="correct_answer" id="correct_answer" class="form-control @error('correct_answer') is-invalid @enderror" placeholder="Type the exact correct answer" value="{{ old('correct_answer') }}" required>
                        @error('correct_answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="explanation" class="form-label font-weight-bold">Explanation / Sample Answer</label>
                        <textarea name="explanation" id="explanation" rows="3" class="form-control @error('explanation') is-invalid @enderror" placeholder="Explain the answer or provide a sample writing response...">{{ old('explanation') }}</textarea>
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
                    
                    @if(!isset($selectedGroup))
                        <div class="mb-3">
                            <label for="category_id" class="form-label small font-weight-bold text-muted">Module</label>
                            <select name="category_id" id="category_id" class="form-select" onchange="handleModuleChange(this)" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-slug="{{ $category->slug }}" {{ (request()->category_id == $category->id || old('category_id') == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="test_type_id" class="form-label small font-weight-bold text-muted">Test Type</label>
                            <select name="test_type_id" id="test_type_id" class="form-select" required>
                                @foreach($testTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('test_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="level_id" class="form-label small font-weight-bold text-muted">Level / Batch</label>
                            <select name="level_id" id="level_id" class="form-select" required>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="mb-3 px-3 py-2 bg-light rounded">
                            <p class="mb-1 small text-muted font-weight-bold text-uppercase">Module</p>
                            <p class="mb-2 font-weight-bold">{{ $selectedGroup->category->name }}</p>
                            
                            <p class="mb-1 small text-muted font-weight-bold text-uppercase">Test Type</p>
                            <p class="mb-2 font-weight-bold">{{ $selectedGroup->testType->name }}</p>
                            
                            <p class="mb-1 small text-muted font-weight-bold text-uppercase">Level</p>
                            <p class="mb-0 font-weight-bold">{{ $selectedGroup->level->name }}</p>
                        </div>
                    @endif

                    <hr>

                    <div class="mb-3">
                        <label for="question_type" class="form-label small font-weight-bold text-muted">Question Format</label>
                        <select name="question_type" id="question_type" class="form-select" onchange="toggleOptions(this.value)" required>
                            <option value="short_answer">Short Answer</option>
                            <option value="mcq">Multiple Choice (MCQ)</option>
                            <option value="fill_blanks">Fill in the Blanks</option>
                            <option value="tfng">True / False / Not Given</option>
                            <option value="match_heading">Heading Matching</option>
                            <option value="essay">Essay / Long Answer</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="marks" class="form-label small font-weight-bold text-muted">Marks</label>
                        <input type="number" name="marks" id="marks" class="form-control" value="1" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label small font-weight-bold text-muted">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 mb-2" style="border-radius: 10px;">Save Question</button>
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
        handleModuleChange(document.getElementById('category_id'));
    });
</script>
</div>
@endsection
