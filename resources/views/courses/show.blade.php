@extends('layouts.admin')

@section('title', $subject->subject_name . ' - Course Detail')

@section('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

.detail-page {
    font-family: 'Inter', sans-serif;
}

/* Toast Notifications */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    animation: slideInRight 0.3s ease;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Hero Section */
.detail-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 45%, #4f46e5 100%);
    border-radius: 32px;
    padding: 40px;
    display: flex;
    align-items: center;
    gap: 28px;
    flex-wrap: wrap;
    box-shadow: 0 20px 60px rgba(79,70,229,0.25);
    margin-bottom: 32px;
}

.hero-icon {
    width: 88px;
    height: 88px;
    border-radius: 28px;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(12px);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 34px;
    flex-shrink: 0;
}

.hero-info {
    flex: 1;
}

.hero-code {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: rgba(255,255,255,0.12);
    color: #cbd5e1;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    margin-bottom: 14px;
}

.hero-title {
    font-size: 36px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 10px;
}

.hero-subtitle {
    display: flex;
    align-items: center;
    gap: 10px;
    color: rgba(255,255,255,0.72);
    font-size: 14px;
}

.hero-stats {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.h-stat {
    min-width: 110px;
    padding: 16px 20px;
    border-radius: 22px;
    background: rgba(255,255,255,0.1);
    text-align: center;
    transition: all .25s ease;
}

.h-stat:hover {
    transform: translateY(-4px);
    background: rgba(255,255,255,0.14);
}

.h-stat-n {
    display: block;
    font-size: 30px;
    font-weight: 800;
    color: #fff;
}

.h-stat-l {
    display: block;
    margin-top: 6px;
    font-size: 11px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.6);
}

.btn-back-hero {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 22px;
    border-radius: 18px;
    background: rgba(255,255,255,0.1);
    color: #fff;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    transition: all .25s ease;
}

.btn-back-hero:hover {
    background: rgba(255,255,255,0.18);
    color: #fff;
    transform: translateY(-2px);
}

/* Panels */
.panels-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

@media (max-width: 992px) {
    .panels-grid {
        grid-template-columns: 1fr;
    }
}

.panel {
    background: #fff;
    border-radius: 28px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 6px 30px rgba(15,23,42,0.05);
}

.panel-head {
    padding: 20px 24px;
    display: flex;
    align-items: center;
    gap: 14px;
    border-bottom: 1px solid #f1f5f9;
    background: #fafbff;
}

.panel-head-icon {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.panel-head-icon.green {
    background: #dcfce7;
    color: #16a34a;
}

.panel-head-icon.violet {
    background: #ede9fe;
    color: #7c3aed;
}

.panel-title {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
}

.panel-count {
    margin-left: auto;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    padding: 5px 12px;
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
}

.panel-body {
    padding: 20px;
    max-height: 600px;
    overflow-y: auto;
}

.panel-body::-webkit-scrollbar {
    width: 6px;
}

.panel-body::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.panel-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

/* Quiz Cards */
.quiz-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    padding: 22px;
    margin-bottom: 18px;
    transition: all .25s ease;
    cursor: pointer;
    position: relative;
    text-decoration: none;
    display: block;
}

.quiz-card:hover {
    transform: translateY(-4px);
    border-color: #c7d2fe;
    box-shadow: 0 14px 40px rgba(79,70,229,0.12);
}

.quiz-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background: linear-gradient(to bottom, #6366f1, #8b5cf6);
    border-radius: 24px 0 0 24px;
}

.qc-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.qc-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 8px;
}

.qc-status {
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
}

.qc-status.active {
    background: #dcfce7;
    color: #166534;
}

.qc-status.completed {
    background: #e0e7ff;
    color: #3730a3;
}

.qc-meta {
    display: flex;
    gap: 16px;
    font-size: 12px;
    color: #64748b;
}

