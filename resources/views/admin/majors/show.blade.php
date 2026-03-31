@extends('layouts.admin')
@section('title', $major->name . ' — Major Detail')
@section('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
.detail-page { font-family: 'Inter', sans-serif; }
/* Hero */
.detail-hero { background: linear-gradient(135deg, #312e81 0%, #4f46e5 60%, #7c3aed 100%); border-radius: 20px; padding: 32px 36px; display: flex; align-items: center; gap: 24px; flex-wrap: wrap; position: relative; overflow: hidden; box-shadow: 0 10px 40px rgba(79,70,229,0.28); margin-bottom: 28px; }
.detail-hero::before { content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; }
.hero-icon { width: 76px; height: 76px; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #fff; flex-shrink: 0; }
.hero-info { flex: 1; min-width: 160px; }
.hero-code { display: inline-block; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #c4b5fd; border-radius: 6px; padding: 2px 12px; font-size: 11px; font-weight: 700; letter-spacing: 2px; margin-bottom: 8px; }
.hero-title { font-size: 28px; font-weight: 800; color: #fff; margin: 0 0 6px; }
.hero-subtitle { color: rgba(255,255,255,0.65); font-size: 14px; display: flex; align-items: center; gap: 8px; }
.hero-subtitle a { color: #c4b5fd; text-decoration: none; }
.hero-subtitle a:hover { color: #fff; }
.hero-stats { display: flex; gap: 12px; flex-wrap: wrap; z-index: 1; }
.h-stat { display: flex; flex-direction: column; align-items: center; min-width: 76px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18); border-radius: 14px; padding: 12px 18px; transition: transform 0.2s; }
.h-stat:hover { transform: translateY(-2px); background: rgba(255,255,255,0.16); }
.h-stat-n { font-size: 26px; font-weight: 800; color: #fff; line-height: 1; }
.h-stat-l { font-size: 11px; color: rgba(255,255,255,0.6); margin-top: 3px; }
.btn-back-hero { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.25); color: #fff; border-radius: 10px; padding: 8px 20px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 7px; transition: all 0.2s; margin-left: auto; z-index:1; }
.btn-back-hero:hover { background: rgba(255,255,255,0.22); color: #fff; }
/* Two panels */
.panels-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
@media(max-width:900px) { .panels-grid { grid-template-columns: 1fr; } }
.panel { background: #fff; border-radius: 16px; border: 1px solid #e8edf5; box-shadow: 0 2px 16px rgba(0,0,0,0.05); overflow: hidden; }
.panel-head { padding: 14px 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #f1f5f9; }
.panel-head-icon { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
.panel-head-icon.green { background: #d1fae5; color: #059669; }
.panel-head-icon.blue { background: #dbeafe; color: #1d4ed8; }
.panel-title { font-size: 13px; font-weight: 700; color: #374151; }
.panel-count { margin-left: auto; background: #f3f4f6; border-radius: 20px; padding: 2px 10px; font-size: 12px; color: #6b7280; font-weight: 600; }
.panel-body { padding: 14px 16px; max-height: 520px; overflow-y: auto; }
/* Class item */
.class-item { border: 1px solid #e8f5e9; border-left: 4px solid #10b981; border-radius: 10px; padding: 11px 14px; margin-bottom: 8px; background: #fafffe; transition: box-shadow 0.18s; }
.class-item:hover { box-shadow: 0 4px 14px rgba(16,185,129,0.10); }
.ci-top { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 6px; }
.ci-left { display: flex; align-items: center; gap: 8px; }
.ci-code { background: #d1fae5; color: #065f46; border-radius: 5px; padding: 2px 8px; font-size: 10px; font-weight: 800; letter-spacing: 0.5px; }
.ci-name { font-size: 13px; font-weight: 600; color: #1e293b; }
.ci-badges { display: flex; gap: 6px; align-items: center; }
.year-badge { background: #f3f4f6; border: 1px solid #d1d5db; color: #6b7280; border-radius: 5px; padding: 2px 8px; font-size: 11px; }
.stu-badge { background: #fef3c7; border: 1px solid #fcd34d; color: #92400e; border-radius: 5px; padding: 2px 8px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 3px; }
.ci-courses { margin-top: 7px; display: flex; flex-wrap: wrap; gap: 4px; }
.ci-course-tag { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-radius: 4px; padding: 1px 7px; font-size: 10px; font-weight: 600; }
/* Subject item */
.subj-item { border: 1px solid #e0f2fe; border-left: 4px solid #0ea5e9; border-radius: 10px; padding: 11px 14px; margin-bottom: 8px; background: #f0f9ff; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; transition: box-shadow 0.18s; }
.subj-item:hover { box-shadow: 0 4px 14px rgba(14,165,233,0.10); }
.si-left { display: flex; align-items: center; gap: 8px; }
.si-code { background: #bae6fd; color: #0c4a6e; border-radius: 5px; padding: 2px 8px; font-size: 10px; font-weight: 800; }
.si-name { font-size: 13px; font-weight: 600; color: #1e293b; }
.si-right { display: flex; gap: 6px; }
.credits-badge { background: #e0f2fe; border: 1px solid #7dd3fc; color: #0369a1; border-radius: 5px; padding: 2px 10px; font-size: 11px; font-weight: 700; }
.quiz-badge { background: #f5f3ff; border: 1px solid #c4b5fd; color: #5b21b6; border-radius: 5px; padding: 2px 10px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 3px; }
/* Empty state */
.empty-panel { text-align: center; padding: 34px 20px; color: #94a3b8; }
.empty-panel i { font-size: 30px; margin-bottom: 10px; display: block; opacity: 0.35; }
.empty-panel p { font-size: 13px; margin: 0; }
/* Link style */
.detail-link { color: #4f46e5; text-decoration: none; font-weight: 600; }
.detail-link:hover { text-decoration: underline; color: #3730a3; }
</style>
@endsection

@section('content')
<div class="detail-page container-fluid px-4 py-3">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:13px; background:transparent; padding:0;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color:#6c8ebf;">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.majors.index', ['tab' => 'departments']) }}" style="color:#6c8ebf;">Departments</a></li>
            @if($major->department)
            <li class="breadcrumb-item"><a href="{{ route('admin.departments.show', $major->department->id) }}" style="color:#6c8ebf;">{{ $major->department->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" style="color:#495057; font-weight:600;">{{ $major->name }}</li>
        </ol>
    </nav>

    {{-- Hero --}}
    <div class="detail-hero">
        <div class="hero-icon"><i class="fas fa-graduation-cap"></i></div>
        <div class="hero-info">
            <div class="hero-code">{{ $major->code }}</div>
            <h1 class="hero-title">{{ $major->name }}</h1>
            <div class="hero-subtitle">
                <i class="fas fa-building" style="font-size:12px;"></i>
                @if($major->department)
                    <a href="{{ route('admin.departments.show', $major->department->id) }}">{{ $major->department->name }}</a>
                @else <span>No Department</span> @endif
                @if($major->description)
                    <span style="opacity:.4;">•</span>
                    <span>{{ $major->description }}</span>
                @endif
            </div>
        </div>
        <div class="hero-stats">
            <div class="h-stat">
                <span class="h-stat-n">{{ $major->classes->count() }}</span>
                <span class="h-stat-l"><i class="fas fa-chalkboard me-1"></i>Classes</span>
            </div>
            <div class="h-stat">
                <span class="h-stat-n">{{ $major->subjects->count() }}</span>
                <span class="h-stat-l"><i class="fas fa-book me-1"></i>Courses</span>
            </div>
            <div class="h-stat">
                <span class="h-stat-n">{{ $major->classes->sum('students_count') }}</span>
                <span class="h-stat-l"><i class="fas fa-user-graduate me-1"></i>Students</span>
            </div>
        </div>
        <a href="{{ route('admin.majors.index', ['tab' => 'majors']) }}" class="btn-back-hero">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    {{-- Two panels side by side --}}
    <div class="panels-grid">

        {{-- Classes Panel --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon green"><i class="fas fa-chalkboard"></i></div>
                <span class="panel-title">Classes</span>
                <span class="panel-count">{{ $major->classes->count() }}</span>
            </div>
            <div class="panel-body">
                @forelse($major->classes as $class)
                <div class="class-item">
                    <div class="ci-top">
                        <div class="ci-left">
                            <span class="ci-code">{{ $class->code }}</span>
                            <a href="{{ route('admin.classes.show', $class->id) }}" class="ci-name detail-link">{{ $class->name }}</a>
                        </div>
                        <div class="ci-badges">
                            @if($class->academic_year)
                                <span class="year-badge"><i class="far fa-calendar me-1"></i>{{ $class->academic_year }}</span>
                            @endif
                            <span class="stu-badge"><i class="fas fa-user-graduate"></i>{{ $class->students_count }}</span>
                        </div>
                    </div>
                    @if($class->subjects->isNotEmpty())
                    <div class="ci-courses">
                        @foreach($class->subjects as $cs)
                            <span class="ci-course-tag" title="{{ $cs->name }}">{{ $cs->code }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
                @empty
                <div class="empty-panel"><i class="fas fa-chalkboard"></i><p>No classes yet.</p></div>
                @endforelse
            </div>
        </div>

        {{-- Courses (Subjects) Panel --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon blue"><i class="fas fa-book"></i></div>
                <span class="panel-title">Courses</span>
                <span class="panel-count">{{ $major->subjects->count() }}</span>
            </div>
            <div class="panel-body">
                @forelse($major->subjects as $subject)
                <div class="subj-item">
                    <div class="si-left">
                        <span class="si-code">{{ $subject->code }}</span>
                        <a href="{{ route('admin.subjects.show', $subject->id) }}" class="si-name detail-link">{{ $subject->name }}</a>
                    </div>
                    <div class="si-right">
                        <span class="credits-badge"><i class="fas fa-star me-1" style="font-size:9px;"></i>{{ $subject->credits }} Credits</span>
                        <span class="quiz-badge"><i class="fas fa-question-circle"></i>{{ $subject->quizzes_count }}</span>
                    </div>
                </div>
                @empty
                <div class="empty-panel"><i class="fas fa-book-open"></i><p>No courses yet.</p></div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
