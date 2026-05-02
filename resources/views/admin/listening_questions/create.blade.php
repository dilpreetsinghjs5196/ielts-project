@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Add New Question</h2>
        <p class="text-muted">Part {{ $part->part_number }}: {{ $part->title }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="{{ route('admin.listening-questions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="listening_part_id" value="{{ $part->id }}">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="question_number" class="form-label fw-bold">Question Number</label>
                            <input type="text" name="question_number" id="question_number" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="marks" class="form-label fw-bold">Marks</label>
                            <input type="number" name="marks" id="marks" class="form-control" value="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="question_type" class="form-label fw-bold">Question Type</label>
                        <select name="question_type" id="question_type" class="form-select" required>
                            <option value="mcq">MCQ</option>
                            <option value="fill_blanks">Fill Blanks</option>
                            <option value="short_answer">Short Answer</option>
                            <option value="tfng">TFNG</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Question Text / Body</label>
                        <textarea name="title" id="title" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="correct_answer" class="form-label fw-bold">Correct Answer</label>
                        <input type="text" name="correct_answer" id="correct_answer" class="form-control">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 10px;">Save Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
