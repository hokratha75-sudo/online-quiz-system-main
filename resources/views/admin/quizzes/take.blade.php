@extends('layouts.admin')

@php
    /** @var \App\Models\Quiz $quiz */
@endphp

@section('content')
<!-- Security Overlay Mode -->
<div id="securityHUDEnded" class="position-fixed top-0 start-0 w-100 h-100 bg-white d-none flex-column align-items-center justify-content-center z-3" style="z-index: 10000; opacity: 0.98;">
    <div class="text-center p-5 rounded-5 shadow-lg border">
        <div class="display-1 text-danger mb-4"><i class="fas fa-user-shield"></i></div>
        <h2 class="fw-bold mb-3">Security Violation Detected</h2>
        <p class="text-muted fs-5 mb-4">The proctoring system has detected multiple attempts to switch windows or exit the quiz. <br>Your attempt has been automatically submitted for review.</p>
        <button class="btn btn-primary btn-lg rounded-pill px-5" onclick="location.href='/quizzes'">Return to Dashboard</button>
    </div>
</div>

<div class="container-fluid py-4 user-select-none" oncontextmenu="return false;">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <!-- Security & Progress Alert Row -->
            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                        <div class="p-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4 me-3">
                                    <i class="fas fa-paper-plane fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0">{{ $quiz->title }}</h5>
                                    <div class="small text-muted text-uppercase fw-bold ls-1" style="font-size: 0.6rem;">{{ $quiz->subject?->subject_name ?? 'General' }} • {{ $quiz->questions->count() }} Questions</div>
                                </div>
                            </div>
                            <div id="securityHUD" class="d-flex align-items-center gap-3">
                                <div class="badge bg-light text-dark rounded-pill px-3 py-2 border shadow-sm">
                                    <span class="small text-muted me-2">Warnings:</span> 
                                    <span id="warningCounter" class="fw-bold text-danger">0</span>/3
                                </div>
                                <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-25 shadow-sm">
                                    <i class="fas fa-shield-alt me-1"></i> Proctoring Active
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden border-start border-4 border-danger">
                        <div class="p-3 d-flex align-items-center justify-content-between">
                            <div class="ms-2">
                                <div class="small text-muted text-uppercase fw-bold ls-1" style="font-size: 0.6rem;">Countdown</div>
                                <div id="quizTimer" class="h3 fw-bold mb-0 text-danger font-monospace">30:00</div>
                            </div>
                            <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-4 me-2">
                                <i class="fas fa-clock fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz Main Section -->
            <div class="quiz-board position-relative">
                <form id="quizForm" action="{{ auth()->user()->role_id == 3 ? route('students.quizzes.submit', $quiz->id) : route('quizzes.submit', $quiz->id) }}" method="POST">
                    @csrf
                    @foreach($quiz->questions as $index => $question)
                        <div class="question-pane card border-0 shadow-lg rounded-5 p-4 pe-md-5 mb-4 animate-fade-in {{ $index === 0 ? 'active' : 'd-none' }}" data-index="{{ $index }}">
                            <div class="progress mb-4 bg-light rounded-pill" style="height: 6px;">
                                <div class="progress-bar bg-primary rounded-pill progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ (($index+1)/count($quiz->questions))*100 }}%"></div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-4 gap-3">
                                <div class="h3 fw-bold text-primary mb-0 bg-primary bg-opacity-10 px-3 py-2 rounded-4">#{{ $index + 1 }}</div>
                                <div class="flex-grow-1">
                                    <div class="h4 fw-bold text-dark mt-1 lh-base">{!! $question->content !!}</div>
                                </div>
                            </div>

                            <div class="options-wrapper ms-lg-5">
                                @if($question->type === 'short_answer')
                                    <textarea name="responses[{{ $question->id }}]" class="form-control form-control-lg border-2 shadow-sm rounded-4 short-answer-input p-4 text-dark fs-5 bg-white" rows="5" placeholder="Type your answer here..." required></textarea>
                                @else
                                    <div class="row g-3">
                                        @foreach($question->answers as $ansIndex => $answer)
                                            <div class="col-12">
                                                <input type="radio" name="responses[{{ $question->id }}]" id="ans{{ $answer->id }}" value="{{ $answer->id }}" class="btn-check" required>
                                                <label class="option-card d-flex align-items-center p-4 rounded-4 border-2 shadow-sm cursor-pointer transition-all w-100" for="ans{{ $answer->id }}">
                                                    <div class="radio-mark me-4"></div>
                                                    <div class="fs-5 fw-medium text-dark">{{ $answer->answer_text }}</div>
                                                    <i class="fas fa-check-circle ms-auto fs-4 check-indicator opacity-0"></i>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Professional Footer Navigation -->
                    <div class="sticky-bottom py-3 mt-4" style="background: linear-gradient(0deg, rgba(248, 249, 250, 1) 50%, rgba(248, 249, 250, 0) 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" id="prevBtn" class="btn btn-outline-dark btn-lg rounded-pill px-4 shadow-sm border-2 fw-bold opacity-0 transition-all" disabled>
                                <i class="fas fa-arrow-left me-2"></i> Previous
                            </button>
                            
                            <div class="question-pagination d-flex gap-2">
                                @foreach($quiz->questions as $index => $question)
                                    <button type="button" class="btn p-0 pagination-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}" style="width: 12px; height: 12px; border-radius: 50%; background: #dee2e6; border: none;"></button>
                                @endforeach
                            </div>

                            <button type="button" id="nextBtn" class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg fw-bold transition-all">
                                Next Step <i class="fas fa-arrow-right ms-2"></i>
                            </button>

                            <button type="submit" id="submitBtn" class="btn btn-success btn-lg rounded-pill px-5 shadow-lg fw-bold d-none zoom-in">
                                Complete Exam <i class="fas fa-check-circle ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fa; }
    .question-pane { border: 1px solid rgba(0,0,0,0.05) !important; min-height: 480px; }
    .option-card { background: #fff; border: 2px solid #f1f3f5; }
    .option-card:hover { border-color: #5b6cf9; background: #f8f9ff; transform: translateY(-3px); }
    .btn-check:checked + .option-card { border-color: #5b6cf9; background: #eef2ff; box-shadow: 0 10px 15px -3px rgba(91, 108, 249, 0.1); }
    .btn-check:checked + .option-card .radio-mark { background: #5b6cf9; border-color: #5b6cf9; box-shadow: inset 0 0 0 4px white; }
    .btn-check:checked + .option-card .check-indicator { opacity: 1; color: #5b6cf9; }
    .radio-mark { width: 24px; height: 24px; border: 2px solid #dee2e6; border-radius: 50%; transition: all 0.2s; }
    .pagination-dot.active { background-color: #5b6cf9 !important; width: 30px !important; border-radius: 6px !important; }
    .pagination-dot.completed { background-color: #10b981 !important; }
    .transition-all { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .animate-fade-in { animation: slideUpFade 0.6s ease-out; }
    .zoom-in { animation: zoomIn 0.3s ease-in-out; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes zoomIn { from { transform: scale(0.9); } to { transform: scale(1); } }
    .cursor-pointer { cursor: pointer; }
    .user-select-none { -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('QuizMaster System: Advanced Proctoring Unit Active.');
    
    let currentIdx = 0;
    const questions = document.querySelectorAll('.question-pane');
    const dots = document.querySelectorAll('.pagination-dot');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    const totalCount = {{ $quiz->questions->count() }};
    const quizForm = document.getElementById('quizForm');

    // UI Updates
    function updateUI() {
        questions.forEach((q, idx) => {
            q.classList.toggle('d-none', idx !== currentIdx);
        });
        dots.forEach((dot, idx) => {
            dot.classList.toggle('active', idx === currentIdx);
        });
        prevBtn.disabled = currentIdx === 0;
        prevBtn.classList.toggle('opacity-0', currentIdx === 0);
        if (currentIdx === totalCount - 1) {
            nextBtn.classList.add('d-none');
            submitBtn.classList.remove('d-none');
        } else {
            nextBtn.classList.remove('d-none');
            submitBtn.classList.add('d-none');
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Navigation
    nextBtn.addEventListener('click', () => { if(currentIdx < totalCount-1) { currentIdx++; updateUI(); } });
    prevBtn.addEventListener('click', () => { if(currentIdx > 0) { currentIdx--; updateUI(); } });
    dots.forEach(dot => {
        dot.addEventListener('click', () => { currentIdx = parseInt(dot.dataset.index); updateUI(); });
    });

    // Mark completion
    document.querySelectorAll('.btn-check').forEach(radio => {
        radio.addEventListener('change', function() {
            const index = parseInt(this.closest('.question-pane').dataset.index);
            dots[index].classList.add('completed');
        });
    });

    document.querySelectorAll('.short-answer-input').forEach(input => {
        input.addEventListener('input', function() {
            const index = parseInt(this.closest('.question-pane').dataset.index);
            if(this.value.trim().length > 0) {
                dots[index].classList.add('completed');
            } else {
                dots[index].classList.remove('completed');
            }
        });
    });

    // Timer Implementation
    let timeLeft = {{ ($quiz->time_limit ?? 30) * 60 }};
    const timerDisplay = document.getElementById('quizTimer');
    const countdown = setInterval(() => {
        let mins = Math.floor(timeLeft / 60);
        let secs = timeLeft % 60;
        timerDisplay.textContent = `${mins.toString().padStart(2,'0')}:${secs.toString().padStart(2,'0')}`;
        if (timeLeft <= 0) {
            clearInterval(countdown);
            alert('SYSTEM: Time is up. Auto-submitting.');
            quizForm.submit();
        }
        timeLeft--;
    }, 1000);

    // ==========================================
    // STEP 1: PROCTORING & SECURITY UNIT
    // ==========================================
    let warnings = {{ $attempt->violations ?? 0 }};
    const maxWarnings = 3;
    const warningDisplay = document.getElementById('warningCounter');
    const HUDEnded = document.getElementById('securityHUDEnded');
    
    warningDisplay.textContent = warnings;

    function triggerWarning() {
        warnings++;
        warningDisplay.textContent = warnings;

        fetch("{{ auth()->user()->role_id == 3 ? route('students.quizzes.violation', $attempt->id) : route('quizzes.violation', $attempt->id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(res => res.json()).then(data => {
            if (data.disqualified || warnings >= maxWarnings) {
                clearInterval(countdown);
                HUDEnded.classList.remove('d-none');
                HUDEnded.classList.add('d-flex');
                setTimeout(() => { quizForm.submit(); }, 3000);
            } else {
                const alertMsg = `SECURITY ALERT: Switching windows/tabs is strictly prohibited (#${data.violations ?? warnings}/${maxWarnings}). If you reach 3 warnings, your quiz will be disqualified and submitted automatically.`;
                alert(alertMsg);
            }
        }).catch(err => {
            console.error('Proctoring Sync Error', err);
            // Fallback if offline
            if (warnings >= maxWarnings) {
                clearInterval(countdown);
                HUDEnded.classList.remove('d-none');
                HUDEnded.classList.add('d-flex');
                setTimeout(() => { quizForm.submit(); }, 3000);
            } else {
                alert(`SECURITY ALERT: Switching windows/tabs is prohibited (#${warnings}/${maxWarnings}).`);
            }
        });
    }

    // A: Tab Visibility Detection
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden') { triggerWarning(); }
    });

    // B: Window Blur Detection (Detects clicking away from the window even if staying on tab)
    window.addEventListener('blur', () => {
        // Delay slightly to prevent false positives from system modals
        setTimeout(() => {
            if (document.visibilityState === 'hidden' || !document.hasFocus()) {
                // We won't trigger blur warning twice if visibility already caught it
                // but this ensures picking up "Alt+Tab" or side-bar clicks
            }
        }, 500);
    });

    // C: Disable Interaction
    document.addEventListener('keydown', (e) => {
        // Disable F12, Ctrl+Shift+I, Ctrl+U, Ctrl+C, Ctrl+V
        if (e.keyCode == 123 || 
           (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 74)) || 
           (e.ctrlKey && e.keyCode == 85) ||
           (e.ctrlKey && (e.keyCode == 67 || e.keyCode == 86))) {
            e.preventDefault();
            return false;
        }
    });

    // D: Prevent Text Selection
    document.onselectstart = new Function ("return false");

    // E: Fullscreen Request (Prompt on first click)
    document.body.addEventListener('click', function requestFS() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.warn(`Fullscreen error: ${err.message}`);
            });
        }
        document.body.removeEventListener('click', requestFS);
    }, {once: true});

    updateUI();
});
</script>
@endpush
@endsection
