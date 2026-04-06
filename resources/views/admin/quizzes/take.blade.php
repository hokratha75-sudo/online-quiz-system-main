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

    <main class="max-w-3xl mx-auto px-6 py-12 md:py-20 lg:py-24">
        <form id="quizForm" action="{{ auth()->user()->role_id == 3 ? route('students.quizzes.submit', $quiz->id) : route('quizzes.submit', $quiz->id) }}" method="POST">
            @csrf
            
            @foreach($quiz->questions as $index => $question)
            <div class="question-pane hidden opacity-0 transition-all duration-500 translate-y-4" data-index="{{ $index }}" id="q-{{ $index }}">
                <div class="mb-10 text-center md:text-left">
                    <div class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-600 px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest mb-6 border border-indigo-100/50">
                        <i class="fas fa-database text-[8px]"></i> Vector {{ $index + 1 }} of {{ $quiz->questions->count() }}
                    </div>
                    <div class="text-xl md:text-2xl font-bold text-slate-900 leading-snug tracking-tight antialiased uppercase">
                        {!! $question->content !!}
                    </div>
                </div>

                <div class="space-y-4">
                    @if($question->type === 'short_answer')
                        <textarea 
                            name="responses[{{ $question->id }}]" 
                            class="w-full bg-white border border-slate-200 rounded-3xl p-8 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all min-h-[250px] outline-none placeholder:text-slate-300 uppercase leading-relaxed shadow-sm" 
                            placeholder="Initialize decentralized intelligence response..."
                            required
                        ></textarea>
                    @else
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($question->answers as $ansIndex => $answer)
                            <label class="group relative flex items-center cursor-pointer">
                                <input type="radio" name="responses[{{ $question->id }}]" value="{{ $answer->id }}" class="peer hidden" required>
                                <div class="w-full p-5 bg-white border border-slate-200 rounded-2xl flex items-center gap-5 transition-all duration-300 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:shadow-xl peer-checked:shadow-indigo-600/20 group-hover:bg-slate-50 shadow-sm">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-xs font-bold text-slate-400 transition-all peer-checked:bg-white/20 peer-checked:border-white/10 peer-checked:text-white uppercase tabular-nums">
                                        {{ chr(65 + $ansIndex) }}
                                    </div>
                                    <div class="flex-grow text-sm font-bold text-slate-600 transition-all peer-checked:text-white uppercase tracking-tight">
                                        {{ $answer->answer_text }}
                                    </div>
                                    <div class="w-2 h-2 rounded-full bg-slate-100 peer-checked:bg-white shadow-[0_0_10px_rgba(255,255,255,1)] transition-all"></div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endforeach

            <!-- Action Bar: Minimalist Hub -->
            <div class="fixed bottom-0 left-0 right-0 py-8 bg-gradient-to-t from-slate-50 via-slate-50 to-transparent pointer-events-none z-40">
                <div class="max-w-2xl mx-auto px-6 pointer-events-auto">
                    <div class="bg-white p-2.5 rounded-3xl shadow-2xl flex items-center justify-between gap-3 border border-slate-100">
                        <button type="button" id="prevBtn" class="h-12 px-8 rounded-2xl font-bold text-[10px] uppercase tracking-widest text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all disabled:opacity-20 flex items-center gap-2">
                             <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        
                        <div class="hidden sm:flex gap-1.5 px-4 h-12 items-center bg-slate-50 rounded-2xl border border-slate-100/50">
                            @foreach($quiz->questions as $index => $question)
                                <div class="pagination-dot w-2 h-2 rounded-full bg-slate-200 transition-all duration-500" data-index="{{ $index }}"></div>
                            @endforeach
                        </div>

                        <div class="flex-grow flex justify-end">
                            <button type="button" id="nextBtn" class="h-12 px-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-600/20 transition-all flex items-center gap-3">
                                Continue <i class="fas fa-arrow-right text-[8px]"></i>
                            </button>
                            <button type="submit" id="submitBtn" class="hidden h-12 px-10 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-3">
                                Submit Deployment <i class="fas fa-check-double text-[8px]"></i>
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
        prevBtn.disabled = currentIdx === 0;
        
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
    let warnings = parseInt("{{ $attempt->violations ?? 0 }}");
    const maxWarnings = 3;
    const warningDisplay = document.getElementById('warningCounter');
    const securityOverlay = document.getElementById('securityHUDEnded');
    
    function triggerWarning() {
        warnings++;
        if(warningDisplay) warningDisplay.textContent = warnings;

        fetch("{{ auth()->user()->role_id == 3 ? route('students.quizzes.violation', $attempt->id) : route('quizzes.violation', $attempt->id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(res => res.json()).then(data => {
            if (data.disqualified || warnings >= maxWarnings) {
                if(securityOverlay) {
                    securityOverlay.classList.remove('hidden');
                    securityOverlay.classList.add('flex');
                }
                setTimeout(() => { quizForm.submit(); }, 3000);
            } else {
                alert(`INSTITUTIONAL ALERT: Unauthorized window switching detected (#${warnings}/${maxWarnings}). Exceeding ${maxWarnings} violations results in immediate disqualification.`);
            }
        });
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
</style>
@endsection
