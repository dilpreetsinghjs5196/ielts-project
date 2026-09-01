@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Create New Segment (Part)</h2>
        <p class="text-muted">Test: {{ $test->name }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <form action="{{ route('admin.listening-parts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="listening_test_id" value="{{ $test->id }}">
                    
                    <div class="mb-3">
                        <label for="part_number" class="form-label fw-bold">Part Number</label>
                        <input type="number" name="part_number" id="part_number" class="form-control" value="{{ $test->parts->count() + 1 }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Part Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Introduction or Conversation at a Library" required>
                    </div>

                    <div class="mb-3">
                        <label for="instruction" class="form-label fw-bold">Instructions</label>
                        <input type="text" name="instruction" id="instruction" class="form-control" placeholder="e.g. Questions 1-10">
                    </div>

                    <div class="mb-3">
                        <label for="passage" class="form-label fw-bold">Transcript / Passage (Optional)</label>
                        <textarea name="passage" id="passage" class="form-control" rows="5"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Part Audio File</label>
                        <input type="file" name="audio_file" class="form-control" accept="audio/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Part Images</label>
                        <input type="file" name="images[]" multiple class="form-control" accept="image/*">
                        <small class="text-muted">You can select multiple images by holding Ctrl (Windows) or Cmd (Mac).</small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 10px;">Create Segment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
