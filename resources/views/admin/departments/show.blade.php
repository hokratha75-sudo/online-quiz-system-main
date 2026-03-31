@extends('layouts.admin')

@section('title', $department->name . ' - Department Detail')

@section('styles')
<style>
    .department-detail-page {
        padding: 22px 20px 28px;
    }

    .department-hero {
        background: linear-gradient(135deg, #21406a 0%, #2b5e97 100%);
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(43, 94, 151, 0.22);
        color: #fff;
        display: flex;
        gap: 18px;
        align-items: center;
        justify-content: space-between;
        padding: 24px 28px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .department-hero-main {
        display: flex;
        gap: 18px;
        align-items: center;
        min-width: 260px;
    }

    .department-icon {
        width: 74px;
        height: 74px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
    }

    .department-code {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.16);
        color: #dce9f8;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .department-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .department-stats {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .department-stat {
        min-width: 72px;
        text-align: center;
        padding: 10px 14px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .department-stat strong {
        display: block;
        font-size: 28px;
        line-height: 1;
    }

    .department-stat span {
        font-size: 11px;
        color: #dce9f8;
    }

    .department-back {
        color: #dce9f8;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 999px;
        padding: 9px 16px;
        font-weight: 600;
    }

    .department-back:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.08);
    }

    .major-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 3px 16px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 18px;
        border: 1px solid #edf1f5;
    }

    .major-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-bottom: 1px solid #edf1f5;
        background: #fbfdff;
        flex-wrap: wrap;
    }

    .major-badge {
        display: inline-block;
        background: #dbeafe;
        color: #1d4ed8;
        border-radius: 6px;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .major-name {
        display: block;
        color: #1f2937;
        font-size: 20px;
        font-weight: 700;
        text-decoration: none;
    }

    .major-name:hover {
        color: #0d6efd;
    }

    .major-head-stats {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .major-pill {
        border-radius: 999px;
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 700;
    }

    .major-pill.classes {
        background: #e7f7ef;
        color: #198754;
    }

    .major-pill.courses {
        background: #e3f5fd;
        color: #0dcaf0;
    }

    .major-card-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }

    .major-column {
        padding: 14px 18px 18px;
    }

    .major-column + .major-column {
        border-left: 1px solid #edf1f5;
    }

    .major-column-title {
        font-size: 12px;
        font-weight: 700;
        color: #52606d;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .mini-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        border: 1px solid #eef2f7;
        border-radius: 10px;
        padding: 10px 12px;
        background: #fbfefe;
        margin-bottom: 8px;
    }

    .mini-row-code {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        border-radius: 6px;
        padding: 2px 8px;
        margin-right: 8px;
    }

    .mini-row-code.class-code {
        background: #e7f7ef;
        color: #198754;
    }

    .mini-row-code.subject-code {
        background: #d9f3ff;
        color: #0aa2d0;
    }

    .mini-row-name {
        color: #1f2937;
        font-weight: 600;
        text-decoration: none;
    }

    .mini-row-meta {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .mini-pill {
        font-size: 11px;
        font-weight: 700;
        border-radius: 999px;
        padding: 3px 9px;
    }

    .mini-pill.year {
        background: #f1f5f9;
        color: #64748b;
    }

    .mini-pill.students {
        background: #fff3cd;
        color: #d39e00;
    }

    .mini-pill.credits {
        background: #d9f3ff;
        color: #0aa2d0;
    }

    .empty-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 3px 16px rgba(0, 0, 0, 0.08);
        border: 1px solid #edf1f5;
        padding: 34px 20px;
        text-align: center;
        color: #6c757d;
    }

    @media (max-width: 991.98px) {
        .major-card-grid {
            grid-template-columns: 1fr;
        }

        .major-column + .major-column {
            border-left: 0;
            border-top: 1px solid #edf1f5;
        }
    }
</style>
@endsection

@section('content')
<div class="department-detail-page">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="font-size: 14px;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.departments.index') }}">Departments</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $department->department_name }}</li>
        </ol>
    </nav>

    <div class="department-hero">
        <div class="department-hero-main">
            <div class="department-icon">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <span class="department-code">{{ $department->code ?? 'DEPT' }}</span>
                <h1 class="department-title">{{ $department->department_name }}</h1>
            </div>
        </div>

        <div class="department-stats">
            <div class="department-stat" style="border: 1px solid rgba(255,255,255,0.4); border-radius: 50%; width: 70px; height: 70px; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 0;">
                <strong style="font-size: 22px;">{{ $department->majors_count }}</strong>
                <span style="font-size: 10px;">Majors</span>
            </div>
            <div class="department-stat" style="border: 1px solid rgba(255,255,255,0.4); border-radius: 50%; width: 70px; height: 70px; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 0;">
                <strong style="font-size: 22px;">{{ $department->classes_count }}</strong>
                <span style="font-size: 10px;">Classes</span>
            </div>
            <div class="department-stat" style="border: 1px solid rgba(255,255,255,0.4); border-radius: 50%; width: 70px; height: 70px; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 0;">
                <strong style="font-size: 22px;">{{ $department->subjects_count }}</strong>
                <span style="font-size: 10px;">Courses</span>
            </div>
            <a href="{{ route('admin.departments.index') }}" class="department-back ms-3" style="background: rgba(255,255,255,0.1); border-radius: 20px;">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    @forelse($department->majors as $major)
        <div class="major-card">
            <div class="major-card-head">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: #0d6efd; color: white; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 20px;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <span class="major-badge" style="background: #e0edff; color: #0d6efd; border-radius: 4px;">{{ $major->code }}</span>
                        <a href="{{ route('admin.majors.show', $major->id) }}" class="major-name" style="margin-top: 2px;">{{ $major->name }}</a>
                    </div>
                </div>
                <div class="major-head-stats">
                    <span class="major-pill classes" style="background: #fff; border: 1px solid #198754;"><i class="fas fa-chalkboard me-1"></i> {{ $major->classes->count() }} Classes</span>
                    <span class="major-pill courses" style="background: #fff; border: 1px solid #0dcaf0;"><i class="fas fa-book me-1"></i> {{ $major->subjects->count() }} Courses</span>
                </div>
            </div>

            <div class="major-card-grid">
                <div class="major-column">
                    <div class="major-column-title" style="color: #198754; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-chalkboard"></i> CLASSES
                    </div>
                    @forelse($major->classes as $class)
                        <div class="mini-row">
                            <div>
                                <span class="mini-row-code class-code">{{ $class->code }}</span>
                                <a href="{{ route('admin.classes.show', $class->id) }}" class="mini-row-name">{{ $class->name }}</a>
                            </div>
                            <div class="mini-row-meta">
                                @if($class->academic_year)
                                    <span class="mini-pill year">{{ $class->academic_year }}</span>
                                @endif
                                <span class="mini-pill students">
                                    <i class="fas fa-user-graduate me-1"></i>{{ $class->students_count }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small">No classes assigned.</div>
                    @endforelse
                </div>

                <div class="major-column">
                    <div class="major-column-title" style="color: #0dcaf0; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-book"></i> COURSES
                    </div>
                    @forelse($major->subjects as $subject)
                        <div class="mini-row">
                            <div>
                                <span class="mini-row-code subject-code">{{ $subject->code }}</span>
                                <a href="{{ route('admin.subjects.show', $subject->id) }}" class="mini-row-name">{{ $subject->name }}</a>
                            </div>
                            <div class="mini-row-meta">
                                <span class="mini-pill credits">{{ $subject->credits }} Credits</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small">No courses assigned.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @empty
        <div class="empty-card">
            <i class="fas fa-folder-open fa-2x mb-3"></i>
            <div>No major structure has been assigned to this department yet.</div>
        </div>
    @endforelse
</div>
@endsection
