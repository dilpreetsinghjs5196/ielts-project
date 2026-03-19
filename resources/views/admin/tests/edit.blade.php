@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tests.index') }}">Tests</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Test</li>
            </ol>
        </nav>
        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Edit Test: {{ $test->name }}</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-10">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="{{ route('admin.tests.update', $test) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="name" class="form-label fw-bold">Test Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Test 01" value="{{ old('name', $test->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label for="level_id" class="form-label fw-bold">Test Level</label>
                            <select name="level_id" id="level_id" class="form-select @error('level_id') is-invalid @enderror" required>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}" {{ old('level_id', $test->level_id) == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                @endforeach
                            </select>
                            @error('level_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="category_id" class="form-label fw-bold">Module (Category)</label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $test->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="test_type_id" class="form-label fw-bold">Test Type</label>
                            <select name="test_type_id" id="test_type_id" class="form-select @error('test_type_id') is-invalid @enderror" required>
                                @foreach($testTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('test_type_id', $test->test_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('test_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-8">
                            <label for="module_set_id" class="form-label fw-bold">Portfolio (Module Set)</label>
                            <select name="module_set_id" id="module_set_id" class="form-select @error('module_set_id') is-invalid @enderror" required>
                                @foreach($moduleSets as $set)
                                    <option value="{{ $set->id }}" {{ old('module_set_id', $test->module_set_id) == $set->id ? 'selected' : '' }}>
                                        {{ $set->name }} ({{ $set->category->name }} - {{ $set->testType->name }} - {{ $set->level->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('module_set_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active" {{ old('status', $test->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $test->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Test Structure Info -->
                    <div class="alert alert-info border-0 shadow-sm p-4 d-flex align-items-center gap-3">
                        <i class="fas fa-info-circle fa-2x text-info"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Test Content Management</h6>
                            <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                This test currently contains <strong>{{ $test->questionGroups->count() }}</strong> question groups.
                                You can manage them in the <a href="{{ route('admin.question-groups.index', ['test_id' => $test->id]) }}" class="fw-bold text-decoration-none">Question Bank</a>.
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 d-flex justify-content-end gap-2 text-center text-md-end">
                        <a href="{{ route('admin.tests.index') }}" class="btn btn-light px-5 shadow-sm" style="border-radius: 10px; font-weight: 600;">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm" style="border-radius: 10px; font-weight: 600;">Update Mock Test</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
