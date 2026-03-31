@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 animate-fade-in">
            
            @if(!$attempt->result->is_published && $userRole === 'student')
                <!-- Pending Review Splash -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4 text-center">
                    <div class="card-header border-0 py-5 bg-warning text-white">
                        <div class="display-1 mb-3"><i class="fas fa-clock"></i></div>
                        <h2 class="fw-bold fs-1 mb-0">Pending Review</h2>
                        <p class="lead opacity-75 mt-2">Your quiz contains short-answer responses and is awaiting manual grading.</p>
                    </div>
                    <div class="card-body p-4 text-muted">
                        <p class="mb-0">You will be notified once your teacher publishes the final results.</p>
                        <div class="mt-4">
                            <a href="{{ route('students.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4"><i class="fas fa-arrow-left me-2"></i> Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Result Splash Card -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4 text-center">
                    @php 
                        $finalScore = $attempt->result->manual_score ?? $attempt->result->score; 
                    @endphp
                    <div class="card-header border-0 py-5 {{ $attempt->result->passed ? 'bg-success' : 'bg-danger' }} text-white">
                        <div class="display-1 mb-3">
                            <i class="fas {{ $attempt->result->passed ? 'fa-trophy' : 'fa-times-circle' }}"></i>
                        </div>
                        <h2 class="fw-bold fs-1 mb-0">{{ $attempt->result->passed ? 'Congratulations!' : 'Keep Practicing!' }}</h2>
                        <p class="opacity-75 lead">{{ $attempt->result->passed ? 'You have successfully passed the quiz.' : 'You did not reach the passing grade this time.' }}</p>
                    </div>
                    
                    <div class="card-body p-5">
                        <div class="row g-4 justify-content-center mb-5">
                            <div class="col-md-4">
                                <div class="p-4 rounded-4 bg-light border">
                                    <div class="text-uppercase small fw-bold text-muted mb-2">Final Score</div>
                                    <div class="display-4 fw-bold {{ $attempt->result->passed ? 'text-success' : 'text-danger' }}">
                                        {{ round($finalScore) }}%
                                    </div>
                                    @if($attempt->result->manual_score !== null)
                                        <div class="small text-muted mt-1 fst-italic"><i class="fas fa-user-edit"></i> Manually Graded</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-4 rounded-4 bg-light border">
                                    <div class="text-uppercase small fw-bold text-muted mb-2">Status</div>
                                    <div class="display-6 fw-bold">
                                        <span class="badge {{ $attempt->result->passed ? 'bg-success' : 'bg-danger' }} rounded-pill px-4">
                                            {{ $attempt->result->passed ? 'PASSED' : 'FAILED' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($attempt->result->teacher_feedback)
                            <div class="text-start mb-5 p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-4">
                                <h6 class="fw-bold text-primary mb-2"><i class="fas fa-comment-dots me-2"></i> Teacher Feedback</h6>
                                <p class="mb-0 text-dark">{{ $attempt->result->teacher_feedback }}</p>
                            </div>
                        @endif

                        <div class="row text-start g-3 mb-5">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-white border rounded-3">
                                    <div class="icon-circle bg-primary-subtle text-primary me-3">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted">Time Spent</div>
                                        <div class="fw-bold">
                                            {{ \Carbon\Carbon::parse($attempt->started_at)->diffInMinutes($attempt->completed_at) }} minutes
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-white border rounded-3">
                                    <div class="icon-circle bg-info-subtle text-info me-3">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted">Completed On</div>
                                        <div class="fw-bold">{{ $attempt->completed_at->format('M d, Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3 justify-content-center">
                            @php $isStudent = auth()->user()->role_id == 3; @endphp
                            <a href="{{ $isStudent ? route('students.dashboard') : route('quizzes.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                                <i class="fas fa-list me-2"></i> {{ $isStudent ? 'Back to Dashboard' : 'View Other Quizzes' }}
                            </a>
                            <a href="{{ $isStudent ? route('students.quizzes.take', $attempt->quiz_id) : route('quizzes.take', $attempt->quiz_id) }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5">
                                <i class="fas fa-redo me-2"></i> Retake Quiz
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if(in_array($userRole, ['teacher', 'admin']))
                <!-- Teacher Grading Panel -->
                <div class="card border-0 shadow-sm rounded-4 mt-5">
                    <div class="card-header bg-white border-0 py-4 px-4 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="fas fa-edit me-2 text-primary"></i> Manual Grading & Responses</h5>
                        @if($attempt->violations > 0)
                            <div class="badge {{ $attempt->violations >= 3 ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill px-3 py-2 border shadow-sm">
                                <i class="fas fa-shield-alt me-1"></i> Security Violations: {{ $attempt->violations }}
                            </div>
                        @else
                            <div class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 shadow-sm">
                                <i class="fas fa-shield-check me-1"></i> Clean Security Record
                            </div>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <span class="badge bg-secondary mb-2">Automated Score: {{ round($attempt->result->score) }}%</span>
                        </div>
                        
                        <!-- Show student responses -->
                        @foreach($attempt->quiz->questions as $index => $q)
                            @php $ans = isset($attemptAnswers) ? $attemptAnswers->get($q->id) : null; @endphp
                            <div class="mb-4 p-3 bg-light rounded border">
                                <p class="fw-bold mb-2 text-dark">{{ $index + 1 }}. {!! $q->content !!}</p>
                                @if($q->type === 'short_answer')
                                    <div class="p-3 bg-white border rounded text-dark mt-2 fst-italic">
                                        {{ $ans->short_text ?? 'No answer provided' }}
                                    </div>
                                @else
                                    <div class="p-2 bg-white border rounded mt-2">
                                        @if($ans && $ans->answer)
                                            <i class="fas {{ $ans->is_correct ? 'fa-check text-success' : 'fa-times text-danger' }} me-2"></i> {{ $ans->answer->content }}
                                        @else
                                            <span class="text-muted">Skipped</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <!-- Grading Form -->
                        <form action="{{ route('quizzes.grade', $attempt->id) }}" method="POST" class="mt-5 border-top pt-4">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Manual Score Override (%)</label>
                                    <input type="number" name="manual_score" class="form-control form-control-lg" value="{{ $attempt->result->manual_score ?? $attempt->result->score }}" min="0" max="100" step="0.1" required>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Feedback for Student (Optional)</label>
                                    <textarea name="teacher_feedback" class="form-control" rows="3" placeholder="Great job on the short answers...">{{ $attempt->result->teacher_feedback }}</textarea>
                                </div>
                                <div class="col-12 mt-4 d-flex justify-content-between align-items-center bg-light p-3 rounded border">
                                    <div class="form-check form-switch fs-5 mb-0">
                                        <input class="form-check-input" type="checkbox" role="switch" name="publish" id="publishGrade" value="1" {{ $attempt->result->is_published ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold ms-2" for="publishGrade" style="cursor: pointer;">Publish Grade to Student</label>
                                        <div class="small text-muted mt-1 fs-6">Once published, the student will be notified and can view this score.</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold rounded-pill shadow-sm"><i class="fas fa-save me-2"></i> Save Grades</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="text-center text-muted small mt-4">
                A copy of this result has been saved to your academic record.
            </div>

        </div>
    </div>
</div>

<style>
    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .bg-primary-subtle { background-color: #e0e7ff !important; }
    .bg-info-subtle { background-color: #e0f2fe !important; }
    .animate-fade-in { animation: fadeIn 0.6s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
</style>
@endsection
