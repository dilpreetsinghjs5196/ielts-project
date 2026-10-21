@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    @php
                        $bankUrl = route('admin.questions.index');
                        if (isset($selectedGroup)) {
                            $bankUrl = route('admin.questions.index', ['category' => $selectedGroup->category->slug]);
                        } elseif (request()->has('category_id')) {
                            $cat = $categories->find(request()->category_id);
                            if ($cat) {
                                $bankUrl = route('admin.questions.index', ['category' => $cat->slug]);
                            }
                        }
                    @endphp
                    <li class="breadcrumb-item"><a href="{{ $bankUrl }}">Question Bank</a></li>
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
                    @if (isset($selectedGroup))
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

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="question_number" class="form-label font-weight-bold">Q. Number</label>
                                <input type="text" name="question_number" id="question_number" class="form-control" placeholder="e.g. 14 or 14-17" value="{{ old('question_number') }}">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="mb-3">
                                <label for="title" class="form-label font-weight-bold">Question Heading / Title</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Section 1 - Note Completion" value="{{ old('title') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="common_heading" class="form-label font-weight-bold">Common Heading (Shows below Title badge)</label>
                                <textarea name="common_heading" id="common_heading" rows="2" class="form-control @error('common_heading') is-invalid @enderror" placeholder="e.g. Use the information in the text to match the people (listed A-E) with the opinions...">{{ old('common_heading') }}</textarea>
                                @error('common_heading')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if (!isset($selectedGroup))
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

                    <div class="mb-3" id="image_section">
                        <label for="images" class="form-label font-weight-bold">Optional Question Images</label>
                        <input type="file" name="images[]" id="images" class="form-control @error('images') is-invalid @enderror" accept="image/*" multiple>
                        <small class="text-muted">Upload one or more images specific to this question (Optional).</small>
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="q_content" class="form-label font-weight-bold" id="contentLabel">Question Instruction</label>
                        <textarea name="content" id="q_content" rows="4" class="form-control @error('content') is-invalid @enderror" placeholder="Type the specific question prompt here..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="mcq_options_section" style="{{ in_array(old('question_type', 'short_answer'), ['mcq', 'mcq_multi', 'match_heading', 'fill_blanks']) ? 'display: block;' : 'display: none;' }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label font-weight-bold mb-0" id="options_label">Options (For MCQs)</label>
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

                    <!-- TFNG Specific Section: Visual Option & Answer Selector -->
                    <div id="tfng_options_section" class="mb-4 p-3 rounded-3 border" style="display: {{ old('question_type') == 'tfng' ? 'block' : 'none' }}; background: #f8fafc; border-color: #cbd5e1 !important;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label font-weight-bold text-dark mb-0">
                                <i class="fas fa-check-double text-primary me-1"></i> True / False / Not Given Choices
                            </label>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary active" id="tfng_mode_tfng" onclick="setTfngVariant('tfng')">TRUE / FALSE</button>
                                <button type="button" class="btn btn-outline-primary" id="tfng_mode_yn" onclick="setTfngVariant('yn')">YES / NO</button>
                            </div>
                        </div>
                        <p class="small text-muted mb-3">Click on the correct answer below to automatically select it.</p>
                        
                        <div class="d-flex gap-2" id="tfng_buttons_container">
                            <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill flex-grow-1 fw-bold tfng-choice-btn" onclick="selectTfngAnswer('TRUE', this)">
                                TRUE
                            </button>
                            <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill flex-grow-1 fw-bold tfng-choice-btn" onclick="selectTfngAnswer('FALSE', this)">
                                FALSE
                            </button>
                            <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill flex-grow-1 fw-bold tfng-choice-btn" onclick="selectTfngAnswer('NOT GIVEN', this)">
                                NOT GIVEN
                            </button>
                        </div>

                        <!-- Hidden options inputs to persist variant options -->
                        <div id="tfng_hidden_options">
                            <input type="hidden" name="options[]" value="TRUE">
                            <input type="hidden" name="options[]" value="FALSE">
                            <input type="hidden" name="options[]" value="NOT GIVEN">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="correct_answer" class="form-label font-weight-bold">Correct Answer</label>
                        <input type="text" name="correct_answer" id="correct_answer" class="form-control @error('correct_answer') is-invalid @enderror" placeholder="Type the exact correct answer" value="{{ old('correct_answer') }}" required>
                        <div class="form-text mt-2 p-2 bg-light rounded border">
                            <strong>Formatting Guide:</strong><br>
                            - <span class="text-primary font-weight-bold">Single Choice / TFNG</span>: Enter just the letter/word (e.g., <code class="bg-white px-1">A</code> or <code class="bg-white px-1">TRUE</code>)<br>
                            - <span class="text-primary font-weight-bold">Multi-select</span>: Enter letters separated by comma or 'and' (e.g., <code class="bg-white px-1">A, B</code> or <code class="bg-white px-1">A and B</code>)<br>
                            - <span class="text-primary font-weight-bold">Short Answer</span>: Enter the exact case-insensitive word (limit special characters)
                        </div>
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
                    
                    @if (!isset($selectedGroup))
                        <div class="mb-3">
                            <label for="category_id" class="form-label small font-weight-bold text-muted">Module</label>
                            <select name="category_id" id="category_id" class="form-select" onchange="handleModuleChange(this)" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" data-slug="{{ $category->slug }}" {{ (request()->category_id == $category->id || old('category_id') == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="test_type_id" class="form-label small font-weight-bold text-muted">Test Type</label>
                            <select name="test_type_id" id="test_type_id" class="form-select" required>
                                @foreach ($testTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('test_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="level_id" class="form-label small font-weight-bold text-muted">Level / Batch</label>
                            <select name="level_id" id="level_id" class="form-select" required>
                                @foreach ($levels as $level)
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
                            <option value="short_answer" {{ old('question_type') == 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                            <option value="mcq" {{ old('question_type') == 'mcq' ? 'selected' : '' }}>Multiple Choice (Single)</option>
                            <option value="mcq_multi" {{ old('question_type') == 'mcq_multi' ? 'selected' : '' }}>Multiple Choice (Select Two/Three)</option>
                            <option value="fill_blanks" {{ old('question_type') == 'fill_blanks' ? 'selected' : '' }}>Fill in the Blanks</option>
                            <option value="tfng" {{ old('question_type') == 'tfng' ? 'selected' : '' }}>True / False / Not Given</option>
                            <option value="match_heading" {{ old('question_type') == 'match_heading' ? 'selected' : '' }}>Heading Matching</option>
                            <option value="essay" {{ old('question_type') == 'essay' ? 'selected' : '' }}>Essay / Long Answer</option>
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
                        @php
                            $cancelRoute = route('admin.questions.index');
                            if (isset($selectedGroup)) {
                                $cancelRoute = route('admin.question-groups.show', $selectedGroup->id);
                            } elseif (request()->has('category_id')) {
                                $cat = $categories->find(request()->category_id);
                                if ($cat) {
                                    $cancelRoute = route('admin.questions.index', ['category' => $cat->slug]);
                                }
                            }
                        @endphp
                        <a href="{{ $cancelRoute }}" class="btn btn-light w-100" style="border-radius: 10px;">Cancel</a>
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
        const tfngSection = document.getElementById('tfng_options_section');
        const optionsLabel = document.getElementById('options_label');
        
        if (type === 'tfng') {
            if (mcqSection) mcqSection.style.display = 'none';
            if (tfngSection) {
                tfngSection.style.display = 'block';
                syncTfngButtonHighlight();
            }
            return;
        } else {
            if (tfngSection) tfngSection.style.display = 'none';
        }

        if (['mcq', 'mcq_multi', 'match_heading', 'fill_blanks'].includes(type)) {
            if (mcqSection) {
                mcqSection.style.display = 'block';
                
                if (type === 'mcq') {
                    optionsLabel.innerText = "Options (Single Choice)";
                } else if (type === 'mcq_multi') {
                    optionsLabel.innerText = "Options (Multi-select)";
                } else if (type === 'match_heading') {
                    optionsLabel.innerText = "Headings (List of headings to match)";
                } else if (type === 'fill_blanks') {
                    optionsLabel.innerText = "Word Bank (Optional choices for blanks)";
                }
            }
        } else {
            if (mcqSection) mcqSection.style.display = 'none';
        }
    }

    function setTfngVariant(variant) {
        const btnTfng = document.getElementById('tfng_mode_tfng');
        const btnYn = document.getElementById('tfng_mode_yn');
        const container = document.getElementById('tfng_buttons_container');
        const hiddenOpts = document.getElementById('tfng_hidden_options');

        if (variant === 'yn') {
            btnTfng.classList.remove('active');
            btnYn.classList.add('active');
            container.innerHTML = `
                <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill flex-grow-1 fw-bold tfng-choice-btn" onclick="selectTfngAnswer('YES', this)">YES</button>
                <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill flex-grow-1 fw-bold tfng-choice-btn" onclick="selectTfngAnswer('NO', this)">NO</button>
                <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill flex-grow-1 fw-bold tfng-choice-btn" onclick="selectTfngAnswer('NOT GIVEN', this)">NOT GIVEN</button>
            `;
            hiddenOpts.innerHTML = `
                <input type="hidden" name="options[]" value="YES">
                <input type="hidden" name="options[]" value="NO">
                <input type="hidden" name="options[]" value="NOT GIVEN">
            `;
        } else {
            btnYn.classList.remove('active');
            btnTfng.classList.add('active');
            container.innerHTML = `
                <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill flex-grow-1 fw-bold tfng-choice-btn" onclick="selectTfngAnswer('TRUE', this)">TRUE</button>
                <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill flex-grow-1 fw-bold tfng-choice-btn" onclick="selectTfngAnswer('FALSE', this)">FALSE</button>
                <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill flex-grow-1 fw-bold tfng-choice-btn" onclick="selectTfngAnswer('NOT GIVEN', this)">NOT GIVEN</button>
            `;
            hiddenOpts.innerHTML = `
                <input type="hidden" name="options[]" value="TRUE">
                <input type="hidden" name="options[]" value="FALSE">
                <input type="hidden" name="options[]" value="NOT GIVEN">
            `;
        }
        syncTfngButtonHighlight();
    }

    function selectTfngAnswer(val, btn) {
        const answerInput = document.getElementById('correct_answer');
        if (answerInput) {
            answerInput.value = val;
        }
        syncTfngButtonHighlight();
    }

    function syncTfngButtonHighlight() {
        const answerInput = document.getElementById('correct_answer');
        if (!answerInput) return;
        const val = (answerInput.value || '').trim().toUpperCase();
        document.querySelectorAll('.tfng-choice-btn').forEach(btn => {
            const btnText = btn.innerText.replace(/[^A-Za-z ]/g, '').trim().toUpperCase();
            if (btnText === val) {
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-primary', 'shadow-sm');
                btn.innerHTML = `<i class="fas fa-check-circle me-1"></i> ${btnText}`;
            } else {
                btn.classList.remove('btn-primary', 'shadow-sm');
                btn.classList.add('btn-outline-secondary');
                btn.innerText = btnText;
            }
        });
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
        
        // Handle options initial state
        const typeSelect = document.getElementById('question_type');
        if (typeSelect) {
            toggleOptions(typeSelect.value);
        }

        const answerInput = document.getElementById('correct_answer');
        if (answerInput) {
            answerInput.addEventListener('input', syncTfngButtonHighlight);
            syncTfngButtonHighlight();
        }
    });
</script>
</div>
@endsection

