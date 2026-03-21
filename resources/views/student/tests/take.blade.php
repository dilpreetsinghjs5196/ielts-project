@extends('layouts.admin')

@section('content')
<style>
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
    }
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .icon-box {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    .step-header {
        border-left: 5px solid #ce9d3c;
        padding-left: 1.5rem;
        margin-bottom: 2rem;
    }
    .test-card {
        border-radius: 15px;
        overflow: hidden;
        border: none;
        transition: all 0.3s;
    }
    .test-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
</style>

<div class="row mb-5">
    <div class="col-12">
        <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Take a New Test</h2>
        <p class="text-muted">Follow the steps below to select and start your mock test.</p>
    </div>
</div>

<div id="selection-flow">
    <!-- Step 1: Select Category -->
    <div id="step-1" class="step-container">
        <div class="step-header">
            <h4 class="fw-bold mb-1">Step 1: Select Module</h4>
            <p class="text-muted small mb-0">What would you like to practice today?</p>
        </div>
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-md-3">
                <div class="card border-0 shadow-sm hover-lift h-100" onclick="selectCategory('{{ $category->slug }}', '{{ $category->name }}')">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="{{ $category->icon ?? 'fas fa-book' }} fa-2x"></i>
                        </div>
                        <h5 class="fw-bold text-dark">{{ $category->name }}</h5>
                        <p class="text-muted small mb-0">{{ $category->description ?? 'Practice your '.$category->name.' skills.' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Step 2: Select Test Type -->
    <div id="step-2" class="step-container" style="display: none;">
        <div class="step-header">
            <h4 class="fw-bold mb-1"><a href="javascript:void(0)" onclick="goToStep(1)" class="text-decoration-none text-dark me-2"><i class="fas fa-arrow-left fa-xs"></i></a> Step 2: Select Test Type</h4>
            <p class="text-muted small mb-0">For <span class="fw-bold text-primary" id="display-module"></span></p>
        </div>
        <div class="row g-4" id="test-types-container">
            <!-- Populated via JS -->
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        </div>
    </div>

    <!-- Step 3: Select Portfolio/Set -->
    <div id="step-3" class="step-container" style="display: none;">
        <div class="step-header d-flex justify-content-between align-items-end">
            <div>
                <h4 class="fw-bold mb-1"><a href="javascript:void(0)" onclick="goToStep(2)" class="text-decoration-none text-dark me-2"><i class="fas fa-arrow-left fa-xs"></i></a> Step 3: Select Level & Set</h4>
                <p class="text-muted small mb-0"><span id="display-type" class="fw-bold"></span> Training for <span id="display-module-2" class="fw-bold"></span></p>
            </div>
        </div>
        <div id="sets-container" class="mt-4">
            <!-- Populated via JS -->
        </div>
    </div>

    <!-- Step 4: Select Test -->
    <div id="step-4" class="step-container" style="display: none;">
        <div class="step-header">
            <h4 class="fw-bold mb-1"><a href="javascript:void(0)" onclick="goToStep(3)" class="text-decoration-none text-dark me-2"><i class="fas fa-arrow-left fa-xs"></i></a> Step 4: Select Mock Test</h4>
            <p class="text-muted small mb-0">From <span id="display-set" class="fw-bold"></span></p>
        </div>
        <div class="row g-4" id="tests-container">
            <!-- Populated via JS -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selection = {
        categorySlug: '',
        categoryName: '',
        typeName: '',
        typeId: '',
        setId: '',
        setName: ''
    };

    function goToStep(step) {
        document.querySelectorAll('.step-container').forEach(el => el.style.display = 'none');
        document.getElementById(`step-${step}`).style.display = 'block';
        window.scrollTo(0, 0);
    }

    function selectCategory(slug, name) {
        selection.categorySlug = slug;
        selection.categoryName = name;
        document.getElementById('display-module').innerText = name;
        document.getElementById('display-module-2').innerText = name;
        
        // Fetch test types
        fetch("{{ route('frontend.test-types') }}")
            .then(res => res.json())
            .then(types => {
                const container = document.getElementById('test-types-container');
                container.innerHTML = '';
                types.forEach(type => {
                    container.innerHTML += `
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm hover-lift" onclick="selectType('${type.name}', '${type.id}')">
                                <div class="card-body p-4 text-center">
                                    <div class="icon-box bg-warning bg-opacity-10 text-warning">
                                        <i class="fas ${type.slug === 'academic' ? 'fa-graduation-cap' : 'fa-briefcase'} fa-2x"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1">${type.name}</h5>
                                    <p class="text-muted small mb-0">Targeted preparation for ${type.name} learners.</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
                goToStep(2);
            });
    }

    function selectType(name, id) {
        selection.typeName = name;
        selection.typeId = id;
        document.getElementById('display-type').innerText = name;
        
        loadSets();
    }

    function loadSets() {
        const setsContainer = document.getElementById('sets-container');
        setsContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
        
        // Get levels first
        fetch("{{ route('frontend.levels') }}")
            .then(res => res.json())
            .then(levels => {
                setsContainer.innerHTML = '';
                levels.forEach(level => {
                    // Create level row
                    const levelRow = document.createElement('div');
                    levelRow.className = 'mb-5';
                    levelRow.innerHTML = `
                        <h5 class="fw-bold text-dark mb-4 border-start border-4 border-primary ps-3">${level.name}</h5>
                        <div id="level-${level.id}-sets" class="row g-4">
                            <div class="col-12"><p class="text-muted italic">Loading portolios...</p></div>
                        </div>
                    `;
                    setsContainer.appendChild(levelRow);
                    
                    // Fetch sets for this level
                    fetch(`{{ route('frontend.module-sets') }}?category=${selection.categorySlug}&test_type=${selection.typeName}&level_id=${level.id}`)
                        .then(res => res.json())
                        .then(sets => {
                            const grid = document.getElementById(`level-${level.id}-sets`);
                            grid.innerHTML = '';
                            if (sets.length === 0) {
                                grid.innerHTML = '<div class="col-12"><p class="text-muted small ms-3">No portfolios available for this level yet.</p></div>';
                            } else {
                                sets.forEach(set => {
                                    grid.innerHTML += `
                                        <div class="col-md-3">
                                            <div class="card border-0 shadow-sm hover-lift h-100" onclick="selectSet('${set.id}', '${set.name}')">
                                                <div class="card-body p-4">
                                                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3">Portfolio</span>
                                                    <h6 class="fw-bold text-dark mb-1">${set.name}</h6>
                                                    <p class="text-muted small mb-0">${set.tests_count} Mock Tests</p>
                                                </div>
                                                <div class="card-footer bg-light border-0 py-2 text-center text-primary fw-bold small">
                                                    Select Set <i class="fas fa-chevron-right ms-1"></i>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                });
                            }
                        });
                });
                goToStep(3);
            });
    }

    function selectSet(id, name) {
        selection.setId = id;
        selection.setName = name;
        document.getElementById('display-set').innerText = name;
        
        loadTests(id);
    }

    function loadTests(setId) {
        const testsContainer = document.getElementById('tests-container');
        testsContainer.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>';
        
        fetch(`{{ route('frontend.tests') }}?module_set_id=${setId}`)
            .then(res => res.json())
            .then(tests => {
                testsContainer.innerHTML = '';
                if (tests.length === 0) {
                    testsContainer.innerHTML = '<div class="col-12 text-center text-muted py-5"><h5>No tests available in this set.</h5></div>';
                } else {
                    tests.forEach(test => {
                        const showRoute = "{{ route('student.tests.show', ':id') }}".replace(':id', test.id);
                        
                        let badgeClass = 'success-subtle';
                        let badgeText = 'Available';
                        let buttonText = 'Take Mock Test';

                        if (test.status === 'pending') {
                            badgeClass = 'warning-subtle text-warning';
                            badgeText = 'In Progress';
                            buttonText = 'Resume / Restart';
                        } else if (test.status === 'completed') {
                            badgeClass = 'secondary-subtle text-secondary';
                            badgeText = 'Completed';
                            buttonText = 'Finished';
                        }

                        testsContainer.innerHTML += `
                            <div class="col-md-4">
                                <div class="card test-card shadow-sm border-0 h-100">
                                    <div class="card-body p-4 text-center">
                                        <div class="mb-3">
                                            <span class="badge bg-${badgeClass} px-3 py-2 rounded-pill">${badgeText}</span>
                                        </div>
                                        <h5 class="fw-bold mb-1">${test.name}</h5>
                                        <p class="text-muted small mb-3">Standard IELTS Format</p>
                                        <button onclick="handleStart('${test.id}', '${test.status}')" class="btn btn-primary w-100 rounded-pill py-2" ${test.status === 'completed' ? 'disabled' : ''}>
                                            ${buttonText} <i class="fas ${test.status === 'pending' ? 'fa-redo' : 'fa-play'} ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                goToStep(4);
            });
    }

    function handleStart(testId, status) {
        const showRoute = "{{ route('student.tests.show', ':id') }}".replace(':id', testId);
        const restartRoute = "{{ route('student.tests.restart', ':id') }}".replace(':id', testId);

        if (status === 'pending') {
            if (confirm("You have a test in progress. \n\nOK -> Continue where you left off \nCancel -> Restart from the beginning")) {
                window.location.href = showRoute;
            } else {
                if (confirm("Are you sure you want to RESTART? This will delete your current progress.")) {
                    window.location.href = restartRoute;
                }
            }
        } else {
            window.location.href = showRoute;
        }
    }
</script>
@endpush
@endsection
