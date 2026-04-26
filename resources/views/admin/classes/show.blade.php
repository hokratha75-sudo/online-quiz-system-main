@extends('layouts.admin')
@section('title', $class->name . ' — Class Detail')
@section('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');
.detail-page { font-family: 'Open Sans', Helvetica, Arial, sans-serif; }
/* Hero */
.detail-hero { background: linear-gradient(135deg, #064e3b 0%, #059669 60%, #10b981 100%); border-radius: 20px; padding: 32px 36px; display: flex; align-items: center; gap: 24px; flex-wrap: wrap; position: relative; overflow: hidden; box-shadow: 0 10px 40px rgba(16,185,129,0.25); margin-bottom: 28px; }
.detail-hero::before { content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; }
.hero-icon { width: 76px; height: 76px; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #fff; flex-shrink: 0; }
.hero-info { flex: 1; min-width: 160px; }
.hero-code { display: inline-block; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #a7f3d0; border-radius: 6px; padding: 2px 12px; font-size: 11px; font-weight: 700; letter-spacing: 2px; margin-bottom: 8px; }
.hero-title { font-size: 28px; font-weight: 800; color: #fff; margin: 0 0 6px; }
.hero-subtitle { color: rgba(255,255,255,0.65); font-size: 14px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.hero-subtitle a { color: #a7f3d0; text-decoration: none; }
.hero-subtitle a:hover { color: #fff; }
.hero-stats { display: flex; gap: 12px; flex-wrap: wrap; z-index: 1; }
.h-stat { display: flex; flex-direction: column; align-items: center; min-width: 76px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18); border-radius: 14px; padding: 12px 18px; transition: transform 0.2s; }
.h-stat:hover { transform: translateY(-2px); background: rgba(255,255,255,0.16); }
.h-stat-n { font-size: 26px; font-weight: 800; color: #fff; line-height: 1; }
.h-stat-l { font-size: 11px; color: rgba(255,255,255,0.6); margin-top: 3px; }
.btn-back-hero { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.25); color: #fff; border-radius: 10px; padding: 8px 20px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 7px; transition: all 0.2s; margin-left: auto; z-index:1; }
.btn-back-hero:hover { background: rgba(255,255,255,0.22); color: #fff; }
/* Panels */
.panels-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
@media(max-width:900px) { .panels-grid { grid-template-columns: 1fr; } }
.panel { background: #fff; border-radius: 16px; border: 1px solid #e8edf5; box-shadow: 0 2px 16px rgba(0,0,0,0.05); overflow: hidden; }
.panel-head { padding: 14px 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid #f1f5f9; }
.panel-head-icon { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
.panel-head-icon.yellow { background: #fef9c3; color: #b45309; }
.panel-head-icon.blue { background: #dbeafe; color: #1d4ed8; }
.panel-title { font-size: 13px; font-weight: 700; color: #374151; }
.panel-count { margin-left: auto; background: #f3f4f6; border-radius: 20px; padding: 2px 10px; font-size: 12px; color: #6b7280; font-weight: 600; }
.panel-body { padding: 14px 16px; max-height: 520px; overflow-y: auto; }
/* Student row */
.student-row { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 9px; border: 1px solid #fef3c7; background: #fffbeb; margin-bottom: 6px; transition: box-shadow 0.18s; }
.student-row:hover { box-shadow: 0 3px 10px rgba(245,158,11,0.12); }
.stu-avatar { width: 34px; height: 34px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 14px; font-weight: 700; flex-shrink: 0; }
.stu-name { font-size: 13px; font-weight: 600; color: #1e293b; }
.stu-email { font-size: 11px; color: #6b7280; }
.stu-role { margin-left: auto; background: #fef3c7; border: 1px solid #fcd34d; color: #92400e; border-radius: 5px; padding: 2px 8px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
/* Subject/course row */
.subj-item { border: 1px solid #e0f2fe; border-left: 4px solid #0ea5e9; border-radius: 10px; padding: 11px 14px; margin-bottom: 8px; background: #f0f9ff; transition: box-shadow 0.18s; }
.subj-item:hover { box-shadow: 0 4px 14px rgba(14,165,233,0.10); }
.si-top { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }
.si-left { display: flex; align-items: center; gap: 8px; }
.si-code { background: #bae6fd; color: #0c4a6e; border-radius: 5px; padding: 2px 8px; font-size: 10px; font-weight: 800; }
.si-name-link { font-size: 13px; font-weight: 600; color: #0369a1; text-decoration: none; }
.si-name-link:hover { color: #0c4a6e; text-decoration: underline; }
.si-right { display: flex; gap: 6px; flex-wrap: wrap; }
.credits-badge { background: #e0f2fe; border: 1px solid #7dd3fc; color: #0369a1; border-radius: 5px; padding: 2px 10px; font-size: 11px; font-weight: 700; }
.quiz-badge { background: #f5f3ff; border: 1px solid #c4b5fd; color: #5b21b6; border-radius: 5px; padding: 2px 10px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 3px; }
/* Quiz row under subject */
.quiz-row { margin-top: 8px; padding-top: 8px; border-top: 1px dashed #bae6fd; display: flex; flex-direction: column; gap: 4px; }
.quiz-item-tag { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #374151; }
.quiz-status { border-radius: 4px; padding: 1px 8px; font-size: 10px; font-weight: 700; }
.quiz-status.active { background: #d1fae5; color: #065f46; }
.quiz-status.draft { background: #f3f4f6; color: #6b7280; }
/* Empty */
.empty-panel { text-align: center; padding: 34px 20px; color: #94a3b8; }
.empty-panel i { font-size: 30px; margin-bottom: 10px; display: block; opacity: 0.35; }
.empty-panel p { font-size: 13px; margin: 0; }
</style>
@endsection

@section('content')
<div class="detail-page container-fluid px-4 py-3">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:13px; background:transparent; padding:0;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color:#6c8ebf;">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.majors.index', ['tab' => 'departments']) }}" style="color:#6c8ebf;">Departments</a></li>
            @if($class->major?->department)
            <li class="breadcrumb-item"><a href="{{ route('admin.departments.show', $class->major->department->id) }}" style="color:#6c8ebf;">{{ $class->major->department->name }}</a></li>
            @endif
            @if($class->major)
            <li class="breadcrumb-item"><a href="{{ route('admin.majors.show', $class->major->id) }}" style="color:#6c8ebf;">{{ $class->major->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" style="color:#495057; font-weight:600;">{{ $class->name }}</li>
        </ol>
    </nav>

    {{-- Hero --}}
    <div class="detail-hero">
        <div class="hero-icon"><i class="fas fa-chalkboard"></i></div>
        <div class="hero-info">
            <div class="hero-code">{{ $class->code }}</div>
            <h1 class="hero-title">{{ $class->name }}</h1>
            <div class="hero-subtitle">
                @if($class->major)
                    <i class="fas fa-graduation-cap" style="font-size:12px;"></i>
                    <a href="{{ route('admin.majors.show', $class->major->id) }}">{{ $class->major->name }}</a>
                @endif
                @if($class->academic_year)
                    <span style="opacity:.4;">•</span>
                    <i class="far fa-calendar" style="font-size:12px;"></i> Academic Year {{ $class->academic_year }}
                @endif
            </div>
        </div>
        <div class="hero-stats">
            <div class="h-stat">
                <span class="h-stat-n">{{ $class->students->count() }}</span>
                <span class="h-stat-l"><i class="fas fa-user-graduate me-1"></i>Students</span>
            </div>
            <div class="h-stat">
                <span class="h-stat-n">{{ $class->subjects->count() }}</span>
                <span class="h-stat-l"><i class="fas fa-book me-1"></i>Courses</span>
            </div>
            <div class="h-stat">
                <span class="h-stat-n">{{ $class->subjects->sum('quizzes_count') }}</span>
                <span class="h-stat-l"><i class="fas fa-question-circle me-1"></i>Quizzes</span>
            </div>
        </div>
        <a href="{{ route('admin.majors.index', ['tab' => 'classes']) }}" class="btn-back-hero">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    {{-- Two panels --}}
    <div class="panels-grid">

        {{-- Students Panel --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon yellow"><i class="fas fa-user-graduate"></i></div>
                <span class="panel-title">Enrolled Students</span>
                <span class="panel-count">{{ $class->students->count() }}</span>
            </div>
            <div class="panel-body">
                @forelse($class->students as $student)
                <div class="student-row">
                    <div class="stu-avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                    <div>
                        <div class="stu-name">{{ $student->name }}</div>
                        <div class="stu-email">{{ $student->email }}</div>
                    </div>
                    <span class="stu-role">{{ $student->pivot->role ?? 'student' }}</span>
                </div>
                @empty
                <div class="empty-panel"><i class="fas fa-user-slash"></i><p>No students enrolled yet.</p></div>
                @endforelse
            </div>
        </div>

        {{-- Courses Panel --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon blue"><i class="fas fa-book"></i></div>
                <span class="panel-title">Assigned Courses</span>
                <span class="panel-count">{{ $class->subjects->count() }}</span>
            </div>
            <div class="panel-body">
                @forelse($class->subjects as $subject)
                <div class="subj-item">
                    <div class="si-top">
                        <div class="si-left">
                            <span class="si-code">{{ $subject->code }}</span>
                            <a href="{{ route('admin.subjects.show', $subject->id) }}" class="si-name-link">{{ $subject->name }}</a>
                        </div>
                        <div class="si-right">
                            <span class="credits-badge"><i class="fas fa-star me-1" style="font-size:9px;"></i>{{ $subject->credits }} Cr.</span>
                            <span class="quiz-badge"><i class="fas fa-question-circle"></i>{{ $subject->quizzes_count }} Quizzes</span>
                        </div>
                    </div>
                    @if($subject->quizzes->isNotEmpty())
                    <div class="quiz-row">
                        @foreach($subject->quizzes->take(4) as $quiz)
                        <div class="quiz-item-tag">
                            <i class="far fa-circle" style="font-size:8px; color:#94a3b8;"></i>
                            <span>{{ $quiz->title }}</span>
                            <span class="quiz-status {{ $quiz->status === 'active' ? 'active' : 'draft' }}">{{ ucfirst($quiz->status) }}</span>
                        </div>
                        @endforeach
                        @if($subject->quizzes->count() > 4)
                        <div class="quiz-item-tag" style="color:#94a3b8; font-size:11px;">+{{ $subject->quizzes->count() - 4 }} more quizzes</div>
                        @endif
                    </div>
                    @endif
                </div>
                @empty
                <div class="empty-panel"><i class="fas fa-book-open"></i><p>No courses assigned yet.</p></div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
