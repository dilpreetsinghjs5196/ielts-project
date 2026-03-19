@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Modules</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Module</li>
            </ol>
        </nav>
        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Create New Module</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label font-weight-bold">Module Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Listening" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label font-weight-bold">Icon (FontAwesome Class)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-icons"></i></span>
                            <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" placeholder="e.g. fas fa-headphones" value="{{ old('icon') }}">
                        </div>
                        <small class="text-muted mt-1 d-block">Use FontAwesome 6 classes (e.g., fas fa-headphones).</small>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label font-weight-bold">Description</label>
                        <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="Describe the purpose of this module...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light px-4" style="border-radius: 10px;">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Save Module</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white" style="border-radius: 15px; background: linear-gradient(135deg, #0d1624, #16243b);">
            <div class="card-body p-4 text-center">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-info-circle text-primary fa-2x"></i>
                </div>
                <h5>Quick Help</h5>
                <p class="small opacity-75">Modules represent the main pillars of the IELTS exam. Standard modules are Listening, Reading, Writing, and Speaking.</p>
                <hr class="border-white opacity-25">
                <ul class="list-unstyled text-start small">
                    <li class="mb-2"><i class="fas fa-check-circle me-2"></i> Name: Visible to students.</li>
                    <li class="mb-2"><i class="fas fa-check-circle me-2"></i> Slug: Auto-generated for URLs.</li>
                    <li><i class="fas fa-check-circle me-2"></i> Icon: Used in navigation.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

