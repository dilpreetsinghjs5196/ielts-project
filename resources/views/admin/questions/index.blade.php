@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0 text-gray-800" style="font-weight: 700;">{{ $activeCategory->name }} Bank</h2>
            <p class="text-muted">Repository of questions for the {{ $activeCategory->name }} module.</p>
        </div>
        <a href="{{ route('admin.questions.create', ['category_id' => $activeCategory->id]) }}" class="btn btn-primary shadow-sm" style="border-radius: 10px; padding: 10px 20px;">
            <i class="fas fa-plus me-2"></i> Add {{ $activeCategory->name }} Question
        </a>
    </div>
</div>

<!-- Category Tabs -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-2">
                <ul class="nav nav-pills nav-justified" id="testTypeTabs">
                    @foreach($testTypes as $type)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTestType && $activeTestType->id == $type->id ? 'active' : '' }}" 
                           href="{{ route('admin.questions.index', ['category' => $activeCategory->slug, 'type' => $type->slug]) }}"
                           style="border-radius: 10px; font-weight: 600;">
                            {{ $type->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-0">
                <div class="table-responsive" style="overflow: visible;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-uppercase text-secondary font-weight-bolder opacity-7" style="font-size: 0.75rem;">Question Details</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7" style="font-size: 0.75rem;">Type & Marks</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7" style="font-size: 0.75rem;">Level</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7 text-center" style="font-size: 0.75rem;">Media</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7 text-center" style="font-size: 0.75rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($questions as $question)
                            <tr>
                                <td class="px-4 py-4">
                                    <h6 class="mb-1 font-weight-bold" style="color: #0d1624;">{{ $question->title ?? 'Question #'.$question->id }}</h6>
                                    <p class="mb-0 text-muted small" style="max-width: 300px;">
                                        @if($question->passage)
                                            <span class="badge bg-light text-dark me-2">Passage Linked</span>
                                        @endif
                                        {{ Str::limit($question->content, 80) }}
                                    </p>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark font-weight-bold small text-uppercase">{{ str_replace('_', ' ', $question->question_type) }}</span>
                                        <span class="text-muted small">{{ $question->marks }} Mark(s)</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info px-2 py-1" style="font-size: 0.75rem; border-radius: 6px;">{{ $question->level->name }}</span>
                                </td>
                                <td class="text-center">
                                    @if($question->audio_file)
                                        <i class="fas fa-volume-up text-primary cursor-pointer" title="Audio File Present"></i>
                                    @endif
                                    @if($question->attachment)
                                        <i class="fas fa-image text-success cursor-pointer ms-2" title="Image Attached"></i>
                                    @endif
                                    @if(!$question->audio_file && !$question->attachment)
                                        <span class="text-muted small">None</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary mb-0 outline-none shadow-none" type="button" data-bs-toggle="dropdown">
                                            <i class="fa fa-ellipsis-v text-xs"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px;">
                                            <li><a class="dropdown-item py-2" href="{{ route('admin.questions.edit', $question) }}"><i class="fas fa-edit me-2 text-primary"></i> Edit Question</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                                        <i class="fas fa-trash-alt me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center">
                                    <h5 class="text-muted">No Questions Found</h5>
                                    <p class="text-secondary mb-0">No questions found for {{ $activeCategory->name }} ({{ $activeTestType ? $activeTestType->name : 'All Types' }}).</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
