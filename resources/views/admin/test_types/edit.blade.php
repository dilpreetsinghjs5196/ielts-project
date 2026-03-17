@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.test-types.index') }}">Test Types</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Type</li>
            </ol>
        </nav>
        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Edit Test Type: {{ $testType->name }}</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="{{ route('admin.test-types.update', $testType) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label font-weight-bold">Type Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Academic" value="{{ old('name', $testType->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label font-weight-bold">Description</label>
                        <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="Describe the purpose of this test type...">{{ old('description', $testType->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.test-types.index') }}" class="btn btn-light px-4" style="border-radius: 10px;">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Update Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