.qc-footer {
    margin-top: 18px;
    padding-top: 18px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.qc-badges {
    display: flex;
    gap: 14px;
}

.qc-badge {
    display: flex;
    align-items: center;
    gap: 8px;
}

.qc-badge-icon {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qc-badge-icon.blue {
    background: #eff6ff;
    color: #2563eb;
}

.qc-badge-icon.indigo {
    background: #eef2ff;
    color: #4f46e5;
}

.qc-badge-text {
    font-size: 11px;
    font-weight: 800;
    color: #475569;
}

.qc-action {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 11px;
    font-weight: 800;
    color: #4f46e5;
    text-transform: uppercase;
}

/* Material Cards */
.material-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px;
    border-radius: 22px;
    background: #f8fafc;
    border: 1px solid #eef2f7;
    transition: all .2s ease;
    margin-bottom: 16px;
}

.material-card:hover {
    background: #fff;
    border-color: #dbeafe;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59,130,246,0.08);
}

.material-icon {
    width: 52px;
    height: 52px;
    border-radius: 18px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    border: 1px solid #e2e8f0;
    flex-shrink: 0;
}

.material-content {
    flex: 1;
}

.material-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 4px;
}

.material-description {
    font-size: 12px;
    color: #64748b;
    margin-top: 4px;
}

.material-meta {
    font-size: 11px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 4px;
}

.material-actions {
    display: flex;
    gap: 8px;
}

.btn-circle {
    width: 38px;
    height: 38px;
    border-radius: 14px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .2s ease;
    cursor: pointer;
    text-decoration: none;
}

.btn-circle.primary {
    background: #eef2ff;
    color: #4f46e5;
}

.btn-circle.primary:hover {
    background: #4f46e5;
    color: #fff;
    transform: translateY(-2px);
}

.btn-circle.danger {
    background: #fef2f2;
    color: #dc2626;
}

.btn-circle.danger:hover {
    background: #dc2626;
    color: #fff;
    transform: translateY(-2px);
}

/* Buttons */
.btn-primary-modern {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border: none;
    color: #fff;
    border-radius: 16px;
    padding: 10px 20px;
    font-weight: 700;
    font-size: 13px;
    transition: all .2s ease;
    cursor: pointer;
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(124,58,237,0.25);
    color: #fff;
}

.btn-secondary {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 10px 20px;
    font-weight: 600;
    color: #64748b;
    transition: all .2s;
    cursor: pointer;
}

.btn-secondary:hover {
    background: #e2e8f0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 18px;
    border-radius: 24px;
    background: #f8fafc;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.empty-title {
    font-size: 16px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
}

.empty-text {
    font-size: 13px;
    color: #64748b;
}

/* Modal */
.modal-content {
    border: none;
    border-radius: 28px;
    overflow: hidden;
}

.modal-header {
    background: #fafbff;
    border-bottom: 1px solid #e2e8f0;
    padding: 20px 24px;
}

.form-label {
    font-weight: 700;
    font-size: 12px;
    color: #334155;
    margin-bottom: 6px;
}

.form-control, .form-select {
    border-radius: 14px;
    padding: 10px 16px;
    border: 1px solid #e2e8f0;
    font-size: 14px;
}

.form-control:focus, .form-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
}

/* Loading Spinner */
.spinner-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10000;
}

