@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Test Types</h2>
            <p class="text-muted">Manage different IELTS test categories (e.g. Academic, General Training).</p>
        </div>
        <a href="{{ route('admin.test-types.create') }}" class="btn btn-primary shadow-sm" style="border-radius: 10px; padding: 10px 20px;">
            <i class="fas fa-plus me-2"></i> Add New Type
        </a>
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
                                <th class="px-4 py-3 text-uppercase text-secondary font-weight-bolder opacity-7" style="font-size: 0.75rem;">Name</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7" style="font-size: 0.75rem;">Slug</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7" style="font-size: 0.75rem;">Description</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7 text-center" style="font-size: 0.75rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testTypes as $type)
                            <tr>
                                <td class="px-4 py-4">
                                    <h6 class="mb-0 font-weight-bold" style="color: #0d1624;">{{ $type->name }}</h6>
                                </td>
                                <td>
                                    <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded" style="font-size: 0.85rem;">{{ $type->slug }}</code>
                                </td>
                                <td>
                                    <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                        {{ Str::limit($type->description, 100) }}
                                    </p>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary mb-0 outline-none shadow-none" type="button" data-bs-toggle="dropdown">
                                            <i class="fa fa-ellipsis-v text-xs"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px;">
                                            <li><a class="dropdown-item py-2" href="{{ route('admin.test-types.edit', $type) }}"><i class="fas fa-edit me-2 text-primary"></i> Edit Type</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.test-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this test type?');">
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
                                <td colspan="4" class="py-5 text-center">
                                    <h5 class="text-muted">No Test Types Found</h5>
                                    <p class="text-secondary mb-0">Start by adding your first IELTS test type.</p>
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
