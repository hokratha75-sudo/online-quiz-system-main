@extends('layouts.admin')
@section('title', $subject->subject_name . ' - Course Detail')

@section('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');
.detail-page { font-family: 'Open Sans', Helvetica, Arial, sans-serif; }
/* Hero */
.detail-hero { background: linear-gradient(135deg, #0c4a6e 0%, #0284c7 60%, #38bdf8 100%); border-radius: 20px; padding: 32px 36px; display: flex; align-items: center; gap: 24px; flex-wrap: wrap; position: relative; overflow: hidden; box-shadow: 0 10px 40px rgba(2,132,199,0.28); margin-bottom: 28px; }
.detail-hero::before { content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; }
.hero-icon { width: 76px; height: 76px; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #fff; flex-shrink: 0; }
.hero-info { flex: 1; min-width: 160px; }
.hero-code { display: inline-block; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #bae6fd; border-radius: 6px; padding: 2px 12px; font-size: 11px; font-weight: 700; letter-spacing: 2px; margin-bottom: 8px; }
.hero-title { font-size: 28px; font-weight: 800; color: #fff; margin: 0 0 6px; }
.hero-subtitle { color: rgba(255,255,255,0.65); font-size: 14px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
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
/* Quiz card */
.quiz-card { border: 1px solid #ede9fe; border-left: 4px solid #7c3aed; border-radius: 10px; padding: 15px; margin-bottom: 12px; background: #faf5ff; transition: box-shadow 0.18s; display: block; text-decoration: none; }
.quiz-card:hover { box-shadow: 0 4px 14px rgba(124,58,237,0.12); transform: translateY(-2px); }
.qc-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
.qc-title { font-size: 15px; font-weight: 700; color: #1e293b; }
.qc-status { border-radius: 50px; padding: 2px 12px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
.qc-status.active { background: #d1fae5; color: #065f46; }
.qc-meta { font-size: 12px; color: #64748b; display: flex; align-items: center; gap: 15px; }
/* Class info */
.info-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
.info-item:last-child { border-bottom: none; }
.info-label { font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 8px; }
.info-value { font-size: 13px; font-weight: 600; color: #1e293b; }
</style>
@endsection

@section('content')
<div class="detail-page container-fluid px-4 py-3">
    <div class="detail-hero">
        <div class="hero-icon"><i class="fas fa-book"></i></div>
        <div class="hero-info">
            <div class="hero-code">{{ $subject->code ?? 'SUB-'.str_pad($subject->id, 3, '0', STR_PAD_LEFT) }}</div>
            <h1 class="hero-title">{{ $subject->subject_name }}</h1>
            <div class="hero-subtitle">
                <i class="fas fa-graduation-cap"></i> {{ $subject->major->name ?? 'General' }}
            </div>
        </div>
        <div class="hero-stats">
            <div class="h-stat">
                <span class="h-stat-n">{{ $subject->quizzes->where('status', 'active')->count() }}</span>
                <span class="h-stat-l">Active Quizzes</span>
            </div>
            <div class="h-stat">
                <span class="h-stat-n">{{ $subject->credits }}</span>
                <span class="h-stat-l">Credits</span>
            </div>
        </div>
        <a href="{{ route('courses.index') }}" class="btn-back-hero">
            <i class="fas fa-arrow-left"></i> Back to Courses
        </a>
    </div>

    <div class="panels-grid">
        {{-- Quizzes Panel --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon violet"><i class="fas fa-tasks"></i></div>
                <span class="panel-title">Available Quizzes</span>
                <span class="panel-count">{{ $subject->quizzes->where('status', 'active')->count() }}</span>
            </div>
            <div class="panel-body">
                @forelse($subject->quizzes->where('status', 'active') as $quiz)
                <a href="{{ $userRole == 'student' ? route('students.quizzes.take', $quiz->id) : '#' }}" class="group bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 no-underline block mb-4">
                    <div class="flex flex-col">
                        <div class="mb-4">
                            <h4 class="text-lg font-extrabold text-slate-900 leading-tight mb-1 group-hover:text-indigo-600 transition-colors tracking-tight">{{ $quiz->title }}</h4>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500">
                                    <i class="far fa-calendar-alt text-indigo-500"></i>
                                    <span>{{ \Carbon\Carbon::parse($quiz->opened_at)->format('M d') }} - {{ \Carbon\Carbon::parse($quiz->closed_at)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2 text-slate-400">
                                    <div class="w-6 h-6 rounded-md bg-blue-50 flex items-center justify-center text-blue-500">
                                        <i class="fas fa-list-ul text-[9px]"></i>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-tight">{{ $quiz->questions->count() }} Qs</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-400">
                                    <div class="w-6 h-6 rounded-md bg-indigo-50 flex items-center justify-center text-indigo-500">
                                        <i class="far fa-clock text-[9px]"></i>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-tight">{{ $quiz->time_limit ?? 30 }}m</span>
                                </div>
                            </div>
                            @if($userRole == 'student')
                            <div class="text-[9px] font-black text-indigo-600 uppercase tracking-[0.2em] flex items-center gap-2 group-hover:gap-3 transition-all">
                                Take <i class="fas fa-arrow-right text-[8px]"></i>
                            </div>
                            @endif
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-info-circle mb-2 fa-2x opacity-25"></i>
                    <p class="small">No active quizzes found for this course.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Study Materials Panel --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon green"><i class="fas fa-book-reader"></i></div>
                <span class="panel-title">Study Materials</span>
                <span class="panel-count">{{ $subject->materials->count() }}</span>
                @if($userRole != 'student')
                    <button class="btn btn-sm btn-primary rounded-pill px-3 ms-auto" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                        <i class="fas fa-plus me-1"></i> Add
                    </button>
                @endif
            </div>
            <div class="panel-body">
                @forelse($subject->materials as $material)
                <div class="d-flex align-items-center mb-3 p-3 rounded-4 bg-light bg-opacity-50 border border-white">
                    <div class="icon-box-sm bg-white text-primary shadow-sm me-3">
                        <i class="fas {{ $material->icon }}"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="mb-0 fw-bold text-dark text-truncate">{{ $material->title }}</h6>
                        <span class="text-muted small">{{ ucfirst($material->type) }} • {{ $material->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="ms-2">
                        @if($material->type == 'link' || $material->type == 'video')
                            <a href="{{ $material->content }}" target="_blank" class="btn btn-sm btn-soft-primary rounded-circle"><i class="fas fa-external-link-alt"></i></a>
                        @else
                            <a href="#" class="btn btn-sm btn-soft-primary rounded-circle"><i class="fas fa-download"></i></a>
                        @endif
                        
                        @if($userRole != 'student')
                        <form action="{{ route('materials.destroy', $material->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-soft-danger rounded-circle ms-1" onclick="return confirm('Are you sure you want to remove this study material? Students will no longer be able to access it.')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-folder-open mb-2 fa-2x opacity-25"></i>
                    <p class="small">No materials uploaded yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if($userRole != 'student')
<!-- Add Material Modal -->
<div class="modal fade" id="addMaterialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Add Study Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('materials.store', $subject->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Title</label>
                        <input type="text" name="title" class="form-control rounded-3" placeholder="e.g. Weekly Lecture Note" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Material Type</label>
                        <select name="type" class="form-select rounded-3" required>
                            <option value="document">Document</option>
                            <option value="link">Web Link</option>
                            <option value="video">Video URL</option>
                            <option value="file">File (External)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Content (URL or Description)</label>
                        <textarea name="content" class="form-control rounded-3" rows="3" placeholder="Paste link or content here..." required></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Brief Description</label>
                        <input type="text" name="description" class="form-control rounded-3" placeholder="Optional brief info">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Upload Material</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
