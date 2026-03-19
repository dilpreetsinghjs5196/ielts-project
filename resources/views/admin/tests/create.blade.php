@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tests.index') }}">Tests</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Test</li>
            </ol>
        </nav>
        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Create New Test</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="{{ route('admin.tests.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="module_set_id" class="form-label fw-bold small text-muted text-uppercase">Parent Portfolio (Big Container)</label>
                        <select name="module_set_id" id="module_set_id" class="form-select @error('module_set_id') is-invalid @enderror" required>
                            <option value="" disabled {{ !old('module_set_id') && !$preselectedModuleSetId ? 'selected' : '' }}>Select Module Portfolio</option>
                            @foreach($moduleSets as $set)
                                <option value="{{ $set->id }}" 
                                    {{ old('module_set_id', $preselectedModuleSetId) == $set->id ? 'selected' : '' }}>
                                    [{{ $set->level->name }}] - {{ $set->name }} ({{ $set->category->name }} {{ $set->testType->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('module_set_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold small text-muted text-uppercase">Mock Test Name (Small Container)</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Test 01" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="level_id" class="form-label fw-bold">Test Level</label>
                            <select name="level_id" id="level_id" class="form-select @error('level_id') is-invalid @enderror" required>
                                <option value="" disabled {{ !old('level_id') && !$preselectedLevelId ? 'selected' : '' }}>Select Level</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}" 
                                        {{ old('level_id', $preselectedLevelId) == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('level_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-bold">Module (Category)</label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="" disabled {{ !old('category_id') && !$preselectedCategoryId ? 'selected' : '' }}>Select Module</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $preselectedCategoryId) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="test_type_id" class="form-label fw-bold">Test Type</label>
                            <select name="test_type_id" id="test_type_id" class="form-select @error('test_type_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Select Type</option>
                                @foreach($testTypes as $type)
                                    <option value="{{ $type->id }}" 
                                        {{ old('test_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('test_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.tests.index') }}" class="btn btn-light px-4" style="border-radius: 10px;">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Create Test</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
