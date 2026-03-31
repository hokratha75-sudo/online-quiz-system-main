@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 animate-slide-in">
            <!-- Breadcrumbs / Top Bar -->
            <div class="top-status-bar d-flex justify-content-between mb-4 bg-white p-3 rounded-4 shadow-sm align-items-center">
                <div class="d-flex align-items-center">
                    <div class="status-dot bg-primary me-2" style="width: 8px; height: 8px; border-radius: 50%;"></div>
                    <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 1px;">Quiz Examination</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ $userRole === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="btn btn-sm btn-light rounded-pill px-3 shadow-sm border-0">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                </div>
            </div>

            <!-- Quiz Header Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-primary text-white p-4 border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="fw-bold mb-1">{{ $quiz->title }}</h3>
                            <p class="mb-0 opacity-75"><i class="fas fa-book-open me-2"></i>{{ $quiz->subject?->subject_name ?? 'Subject' }}</p>
                        </div>
                        <div class="col-auto text-end">
                            <div class="bg-white bg-opacity-25 rounded-3 p-3 text-center" style="backdrop-filter: blur(10px);">
                                <div class="small text-uppercase opacity-75 fw-bold" style="font-size: 0.65rem;">Time Limit</div>
                                <div class="h4 fw-bold mb-0">{{ $quiz->time_limit ?? 30 }}m</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 mb-4 text-center">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-4">
                                <i class="fas fa-tasks text-primary fa-2x mb-2"></i>
                                <div class="small text-muted">Total Questions</div>
                                <div class="h5 fw-bold mb-0">{{ $quiz->questions->count() }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-4">
                                <i class="fas fa-star text-warning fa-2x mb-2"></i>
                                <div class="small text-muted">Points</div>
                                <div class="h5 fw-bold mb-0">100 Max</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-4">
                                <i class="fas fa-info-circle text-info fa-2x mb-2"></i>
                                <div class="small text-muted">Passing Grade</div>
                                <div class="h5 fw-bold mb-0">{{ $quiz->pass_percentage ?? 60 }}%</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Quiz Description</h5>
                        <div class="p-3 bg-white border rounded-4 text-muted" style="line-height: 1.6;">
                            {{ $quiz->description ?: 'No description provided for this quiz.' }}
                        </div>
                    </div>

                    @if($userRole !== 'student')
                    <div class="mb-5 mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="fas fa-chart-bar text-primary me-2"></i> Student Submissions
                            </h5>
                            <span class="badge bg-light text-dark border">{{ $quiz->attempts->where('status', 'completed')->count() }} Completed</span>
                        </div>
                        
                        <div class="table-responsive bg-white rounded-4 border shadow-sm">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 border-0 py-3 small text-uppercase text-muted fw-bold">Student</th>
                                        <th class="border-0 py-3 small text-uppercase text-muted fw-bold">Date</th>
                                        <th class="border-0 py-3 small text-uppercase text-muted fw-bold">Score</th>
                                        <th class="border-0 py-3 small text-uppercase text-muted fw-bold">Status</th>
                                        <th class="pe-4 border-0 py-3 small text-uppercase text-muted fw-bold text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($quiz->attempts as $attempt)
                                        @if($attempt->status === 'completed')
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary-subtle rounded-circle me-3 d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                        {{ strtoupper(substr($attempt->user->username, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark small">{{ $attempt->user->username }}</div>
                                                        <div class="text-muted" style="font-size: 0.7rem;">{{ $attempt->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="small text-muted">{{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y') : 'N/A' }}</div>
                                                <div class="text-muted small" style="font-size: 0.7rem;">{{ $attempt->completed_at ? $attempt->completed_at->format('H:i') : '' }}</div>
                                            </td>
                                            <td class="py-3">
                                                <div class="fw-bold fs-5 {{ $attempt->result?->passed ? 'text-success' : 'text-danger' }}">
                                                    {{ round($attempt->result?->score ?? 0) }}%
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <span class="badge {{ $attempt->result?->passed ? 'bg-success' : 'bg-danger' }} rounded-pill px-3" style="font-size: 0.65rem;">
                                                    {{ $attempt->result?->passed ? 'PASSED' : 'FAILED' }}
                                                </span>
                                            </td>
                                            <td class="pe-4 py-3 text-center">
                                                <a href="{{ route('quizzes.result', $attempt->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="font-size: 0.75rem;">
                                                    <i class="fas fa-eye me-1"></i> Details
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <div class="mb-2"><i class="fas fa-inbox fa-3x opacity-25"></i></div>
                                                <p class="mb-0">No submissions found for this quiz yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if($userRole !== 'student' && $quiz->questions->count() > 0)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Questions Preview (Admin/Teacher)</h5>
                        <div class="accordion shadow-sm" id="questionsAccordion">
                            @foreach($quiz->questions as $index => $question)
                                <div class="accordion-item border-0 mb-2 rounded-4 overflow-hidden shadow-sm">
                                    <h2 class="accordion-header" id="heading{{ $question->id }}">
                                        <button class="accordion-button collapsed fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $question->id }}" aria-expanded="false" style="background: white;">
                                            <span class="badge bg-primary rounded-pill me-3">{{ $index + 1 }}</span>
                                            {{ $question->content }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $question->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $question->id }}" data-bs-parent="#questionsAccordion">
                                        <div class="accordion-body bg-light bg-opacity-50">
                                            <ul class="list-group list-group-flush rounded-3 border">
                                                @foreach($question->answers as $answer)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center {{ $answer->is_correct ? 'bg-success bg-opacity-10' : '' }}">
                                                        <span class="small">{{ $answer->answer_text }}</span>
                                                        @if($answer->is_correct)
                                                            <span class="badge bg-success small"><i class="fas fa-check"></i> Correct</span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Important Instructions</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2 d-flex align-items-start">
                                <i class="fas fa-check-circle text-success mt-1 me-3"></i>
                                <span>Once you start the quiz, the timer will begin and cannot be paused.</span>
                            </li>
                            <li class="mb-2 d-flex align-items-start">
                                <i class="fas fa-check-circle text-success mt-1 me-3"></i>
                                <span>Make sure you have a stable internet connection before beginning.</span>
                            </li>
                            <li class="mb-2 d-flex align-items-start">
                                <i class="fas fa-check-circle text-success mt-1 me-3"></i>
                                <span>Refreshing the page during the quiz may result in loss of progress.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="d-grid pt-3">
                        @if($userRole === 'student')
                            @if(isset($previousAttempts) && $previousAttempts->count() > 0)
                                <div class="mb-4 p-4 bg-light rounded-4 border">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-history text-primary me-2"></i>Your Previous Attempts</h6>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small fw-bold">{{ $previousAttempts->count() }} attempt{{ $previousAttempts->count() > 1 ? 's' : '' }}</span>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle mb-0 bg-white rounded-3 overflow-hidden shadow-sm">
                                            <thead class="bg-white">
                                                <tr>
                                                    <th class="ps-3 small text-muted text-uppercase fw-bold border-0 py-2" style="font-size:0.65rem;">Date</th>
                                                    <th class="small text-muted text-uppercase fw-bold border-0 py-2" style="font-size:0.65rem;">Score</th>
                                                    <th class="small text-muted text-uppercase fw-bold border-0 py-2" style="font-size:0.65rem;">Result</th>
                                                    <th class="pe-3 small text-muted text-uppercase fw-bold border-0 py-2 text-end" style="font-size:0.65rem;">Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($previousAttempts as $prevAttempt)
                                                    <tr>
                                                        <td class="ps-3 small text-muted">{{ $prevAttempt->completed_at ? $prevAttempt->completed_at->format('M d, Y H:i') : 'N/A' }}</td>
                                                        <td>
                                                            <span class="fw-bold {{ $prevAttempt->result?->passed ? 'text-success' : 'text-danger' }}">
                                                                {{ $prevAttempt->result ? round($prevAttempt->result->score) . '%' : 'Pending' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if($prevAttempt->result?->is_published === false)
                                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2" style="font-size:0.65rem;">Reviewing</span>
                                                            @elseif($prevAttempt->result?->passed)
                                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2" style="font-size:0.65rem;">Passed</span>
                                                            @else
                                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-2" style="font-size:0.65rem;">Failed</span>
                                                            @endif
                                                        </td>
                                                        <td class="pe-3 text-end">
                                                            <a href="{{ route('students.quizzes.result', $prevAttempt->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2" style="font-size:0.7rem;">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                        <a href="{{ route('students.quizzes.take', $quiz->id) }}" class="btn btn-primary btn-lg rounded-pill shadow" style="padding: 15px;">
                            <i class="fas fa-play me-2"></i> {{ isset($previousAttempts) && $previousAttempts->count() > 0 ? 'Retake Quiz' : 'Begin Quiz and Start Timer' }}
                        </a>
                        @else
                        <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-warning btn-lg rounded-pill shadow" style="padding: 15px;">
                            <i class="fas fa-edit me-2"></i> Edit Quiz in Builder
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="text-center text-muted small p-2">
                <i class="fas fa-shield-alt me-1"></i> This session is being monitored for academic integrity.
            </div>
        </div>
    </div>
</div>

<style>
    .card-header.bg-primary {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%) !important;
    }
    .btn-primary {
        background: linear-gradient(135deg, #4361ee 0%, #4895ef 100%);
        border: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }
    .accordion-button:not(.collapsed) {
        color: #3a0ca3;
        box-shadow: none;
    }
</style>
@endsection