.spinner-overlay.active {
    display: flex;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #4f46e5;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection

@section('content')

<div class="detail-page container-fluid px-4 py-4">

    {{-- Loading Spinner --}}
    <div class="spinner-overlay" id="spinnerOverlay">
        <div class="spinner"></div>
    </div>

    {{-- Hero Section --}}
    <div class="detail-hero">
        <div class="hero-icon">
            <i class="fas fa-book-open"></i>
        </div>

        <div class="hero-info">
            <div class="hero-code">
                <i class="fas fa-hashtag"></i>
                {{ $subject->code ?? 'SUB-'.str_pad($subject->id, 3, '0', STR_PAD_LEFT) }}
            </div>
            <h1 class="hero-title">{{ $subject->subject_name }}</h1>
            <div class="hero-subtitle">
                <i class="fas fa-graduation-cap"></i>
                {{ $subject->major->name ?? 'General Education' }}
            </div>
        </div>

        <div class="hero-stats">
            <div class="h-stat">
                <span class="h-stat-n">{{ $subject->quizzes->where('status','active')->count() }}</span>
                <span class="h-stat-l">Active Quizzes</span>
            </div>
            <div class="h-stat">
                <span class="h-stat-n">{{ $subject->credits ?? 3 }}</span>
                <span class="h-stat-l">Credits</span>
            </div>
            <div class="h-stat">
                <span class="h-stat-n" id="materialCount">{{ $subject->materials->count() }}</span>
                <span class="h-stat-l">Materials</span>
            </div>
        </div>

        <a href="{{ route('courses.index') }}" class="btn-back-hero">
            <i class="fas fa-arrow-left"></i>
            Back to Courses
        </a>
    </div>

    {{-- Main Content Grid --}}
    <div class="panels-grid">
        {{-- Quizzes Panel --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon violet">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="panel-title">Available Quizzes</div>
                <div class="panel-count">{{ $subject->quizzes->where('status','active')->count() }}</div>
                
                @if($userRole != 'student')
                <a href="{{ route('admin.quizzes.create', ['subject_id' => $subject->id]) }}" class="btn-primary-modern ms-2" style="padding:6px 14px; font-size:11px;">
                    <i class="fas fa-plus me-1"></i> Add Quiz
                </a>
                @endif
            </div>

            <div class="panel-body">
                @php
                    $completedQuizIds = [];
                    if($userRole == 'student') {
                        $completedQuizIds = \App\Models\Result::where('user_id', auth()->id())
                            ->whereIn('quiz_id', $subject->quizzes->pluck('id'))
                            ->pluck('quiz_id')
                            ->toArray();
                    }
                @endphp

                @forelse($subject->quizzes->where('status','active') as $quiz)
                    @php $isCompleted = in_array($quiz->id, $completedQuizIds); @endphp

                    @if($userRole == 'student')
                        <a href="{{ !$isCompleted ? route('students.quizzes.take', $quiz->id) : '#' }}" 
                           class="quiz-card" style="{{ $isCompleted ? 'opacity:0.6;' : '' }}"
                           @if($isCompleted) onclick="return false;" @endif>
                    @else
                        <div class="quiz-card">
                    @endif

                        <div class="qc-top">
                            <div>
                                <div class="qc-title">{{ $quiz->title }}</div>
                                <div class="qc-meta">
                                    <div>
                                        <i class="far fa-calendar-alt me-1"></i>
                                        @if($quiz->opened_at)
                                            {{ \Carbon\Carbon::parse($quiz->opened_at)->format('M d') }} - {{ \Carbon\Carbon::parse($quiz->closed_at)->format('M d, Y') }}
                                        @else
                                            Always Available
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($isCompleted)
                                <div class="qc-status completed"><i class="fas fa-check-circle me-1"></i> Completed</div>
                            @else
                                <div class="qc-status active">Active</div>
                            @endif
                        </div>

                        <div class="qc-footer">
                            <div class="qc-badges">
                                <div class="qc-badge">
                                    <div class="qc-badge-icon blue"><i class="fas fa-list-ul"></i></div>
                                    <div class="qc-badge-text">{{ $quiz->questions->count() }} Questions</div>
                                </div>
                                <div class="qc-badge">
                                    <div class="qc-badge-icon indigo"><i class="far fa-clock"></i></div>
                                    <div class="qc-badge-text">{{ $quiz->time_limit ?? 30 }} Minutes</div>
                                </div>
                            </div>
                            @if($userRole == 'student')
                                @if(!$isCompleted)
                                    <div class="qc-action">Start Quiz <i class="fas fa-arrow-right"></i></div>
                                @endif
                            @else
                                <div class="qc-action"><i class="fas fa-edit"></i> Manage</div>
                            @endif
                        </div>

                    @if($userRole == 'student')
                        </a>
                    @else
                        </div>
                    @endif
                @empty
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-clipboard-list"></i></div>
                        <div class="empty-title">No Active Quizzes</div>
                        <div class="empty-text">There are currently no active quizzes for this course.</div>
                        @if($userRole != 'student')
                        <a href="{{ route('admin.quizzes.create', ['subject_id' => $subject->id]) }}" class="btn-primary-modern mt-3">Create First Quiz</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Materials Panel --}}
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon green"><i class="fas fa-book-reader"></i></div>
                <div class="panel-title">Study Materials</div>
                <div class="panel-count" id="materialPanelCount">{{ $subject->materials->count() }}</div>
                
                @if($userRole != 'student')
                <button class="btn-primary-modern ms-2" data-bs-toggle="modal" data-bs-target="#addMaterialModal" style="padding:6px 14px; font-size:11px;">
                    <i class="fas fa-plus me-1"></i> Add
                </button>
                @endif
            </div>

            <div class="panel-body" id="materialsList">
                @forelse($subject->materials as $material)
                    <div class="material-card" data-id="{{ $material->id }}">
                        <div class="material-icon">
                            @if($material->type == 'document')
                                <i class="fas fa-file-alt"></i>
                            @elseif($material->type == 'video')
                                <i class="fas fa-video"></i>
                            @elseif($material->type == 'link')
                                <i class="fas fa-link"></i>
                            @elseif($material->type == 'file')
                                <i class="fas fa-file-upload"></i>
                            @else
                                <i class="fas fa-file"></i>
                            @endif
                        </div>
                        <div class="material-content">
                            <div class="material-title">{{ $material->title }}</div>
                            @if($material->description)
                                <div class="material-description">{{ Str::limit($material->description, 100) }}</div>
                            @endif
                            <div class="material-meta">
                                <span>{{ ucfirst($material->type) }}</span>
                                <span>•</span>
                                <span><i class="far fa-calendar-alt me-1"></i>{{ $material->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="material-actions">
                            @if($material->type == 'link' || $material->type == 'video')
                                <a href="{{ $material->content }}" target="_blank" class="btn-circle primary" title="Open">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            @elseif($material->type == 'file' && $material->file_path)
                                <a href="{{ asset('storage/' . $material->file_path) }}" download class="btn-circle primary" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button onclick="previewMaterial({{ $material->id }})" class="btn-circle primary" title="Preview">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @else
                                <button onclick="viewDocument({{ $material->id }})" class="btn-circle primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @endif
                            
                            @if($userRole != 'student')
                                <button onclick="deleteMaterial({{ $material->id }})" class="btn-circle danger" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-folder-open"></i></div>
                        <div class="empty-title">No Materials Available</div>
                        <div class="empty-text">Study materials will appear here once uploaded.</div>
                        @if($userRole != 'student')
                        <button class="btn-primary-modern mt-3" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                            <i class="fas fa-upload me-2"></i> Upload First Material
                        </button>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Add Material Modal --}}
@if($userRole != 'student')
<div class="modal fade" id="addMaterialModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-plus-circle me-2" style="color:#4f46e5;"></i>
                    Add Study Material
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="materialForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Material Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g., Lecture Notes Week 1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Material Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" id="materialType" required>
                            <option value="document">📄 Document / Notes</option>
                            <option value="link">🔗 Web Link</option>
                            <option value="video">🎥 Video URL</option>
                            <option value="file">📁 File Upload</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="contentField">
                        <label class="form-label">Content / URL <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="4" placeholder="Paste link, embed code, or content description..."></textarea>
                        <small class="text-muted mt-1">For YouTube videos, paste the video URL. For web links, paste the full URL.</small>
                    </div>
                    
                    <div id="fileField" style="display:none;">
                        <label class="form-label">Upload File <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.jpg,.png,.mp4">
                        <small class="text-muted mt-1">Max file size: 10MB. Supported: PDF, DOC, PPT, XLS, ZIP, Images, MP4</small>
                        <div id="filePreview" class="mt-2"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brief description of this material"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-modern" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Save Material
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Preview Modal --}}
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Material Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <div class="text-center p-4">
                    <div class="spinner-border text-primary"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="downloadLink" class="btn-primary-modern" style="display:none;">
                    <i class="fas fa-download me-2"></i>Download
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// File preview
document.querySelector('input[name="file"]')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewDiv = document.getElementById('filePreview');
    
    if (file) {
        const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
        previewDiv.innerHTML = `
            <div class="alert alert-info mt-2">
                <i class="fas fa-check-circle me-2"></i>
                <strong>${file.name}</strong><br>
                <small>Size: ${fileSizeMB} MB</small>
            </div>
        `;
    } else {
        previewDiv.innerHTML = '';
    }
});

