@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .module-swiper {
        padding: 10px 10px 40px 10px !important;
        margin: -10px -10px -10px -10px !important;
    }
    .swiper-button-next, .swiper-button-prev {
        width: 40px !important;
        height: 40px !important;
        background: white !important;
        border-radius: 50% !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        color: var(--primary) !important;
    }
    .swiper-pagination-bullet-active {
        background: var(--primary) !important;
    }

    /* Selection Card Styles */
    .selection-card {
        transition: all 0.3s ease;
        border-radius: 20px;
        cursor: pointer;
        border: 2px solid transparent;
        overflow: hidden;
    }
    .selection-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        border-color: var(--primary);
    }
    .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 28px;
    }

    .level-container {
        margin-bottom: 40px;
    }
    .level-header {
        border-left: 5px solid var(--primary);
        padding-left: 15px;
        margin-bottom: 25px;
    }
    .category-section {
        background: white;
        border-radius: 15px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
    }
    .table-responsive {
        /* Standard structural fix for dropdowns in responsive tables */
        overflow: visible !important;
    }
</style>

<div class="row mb-4">
    <div class="col-12">
        <div>
            @if($selectedModuleSet)
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.tests.index') }}" class="text-decoration-none text-primary">Mock Tests</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tests.index', ['category' => $selectedCategory->slug]) }}" class="text-decoration-none text-primary">{{ $selectedCategory->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tests.index', ['category' => $selectedCategory->slug, 'test_type_id' => $selectedModuleSet->test_type_id]) }}" class="text-decoration-none text-primary">{{ $selectedModuleSet->testType->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $selectedModuleSet->name }}</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">{{ $selectedModuleSet->name }} - Tests</h2>
                        <p class="text-muted text-sm mt-1">Manage mock tests within this portfolio.</p>
                    </div>
                    <a href="{{ route('admin.tests.create', ['category_id' => $selectedCategory->id, 'level_id' => $selectedModuleSet->level_id, 'module_set_id' => $selectedModuleSet->id, 'test_type_id' => $selectedModuleSet->test_type_id]) }}" class="btn btn-primary shadow-sm px-4 py-2" style="border-radius: 10px;">
                        <i class="fas fa-plus me-2"></i> Add New Test
                    </a>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.tests.index', ['category' => $selectedCategory->slug, 'test_type_id' => $selectedModuleSet->test_type_id]) }}" class="btn btn-sm btn-white border text-secondary fw-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left me-2"></i> Back to Portfolios
                    </a>
                </div>
            @elseif($selectedTestType)
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.tests.index') }}" class="text-decoration-none text-primary">Mock Tests</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tests.index', ['category' => $selectedCategory->slug]) }}" class="text-decoration-none text-primary">{{ $selectedCategory->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $selectedTestType->name }}</li>
                    </ol>
                </nav>
                <h2 class="mb-0 text-gray-800" style="font-weight: 700;">{{ $selectedCategory->name }} Portfolios ({{ $selectedTestType->name }})</h2>
                <p class="text-muted text-sm">Select a portfolio to see its specific mock tests.</p>
                <div class="mt-3">
                    <a href="{{ route('admin.tests.index', ['category' => $selectedCategory->slug]) }}" class="btn btn-sm btn-white border text-secondary fw-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left me-2"></i> Back to Types
                    </a>
                </div>
            @elseif($selectedCategory)
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.tests.index') }}" class="text-decoration-none text-primary">Mock Tests</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $selectedCategory->name }}</li>
                    </ol>
                </nav>
                <h2 class="mb-0 text-gray-800" style="font-weight: 700;">{{ $selectedCategory->name }} selection</h2>
                <p class="text-muted text-sm">Select the test type to manage its portfolios and tests.</p>
                <div class="mt-3">
                    <a href="{{ route('admin.tests.index') }}" class="btn btn-sm btn-white border text-secondary fw-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left me-2"></i> Back to Categories
                    </a>
                </div>
            @else
                <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Mock Tests</h2>
                <p class="text-muted text-sm">Select a category to view its test types, portfolios and mock tests.</p>
            @endif
        </div>
    </div>
</div>

@if(!$selectedCategory)
    {{-- Stage 1: Category Selection --}}
    <div class="row g-4 mb-5">
        @forelse($categories as $category)
            <div class="col-md-3">
                <a href="{{ route('admin.tests.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm selection-card">
                        <div class="card-body p-4 text-center">
                            <div class="icon-wrapper mx-auto bg-primary bg-opacity-10 text-primary">
                                <i class="{{ $category->icon }}"></i>
                            </div>
                            <h4 class="fw-bold mb-2 text-dark">{{ $category->name }}</h4>
                            <div class="d-inline-flex align-items-center py-1 px-3 bg-light rounded-pill">
                                <span class="fw-bold text-primary me-2">{{ $category->tests_count }}</span>
                                <span class="text-muted small">Tests Overall</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-folder-open fa-3x opacity-25 mb-3"></i>
                <p class="mb-0 text-muted">No categories found. Please create a category first.</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-3">Create Category</a>
            </div>
        @endforelse
    </div>
@elseif(!$selectedTestType)
     {{-- Stage 2: Test Type Selection Cards --}}
     <div class="row g-4 mb-5 justify-content-center">
        @foreach($testTypes as $type)
        <div class="col-md-4">
            <a href="{{ route('admin.tests.index', ['category' => $selectedCategory->slug, 'test_type_id' => $type->id]) }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm selection-card">
                    <div class="card-body p-5 text-center">
                        <div class="icon-wrapper mx-auto bg-primary bg-opacity-10 text-primary">
                            <i class="fas {{ $type->slug == 'academic' ? 'fa-graduation-cap' : 'fa-briefcase' }}"></i>
                        </div>
                        <h3 class="fw-bold mb-2 text-dark">{{ $type->name }}</h3>
                        <p class="text-muted mb-4">Manage {{ $selectedCategory->name }} tests for {{ $type->name }} students.</p>
                        <span class="btn btn-outline-primary rounded-pill px-4">Select {{ $type->name }}</span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
