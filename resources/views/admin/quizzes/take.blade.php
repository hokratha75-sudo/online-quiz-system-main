@extends('layouts.admin')

@php
    $hideSidebar = true;
    /** @var \App\Models\Quiz $quiz */
@endphp

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<style>
        .font-heading { font-family: 'Open Sans', Helvetica, Arial, sans-serif !important; }
    .text-dark-gray { color: #333333; }
</style>
@endsection

@section('content')
<!-- Security Disqualification Overlay -->
<div id="securityHUDEnded" class="fixed inset-0 bg-white/95 backdrop-blur-xl z-[9999] hidden flex-col items-center justify-center text-center p-8">
    <div class="max-w-md w-full bg-white border border-slate-100 p-12 rounded-[40px] shadow-2xl">
        <div class="w-24 h-24 bg-rose-50 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-8 text-4xl animate-pulse shadow-sm">
            <i class="fas fa-shield-slash"></i>
        </div>
                <h2 class="font-heading text-3xl font-bold text-slate-900 tracking-tight mb-4">Focus Required</h2>
                <p class="text-slate-500 font-medium leading-relaxed mb-10 text-sm">We noticed you stepped away from the quiz multiple times. To ensure fairness for all students, your session has been paused and will be reviewed by your teacher.</p>
        <button onclick="window.location.href='{{ route('students.dashboard') }}'" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold uppercase tracking-widest shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all">Back to Dashboard</button>
    </div>
</div>

<div class="min-h-screen bg-[#fafafa] select-none font-sans" oncontextmenu="return false;">
    
    <!-- Top Header -->
    <header class="bg-white border-b border-slate-200 z-50 shadow-sm relative">
        <div class="max-w-[1400px] mx-auto px-6 h-[72px] flex items-center justify-between">
            <!-- Left: Icon & Info -->
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-indigo-500 text-white flex items-center justify-center shadow-md shadow-indigo-500/20">
                    <i class="fas fa-book-open text-lg"></i>
                </div>
                <div>
                    <h1 class="font-heading text-slate-900 font-bold text-lg tracking-tight leading-tight">{{ $quiz->title }}</h1>
                    <p class="text-slate-500 text-xs font-medium">{{ $quiz->subject?->subject_name ?? 'Programming' }} • {{ $quiz->questions->count() }} questions</p>
                </div>
            </div>

            <!-- Right: Timer -->
            <div class="flex items-center gap-3 bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm">
                <i class="far fa-clock text-slate-400 text-sm"></i>
                <div class="flex flex-col items-center">
                    <div id="quizTimer" class="text-sm font-bold text-slate-800 tabular-nums leading-none tracking-wide mb-1">00:00</div>
                    <div class="w-full h-1 bg-slate-100 rounded-full">
                        <div id="timerProgress" class="h-1 bg-indigo-500 rounded-full transition-all duration-1000" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-[768px] mx-auto px-4 py-8 pb-32">
        <form id="quizForm" action="{{ auth()->user()->role_id == 3 ? route('students.quizzes.submit', $quiz->id) : route('quizzes.submit', $quiz->id) }}" method="POST">
            @csrf
            
            <div class="mb-4 flex justify-end">
                <button type="button" class="flex items-center gap-2 text-slate-400 hover:text-slate-600 transition-colors text-sm font-medium">
                    <i class="far fa-flag"></i> Flag for review
                </button>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-end mb-3">
                    <div class="text-slate-500 text-[13px] font-medium">
                        Question <span id="currentQText">1</span> of {{ $quiz->questions->count() }}
                    </div>
                    <div class="text-slate-500 text-[13px] font-medium">
                        <span id="percentText">0%</span> complete
                    </div>
                </div>
                <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                    <div id="qProgressBar" class="h-full bg-indigo-500 transition-all duration-500 ease-out" style="width: 0%"></div>
                </div>
            </div>

            <div class="relative">
                @foreach($quiz->questions as $index => $question)
                <div class="question-pane absolute w-full transition-all duration-300 opacity-0 invisible translate-x-4 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm" data-index="{{ $index }}" id="q-{{ $index }}">
                    
                    <div class="flex justify-between items-center mb-6">
                        <div class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-500 px-3 py-1.5 rounded-full text-xs font-semibold">
                            <i class="far fa-question-circle"></i> Multiple Choice
                        </div>
                        <div class="inline-flex items-center bg-teal-50 text-teal-500 px-3 py-1.5 rounded-full text-xs font-semibold">
                            {{ $question->points ?? 10 }} pts
                        </div>
                    </div>

                    <div class="font-heading text-[20px] font-bold text-dark-gray leading-relaxed mb-8">
                        {!! $question->content !!}
                    </div>

                    <div class="space-y-3 choice-group" data-question-id="{{ $question->id }}">
                        @foreach($question->answers as $ansIndex => $answer)
                        <label class="option-card group relative flex items-center cursor-pointer w-full p-4 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 transition-all">
                            <input type="radio" 
                                   name="responses[{{ $question->id }}]" 
                                   value="{{ $answer->id }}" 
                                   class="sr-only choice-input" 
                                   onchange="handleAnswerChange(this)"
                                   required>
                            
                            <div class="option-visual flex items-center gap-4 w-full">
                                <div class="option-letter w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-[13px] font-bold text-slate-500 transition-colors uppercase">
                                    {{ chr(65 + $ansIndex) }}
                                </div>
                                <div class="option-text flex-grow text-[16px] font-medium text-dark-gray transition-colors">
                                    {{ $answer->answer_text }}
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Bottom Navigation Bar -->
            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 z-[100] py-4">
                <div class="max-w-[1000px] mx-auto px-6 flex flex-col items-center">
                    <div class="w-full flex items-center justify-between mb-2">
                        
                        <button type="button" id="prevBtn" class="px-4 py-2 rounded-lg font-medium text-sm text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all disabled:opacity-0 flex items-center gap-2 border border-slate-100">
                             <i class="fas fa-chevron-left text-xs"></i> Previous
                        </button>
                        
                        <div class="flex gap-1.5">
                            @foreach($quiz->questions as $index => $question)
                                <button type="button" class="pagination-dot w-8 h-8 rounded-full flex items-center justify-center text-[13px] font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors" data-index="{{ $index }}">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="button" id="nextBtn" class="px-5 py-2.5 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-all flex items-center gap-2 shadow-sm">
                                Next <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                            <button type="submit" id="submitBtn" class="hidden px-5 py-2.5 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg font-medium text-sm transition-all flex items-center gap-2 shadow-sm">
                                Finish
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-[13px] font-medium text-slate-500">
                        <i class="far fa-check-circle text-emerald-500"></i> <span id="answeredCount">0</span> / {{ $quiz->questions->count() }}
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>

@push('scripts')
<script>
    // Selection logic
    function handleAnswerChange(inputElement) {
        const group = inputElement.closest('.choice-group');
        const cards = group.querySelectorAll('.option-card');
        
        cards.forEach(card => {
            const letter = card.querySelector('.option-letter');
            
            if (card.querySelector('.choice-input').checked) {
                card.classList.add('border-indigo-500');
                card.classList.remove('border-slate-200', 'hover:border-indigo-300');
                letter.classList.add('bg-indigo-100', 'text-indigo-600');
                letter.classList.remove('bg-slate-100', 'text-slate-500');
            } else {
                card.classList.remove('border-indigo-500');
                card.classList.add('border-slate-200', 'hover:border-indigo-300');
                letter.classList.remove('bg-indigo-100', 'text-indigo-600');
                letter.classList.add('bg-slate-100', 'text-slate-500');
            }
        });
        
        updateAnsweredCount();
    }

    function updateAnsweredCount() {
        const checkedCount = document.querySelectorAll('.choice-input:checked').length;
        document.getElementById('answeredCount').textContent = checkedCount;
    }

    document.addEventListener('DOMContentLoaded', function() {
        let currentIdx = 0;
        const questions = document.querySelectorAll('.question-pane');
        const dots = document.querySelectorAll('.pagination-dot');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const submitBtn = document.getElementById('submitBtn');
        const qProgressBar = document.getElementById('qProgressBar');
        const percentText = document.getElementById('percentText');
        const currentQText = document.getElementById('currentQText');
        const totalCount = parseInt("{{ $quiz->questions->count() }}");
        const quizForm = document.getElementById('quizForm');

        function updateUI() {
            questions.forEach((q, idx) => {
                if (idx === currentIdx) {
                    q.classList.remove('opacity-0', 'invisible', 'translate-x-4', '-translate-x-4');
                    q.classList.add('opacity-100', 'visible', 'translate-x-0');
                    q.style.position = 'relative';
                } else {
                    if (idx < currentIdx) {
                        q.classList.add('-translate-x-4');
                        q.classList.remove('translate-x-0', 'translate-x-4');
                    } else {
                        q.classList.add('translate-x-4');
                        q.classList.remove('translate-x-0', '-translate-x-4');
                    }
                    q.classList.add('opacity-0', 'invisible');
                    q.classList.remove('opacity-100', 'visible');
                    q.style.position = 'absolute';
                }
            });

            dots.forEach((dot, idx) => {
                if (idx === currentIdx) {
                    dot.classList.add('bg-indigo-500', 'text-white');
                    dot.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                } else {
                    dot.classList.remove('bg-indigo-500', 'text-white');
                    dot.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                }
            });

            const percent = Math.round(((currentIdx + 1) / totalCount) * 100);
            qProgressBar.style.width = `${percent}%`;
            percentText.textContent = `${percent}%`;
            currentQText.textContent = currentIdx + 1;
            
            if (currentIdx === 0) {
                prevBtn.classList.add('invisible');
            } else {
                prevBtn.classList.remove('invisible');
            }
            
            if (currentIdx === totalCount - 1) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        nextBtn.addEventListener('click', () => { 
            if(currentIdx < totalCount-1) { 
                currentIdx++; 
                updateUI(); 
            } 
        });

        prevBtn.addEventListener('click', () => { 
            if(currentIdx > 0) { 
                currentIdx--; 
                updateUI(); 
            } 
        });

        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                if (!isNaN(index)) {
                    currentIdx = index;
                    updateUI();
                }
            });
        });

        // Timer logic
        const maxTimeLeft = parseInt("{{ ($quiz->time_limit ?? 30) * 60 }}");
        let timeLeft = maxTimeLeft;
        const timerDisplay = document.getElementById('quizTimer');
        const timerProgress = document.getElementById('timerProgress');
        
        const countdown = setInterval(() => {
            let mins = Math.floor(timeLeft / 60);
            let secs = timeLeft % 60;
            timerDisplay.textContent = `${mins.toString().padStart(2,'0')}:${secs.toString().padStart(2,'0')}`;
            
            timerProgress.style.width = `${(timeLeft / maxTimeLeft) * 100}%`;

            if (timeLeft <= 60) {
                timerDisplay.classList.add('text-rose-600');
                timerProgress.classList.replace('bg-indigo-500', 'bg-rose-500');
            }

            if (timeLeft <= 0) {
                clearInterval(countdown);
                quizForm.submit();
            }
            timeLeft--;
        }, 1000);

        // Proctoring System
        let warnings = 0;
        let isSubmitting = false;
        const maxWarnings = 3;
        const securityOverlay = document.getElementById('securityHUDEnded');
        
        quizForm.addEventListener('submit', () => {
            isSubmitting = true;
        });

        function triggerWarning() {
            if (isSubmitting) return;
            warnings++;
            if (warnings >= maxWarnings) {
                if(securityOverlay) {
                    securityOverlay.classList.remove('hidden');
                    securityOverlay.classList.add('flex');
                }
                setTimeout(() => { 
                    isSubmitting = true;
                    quizForm.submit(); 
                }, 3000);
            } else {
                alert(`Careful! Please stay focused on your quiz. Switching tabs or windows too often (#${warnings}/${maxWarnings}) will cause your session to be automatically submitted to ensure a fair testing environment. Please stay on this page to make sure your hard work counts!`);
            }
        }

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') triggerWarning();
        });

        document.addEventListener('keydown', (e) => {
            if (e.keyCode == 123 || (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 74)) || (e.ctrlKey && (e.keyCode == 85 || e.keyCode == 67 || e.keyCode == 86))) {
                e.preventDefault();
                return false;
            }
        });

        updateUI();
        updateAnsweredCount();
    });
</script>
@endpush
@endsection