// Toggle between content and file fields
document.getElementById('materialType')?.addEventListener('change', function() {
    const contentField = document.getElementById('contentField');
    const fileField = document.getElementById('fileField');
    const contentTextarea = document.querySelector('textarea[name="content"]');
    const fileInput = document.querySelector('input[name="file"]');
    
    if (this.value === 'file') {
        contentField.style.display = 'none';
        fileField.style.display = 'block';
        contentTextarea.removeAttribute('required');
        fileInput.setAttribute('required', 'required');
    } else {
        contentField.style.display = 'block';
        fileField.style.display = 'none';
        contentTextarea.setAttribute('required', 'required');
        fileInput.removeAttribute('required');
    }
});

// Submit material via AJAX
document.getElementById('materialForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalHtml = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    submitBtn.disabled = true;
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("materials.store", $subject->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('addMaterialModal')).hide();
            
            // Show success toast
            showToast('success', 'Material added successfully!');
            
            // Reload page to show new material
            setTimeout(() => location.reload(), 500);
        } else {
            showToast('error', data.message || 'Failed to add material');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('error', 'An error occurred');
    } finally {
        submitBtn.innerHTML = originalHtml;
        submitBtn.disabled = false;
    }
});

// Delete material
async function deleteMaterial(id) {
    if (!confirm('Delete this material permanently?')) return;
    
    try {
        const response = await fetch(`/materials/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            showToast('success', 'Material deleted');
            // Remove from DOM
            const materialCard = document.querySelector(`.material-card[data-id="${id}"]`);
            if (materialCard) materialCard.remove();
            
            // Update counts
            const newCount = document.querySelectorAll('.material-card').length;
            document.getElementById('materialCount').innerText = newCount;
            document.getElementById('materialPanelCount').innerText = newCount;
            
            // Show empty state if no materials
            if (newCount === 0) {
                document.getElementById('materialsList').innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-folder-open"></i></div>
                        <div class="empty-title">No Materials Available</div>
                        <div class="empty-text">Study materials will appear here once uploaded.</div>
                        <button class="btn-primary-modern mt-3" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                            <i class="fas fa-upload me-2"></i> Upload First Material
                        </button>
                    </div>
                `;
            }
        } else {
            showToast('error', data.message || 'Delete failed');
        }
    } catch (error) {
        showToast('error', 'An error occurred');
    }
}

