@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.module-sets.index') }}">Module Portfolios</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Module</li>
            </ol>
        </nav>
        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Create Module Portfolio</h2>
        <p class="text-muted">A "Big Container" (e.g. Module 1) that will hold up to 10 mock tests.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="{{ route('admin.module-sets.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">Portfolio Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Module 1" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="level_id" class="form-label fw-bold small text-muted">Preparation Level</label>
                            <select name="level_id" id="level_id" class="form-select @error('level_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Select Level</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="category_id" class="form-label fw-bold small text-muted">Category (Module)</label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="" disabled {{ !old('category_id') && !$preselectedCategoryId ? 'selected' : '' }}>Select Module</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $preselectedCategoryId) == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="test_type_id" class="form-label fw-bold small text-muted">Training Type</label>
                            <select name="test_type_id" id="test_type_id" class="form-select @error('test_type_id') is-invalid @enderror" required>
                                <option value="" disabled {{ !old('test_type_id', $preselectedTestTypeId) == null ? 'selected' : '' }}>Select Type</option>
                                @foreach($testTypes as $type)
                                    <option value="{{ $type->id }}" {{ (old('test_type_id', $preselectedTestTypeId) == $type->id) ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label fw-bold small text-muted">Portfolio Status</label>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="status" value="active" id="statusSwitch" checked>
                            <label class="form-check-label" for="statusSwitch">Publish Portfolio (Active)</label>
                        </div>
                    </div>

                    <div class="mt-5 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.module-sets.index') }}" class="btn btn-light px-4" style="border-radius: 10px;">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Create Module Portfolio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
