@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.question-groups.index') }}">Question Bank</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Segment</li>
                </ol>
            </nav>
            <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Edit Segment: {{ $questionGroup->title }}</h2>
        </div>
    </div>

    <form action="{{ route('admin.question-groups.update', $questionGroup) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="title" class="form-label font-weight-bold">Segment Title</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Reading Passage 1" value="{{ old('title', $questionGroup->title) }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="instruction" class="form-label font-weight-bold">Instructions for Students</label>
                            <input type="text" name="instruction" id="instruction" class="form-control" value="{{ old('instruction', $questionGroup->instruction) }}">
                        </div>

                        <div class="mb-4 {{ $questionGroup->category->slug != 'reading' ? 'd-none' : '' }}" id="passage_section">
                            <label for="passage" class="form-label font-weight-bold">Common Passage / Paragraph (Reading Text)</label>
                            <textarea name="passage" id="passage" rows="20" class="form-control" style="font-size: 1.1rem; line-height: 1.6; border-color: #ce9d3c;" placeholder="Paste the long reading passage here...">{{ old('passage', $questionGroup->passage) }}</textarea>
                            <small class="text-muted">You can paste very long paragraphs here for Reading tests.</small>
                        </div>

                        <div class="mb-4 {{ $questionGroup->category->slug != 'listening' ? 'd-none' : '' }}" id="audio_section">
                            <label for="audio_file" class="form-label font-weight-bold">Common Audio File (Listening Segment)</label>
                            @if ($questionGroup->audio_file)
                                <div class="mb-3 p-3 bg-light rounded border">
                                    <p class="small text-muted mb-1">Current Audio:</p>
                                    <audio controls class="w-100"><source src="{{ asset('storage/' . $questionGroup->audio_file) }}"></audio>
                                </div>
                            @endif
                            <input type="file" name="audio_file" id="audio_file" class="form-control" accept="audio/*">
                        </div>

                        <div class="mb-4 {{ $questionGroup->category->slug != 'writing' ? 'd-none' : '' }}" id="writing_section">
                            <label for="attachment" class="form-label font-weight-bold">Writing Task Image (Chart/Graph/Table)</label>
                            @if ($questionGroup->attachment)
                                <div class="mb-3 p-2 bg-light rounded border text-center">
                                    <p class="small text-muted mb-2 text-start">Current Image:</p>
                                    <img src="{{ asset('storage/' . $questionGroup->attachment) }}" class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                                </div>
                            @endif
                            <input type="file" name="attachment" id="attachment" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold mb-3 text-uppercase small text-secondary">Categorization</h6>
                        
                        <div class="mb-3">
                            <label class="form-label small font-weight-bold text-muted">Module</label>
                            <select name="category_id" id="category_id" class="form-select" onchange="handleModuleChange(this)" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" {{ $questionGroup->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small font-weight-bold text-muted">Test Type</label>
                            <select name="test_type_id" class="form-select" required>
                                @foreach ($testTypes as $type)
                                    <option value="{{ $type->id }}" {{ $questionGroup->test_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small font-weight-bold text-muted">Level</label>
                            <select name="level_id" class="form-select" required>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->id }}" {{ $questionGroup->level_id == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small font-weight-bold text-muted">Assign to Specific Test</label>
                            <select name="test_id" class="form-select @error('test_id') is-invalid @enderror">
                                <option value="">Draft (Unassigned)</option>
                                @foreach ($tests as $test)
                                    <option value="{{ $test->id }}" {{ (old('test_id', $questionGroup->test_id) == $test->id) ? 'selected' : '' }}>
                                        [{{ $test->level->name }}] - {{ $test->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted" style="font-size: 0.7rem;">Optional. You can assign this segment to a specific test later.</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-2 mt-3">Update Segment</button>
                        <a href="{{ route('admin.question-groups.index') }}" class="btn btn-light w-100">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function handleModuleChange(select) {
        const slug = select.options[select.selectedIndex].getAttribute('data-slug');
        
        document.getElementById('passage_section').classList.add('d-none');
        document.getElementById('audio_section').classList.add('d-none');
        document.getElementById('writing_section').classList.add('d-none');
        
        if (slug === 'reading') document.getElementById('passage_section').classList.remove('d-none');
        if (slug === 'listening') document.getElementById('audio_section').classList.remove('d-none');
        if (slug === 'writing') document.getElementById('writing_section').classList.remove('d-none');
    }
</script>
@endsection