@elseif(!$selectedModuleSet)
    {{-- Stage 3: Portfolio Swiper Selection --}}
    @forelse ($levels as $level)
        <div class="row mb-3 mt-4">
            <div class="col-12 d-flex justify-content-between align-items-end px-4">
                <h4 class="fw-bold text-secondary mb-0 border-start border-4 border-primary px-3" style="letter-spacing: 0.5px;">{{ $level->name }}</h4>
                @if($level->moduleSets->where('test_type_id', $selectedTestType->id)->count() > 3)
                    <div class="d-flex gap-2">
                        <div class="swiper-btn-prev-{{ $level->id }} btn btn-sm btn-white shadow-sm rounded-circle"><i class="fas fa-chevron-left text-primary"></i></div>
                        <div class="swiper-btn-next-{{ $level->id }} btn btn-sm btn-white shadow-sm rounded-circle"><i class="fas fa-chevron-right text-primary"></i></div>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="mb-5 position-relative">
            <div class="swiper module-swiper" id="swiper-{{ $level->id }}">
                <div class="swiper-wrapper">
                    @forelse ($level->moduleSets->where('test_type_id', $selectedTestType->id) as $set)
                    <div class="swiper-slide h-auto">
                        <div class="card border-0 shadow-sm h-100 mx-2" style="border-radius: 15px; overflow: hidden;">
                            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small">
                                    {{ $set->testType->name }}
                                </span>
                                <a href="{{ route('admin.tests.create', ['category_id' => $selectedCategory->id, 'level_id' => $level->id, 'module_set_id' => $set->id, 'test_type_id' => $set->test_type_id]) }}" class="btn btn-xs btn-outline-primary shadow-none border" title="Add Test to this Portfolio">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-1" style="color: #0d1624;">{{ $set->name }}</h5>
                                <p class="text-muted small mb-0">{{ $set->tests_count }} Tests Inside</p>
                            </div>
                            <div class="card-footer bg-light border-0 p-3">
                                <a href="{{ route('admin.tests.index', ['category' => $selectedCategory->slug, 'test_type_id' => $selectedTestType->id, 'module_set_id' => $set->id]) }}" class="btn btn-primary btn-sm w-100 py-2 fw-bold shadow-sm" style="border-radius: 10px;">
                                    View Tests <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                        {{-- No module sets for this level and test type --}}
                    @endforelse
                </div>
                <div class="swiper-pagination mt-4"></div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-box-open fa-3x opacity-25 mb-3"></i>
            <p class="mb-0 text-muted">No portfolios found for {{ $selectedCategory->name }} ({{ $selectedTestType->name }}). Please create a portfolio first.</p>
            <a href="{{ route('admin.module-sets.create', ['category_id' => $selectedCategory->id, 'test_type_id' => $selectedTestType->id]) }}" class="btn btn-primary mt-3">Create Portfolio</a>
        </div>
    @endforelse
@else
    {{-- Stage 4: Tests List for Particular Portfolio --}}
    <div class="level-container">
        <div class="level-header d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold text-dark mb-0">{{ $selectedModuleSet->name }}</h3>
                <small class="text-muted text-uppercase tracking-wider" style="font-size: 0.7rem;">Prep Level: {{ $selectedModuleSet->level->name }} | Type: {{ $selectedModuleSet->testType->name }}</small>
            </div>
            <a href="{{ route('admin.tests.index', ['category' => $selectedCategory->slug, 'test_type_id' => $selectedModuleSet->test_type_id]) }}" class="btn btn-sm btn-light border text-secondary fw-bold" style="border-radius: 8px;">
                <i class="fas fa-chevron-left me-1"></i> Other Portfolios
            </a>
        </div>

        <div class="category-section">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light small text-uppercase text-secondary fw-bold" style="font-size: 0.7rem;">
                        <tr>
                            <th class="px-4 py-3">Mock Test Name</th>
                            <th class="py-3">Type</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($selectedModuleSet->tests as $test)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-dark">{{ $test->name }}</div>
                                    <small class="text-muted ms-2 mt-1" style="font-size: 0.7rem;">#{{ $test->id }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1 small" style="font-size: 0.75rem;">
                                    {{ $test->testType->name }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $test->status == 'active' ? 'success' : 'secondary' }} rounded-pill px-3 py-1 small" style="font-size: 0.75rem;">
                                    {{ ucfirst($test->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-link text-secondary p-0 outline-none shadow-none" type="button" data-bs-toggle="dropdown">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px;">
                                        <li><a class="dropdown-item py-2" href="{{ route('admin.tests.edit', $test) }}"><i class="fas fa-edit me-2 text-primary"></i> Edit Test</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.tests.destroy', $test) }}" method="POST" onsubmit="return confirm('Delete this test?');">
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
                            <td colspan="4" class="py-5 text-center text-muted">
                                <i class="fas fa-vial fa-2x opacity-25 mb-3"></i>
                                <p class="mb-0">No mock tests added to this portfolio yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @foreach ($levels as $level)
            new Swiper('#swiper-{{ $level->id }}', {
                slidesPerView: 1,
                spaceBetween: 20,
                pagination: {
                    el: '#swiper-{{ $level->id }} .swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-btn-next-{{ $level->id }}',
                    prevEl: '.swiper-btn-prev-{{ $level->id }}',
                },
                breakpoints: {
                    640: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 },
                    1400: { slidesPerView: 4 }
                }
            });
        @endforeach
    });
</script>
@endpush

@endsection
