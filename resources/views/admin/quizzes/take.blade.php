@extends('layouts.admin')

@php
    $hideSidebar = true;
    /** @var \App\Models\Quiz $quiz */
@endphp

@section('content')
<!-- Security Disqualification Overlay -->
<div id="securityHUDEnded" class="fixed inset-0 bg-indigo-950/98 backdrop-blur-xl z-[9999] hidden flex-col items-center justify-center text-center p-8">
    <div class="max-w-md w-full bg-white border border-slate-100 p-12 rounded-[40px] shadow-2xl">
        <div class="w-24 h-24 bg-rose-50 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-8 text-4xl animate-pulse shadow-sm">
            <i class="fas fa-shield-slash"></i>
        </div>
        <h2 class="text-3xl font-bold text-slate-900 tracking-tight mb-4 uppercase">Lockdown Breach</h2>
        <p class="text-slate-500 font-bold leading-relaxed mb-10 text-xs uppercase tracking-wide">We detected multiple unauthorized window transitions. Deployment has been frozen and flagged for administrative verification.</p>
        <button onclick="window.location.href='{{ route('students.dashboard') }}'" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold uppercase tracking-widest shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all">Back to Dashboard</button>
    </div>
</div>

<div class="min-h-screen bg-slate-50 select-none font-inter" oncontextmenu="return false;">
    
    <!-- Premium Exam Header: High-Density Flow -->
    <header class="sticky top-0 bg-white border-b border-slate-100 z-50 shadow-sm">
        <div class="max-w-screen-xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-600/20">
                    <i class="fas fa-microchip text-sm"></i>
                </div>
                <div class="hidden sm:block">
                    <h1 class="text-slate-900 font-bold text-sm tracking-tight leading-none uppercase">{{ $quiz->title }}</h1>
                    <p class="text-indigo-600 text-[10px] font-bold uppercase tracking-widest mt-1.5">{{ $quiz->subject?->subject_name ?? 'SYSTEM UNIT' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <!-- Proctoring HUD: Compact -->
                <div id="proctoringHUD" class="hidden md:flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Protocol Active</span>
                    <div class="w-px h-3 bg-slate-200"></div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Failures: <span id="warningCounter" class="text-rose-500 ml-1">0</span>/3</span>
                </div>

                <!-- Timer Section: High-Contrast -->
                <div class="flex items-center gap-3 bg-rose-50 border border-rose-100 px-5 py-2 rounded-2xl shadow-sm">
                    <i class="fas fa-clock text-rose-500 text-xs"></i>
                    <div id="quizTimer" class="text-xl font-bold text-rose-600 tabular-nums leading-none font-mono">00:00</div>
                </div>

                <!-- Safe Exit -->
                <button onclick="if(confirm('Institutional Warning: Suspend current session? Progress remains cached.')) window.location.href='{{ route('students.dashboard') }}'" class="w-10 h-10 rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-rose-600 hover:border-rose-100 hover:bg-rose-50 transition-all flex items-center justify-center shadow-sm">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
        
        <!-- Global Progress Bar -->
        <div class="h-1 w-full bg-slate-100">
            <div id="globalProgressBar" class="h-full bg-indigo-600 transition-all duration-700 ease-out shadow-[0_0_12px_rgba(79,70,229,0.4)]" style="width: 0%"></div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-6 py-12 md:py-20 lg:py-24 pb-48">
        <form id="quizForm" action="{{ auth()->user()->role_id == 3 ? route('students.quizzes.submit', $quiz->id) : route('quizzes.submit', $quiz->id) }}" method="POST">
            @csrf
            
            @foreach($quiz->questions as $index => $question)
            <div class="question-pane hidden opacity-0 transition-all duration-500 translate-y-4 bg-white/40 p-8 rounded-[40px] border border-white shadow-xl backdrop-blur-sm" data-index="{{ $index }}" id="q-{{ $index }}">
                <div class="mb-10 text-center">
                    <div class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-600 px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest mb-6 border border-indigo-100/50">
                        <i class="fas fa-database text-[8px]"></i> Vector {{ $index + 1 }} of {{ $quiz->questions->count() }}
                    </div>
                    <div class="text-2xl md:text-3xl font-black text-slate-900 leading-tight tracking-tighter antialiased uppercase">
                        {!! $question->content !!}
                    </div>
                </div>

                <div class="space-y-5">
                    @if($question->type === 'short_answer')
                        <textarea 
                            name="responses[{{ $question->id }}]" 
                            class="w-full bg-white border-2 border-slate-100 rounded-[32px] p-8 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all min-h-[300px] outline-none shadow-sm uppercase pointer-events-auto" 
                            placeholder="Type your response here..."
                            required
                        ></textarea>
                    @else
                        <div class="grid grid-cols-1 gap-5 choice-group" data-question-id="{{ $question->id }}">
                            @foreach($question->answers as $ansIndex => $answer)
                            <div class="option-card group relative flex items-center cursor-pointer min-h-[90px] w-full" 
                                 onclick="selectOption(this, '{{ $answer->id }}')">
                                
                                <input type="radio" 
                                       name="responses[{{ $question->id }}]" 
                                       value="{{ $answer->id }}" 
                                       class="sr-only choice-input" 
                                       required>
                                
                                <div class="option-visual w-full p-6 bg-white border-2 border-slate-100 rounded-[28px] flex items-center gap-6 transition-all duration-300 relative overflow-hidden group-hover:border-indigo-200">
                                    
                                    <div class="selection-glow absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 transition-opacity"></div>
                                    
                                    <div class="option-letter w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-sm font-black text-slate-400 transition-all uppercase tabular-nums">
                                        {{ chr(65 + $ansIndex) }}
                                    </div>
                                    
                                    <div class="option-text flex-grow text-base font-bold text-slate-700 transition-all uppercase tracking-tight">
                                        {{ $answer->answer_text }}
                                    </div>
                                    
                                    <div class="option-indicator w-6 h-6 rounded-full border-2 border-slate-300 bg-white transition-all flex items-center justify-center shrink-0">
                                        <div class="indicator-dot w-3 h-3 rounded-full bg-indigo-600 opacity-0 scale-50 transition-all"></div>
                                    </div>
                                    
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endforeach

            <!-- Action Bar Hub -->
            <div class="fixed bottom-12 left-0 right-0 z-[100] flex justify-center">
                <div class="max-w-2xl w-full px-6">
                    <div class="bg-white/90 backdrop-blur-xl p-3 rounded-[32px] shadow-[0_20px_50px_rgba(0,0,0,0.15)] flex items-center justify-between gap-4 border border-white">
                        <button type="button" id="prevBtn" class="h-14 px-8 rounded-2xl font-black text-xs uppercase tracking-[0.15em] text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all disabled:opacity-0 flex items-center gap-3">
                             <i class="fas fa-chevron-left text-[10px]"></i> Previous
                        </button>
                        
                        <div class="hidden sm:flex gap-2 px-5 h-14 items-center bg-slate-50/50 rounded-2xl border border-slate-100">
                            @foreach($quiz->questions as $index => $question)
                                <div class="pagination-dot w-2 h-2 rounded-full bg-slate-200 transition-all duration-500 shadow-sm" data-index="{{ $index }}"></div>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="button" id="nextBtn" class="h-14 px-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-xs uppercase tracking-[0.15em] shadow-xl shadow-indigo-600/30 transition-all flex items-center gap-3">
                                Next <i class="fas fa-chevron-right text-[10px]"></i>
                            </button>
                            <button type="submit" id="submitBtn" class="hidden h-14 px-10 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-xs uppercase tracking-[0.15em] shadow-xl shadow-emerald-600/30 transition-all flex items-center gap-3">
                                Finish <i class="fas fa-check-double text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>

@push('scripts')
<script>
/**
 * Visual Selection Logic via Vanilla JS
 * Ensures bulletproof interaction even without Tailwind JIT updates
 */
function selectOption(element, value) {
    const group = element.closest('.choice-group');
    const cards = group.querySelectorAll('.option-card');
    const input = element.querySelector('.choice-input');
    
    // 1. Mark underlying radio as checked
    input.checked = true;
    
    // 2. Reset all cards in this group
    cards.forEach(card => {
        const visual = card.querySelector('.option-visual');
        const letter = card.querySelector('.option-letter');
        const text = card.querySelector('.option-text');
        const indicator = card.querySelector('.option-indicator');
        const dot = card.querySelector('.indicator-dot');
        const glow = card.querySelector('.selection-glow');

        // Reset to default
        visual.classList.remove('bg-indigo-600', 'border-indigo-600', 'shadow-2xl', 'shadow-indigo-600/30');
        visual.classList.add('bg-white', 'border-slate-100');
        letter.classList.remove('bg-white/20', 'border-transparent', 'text-white');
        letter.classList.add('bg-slate-50', 'border-slate-100', 'text-slate-400');
        text.classList.remove('text-white');
        text.classList.add('text-slate-700');
        indicator.classList.remove('border-none', 'bg-white');
        indicator.classList.add('border-2', 'border-slate-300', 'bg-white');
        dot.classList.add('opacity-0', 'scale-50');
        dot.classList.remove('opacity-100', 'scale-100');
        glow.classList.add('opacity-0');
        glow.classList.remove('opacity-100');
    });

    // 3. Highlight selected card
    const selVisual = element.querySelector('.option-visual');
    const selLetter = element.querySelector('.option-letter');
    const selText = element.querySelector('.option-text');
    const selIndicator = element.querySelector('.option-indicator');
    const selDot = element.querySelector('.indicator-dot');
    const selGlow = element.querySelector('.selection-glow');

    selVisual.classList.add('bg-indigo-600', 'border-indigo-600', 'shadow-2xl', 'shadow-indigo-600/30');
    selVisual.classList.remove('bg-white', 'border-slate-100');
    selLetter.classList.add('bg-white/20', 'border-transparent', 'text-white');
    selLetter.classList.remove('bg-slate-50', 'border-slate-100', 'text-slate-400');
    selText.classList.add('text-white');
    selText.classList.remove('text-slate-700');
    selIndicator.classList.add('border-none', 'bg-white');
    selIndicator.classList.remove('border-2', 'border-slate-300', 'bg-white');
    selDot.classList.remove('opacity-0', 'scale-50');
    selDot.classList.add('opacity-100', 'scale-100');
    selGlow.classList.remove('opacity-0');
    selGlow.classList.add('opacity-100');
}

document.addEventListener('DOMContentLoaded', function() {
    let currentIdx = 0;
    const questions = document.querySelectorAll('.question-pane');
    const dots = document.querySelectorAll('.pagination-dot');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    const globalProgress = document.getElementById('globalProgressBar');
    const totalCount = parseInt("{{ $quiz->questions->count() }}");
    const quizForm = document.getElementById('quizForm');

    function updateUI() {
        questions.forEach((q, idx) => {
            if (idx === currentIdx) {
                q.classList.remove('hidden');
                setTimeout(() => {
                    q.classList.remove('opacity-0', 'translate-y-4');
                    q.classList.add('opacity-100', 'translate-y-0');
                }, 50);
            } else {
                q.classList.add('hidden', 'opacity-0', 'translate-y-4');
                q.classList.remove('opacity-100', 'translate-y-0');
            }
        });

        dots.forEach((dot, idx) => {
            if (idx < currentIdx) {
                dot.classList.remove('bg-slate-200', 'w-2');
                dot.classList.add('bg-indigo-500', 'w-4');
            } else if (idx === currentIdx) {
                dot.classList.remove('bg-slate-200', 'w-2');
                dot.classList.add('bg-indigo-600', 'w-6');
            } else {
                dot.classList.remove('bg-indigo-600', 'bg-indigo-500', 'w-6', 'w-4');
                dot.classList.add('bg-slate-200', 'w-2');
            }
        });

        globalProgress.style.width = `${((currentIdx + 1) / totalCount) * 100}%`;
        
        // Hide previous button on first question
        if (currentIdx === 0) {
            prevBtn.classList.add('invisible', 'opacity-0');
        } else {
            prevBtn.classList.remove('invisible', 'opacity-0');
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

    // Timer logic
    let timeLeft = parseInt("{{ ($quiz->time_limit ?? 30) * 60 }}");
    const timerDisplay = document.getElementById('quizTimer');
    const countdown = setInterval(() => {
        let mins = Math.floor(timeLeft / 60);
        let secs = timeLeft % 60;
        timerDisplay.textContent = `${mins.toString().padStart(2,'0')}:${secs.toString().padStart(2,'0')}`;
        
        if (timeLeft <= 60) {
            timerDisplay.closest('div').classList.add('animate-pulse', 'bg-rose-100', 'border-rose-200');
            timerDisplay.classList.add('text-rose-700');
        }

        if (timeLeft <= 0) {
            clearInterval(countdown);
            quizForm.submit();
        }
        timeLeft--;
    }, 1000);

    // Proctoring System
    let warnings = 0;
    const maxWarnings = 3;
    const warningDisplay = document.getElementById('warningCounter');
    const securityOverlay = document.getElementById('securityHUDEnded');
    
    function triggerWarning() {
        warnings++;
        if(warningDisplay) warningDisplay.textContent = warnings;

        // Visual alert
        const hud = document.getElementById('proctoringHUD');
        hud.classList.remove('bg-slate-50');
        hud.classList.add('bg-rose-50', 'border-rose-200');
        setTimeout(() => {
            hud.classList.add('bg-slate-50');
            hud.classList.remove('bg-rose-50', 'border-rose-200');
        }, 1000);

        if (warnings >= maxWarnings) {
            if(securityOverlay) {
                securityOverlay.classList.remove('hidden');
                securityOverlay.classList.add('flex');
            }
            setTimeout(() => { quizForm.submit(); }, 3000);
        } else {
            alert(`INSTITUTIONAL ALERT: Unauthorized window switching detected (#${warnings}/${maxWarnings}). Exceeding ${maxWarnings} violations results in immediate disqualification.`);
        }
    }

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden') triggerWarning();
    });

    // Disable keys
    document.addEventListener('keydown', (e) => {
        if (e.keyCode == 123 || (e.ctrlKey && e.shiftKey && (e.keyCode == 73 || e.keyCode == 74)) || (e.ctrlKey && (e.keyCode == 85 || e.keyCode == 67 || e.keyCode == 86))) {
            e.preventDefault();
            return false;
        }
    });

    updateUI();
});
</script>
@endpush

<style>
    .question-pane { backface-visibility: hidden; transform-style: preserve-3d; }
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f8fafc; }
    ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    .option-card { -webkit-tap-highlight-color: transparent; }
</style>
@endsection
