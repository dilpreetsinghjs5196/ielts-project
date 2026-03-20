@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .module-swiper {
        padding: 10px 10px 40px 10px !important;
        margin: -10px -10px -10px -10px !important;
    }
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
            <h2 class="mb-0 text-gray-800" style="font-weight: 700;">{{ $selectedCategory->name ?? 'Module' }} Portfolios</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.module-sets.index') }}">Categories</a></li>
                    @if($selectedCategory)
                        <li class="breadcrumb-item"><a href="{{ route('admin.module-sets.index', ['category' => $selectedCategory->slug]) }}">{{ $selectedCategory->name }}</a></li>
                    @endif
                    @if($selectedTestType)
                        <li class="breadcrumb-item active" aria-current="page">{{ $selectedTestType->name }}</li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>

    @if(!$selectedCategory)
        <!-- Step 1: Category Selection -->
        <div class="row gx-4 gy-4">
            <div class="col-12 mb-2">
                <h5 class="text-secondary">Step 1: Select Module</h5>
            </div>
            @foreach($categories as $category)
            <div class="col-md-3">
                <a href="{{ route('admin.module-sets.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm hover-lift h-100" style="border-radius: 15px;">
                        <div class="card-body p-4 text-center">
                            <div class="icon-shape bg-primary text-white mx-auto mb-3">
                                <i class="{{ $category->icon }} fa-lg"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-1">{{ $category->name }}</h4>
                            <span class="badge bg-light text-primary rounded-pill px-3">{{ $category->module_sets_count }} Portfolios</span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

    @elseif(!$selectedTestType)
        <!-- Step 2: Test Type Selection -->
        <div class="row gx-4 gy-4">
            <div class="col-12 mb-2">
                <h5 class="text-secondary"><a href="{{ route('admin.module-sets.index') }}" class="text-decoration-none text-secondary"><i class="fas fa-arrow-left me-2"></i></a> Step 2: Select Type</h5>
            </div>
            @foreach($testTypes as $type)
            <div class="col-md-6 mb-4">
                <a href="{{ route('admin.module-sets.index', ['category' => $selectedCategory->slug, 'test_type_id' => $type->id]) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm hover-lift h-100" style="border-radius: 15px;">
                        <div class="card-body p-4 text-center">
                            <div class="icon-shape bg-primary text-white mx-auto mb-3">
                                <i class="fas {{ $type->slug == 'academic' ? 'fa-graduation-cap' : 'fa-briefcase' }} fa-lg"></i>
                            </div>
                            <h4 class="fw-bold text-dark">{{ $type->name }}</h4>
                            <p class="text-muted small mb-0">{{ $type->slug == 'academic' ? 'University and professional registration training.' : 'Secondary education, work experience, or migration training.' }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

    @else
        <!-- Step 3: Resulting Swipers -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h5 class="text-secondary mb-0"><a href="{{ route('admin.module-sets.index', ['category' => $selectedCategory->slug]) }}" class="text-decoration-none text-secondary"><i class="fas fa-arrow-left me-2"></i></a> Portfolios for {{ $selectedTestType->name }}</h5>
                <a href="{{ route('admin.module-sets.create', ['category_id' => $selectedCategory->id, 'test_type_id' => $selectedTestType->id]) }}" class="btn btn-primary shadow-sm px-4" style="border-radius: 10px;">
                    <i class="fas fa-plus me-2"></i> Add Portfolio
                </a>
            </div>
        </div>

        @foreach ($levels as $level)
            <div class="row mb-3 mt-4">
                <div class="col-12 d-flex justify-content-between align-items-end px-4">
                    <h5 class="fw-bold text-dark mb-0 border-start border-4 border-primary ps-3">{{ $level->name }}</h5>
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
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small">
                                        {{ $set->testType->name }}
                                    </span>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary p-0 outline-none shadow-none" type="button" data-bs-toggle="dropdown">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            <li><a class="dropdown-item py-2" href="{{ route('admin.module-sets.edit', $set) }}"><i class="fas fa-edit me-2 text-primary"></i> Edit</a></li>
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
                                    <p class="text-muted small mb-3">{{ $set->tests->count() }} Mock Tests</p>
                                </div>
                                <div class="card-footer bg-light border-0 p-3">
                                    <a href="{{ route('admin.tests.index', ['category' => $selectedCategory->slug, 'test_type_id' => $set->test_type_id, 'module_set_id' => $set->id]) }}" class="btn btn-outline-primary btn-sm w-100 py-2 fw-bold" style="border-radius: 10px;">
                                        View Mock Tests <i class="fas fa-arrow-right ms-1"></i>
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
                <div class="p-5 bg-white shadow-sm rounded-4 border-0">
                    <i class="fas fa-folder-open fa-3x text-light mb-4 opacity-50"></i>
                    <h5 class="text-muted font-weight-bold">No Portfolios Found</h5>
                    <p class="text-secondary mb-4 small">Organize your {{ $selectedCategory->name }} mock tests here.</p>
                    <a href="{{ route('admin.module-sets.create', ['category_id' => $selectedCategory->id, 'test_type_id' => $selectedTestType->id]) }}" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">+ Create First Portfolio</a>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>

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

