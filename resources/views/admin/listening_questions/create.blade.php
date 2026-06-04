@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0 mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.listening-tests.index') }}" class="text-decoration-none">Listening Test</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.listening-parts.show', $part->id) }}" class="text-decoration-none">Part {{ $part->part_number }}</a></li>
            <li class="breadcrumb-item active">Add Question</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-12">
            <h2 class="display-6 fw-bold text-dark">Add New Question</h2>
        </div>
    </div>

    <form action="{{ route('admin.listening-questions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="listening_part_id" value="{{ $part->id }}">

        <div class="row g-4">
            <!-- Main Content Area -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center" style="border-radius: 12px; background: rgba(59, 130, 246, 0.05); color: #1e40af;">
                            <i class="fas fa-info-circle me-3 fs-4"></i>
                            <div>Creating a new question for <strong>Listening Part {{ $part->part_number }}</strong>.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold text-secondary small text-uppercase tracking-wider">Q. Number</label>
                                    <input type="text" name="question_number" class="form-control form-control-lg border-2" placeholder="e.g., 14" style="border-radius: 12px;" required>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold text-secondary small text-uppercase tracking-wider">Question Heading / Title</label>
                                    <input type="text" name="content" class="form-control form-control-lg border-2" placeholder="Brief descriptive title..." style="border-radius: 12px;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase tracking-wider">Question Content / Text</label>
                            <textarea name="title" class="form-control border-2" rows="6" placeholder="Paste the question text here..." style="border-radius: 15px; font-size: 1.1rem; line-height: 1.6;" required></textarea>
                            <div class="form-text mt-2 text-muted">
                                <i class="fas fa-keyboard me-1"></i> Use <code>____</code> (four underscores) to create a blank space for students to type.
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase tracking-wider">Correct Answer(s)</label>
                            <input type="text" name="correct_answer" class="form-control form-control-lg border-2" placeholder="Enter answer..." style="border-radius: 12px; background: #f8fafc;">
                        </div>

                        <!-- Formatting Guide -->
                        <div class="card bg-light border-0 mb-4" style="border-radius: 15px;">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-2 small text-uppercase">Formatting Guide:</h6>
                                <ul class="list-unstyled mb-0 small text-muted" style="line-height: 1.8;">
                                    <li><span class="text-primary fw-bold">- Single Choice / TFNG:</span> Enter just the letter/word (e.g., <code class="bg-white px-2 py-0 border rounded">A</code> or <code class="bg-white px-2 py-0 border rounded">TRUE</code>)</li>
                                    <li><span class="text-primary fw-bold">- Multi-select:</span> Enter letters separated by comma or 'and' (e.g., <code class="bg-white px-2 py-0 border rounded">A, B</code>)</li>
                                    <li><span class="text-primary fw-bold">- Short Answer:</span> Enter the exact case-insensitive word.</li>
                                    <li><span class="text-primary fw-bold">- Range Questions:</span> Enter answers separated by comma (e.g., <code class="bg-white px-2 py-0 border rounded">Apple, Banana</code>)</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Options Section (Visible for MCQ) -->
                        <div id="optionsSection" style="display: none;">
                            <div class="form-group mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label fw-bold text-secondary small text-uppercase tracking-wider mb-0">Options (A, B, C...)</label>
                                    <button type="button" class="btn btn-sm btn-outline-primary shadow-sm px-3" onclick="addOption()" style="border-radius: 8px;">
                                        <i class="fas fa-plus me-1"></i> Add Option
                                    </button>
                                </div>
                                <div id="optionsContainer" class="row g-3">
                                    <div class="col-md-6 option-item">
                                        <div class="input-group">
                                            <span class="input-group-text border-2 bg-light fw-bold">A</span>
                                            <input type="text" name="options[A]" class="form-control border-2" placeholder="Option text">
                                        </div>
                                    </div>
                                    <div class="col-md-6 option-item">
                                        <div class="input-group">
                                            <span class="input-group-text border-2 bg-light fw-bold">B</span>
                                            <input type="text" name="options[B]" class="form-control border-2" placeholder="Option text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label fw-bold text-secondary small text-uppercase tracking-wider">Question Image (Optional)</label>
                            <div class="input-group">
                                <input type="file" name="image" class="form-control border-2" accept="image/*" style="border-radius: 12px 0 0 12px;">
                                <span class="input-group-text bg-light border-2" style="border-radius: 0 12px 12px 0;"><i class="fas fa-image"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm position-sticky" style="top: 20px; border-radius: 20px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-dark small text-uppercase tracking-widest border-bottom pb-2">Categorization</h5>
                        
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Module</label>
                            <input type="text" class="form-control border-2 bg-light" value="Listening" readonly style="border-radius: 10px;">
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Question Format</label>
                            <select name="question_type" id="question_type" class="form-select form-select-lg border-2" style="border-radius: 12px; font-weight: 600;" required>
                                <option value="mcq">Multiple Choice</option>
                                <option value="mcq_multi">Multiple Choice (Multi-select)</option>
                                <option value="fill_blanks" selected>Fill in the Blanks</option>
                                <option value="short_answer">Short Answer</option>
                                <option value="tfng">True/False/Not Given</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Marks / Weightage</label>
                            <div class="input-group">
                                <input type="number" name="marks" class="form-control form-control-lg border-2" value="1" style="border-radius: 12px 0 0 12px;" required>
                                <span class="input-group-text bg-light border-2 fw-bold" style="border-radius: 0 12px 12px 0;">PTS</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow" style="border-radius: 15px;">
                                <i class="fas fa-check-circle me-2"></i> Create Question
                            </button>
                            <a href="{{ route('admin.listening-parts.show', $part->id) }}" class="btn btn-light btn-lg fw-bold text-muted mt-2" style="border-radius: 15px;">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function addOption() {
        const container = document.getElementById('optionsContainer');
        const count = container.querySelectorAll('.option-item').length;
        const letter = String.fromCharCode(65 + count);
        
        const div = document.createElement('div');
        div.className = 'col-md-6 option-item';
        div.innerHTML = `
            <div class="input-group">
                <span class="input-group-text border-2 bg-light fw-bold">${letter}</span>
                <input type="text" name="options[${letter}]" class="form-control border-2" placeholder="Option text">
                <button type="button" class="btn btn-outline-danger border-2" onclick="this.closest('.option-item').remove()"><i class="fas fa-times"></i></button>
            </div>
        `;
        container.appendChild(div);
    }

    document.getElementById('question_type').addEventListener('change', function() {
        const section = document.getElementById('optionsSection');
        if (this.value === 'mcq' || this.value === 'mcq_multi') {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    });
</script>
@endpush
@endsection
