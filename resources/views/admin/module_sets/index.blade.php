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
        color: var(--sidebar-active-bg) !important;
    }
    .swiper-button-next::after, .swiper-button-prev::after {
        font-size: 18px !important;
        font-weight: 800 !important;
    }
    .swiper-pagination-bullet-active {
        background: var(--sidebar-active-bg) !important;
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
</style>

<div class="row mb-4">
    <div class="col-12">
        <div>
            @if($selectedTestType)
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.module-sets.index') }}" class="text-decoration-none text-primary">Module Portfolios</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.module-sets.index', ['category' => $selectedCategory->slug]) }}" class="text-decoration-none text-primary">{{ $selectedCategory->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $selectedTestType->name }}</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">{{ $selectedCategory->name }} ({{ $selectedTestType->name }})</h2>
                        <p class="text-muted text-sm">{{ $selectedCategory->description }}</p>
                    </div>
                    <a href="{{ route('admin.module-sets.create', ['category_id' => $selectedCategory->id, 'test_type_id' => $selectedTestType->id]) }}" class="btn btn-primary shadow-sm px-4 py-2" style="border-radius: 10px;">
                        <i class="fas fa-plus me-1"></i> Add Portfolio
                    </a>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.module-sets.index', ['category' => $selectedCategory->slug]) }}" class="btn btn-sm btn-white border text-secondary fw-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left me-2"></i> Change Test Type
                    </a>
                </div>
            @elseif($selectedCategory)
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.module-sets.index') }}" class="text-decoration-none text-primary">Module Portfolios</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $selectedCategory->name }}</li>
                    </ol>
                </nav>
                <h2 class="mb-0 text-gray-800" style="font-weight: 700;">{{ $selectedCategory->name }} Selection</h2>
                <p class="text-muted text-sm">Select the test type to manage its portfolios.</p>
                <div class="mt-3">
                    <a href="{{ route('admin.module-sets.index') }}" class="btn btn-sm btn-white border text-secondary fw-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left me-2"></i> Back to Modules
                    </a>
                </div>
            @else
                <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Module Portfolios</h2>
                <p class="text-muted text-sm">Select a module to view its portfolios and containers.</p>
            @endif
        </div>
    </div>
</div>

@if(!$selectedCategory)
    {{-- Stage 1: Category Selection --}}
    <div class="row g-4 mb-5">
        @foreach($categories as $category)
            <div class="col-md-3">
                <a href="{{ route('admin.module-sets.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm selection-card">
                        <div class="card-body p-4 text-center">
                            <div class="icon-wrapper mx-auto bg-primary bg-opacity-10 text-primary">
                                <i class="{{ $category->icon }}"></i>
                            </div>
                            <h4 class="fw-bold mb-2 text-dark">{{ $category->name }}</h4>
                            <div class="d-inline-flex align-items-center py-1 px-3 bg-light rounded-pill">
                                <span class="fw-bold text-primary me-2">{{ $category->module_sets_count }}</span>
                                <span class="text-muted small">Portfolios</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@elseif(!$selectedTestType)
    {{-- Stage 2: Test Type Selection --}}
    <div class="row g-4 mb-5 justify-content-center">
        @foreach($testTypes as $type)
        <div class="col-md-4">
            <a href="{{ route('admin.module-sets.index', ['category' => $selectedCategory->slug, 'test_type_id' => $type->id]) }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm selection-card">
                    <div class="card-body p-5 text-center">
                        <div class="icon-wrapper mx-auto bg-primary bg-opacity-10 text-primary">
                            <i class="fas {{ $type->slug == 'academic' ? 'fa-graduation-cap' : 'fa-briefcase' }}"></i>
                        </div>
                        <h3 class="fw-bold mb-2 text-dark">{{ $type->name }}</h3>
                        <p class="text-muted mb-4">View and manage {{ $selectedCategory->name }} Portfolios for {{ $type->name }} students.</p>
                        <span class="btn btn-outline-primary rounded-pill px-4">Select {{ $type->name }}</span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
@else
    {{-- Stage 3: Filtered Portfolio Swipers --}}
    @foreach ($levels as $level)
        <div class="row mb-3 mt-4">
            <div class="col-12 d-flex justify-content-between align-items-end px-4">
                <h4 class="fw-bold text-secondary mb-0 border-start border-4 border-primary px-3" style="letter-spacing: 0.5px;">{{ $level->name }}</h4>
                @if($level->moduleSets->count() > 3)
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
                    @foreach ($level->moduleSets as $set)
                    <div class="swiper-slide h-auto">
                        <div class="card border-0 shadow-sm h-100 mx-2" style="border-radius: 15px; overflow: hidden;">
                            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between">
                                <span class="badge bg-{{ $set->testType->slug == 'academic' ? 'primary' : 'warning' }} bg-opacity-10 text-{{ $set->testType->slug == 'academic' ? 'primary' : 'dark' }} px-3 py-2 rounded-pill small">
                                    {{ $set->testType->name }}
                                </span>
                                <div class="dropdown">
                                    <button class="btn btn-link text-secondary p-0 outline-none shadow-none" type="button" data-bs-toggle="dropdown">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li><a class="dropdown-item py-2" href="{{ route('admin.module-sets.edit', $set) }}"><i class="fas fa-edit me-2 text-primary"></i> Edit Portfolio</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.module-sets.destroy', $set) }}" method="POST" onsubmit="return confirm('Delete this container?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 text-danger"><i class="fas fa-trash-alt me-2"></i> Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-1" style="color: #0d1624;">{{ $set->name }}</h5>
                                <p class="text-muted small mb-3">Module: {{ $set->category->name }}</p>
                                
                                <div class="d-flex align-items-center gap-2 mt-4">
                                    <div class="p-2 bg-light rounded text-center flex-fill">
                                        <h4 class="mb-0 fw-bold text-primary">{{ $set->tests->count() }}</h4>
                                        <span class="text-muted" style="font-size: 0.7rem;">Mock Tests</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light border-0 p-3">
                                <a href="{{ route('admin.tests.index', ['category' => $selectedCategory->slug, 'module_set_id' => $set->id]) }}" class="btn btn-outline-primary btn-sm w-100 py-2 fw-bold" style="border-radius: 10px;">
                                    View Mock Test <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination mt-4"></div>
            </div>
        </div>
    @endforeach

    @if ($levels->count() == 0)
    <div class="row">
        <div class="col-12 text-center py-5">
            <div class="p-5 bg-white shadow-sm rounded-4">
                <i class="fas fa-folder-open fa-3x text-light mb-4 text-opacity-50"></i>
                <h5 class="text-muted font-weight-bold">No {{ $selectedCategory->name }} Portfolios yet!</h5>
                <p class="text-secondary mb-4 text-sm">Organize your {{ $selectedCategory->name }} mock tests for {{ $selectedTestType->name }} students.</p>
                <a href="{{ route('admin.module-sets.create', ['category_id' => $selectedCategory->id, 'test_type_id' => $selectedTestType->id]) }}" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">+ Create First Portfolio</a>
            </div>
        </div>
    </div>
    @endif
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

