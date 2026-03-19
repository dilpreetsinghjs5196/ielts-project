@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0 text-gray-800" style="font-weight: 700;">Students</h2>
            <p class="text-muted">Manage your students and their enrollment details.</p>
        </div>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary shadow-sm" style="border-radius: 10px; padding: 10px 20px;">
            <i class="fas fa-plus me-2"></i> Add New Student
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
                                <th class="px-4 py-3 text-uppercase text-secondary font-weight-bolder opacity-7" style="font-size: 0.75rem;">Student ID</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7" style="font-size: 0.75rem;">Name</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7" style="font-size: 0.75rem;">Email & Phone</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7 text-center" style="font-size: 0.75rem;">Status</th>
                                <th class="py-3 text-uppercase text-secondary font-weight-bolder opacity-7 text-center" style="font-size: 0.75rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                            <tr>
                                <td class="px-4 py-4">
                                    <span class="badge bg-light text-dark font-weight-bold" style="font-size: 0.85rem; border-radius: 8px;">{{ $student->student_id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3 bg-light rounded-circle d-flex align-items-center justify-content-center text-primary font-weight-bold" style="width: 40px; height: 40px; font-size: 1rem;">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <h6 class="mb-0 font-weight-bold" style="color: #0d1624;">{{ $student->name }}</h6>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 font-weight-bold" style="font-size: 0.9rem;">{{ $student->email }}</p>
                                    <p class="mb-0 text-muted" style="font-size: 0.85rem;">{{ $student->phone ?? 'N/A' }}</p>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $student->status == 'active' ? 'bg-success' : 'bg-danger' }} rounded-pill px-3" style="font-size: 0.75rem;">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary mb-0 outline-none shadow-none" type="button" data-bs-toggle="dropdown">
                                            <i class="fa fa-ellipsis-v text-xs"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 10px;">
                                            <li><a class="dropdown-item py-2" href="{{ route('admin.students.edit', $student) }}"><i class="fas fa-edit me-2 text-primary"></i> Edit Student</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
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
                                    <h5 class="text-muted">No Students Found</h5>
                                    <p class="text-secondary mb-0">Start by adding your first student.</p>
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