// Preview material
async function previewMaterial(id) {
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    const previewContent = document.getElementById('previewContent');
    const downloadLink = document.getElementById('downloadLink');
    
    previewContent.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary"></div></div>';
    downloadLink.style.display = 'none';
    modal.show();
    
    try {
        const response = await fetch(`/materials/${id}/preview`);
        const data = await response.json();
        
        if (response.ok && data.success) {
            if (data.type === 'image') {
                previewContent.innerHTML = `<img src="${data.url}" class="img-fluid rounded">`;
                downloadLink.href = data.download_url;
                downloadLink.style.display = 'inline-flex';
            } else if (data.type === 'pdf') {
                previewContent.innerHTML = `<iframe src="${data.url}" style="width:100%; height:500px;" frameborder="0"></iframe>`;
                downloadLink.href = data.download_url;
                downloadLink.style.display = 'inline-flex';
            } else {
                previewContent.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-file-alt" style="font-size: 64px; color: #4f46e5;"></i>
                        <p class="mt-3">Preview not available for this file type.</p>
                        <small class="text-muted">Click download to view the file.</small>
                    </div>
                `;
                downloadLink.href = data.download_url;
                downloadLink.style.display = 'inline-flex';
            }
        } else {
            previewContent.innerHTML = `
                <div class="text-center text-danger">
                    <i class="fas fa-exclamation-circle" style="font-size: 48px;"></i>
                    <p class="mt-3">Failed to load preview</p>
                </div>
            `;
        }
    } catch (error) {
        previewContent.innerHTML = `
            <div class="text-center text-danger">
                <i class="fas fa-exclamation-circle" style="font-size: 48px;"></i>
                <p class="mt-3">Error loading preview</p>
            </div>
        `;
    }
}

// View document function
function viewDocument(id) {
    previewMaterial(id);
}

// Show toast notification
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `toast-notification alert alert-${type === 'success' ? 'success' : 'danger'} shadow-lg`;
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
            <div>${message}</div>
            <button type="button" class="btn-close ms-3" onclick="this.closest('.toast-notification').remove()"></button>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('materialType');
    if (typeSelect) {
        const contentField = document.getElementById('contentField');
        const fileField = document.getElementById('fileField');
        const contentTextarea = document.querySelector('textarea[name="content"]');
        
        function toggleFields() {
            if (typeSelect.value === 'file') {
                contentField.style.display = 'none';
                fileField.style.display = 'block';
                contentTextarea.removeAttribute('required');
            } else {
                contentField.style.display = 'block';
                fileField.style.display = 'none';
                contentTextarea.setAttribute('required', 'required');
            }
        }
        
        typeSelect.addEventListener('change', toggleFields);
        toggleFields();
    }
});
</script>
@endif

@endsection