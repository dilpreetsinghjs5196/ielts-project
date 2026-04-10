@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Import Test from Document</h2>
            <p class="text-muted">Upload a Docx or PDF file to automatically create segments and questions.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-5">
            <form action="{{ route('admin.import.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-12">
                        <label for="test_name" class="form-label fw-bold">Test Name</label>
                        <input type="text" name="test_name" id="test_name" class="form-control" placeholder="e.g. Cambridge 15 Test 1">
                    </div>

                    <div class="col-md-4">
                        <label for="category_id" class="form-label fw-bold">Module (Category)</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="test_type_id" class="form-label fw-bold">Test Type</label>
                        <select name="test_type_id" id="test_type_id" class="form-select" required>
                            @foreach($testTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="level_id" class="form-label fw-bold">Preparation Level</label>
                        <select name="level_id" id="level_id" class="form-select" required>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mt-5">
                        <div class="upload-zone p-4 text-center bg-light" style="border: 2px dashed #ce9d3c; border-radius: 15px;">
                            <i class="fas fa-file-pdf fa-3x text-muted mb-2"></i>
                            <h5>Test Question File</h5>
                            <p class="text-muted small">Upload the main test content (Passages & Questions).</p>
                            <input type="file" name="test_file" id="test_file" class="form-control mt-2" accept=".pdf,.docx" required>
                        </div>
                    </div>

                    <div class="col-md-6 mt-5">
                        <div class="upload-zone p-4 text-center bg-light" style="border: 2px dashed #64748b; border-radius: 15px;">
                            <i class="fas fa-key fa-3x text-muted mb-2"></i>
                            <h5>Answer Key File (Optional)</h5>
                            <p class="text-muted small">Upload a file containing the numbered answers to auto-link them.</p>
                            <input type="file" name="answer_file" id="answer_file" class="form-control mt-2" accept=".pdf,.docx">
                        </div>
                    </div>

                    <div class="col-md-12 mt-4 text-center">
                        <button type="submit" class="btn btn-primary px-5 py-3 fw-bold shadow-sm" style="border-radius: 12px; background: #ce9d3c; border: none;">
                            <i class="fas fa-magic me-2"></i> Start Automated Import
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-5">
        <h5 class="fw-bold mb-3">Supported Patterns:</h5>
        <div class="row text-muted">
            <div class="col-md-6">
                <ul class="list-group list-group-flush bg-transparent">
                    <li class="list-group-item bg-transparent border-0 ps-0"><i class="fas fa-check text-success me-2"></i> "READING PASSAGE 1/2/3"</li>
                    <li class="list-group-item bg-transparent border-0 ps-0"><i class="fas fa-check text-success me-2"></i> "Questions 1-13"</li>
                    <li class="list-group-item bg-transparent border-0 ps-0"><i class="fas fa-check text-success me-2"></i> Numbered questions (1., 2., 3.)</li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="list-group list-group-flush bg-transparent">
                    <li class="list-group-item bg-transparent border-0 ps-0"><i class="fas fa-check text-success me-2"></i> Multiple Choice labels (A., B., C.)</li>
                    <li class="list-group-item bg-transparent border-0 ps-0"><i class="fas fa-check text-success me-2"></i> TFNG keywords</li>
                    <li class="list-group-item bg-transparent border-0 ps-0"><i class="fas fa-check text-success me-2"></i> Blank lines (______)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
