@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Top Status Bar -->
    <div class="top-status-bar d-flex justify-content-between mb-4 bg-white p-3 rounded-4 shadow-sm align-items-center">
        <div class="d-flex align-items-center">
            <div class="status-dot bg-indigo me-2" style="width: 8px; height: 8px; border-radius: 50%; background: #6366f1;"></div>
            <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 1px;">Department Hierarchy Overview</span>
        </div>
        <div class="d-flex gap-3 align-items-center">
            <a href="{{ route('admin.majors.index') }}" class="btn btn-sm btn-light rounded-pill px-3 shadow-sm border-0">
                <i class="fas fa-th-list me-1"></i> Management View
            </a>
            <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i> {{ date('M d, Y') }}</span>
        </div>
    </div>

    <!-- Page Title -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">Department Hierarchy</h4>
        <p class="text-muted small mb-0">Visualizing the connections between departments, majors, classes, and subjects.</p>
    </div>

    <div class="accordion hierarchy-accordion" id="deptAccordion">
        @forelse($departments as $dept)
            <div class="accordion-item border-0 mb-4 shadow-sm rounded-4 overflow-hidden">
                <h2 class="accordion-header">
                    <button class="accordion-button bg-white py-4 px-4 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#dept-{{ $dept->id }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-pill bg-indigo bg-opacity-10 text-indigo p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; color: #6366f1;">
                                <i class="fas fa-university fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">{{ $dept->name }}</h5>
                                <div class="d-flex gap-2 mt-1">
                                    <span class="badge bg-indigo bg-opacity-10 text-indigo rounded-pill small px-2 py-1" style="color: #6366f1; font-size: 0.7rem;">
                                        {{ $dept->majors->count() }} Majors
                                    </span>
                                    <span class="badge bg-light text-muted rounded-pill small px-2 py-1" style="font-size: 0.7rem;">
                                        ID: {{ $dept->code }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="dept-{{ $dept->id }}" class="accordion-collapse collapse show">
                    <div class="accordion-body bg-light bg-opacity-25 p-4">
                        <div class="row g-4">
                            @forelse($dept->majors as $major)
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm rounded-4 h-100 transition-2 hover-up">
                                        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-start">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-info bg-opacity-10 text-info p-2 rounded-3">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </div>
                                                <h6 class="mb-0 fw-bold">{{ $major->name }} ({{ $major->code }})</h6>
                                            </div>
                                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">{{ $major->classes->count() }} Classes</span>
                                        </div>
                                        <div class="card-body px-4 pb-4">
                                            <div class="row g-3 mt-1">
                                                @forelse($major->classes as $class)
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="p-3 border rounded-4 bg-white hover-shadow transition-2 h-100">
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="small fw-bold text-dark opacity-75"><i class="fas fa-users-rectangle me-1 text-warning"></i> {{ $class->name }}</span>
                                                                <span class="badge bg-light text-muted rounded-pill" style="font-size: 0.6rem;">{{ $class->subjects->count() }} Subj</span>
                                                            </div>
                                                            <div class="vstack gap-2">
                                                                @forelse($class->subjects as $subject)
                                                                    <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light bg-opacity-50">
                                                                        <span class="small text-muted fw-semibold" style="font-size: 0.75rem;">{{ $subject->name }}</span>
                                                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold" style="font-size: 0.6rem;" title="Quizzes">
                                                                            {{ $subject->quizzes_count }} Quizzes
                                                                        </span>
                                                                    </div>
                                                                @empty
                                                                    <span class="text-muted small italic opacity-50">No subjects</span>
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-center py-3">
                                                        <p class="text-muted small mb-0">No classes assigned to this major.</p>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">
                                    <p class="text-muted mb-0">No majors found in this department.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 bg-white rounded-4 shadow-sm py-5">
                <i class="fas fa-university text-light fa-5x mb-4"></i>
                <h4 class="fw-bold text-muted">No Academic Data Found</h4>
                <p class="text-muted">Start by setting up your departments and majors in the list view.</p>
                <div class="mt-3">
                    <a href="{{ route('admin.majors.index') }}" class="btn btn-primary rounded-pill px-5 shadow">Get Started</a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .hover-up { transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1); }
    .hover-up:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
    .transition-2 { transition: 0.2s ease-in-out; }
    .hover-shadow:hover { box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-color: #6366f1 !important; }
    .accordion-button:not(.collapsed) { background-color: #ffffff; color: #000; }
    .accordion-button::after { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23212529'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e"); }
    .bg-indigo { background-color: #6366f1; }
    .text-indigo { color: #6366f1; }
</style>
@endsection
