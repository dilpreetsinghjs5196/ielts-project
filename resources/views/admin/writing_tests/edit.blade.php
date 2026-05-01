@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tests.index', ['category' => 'writing']) }}">Writing Tests</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Writing Test</li>
            </ol>
        </nav>
        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Edit Writing Test: {{ $writingTest->name }}</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold"><i class="fas fa-cog me-2 text-primary"></i> General Settings</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.writing-tests.update', $writingTest) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">Test Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $writingTest->name) }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="level_id" class="form-label fw-bold">Test Level</label>
                        <select name="level_id" id="level_id" class="form-select" required>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ $writingTest->level_id == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="test_type_id" class="form-label fw-bold">Test Type</label>
                        <select name="test_type_id" id="test_type_id" class="form-select" required>
                            @foreach($testTypes as $type)
                                <option value="{{ $type->id }}" {{ $writingTest->test_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ $writingTest->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $writingTest->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                <h5 class="fw-bold"><i class="fas fa-tasks me-2 text-primary"></i> Test Tasks (Task 1 & Task 2)</h5>
            </div>
            <div class="card-body p-4">
                @foreach($writingTest->tasks as $task)
                <div class="mb-4 p-4 border rounded shadow-sm bg-light bg-opacity-50">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Task {{ $task->task_number }}: {{ $task->title }}</h6>
                        <span class="badge bg-primary px-3 rounded-pill">{{ $task->marks }} Marks</span>
                    </div>

                    <form action="{{ route('admin.writing-tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="marks" value="{{ $task->marks }}">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Instructions</label>
                            <textarea name="instruction" class="form-control form-control-sm" rows="2">{{ $task->instruction }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Prompt (Question Text)</label>
                            <textarea name="task_prompt" class="form-control form-control-sm" rows="4">{{ $task->question_text }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Task {{ $task->task_number }} Image (Optional)</label>
                            @if($task->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $task->image) }}" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control form-control-sm">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Sample Answer (Model Answer)</label>
                            <textarea name="sample_answer" class="form-control form-control-sm" rows="3">{{ $task->sample_answer }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="if(confirm('Delete this task?')) document.getElementById('delete-task-{{ $task->id }}').submit();"><i class="fas fa-trash me-1"></i> Delete Task</button>
                            <button type="submit" class="btn btn-dark btn-sm px-4">Update Task {{ $task->task_number }}</button>
                        </div>
                    </form>
                    <form id="delete-task-{{ $task->id }}" action="{{ route('admin.writing-tasks.destroy', $task) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
                @endforeach

                @if($writingTest->tasks->count() < 2)
                    <div class="text-center py-4 border border-dashed rounded">
                        <button class="btn btn-outline-primary"><i class="fas fa-plus me-2"></i> Add Missing Task</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
