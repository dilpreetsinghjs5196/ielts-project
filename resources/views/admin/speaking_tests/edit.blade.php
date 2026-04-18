@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tests.index', ['category' => 'speaking']) }}">Speaking Tests</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Speaking Test</li>
            </ol>
        </nav>
        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Edit Speaking Test: {{ $speakingTest->name }}</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold"><i class="fas fa-cog me-2 text-primary"></i> General Settings</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.speaking-tests.update', $speakingTest) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">Test Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $speakingTest->name) }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="level_id" class="form-label fw-bold">Test Level</label>
                        <select name="level_id" id="level_id" class="form-select" required>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ $speakingTest->level_id == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="test_type_id" class="form-label fw-bold">Test Type</label>
                        <select name="test_type_id" id="test_type_id" class="form-select" required>
                            @foreach($testTypes as $type)
                                <option value="{{ $type->id }}" {{ $speakingTest->test_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ $speakingTest->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $speakingTest->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="mt-4 pt-2">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 10px;">Save General Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold"><i class="fas fa-tasks me-2 text-primary"></i> Speaking Parts (Part 1, 2 & 3)</h5>
            </div>
            <div class="card-body p-4">
                @foreach($speakingTest->parts as $part)
                <div class="mb-4 p-4 border rounded shadow-sm bg-light bg-opacity-50">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Part {{ $part->part_number }}: {{ $part->title }}</h6>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Passage / Prompt</label>
                        <div class="p-3 bg-white border rounded small">{!! nl2br(e($part->passage)) !!}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Questions</label>
                        <ul class="list-group list-group-flush">
                        @foreach($part->questions as $question)
                            <li class="list-group-item bg-transparent px-0"><i class="fas fa-angle-right text-muted me-2"></i> {{ $question->question_text }}</li>
                        @endforeach
                        </ul>
                    </div>

                </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
@endsection
