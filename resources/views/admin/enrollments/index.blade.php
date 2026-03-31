@extends('layouts.admin')

@section('content')
<!-- Breadcrumbs -->
<div class="breadcrumb-container d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
    <div>
        <h1 class="d-inline-block fw-bold text-dark mb-0">Academic Enrollments</h1>
        <p class="text-muted small mb-0">System-wide Relationships & Departmental Matrix</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none"><i class="fas fa-home me-1"></i> Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Assignments</li>
        </ol>
    </nav>
</div>

<div class="container-fluid px-4 pt-4">
    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 mb-4 shadow-sm" role="alert" style="border-left: 5px solid #28a745 !important;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fs-4 me-3 text-success"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Global Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                        <i class="fas fa-building fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase ls-1">Core Departments</div>
                        <div class="h3 fw-bold mb-0 text-dark">{{ $departments->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box bg-info bg-opacity-10 text-info rounded-3 p-3 me-3">
                        <i class="fas fa-user-graduate fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase ls-1">Enrolled Scholars</div>
                        <div class="h3 fw-bold mb-0 text-dark">{{ $departments->sum(fn($d) => $d->users->where('role_id', 3)->count()) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                        <i class="fas fa-chalkboard-teacher fs-3"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold text-uppercase ls-1">Teaching Faculty</div>
                        <div class="h3 fw-bold mb-0 text-dark">{{ $departments->sum(fn($d) => $d->users->where('role_id', 2)->count()) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department List -->
    <div class="card border-0 shadow-sm rounded-3 bg-white">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2 text-primary"></i> Department Management Matrix</h5>
            <div class="search-box position-relative w-25">
                <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="z-index: 5;"></i>
                <input type="text" class="form-control rounded-pill ps-5 border-0 bg-light" placeholder="Search by name..." style="padding-left: 45px !important;">
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="bg-light text-muted small text-uppercase fw-bold">
                        <tr>
                            <th class="ps-4 py-3">Department</th>
                            <th class="text-center">Staffing</th>
                            <th class="text-center">Student Body</th>
                            <th class="text-center">Assigned Courses</th>
                            <th class="text-end pe-4">Configuration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark fs-6">{{ $department->department_name }}</div>
                                <div class="text-muted extra-small">ID: {{ $department->id }}</div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="avatar-group me-2">
                                        @php $teachersCount = $department->users->where('role_id', 2)->count(); @endphp
                                        <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2 fw-bold">
                                            <i class="fas fa-user-tie me-1"></i> {{ $teachersCount }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-info bg-opacity-10 text-info px-3 py-2 fw-bold">
                                    <i class="fas fa-users me-1"></i> {{ $department->users->where('role_id', 3)->count() }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 fw-bold">
                                    <i class="fas fa-book-reader me-1"></i> {{ $department->subjects->count() }} Courses
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.enrollments.manage', $department->id) }}" class="btn btn-primary rounded-pill btn-sm px-4 shadow-sm hover-elevate">
                                    <i class="fas fa-cog me-1"></i> Configure
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted bg-light bg-opacity-50">
                                <i class="fas fa-folder-open fs-1 d-block mb-3 opacity-25"></i>
                                <h5>No Departments Recorded</h5>
                                <p class="small mb-0">Please start by adding a new department.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3 border-top border-light text-muted small">
            Displaying {{ $departments->count() }} departments
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .extra-small { font-size: 0.75rem; }
    .custom-table thead th { border: 0; }
    .custom-table tbody tr { transition: all 0.2s; }
    .custom-table tbody tr:hover { background-color: #f8fbff !important; }
    
    .hover-elevate { transition: all 0.2s; }
    .hover-elevate:hover { transform: translateY(-2px); }
    
    .icon-box {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .ls-1 { letter-spacing: 1px; }
    .breadcrumb-item a { text-decoration: none; color: #0d6efd; font-weight: 500; }
    .breadcrumb-item.active { color: #6c757d; font-weight: 500; }
    .breadcrumb-item i { font-size: 0.9rem; }
    
    .bg-light { background-color: #f8f9fa !important; }
</style>
@endsection

@section('scripts')
<script>
    document.querySelector('input[placeholder="Search departments..."]').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('.custom-table tbody tr');
        let matchCount = 0;

        rows.forEach(row => {
            const text = row.querySelector('td:first-child').textContent.toLowerCase();
            if (text.includes(query)) {
                row.style.display = '';
                matchCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const footer = document.querySelector('.card-footer');
        if (footer) footer.textContent = 'Displaying ' + matchCount + ' departments';
    });
</script>
@endsection
