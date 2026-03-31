@extends('layouts.admin')
@section('title', $subject->name . ' — Course Detail')
@section('title', $subject->subject_name . ' - Course Detail')
@section('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
.detail-page { font-family: 'Inter', sans-serif; }
/* Hero */
.detail-hero { background: linear-gradient(135deg, #0c4a6e 0%, #0284c7 60%, #38bdf8 100%); border-radius: 20px; padding: 32px 36px; display: flex; align-items: center; gap: 24px; flex-wrap: wrap; position: relative; overflow: hidden; box-shadow: 0 10px 40px rgba(2,132,199,0.28); margin-bottom: 28px; }
.detail-hero::before { content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; }
.hero-icon { width: 76px; height: 76px; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #fff; flex-shrink: 0; }
.hero-info { flex: 1; min-width: 160px; }
.hero-code { display: inline-block; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #bae6fd; border-radius: 6px; padding: 2px 12px; font-size: 11px; font-weight: 700; letter-spacing: 2px; margin-bottom: 8px; }
.hero-title { font-size: 28px; font-weight: 800; color: #fff; margin: 0 0 6px; }
.hero-subtitle { color: rgba(255,255,255,0.65); font-size: 14px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.hero-subtitle a { color: #bae6fd; text-decoration: none; }
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
.panel-head-icon.green { background: #d1fae5; color: #059669; }
.panel-head-icon.violet { background: #f5f3ff; color: #7c3aed; }
.panel-title { font-size: 13px; font-weight: 700; color: #374151; }
.panel-count { margin-left: auto; background: #f3f4f6; border-radius: 20px; padding: 2px 10px; font-size: 12px; color: #6b7280; font-weight: 600; }
.panel-body { padding: 14px 16px; max-height: 520px; overflow-y: auto; }
/* Class row */
.class-row { border: 1px solid #e8f5e9; border-left: 4px solid #10b981; border-radius: 10px; padding: 11px 14px; margin-bottom: 8px; background: #fafffe; transition: box-shadow 0.18s; }
.class-row:hover { box-shadow: 0 4px 14px rgba(16,185,129,0.10); }
.cr-top { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 6px; }
.cr-left { display: flex; align-items: center; gap: 8px; }
.cr-code { background: #d1fae5; color: #065f46; border-radius: 5px; padding: 2px 8px; font-size: 10px; font-weight: 800; }
.cr-name-link { font-size: 13px; font-weight: 600; color: #059669; text-decoration: none; }
.cr-name-link:hover { color: #064e3b; text-decoration: underline; }
.cr-right { display: flex; gap: 6px; }
.year-badge { background: #f3f4f6; border: 1px solid #d1d5db; color: #6b7280; border-radius: 5px; padding: 2px 8px; font-size: 11px; }
.stu-badge { background: #fef3c7; border: 1px solid #fcd34d; color: #92400e; border-radius: 5px; padding: 2px 8px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 3px; }
/* Quiz row */
.quiz-card { border: 1px solid #ede9fe; border-left: 4px solid #7c3aed; border-radius: 10px; padding: 12px 14px; margin-bottom: 8px; background: #faf5ff; transition: box-shadow 0.18s; }
.quiz-card:hover { box-shadow: 0 4px 14px rgba(124,58,237,0.10); }
.qc-top { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 6px; }
.qc-title { font-size: 13px; font-weight: 600; color: #1e293b; }
.qc-status { border-radius: 5px; padding: 2px 10px; font-size: 10px; font-weight: 700; }
.qc-status.active { background: #d1fae5; color: #065f46; }
.qc-status.draft { background: #f3f4f6; color: #6b7280; }
.qc-status.inactive { background: #fee2e2; color: #991b1b; }
.qc-meta { margin-top: 5px; font-size: 11px; color: #9ca3af; display: flex; align-items: center; gap: 10px; }
/* Credits info-bar */
.credit-bar { background: linear-gradient(90deg, #e0f2fe, #f0f9ff); border: 1px solid #bae6fd; border-radius: 12px; padding: 14px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 16px; }
.credit-num { font-size: 36px; font-weight: 800; color: #0284c7; line-height: 1; }
.credit-unit { font-size: 14px; color: #0369a1; font-weight: 600; }
.credit-desc { font-size: 12px; color: #64748b; }
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
            <li class="breadcrumb-item"><a href="{{ route('admin.departments.index') }}" style="color:#6c8ebf;">Departments</a></li>
            @if($subject->department)
            <li class="breadcrumb-item"><a href="{{ route('admin.departments.show', $subject->department->id) }}" style="color:#6c8ebf;">{{ $subject->department->department_name }}</a></li>
            @endif
            <li class="breadcrumb-item active" style="color:#495057; font-weight:600;">{{ $subject->subject_name }}</li>
        </ol>
    </nav>

    {{-- Hero --}}
    <div class="detail-hero">
        <div class="hero-icon"><i class="fas fa-book"></i></div>
        <div class="hero-info">
            <div class="hero-code">SUBJECT #{{ $subject->id }}</div>
            <h1 class="hero-title">{{ $subject->subject_name }}</h1>
            <div class="hero-subtitle">
                @if($subject->department)
                    <i class="fas fa-building" style="font-size:12px;"></i>
                    <a href="{{ route('admin.departments.show', $subject->department->id) }}">{{ $subject->department->department_name }}</a>
                @endif
            </div>
        </div>
        <div class="hero-stats">
            <div class="h-stat">
                <span class="h-stat-n">{{ $subject->quizzes->count() }}</span>
                <span class="h-stat-l"><i class="fas fa-question-circle me-1"></i>Quizzes</span>
            </div>
        </div>
        <a href="{{ route('admin.subjects.index') }}" class="btn-back-hero">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    {{-- Two panels --}}
    <div class="panels-grid">

        {{-- Info Panel --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon green"><i class="fas fa-info-circle"></i></div>
                <span class="panel-title">Subject Information</span>
            </div>
            <div class="panel-body">
                <div class="class-row">
                    <div class="cr-top">
                        <div class="cr-left">
                            <span class="cr-name-link">Created By</span>
                        </div>
                        <div class="cr-right">
                            <span class="year-badge">{{ $subject->creator->username ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quizzes Panel --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon violet"><i class="fas fa-question-circle"></i></div>
                <span class="panel-title">Quizzes for This Course</span>
                <span class="panel-count">{{ $subject->quizzes->count() }}</span>
            </div>
            <div class="panel-body">
                @forelse($subject->quizzes as $quiz)
                <div class="quiz-card">
                    <div class="qc-top">
                        <span class="qc-title">{{ $quiz->title }}</span>
                        <span class="qc-status {{ $quiz->status }}">{{ ucfirst($quiz->status) }}</span>
                    </div>
                    <div class="qc-meta">
                        @if($quiz->creator)
                            <span><i class="fas fa-user me-1"></i>{{ $quiz->creator->username }}</span>
                        @endif
                        <span><i class="far fa-clock me-1"></i>{{ $quiz->created_at?->format('d M Y') }}</span>
                    </div>
                </div>
                @empty
                <div class="empty-panel"><i class="fas fa-question-circle"></i><p>No quizzes created yet.</p></div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
