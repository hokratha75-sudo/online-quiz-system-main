@extends('layouts.admin')

@section('content')

@php
    // Data extraction with fallbacks
    $isStudent = auth()->user()->role_id == 3;
    $result = $attempt->result;
    $isPublished = $result->is_published ?? false;
    $finalScore = $result->manual_score ?? $result->score ?? 0;
    $passed = $result->passed ?? false;
    $feedback = $result->teacher_feedback ?? null;
    
    // Answers data
    $attemptAnswers = $attempt->attemptAnswers ?? collect();
    $totalQuestions = $attempt->quiz->questions->count() ?? 0;
    $correctCount = $attemptAnswers->where('is_correct', true)->count();
    $incorrectCount = max(0, $totalQuestions - $correctCount);
    
    // Time formatting
    $diff = \Carbon\Carbon::parse($attempt->started_at)->diff($attempt->completed_at);
    $timeStr = collect([
        $diff->h > 0 ? "{$diff->h}h" : null,
        $diff->i > 0 ? "{$diff->i}m" : null,
        "{$diff->s}s"
    ])->filter()->implode(' ');
    
    // Score percentage (clamped)
    $scorePercent = round(min(100, max(0, $finalScore)));
    $passPercent = $attempt->quiz->pass_percentage ?? 70;
    $violations = $attempt->violations ?? 0;
    
    // Quiz title with fallback
    $quizTitle = $attempt->quiz->title ?? 'Quiz';
@endphp

<style>
    /* Reset & Base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    /* Utility Classes */
    .container-custom {
        max-width: 1280px;
        margin-left: auto;
        margin-right: auto;
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    @media (min-width: 640px) {
        .container-custom { padding-left: 1.5rem; padding-right: 1.5rem; }
    }
    
    @media (min-width: 1024px) {
        .container-custom { padding-left: 2rem; padding-right: 2rem; }
    }
    
    /* Animations */
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 0.6; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.05); }
    }
    
    .animate-slide-up {
        animation: slideUp 0.5s ease forwards;
    }
    
    .animate-scale-in {
        animation: scaleIn 0.2s ease forwards;
    }
    
    .animate-pulse-slow {
        animation: pulse 2s ease-in-out infinite;
    }
    
    /* Progress Bar */
    .progress-bar {
        height: 0.5rem;
        background: #e5e7eb;
        border-radius: 9999px;
        overflow: hidden;
    }
    
    .progress-bar-fill {
        height: 100%;
        border-radius: 9999px;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        width: 0;
    }
    
    /* Cards */
    .card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .card-body { padding: 2rem; }
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(2, 1fr);
    }
    
    @media (min-width: 768px) {
        .stats-grid { grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
    }
    
    .stat-card {
        background: white;
        padding: 1.25rem;
        border-radius: 1rem;
        text-align: center;
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 0.875rem;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
        text-decoration: none;
    }
    
    .btn-primary {
        background: #6366f1;
        color: white;
    }
    
    .btn-primary:hover {
        background: #4f46e5;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .btn-secondary {
        background: #111827;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #1f2937;
        transform: translateY(-1px);
    }
    
    .btn-success {
        background: #10b981;
        color: white;
    }
    
    .btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
    }
    
    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 9999px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-error {
        background: #fee2e2;
        color: #991b1b;
    }
    
    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    
    .modal-overlay.hidden {
        display: none;
    }
    
    .modal-container {
        background: white;
        border-radius: 1.5rem;
        width: 100%;
        max-width: 1200px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    
    /* Table */
    .table-wrapper {
        overflow-x: auto;
        flex: 1;
    }
    
    .table {
        width: 100%;
        font-size: 0.875rem;
        border-collapse: collapse;
    }
    
    .table th {
        text-align: left;
        padding: 1rem;
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
    }
    
    .table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: top;
    }
    
    .table tr:hover {
        background: #f9fafb;
    }
    
    /* Tabs */
    .tabs {
        display: flex;
        gap: 0.5rem;
        padding: 0 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        background: white;
    }
    
    .tab {
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        cursor: pointer;
        background: none;
        border: none;
        transition: all 0.2s ease;
    }
    
    .tab:hover {
        color: #6366f1;
    }
    
    .tab.active {
        color: #6366f1;
        border-bottom: 2px solid #6366f1;
    }
    
    /* Line clamp */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Hide scrollbar on modal open */
    body.modal-open {
        overflow: hidden;
    }
