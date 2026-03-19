@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 text-gray-800" style="font-weight: 700;">{{ $activeCategory->name }} - Question Bank</h2>
                <p class="text-muted">Manage segments (passages/audio) containing multiple questions.</p>
            </div>
            <a href="{{ route('admin.question-groups.create', ['category_id' => $activeCategory->id]) }}" class="btn btn-primary shadow-sm" style="border-radius: 10px; padding: 10px 20px;">
                <i class="fas fa-plus me-2"></i> Create New Segment
            </a>
        </div>
    </div>

    <!-- Segments Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-uppercase text-secondary font-weight-bolder opacity-7" style="font-size: 0.75rem;">Segment Title</th>
                                    <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7" style="font-size: 0.75rem;">Type & Level</th>
                                    <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7 text-center" style="font-size: 0.75rem;">Questions</th>
                                    <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7 text-center" style="font-size: 0.75rem;">Media</th>
                                    <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7 text-center" style="font-size: 0.75rem;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($groups->count() > 0)
                                    @foreach ($groups as $group)
                                    <tr>
                                        <td class="px-4 py-4">
                                            <h6 class="mb-1 font-weight-bold" style="color: #0d1624;">{{ $group->title }}</h6>
                                            <p class="mb-0 text-muted small">{{ $group->instruction ?? 'No instruction provided.' }}</p>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-dark font-weight-bold small">{{ $group->testType->name }}</span>
                                                <span class="text-muted small">{{ $group->level->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill">{{ $group->questions_count ?? $group->questions->count() }} Questions</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($group->audio_file)
                                                <i class="fas fa-volume-up text-primary" title="Audio File Present"></i>
                                            @endif
                                            @if ($group->passage)
                                                <i class="fas fa-file-alt text-info ms-2" title="Passage Present"></i>
                                            @endif
                                            @if ($group->attachment)
                                                <i class="fas fa-image text-success ms-2" title="Attachment Present"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.question-groups.show', $group) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                                                <i class="fas fa-eye me-1"></i> View & Add Questions
                                            </a>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-link text-secondary mb-0 outline-none shadow-none" type="button" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v text-xs"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px;">
                                                    <li><a class="dropdown-item py-2" href="{{ route('admin.question-groups.edit', $group) }}"><i class="fas fa-edit me-2 text-primary"></i> Edit Segment</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.question-groups.destroy', $group) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this segment? All associated questions will also be deleted.');">
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
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="py-5 text-center">
                                            <h5 class="text-muted">No Segments Found</h5>
                                            <p class="text-secondary mb-0">Click the button above to create your first passage or audio segment for {{ $activeCategory->name }}.</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

