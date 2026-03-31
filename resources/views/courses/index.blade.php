@extends('layouts.admin')

@section('content')
<div class="user-management-heading mb-4 px-2">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ $dashboardTitle }}</h1>
            <p class="text-muted small">View and access all courses assigned to you.</p>
        </div>
        <div class="breadcrumb-right text-muted small">
            <i class="fas fa-home me-1"></i> Dashboard / {{ $dashboardTitle }}
        </div>
    </div>
</div>

<div class="row g-4">
    @forelse($subjects as $course)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-custom transition-all hover-translate-y">
            <div class="card-header border-0 bg-primary bg-opacity-10 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="badge bg-primary bg-opacity-25 text-primary fw-bold px-3 py-2 rounded-pill small">
                        {{ $course->code ?? 'SUB-'.str_pad($course->id, 3, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="text-muted small">
                        <i class="far fa-clock me-1"></i> {{ $course->credits }} Credits
                    </div>
                </div>
                <h5 class="fw-bold text-dark mb-0">{{ $course->subject_name }}</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-light p-2 text-primary" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building fa-sm"></i>
                        </div>
                        <div>
                            <div class="text-muted" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Department</div>
                            <div class="fw-600 text-dark small">{{ $course->major->department->department_name ?? 'General' }}</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-light p-2 text-success" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-graduation-cap fa-sm"></i>
                        </div>
                        <div>
                            <div class="text-muted" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Major</div>
                            <div class="fw-600 text-dark small">{{ $course->major->name ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0 p-4 pt-0">
                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary w-100 rounded-pill py-2 fw-bold small">
                    View Course Details <i class="fas fa-arrow-right ms-2 fa-xs"></i>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <div class="mb-3">
            <i class="fas fa-book-open fa-3x text-muted opacity-25"></i>
        </div>
        <h5 class="text-muted">No courses assigned to you yet.</h5>
        <p class="text-muted small">If you believe this is an error, please contact the administrator.</p>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $subjects->links() }}
</div>

<style>
    .hover-translate-y:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.08) !important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .fw-600 { font-weight: 600; }
</style>
@endsection
