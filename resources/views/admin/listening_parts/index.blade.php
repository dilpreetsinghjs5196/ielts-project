@extends('layouts.admin')

@section('content')
<style>
    .hover-lift {
        transition: all 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .icon-shape {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .breadcrumb-item a {
        color: #4a5568;
        text-decoration: none;
        font-weight: 500;
    }
    .breadcrumb-item.active {
        color: #2d3748;
        font-weight: 700;
    }
</style>

<div class="container-fluid p-0">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0 text-gray-800" style="font-weight: 700;">{{ $activeCategory->name }} - Question Bank</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.listening-tests.index') }}">{{ $activeCategory->name }}</a></li>
                    @if ($testTypeId)
                        <li class="breadcrumb-item"><a href="{{ route('admin.listening-tests.index', ['test_type' => $testTypeId]) }}">{{ $testTypes->where('id', $testTypeId)->first()->name ?? 'Type' }}</a></li>
                    @endif
                    @if ($levelId)
                        <li class="breadcrumb-item"><a href="{{ route('admin.listening-tests.index', ['test_type' => $testTypeId, 'level' => $levelId]) }}">{{ $levels->where('id', $levelId)->first()->name ?? 'Level' }}</a></li>
                    @endif
                    @if ($testId)
                        <li class="breadcrumb-item active" aria-current="page">{{ $tests->where('id', $testId)->first()->name ?? 'Test' }}</li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>

    @if($testId && $tests->where('id', $testId)->first() && $tests->where('id', $testId)->first()->audio_file)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #f6f8fb 0%, #e9eff5 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="icon-shape bg-primary text-white me-3" style="width: 45px; height: 45px;">
                        <i class="fas fa-play fa-xs"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold">Main Test Audio</h6>
                        <audio controls class="w-100" style="height: 30px;">
                            <source src="{{ asset('storage/' . $tests->where('id', $testId)->first()->audio_file) }}" type="audio/mpeg">
                        </audio>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (!$testTypeId)
        <div class="row gx-4 gy-4">
            <div class="col-12 mb-2">
                <h5 class="text-secondary">Step 1: Select Type</h5>
            </div>
            @foreach ($testTypes as $type)
                <div class="col-md-6">
                    <a href="{{ route('admin.listening-tests.index', ['test_type' => $type->id]) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm hover-lift h-100" style="border-radius: 15px;">
                            <div class="card-body p-4 text-center">
                                <div class="icon-shape bg-primary text-white mx-auto mb-3">
                                    <i class="fas {{ $type->slug == 'academic' ? 'fa-graduation-cap' : 'fa-briefcase' }} fa-lg"></i>
                                </div>
                                <h4 class="fw-bold text-dark">{{ $type->name }}</h4>
                                <p class="text-muted small mb-0">{{ $type->description ?? 'Manage '.$type->name.' questions.' }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @elseif (!$levelId)
        <div class="row gx-4 gy-4">
            <div class="col-12 mb-2">
                <h5 class="text-secondary"><a href="{{ route('admin.listening-tests.index') }}" class="text-decoration-none text-secondary"><i class="fas fa-arrow-left me-2"></i></a> Step 2: Select Level</h5>
            </div>
            @foreach ($levels as $level)
                <div class="col-md-4">
                    <a href="{{ route('admin.listening-tests.index', ['test_type' => $testTypeId, 'level' => $level->id]) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm hover-lift h-100" style="border-radius: 15px;">
                            <div class="card-body p-4 text-center">
                                <h4 class="fw-bold text-dark mb-3">{{ $level->name }}</h4>
                                <div class="btn btn-outline-primary btn-sm rounded-pill px-4">Select {{ $level->name }} <i class="fas fa-arrow-right ms-2"></i></div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @elseif (!$testId)
        <div class="row gx-4 gy-4">
            <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
                <h5 class="text-secondary mb-0"><a href="{{ route('admin.listening-tests.index', ['test_type' => $testTypeId]) }}" class="text-decoration-none text-secondary"><i class="fas fa-arrow-left me-2"></i></a> Step 3: Select Test</h5>
            </div>
            @forelse ($tests as $test_item)
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm hover-lift h-100" style="border-radius: 15px;">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                             <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-collapse small">Listening Test</span>
                             <div class="dropdown">
                                <button class="btn btn-link text-secondary p-0 outline-none shadow-none" type="button" data-bs-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px;">
                                    <li><a class="dropdown-item py-2" href="{{ route('admin.listening-tests.edit', $test_item) }}"><i class="fas fa-edit me-2 text-primary"></i> Edit Test</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.listening-tests.destroy', $test_item) }}" method="POST" onsubmit="return confirm('Delete this entire test and all its parts/questions?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item py-2 text-danger"><i class="fas fa-trash-alt me-2"></i> Delete Test</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body p-4 text-center">
                            <h5 class="fw-bold text-dark mb-1">{{ $test_item->name }}</h5>
                            <span class="badge bg-light text-primary mb-3">{{ $test_item->parts->count() }} Parts</span>
                            <div class="mt-2">
                                <a href="{{ route('admin.listening-tests.index', ['test_type' => $testTypeId, 'level' => $levelId, 'test' => $test_item->id]) }}" class="btn btn-outline-primary btn-sm w-100 py-2 fw-bold" style="border-radius: 10px;">
                                    View Mock Questions <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center text-muted">
                    <p>No tests found for this selection.</p>
                </div>
            @endforelse
        </div>
    @else
        <!-- Final Step: Parts List -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold">
                                <a href="{{ route('admin.listening-tests.index', ['test_type' => $testTypeId, 'level' => $levelId]) }}" class="text-decoration-none text-secondary"><i class="fas fa-arrow-left me-2"></i></a> 
                                Mock Segments
                            </h5>
                            <a href="{{ route('admin.listening-parts.create', ['test_id' => $testId]) }}" class="btn btn-primary shadow-sm" style="border-radius: 10px;">
                                <i class="fas fa-plus me-2"></i> Create New Segment
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0 mt-3">
                        <div class="table-responsive" style="overflow: visible;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-uppercase text-secondary fw-bold" style="font-size: 0.75rem;">
                                    <tr>
                                        <th class="px-4 py-3 opacity-7">Segment Title</th>
                                        <th class="py-3 opacity-7 text-center">Questions</th>
                                        <th class="py-3 opacity-7 text-center">Media</th>
                                        <th class="py-3 opacity-7 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($parts as $part)
                                    <tr>
                                        <td class="px-4 py-4">
                                            <h6 class="mb-1 fw-bold text-dark">PART {{ $part->part_number }}: {{ $part->title }}</h6>
                                            <p class="mb-0 text-muted small">{{ \Illuminate\Support\Str::limit($part->instruction ?? 'No instruction provided.', 60) }}</p>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 rounded-pill">{{ $part->questions_count }} Questions</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($part->audio_file)
                                                <i class="fas fa-volume-up text-primary" title="Audio File Present"></i>
                                            @endif
                                            @if ($part->image)
                                                <i class="fas fa-image text-success ms-2" title="Image Present"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.listening-parts.show', $part) }}" class="btn btn-sm btn-outline-primary px-3" style="border-radius: 8px;">
                                                Add Questions <i class="fas fa-plus ms-1"></i>
                                            </a>
                                            <div class="dropdown d-inline-block ms-2">
                                                <button class="btn btn-link text-secondary p-0 outline-none shadow-none" type="button" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px;">
                                                    <li><a class="dropdown-item py-2" href="{{ route('admin.listening-tests.edit', $part->listening_test_id) }}"><i class="fas fa-edit me-2 text-primary"></i> Edit Test</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.listening-parts.destroy', $part) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item py-2 text-danger"><i class="fas fa-trash-alt me-2"></i> Delete Part</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-5 text-center text-muted">
                                                <p class="mb-0">No parts found.</p>
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
    @endif
</div>
@endsection