</style>

<div class="container-custom py-8 md:py-12">
    
    {{-- PENDING STATE --}}
    @if(!$isPublished && $isStudent)
        <div class="min-h-[60vh] flex items-center justify-center">
            <div class="text-center max-w-md mx-auto">
                <div class="w-24 h-24 mx-auto mb-6 bg-indigo-100 rounded-2xl flex items-center justify-center animate-pulse-slow">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Review in Progress</h2>
                <p class="text-gray-600 mb-6">Your instructor is reviewing your submission. Results will appear here once published.</p>
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-8">
                    <p class="text-sm text-amber-800">📝 You'll be notified when results are ready</p>
                </div>
                <a href="{{ route('students.dashboard') }}" class="btn btn-secondary inline-flex">Back to Dashboard</a>
            </div>
        </div>
    
    {{-- RESULTS VIEW --}}
    @else
        {{-- Hero Section --}}
        <div class="relative mb-12">
            <div class="absolute inset-0 bg-gradient-to-br {{ $passed ? 'from-emerald-600 to-teal-700' : 'from-rose-600 to-red-700' }} rounded-3xl"></div>
            <div class="relative bg-white/10 backdrop-blur-sm rounded-3xl p-8 md:p-12 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 rounded-full text-white text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                    </svg>
                    {{ $passed ? 'Achievement Unlocked' : 'Learning Opportunity' }}
                </div>
                
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-3">
                    {{ $passed ? 'Outstanding Work!' : 'Keep Growing!' }}
                </h1>
                
                <p class="text-white/80 text-lg max-w-2xl mx-auto">
                    {{ $passed 
                        ? "You've successfully completed {$quizTitle} with flying colors!" 
                        : "Every attempt brings you closer to mastery. Review and try again." 
                    }}
                </p>
                
                <div class="inline-flex mt-6 px-4 py-2 bg-white/20 rounded-full text-white text-sm">
                    Completed in {{ $timeStr }}
                </div>
            </div>
        </div>
        
        {{-- Score Overview Card --}}
        <div class="card mb-6 animate-slide-up" style="animation-delay: 0s">
            <div class="card-body">
                <div class="flex flex-col lg:flex-row items-center gap-8">
                    {{-- Score Circle --}}
                    <div class="relative flex-shrink-0">
                        <svg class="w-40 h-40 transform -rotate-90">
                            <circle cx="80" cy="80" r="72" fill="none" stroke="#e5e7eb" stroke-width="8" />
                            <circle cx="80" cy="80" r="72" fill="none" 
                                    stroke="{{ $passed ? '#10b981' : '#ef4444' }}" 
                                    stroke-width="8" 
                                    stroke-linecap="round"
                                    stroke-dasharray="452.389"
                                    stroke-dashoffset="452.389"
                                    id="scoreCircle" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-4xl font-bold text-gray-900">{{ $scorePercent }}</span>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Score %</span>
                        </div>
                    </div>
                    
                    {{-- Info --}}
                    <div class="flex-1 text-center lg:text-left">
                        <div class="inline-flex mb-3">
                            <span class="badge {{ $passed ? 'badge-success' : 'badge-error' }}">
                                @if($passed)
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Passed
                                @else
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Failed
                                @endif
                            </span>
                        </div>
                        
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $quizTitle }}</h2>
                        <p class="text-gray-600 mb-4">
                            You answered <strong class="{{ $passed ? 'text-emerald-600' : 'text-rose-600' }}">{{ $correctCount }} out of {{ $totalQuestions }}</strong> questions correctly
                        </p>
                        
                        {{-- Progress Bar --}}
                        <div class="max-w-md mx-auto lg:mx-0">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Your Score</span>
                                <span>Required: {{ $passPercent }}%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-bar-fill {{ $passed ? 'bg-emerald-500' : 'bg-rose-500' }}" 
                                     id="progressFill"
                                     data-width="{{ $scorePercent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Stats Grid --}}
        <div class="stats-grid mb-6">
            <div class="stat-card animate-slide-up" style="animation-delay: 0.05s">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="text-3xl font-bold text-emerald-600">{{ $correctCount }}</div>
                <div class="text-xs font-semibold text-gray-500 uppercase mt-1">Correct Answers</div>
            </div>
            
            <div class="stat-card animate-slide-up" style="animation-delay: 0.1s">
                <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="text-3xl font-bold text-rose-600">{{ $incorrectCount }}</div>
                <div class="text-xs font-semibold text-gray-500 uppercase mt-1">Incorrect Answers</div>
            </div>
            
            <div class="stat-card animate-slide-up" style="animation-delay: 0.15s">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ $timeStr }}</div>
                <div class="text-xs font-semibold text-gray-500 uppercase mt-1">Time Taken</div>
            </div>
            
            <div class="stat-card animate-slide-up" style="animation-delay: 0.2s">
                <div class="w-12 h-12 {{ $violations > 0 ? 'bg-amber-100' : 'bg-emerald-100' }} rounded-xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 {{ $violations > 0 ? 'text-amber-600' : 'text-emerald-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="text-3xl font-bold {{ $violations > 0 ? 'text-amber-600' : 'text-emerald-600' }}">{{ $violations }}</div>
                <div class="text-xs font-semibold text-gray-500 uppercase mt-1">Focus Alerts</div>
            </div>
        </div>
        
        {{-- Feedback Card --}}
        @if($feedback)
            <div class="card mb-6 animate-slide-up" style="animation-delay: 0.25s">
                <div class="card-body bg-gradient-to-r from-indigo-50 to-purple-50">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide mb-1">Instructor Feedback</p>
                            <p class="text-gray-800 leading-relaxed">“{{ e($feedback) }}”</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        {{-- Action Buttons --}}
        <div class="card animate-slide-up" style="animation-delay: 0.3s">
            <div class="card-body">
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ $isStudent ? route('students.dashboard') : route('quizzes.index') }}" 
                       class="btn btn-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    
                    <button type="button" onclick="openReviewModal()" class="btn btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Review Answers
                    </button>
                    
                    <a href="{{ $isStudent ? route('students.quizzes.take', $attempt->quiz_id) : route('quizzes.take', $attempt->quiz_id) }}" 
                       class="btn btn-success">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Try Again
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- ANSWER REVIEW MODAL --}}
<div id="reviewModal" class="modal-overlay hidden">
    <div class="modal-container">
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Answer Review</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $totalQuestions }} questions · {{ $correctCount }} correct · {{ $incorrectCount }} incorrect</p>
            </div>
            <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        {{-- Tabs --}}
        <div class="tabs">
            <button type="button" onclick="filterQuestions('all')" class="tab active" data-filter="all">All ({{ $totalQuestions }})</button>
            <button type="button" onclick="filterQuestions('correct')" class="tab" data-filter="correct">Correct ({{ $correctCount }})</button>
            <button type="button" onclick="filterQuestions('incorrect')" class="tab" data-filter="incorrect">Incorrect ({{ $incorrectCount }})</button>
        </div>
        
        {{-- Table --}}
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>Question</th>
                        <th style="min-width: 200px">Your Answer</th>
                        <th style="min-width: 200px">Correct Answer</th>
                        <th style="width: 100px">Result</th>
                        <th style="width: 80px">Points</th>
                    </tr>
                </thead>
                <tbody id="answersTableBody">
                    @foreach($attempt->quiz->questions as $index => $question)
                        @php
                            $studentAnswer = $attemptAnswers->where('question_id', $question->id)->first();
                            $isCorrect = $studentAnswer?->is_correct ?? false;
                            
                            if ($question->type === 'multiple_choice') {
                                $studentText = optional($question->answers->where('id', $studentAnswer?->answer_id)->first())->answer_text ?? 'No answer provided';
                                $correctText = optional($question->answers->where('is_correct', 1)->first())->answer_text ?? 'N/A';
                            } else {
                                $studentText = $studentAnswer?->short_text ?: 'No answer provided';
                                $correctText = $question->correct_answer ?? 'Pending review';
                            }
                        @endphp
                        <tr data-correct="{{ $isCorrect ? 'true' : 'false' }}">
                            <td class="text-gray-500 font-semibold">{{ $index + 1 }}</td>
                            <td>
                                <div class="text-gray-900 font-medium line-clamp-2">{!! nl2br(e($question->content)) !!}</div>
                                <span class="inline-block mt-1 text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                    {{ str_replace('_', ' ', $question->type) }}
                                </span>
                            </td>
                            <td>
                                <div class="p-2 rounded-lg {{ $isCorrect ? 'bg-emerald-50' : 'bg-rose-50' }}">
                                    <p class="text-sm {{ $isCorrect ? 'text-emerald-800' : 'text-rose-800' }}">{{ nl2br(e($studentText)) }}</p>
                                </div>
                            </td>
                            <td>
                                <div class="p-2 rounded-lg bg-gray-50">
                                    <p class="text-sm text-gray-700">{{ nl2br(e($correctText)) }}</p>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($isCorrect)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Correct
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-rose-100 text-rose-700 text-xs font-semibold rounded">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Wrong
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="font-bold {{ $isCorrect ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $studentAnswer?->points_awarded ?? 0 }}
                                </span>
                                <span class="text-gray-400 text-sm">/{{ $question->points ?? 10 }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Footer --}}
        <div class="p-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
            <span class="text-sm text-gray-600" id="filterLabel">Showing all questions</span>
            <div class="flex gap-4 text-sm">
                <span class="flex items-center gap-1 text-emerald-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    {{ $correctCount }} correct
                </span>
                <span class="flex items-center gap-1 text-rose-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    {{ $incorrectCount }} wrong
                </span>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        'use strict';
        
        // Score circle animation
        const circle = document.getElementById('scoreCircle');
        if (circle) {
            const radius = 72;
            const circumference = 2 * Math.PI * radius;
            const score = {{ $scorePercent }};
            const offset = circumference - (score / 100) * circumference;
            
            setTimeout(() => {
                circle.style.transition = 'stroke-dashoffset 1.5s cubic-bezier(0.4, 0, 0.2, 1)';
                circle.style.strokeDashoffset = offset;
            }, 100);
        }
        
        // Progress bar animation
        const progressFill = document.getElementById('progressFill');
        if (progressFill && progressFill.dataset.width) {
            setTimeout(() => { 
                progressFill.style.width = progressFill.dataset.width; 
            }, 200);
        }
        
        // Modal elements
        const modal = document.getElementById('reviewModal');
        
        // Open modal
        window.openReviewModal = function() {
            if (!modal) return;
            modal.classList.remove('hidden');
            document.body.classList.add('modal-open');
        };
        
        // Close modal
        window.closeReviewModal = function() {
            if (!modal) return;
            modal.classList.add('hidden');
            document.body.classList.remove('modal-open');
        };
        
        // Close on overlay click
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeReviewModal();
                }
            });
        }
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                closeReviewModal();
            }
        });
        
        // Filter questions
        window.filterQuestions = function(type) {
            const rows = document.querySelectorAll('#answersTableBody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const isCorrect = row.getAttribute('data-correct') === 'true';
                let show = false;
                
                if (type === 'all') show = true;
                else if (type === 'correct') show = isCorrect;
                else if (type === 'incorrect') show = !isCorrect;
                
                row.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });
            
            // Update tabs
            document.querySelectorAll('.tab').forEach(tab => {
                const filterType = tab.getAttribute('data-filter');
                if (filterType === type) {
                    tab.classList.add('active');
                } else {
                    tab.classList.remove('active');
                }
            });
            
            // Update label
            const label = document.getElementById('filterLabel');
            if (label) {
                if (type === 'all') {
                    label.textContent = `Showing all ${rows.length} questions`;
                } else {
                    label.textContent = `Showing ${visibleCount} ${type} questions`;
                }
            }
        };
    })();
</script>

@endsection