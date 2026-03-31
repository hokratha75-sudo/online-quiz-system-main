@extends('layouts.admin')

@php
    /** @var \App\Models\Quiz $quiz */
@endphp

@section('topbar-title', 'Manage Assessment')

@section('content')
<div class="max-w-[1400px] mx-auto p-8 md:p-10 font-inter">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('quizzes.index') }}" class="w-10 h-10 rounded-full border border-slate-200/70 bg-white flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-colors shadow-sm">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">{{ $quiz->title }}</h1>
                <p class="text-sm font-medium text-slate-500 mt-1">Configure settings and manage quiz questions.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2" data-bs-toggle="modal" data-bs-target="#newQuestionModal">
                <i class="fas fa-plus text-xs"></i> Add Question
            </button>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-3 shadow-sm">
        <i class="fas fa-exclamation-circle text-rose-500 text-base"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Configuration Settings -->
        <div class="lg:col-span-1">
            <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST" class="bg-white rounded-[20px] border border-slate-200/70 shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden flex flex-col h-full">
                @csrf
                @method('PUT')
                
                <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex justify-center items-center">
                        <i class="fas fa-cog text-sm"></i>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 tracking-tight">Quiz Settings</h3>
                </div>

                <div class="p-6 space-y-5 flex-grow">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Quiz Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" value="{{ $quiz->title }}" required 
                               class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200/70 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200/70 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400 resize-none">{{ $quiz->description }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Subject <span class="text-rose-500">*</span></label>
                            <select name="subject_id" required class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200/70 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                                <option value="">Select...</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ $quiz->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-3.5 top-[35px] text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Time Limit <span class="text-slate-400 font-normal">(Min)</span></label>
                            <input type="number" name="time_limit" value="{{ $quiz->time_limit ?? 30 }}" required min="1" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200/70 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                            <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200/70 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                                <option value="draft" {{ $quiz->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $quiz->status == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3.5 top-[35px] text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pass Mark (%)</label>
                            <input type="number" name="pass_percentage" value="{{ $quiz->pass_percentage ?? 60 }}" required min="1" max="100" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200/70 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>
                    </div>

                    <!-- Custom Toggle -->
                    <label class="p-4 border border-slate-200/70 rounded-xl bg-slate-50/30 flex items-start gap-4 hover:border-indigo-200 transition-colors cursor-pointer group mt-2">
                        <div class="pt-0.5">
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="shuffle_questions" id="shuffleCheck" value="1" class="sr-only peer" {{ $quiz->shuffle_questions ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900 group-hover:text-indigo-700 transition-colors">Shuffle Questions</h4>
                            <p class="text-[12px] text-slate-500 mt-0.5 leading-relaxed">Display questions in random order for each attempt.</p>
                        </div>
                    </label>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex flex-col gap-3">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition-all shadow-sm flex justify-center items-center gap-2">
                        <i class="fas fa-save text-xs"></i> Save Settings
                    </button>
                    <button type="button" onclick="deleteQuiz()" class="w-full bg-white hover:bg-rose-50 border border-slate-200/80 hover:border-rose-200 text-rose-600 px-6 py-2.5 rounded-xl text-sm font-medium transition-all shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex justify-center items-center gap-2">
                        <i class="far fa-trash-alt text-xs"></i> Delete Quiz
                    </button>
                </div>
            </form>
        </div>

        <!-- Questions Builder -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[20px] border border-slate-200/70 shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden h-full flex flex-col">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex justify-center items-center">
                            <i class="fas fa-list-ul text-sm"></i>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 tracking-tight">Question Items (<span id="questionCount">{{ $quiz->questions->count() }}</span>)</h3>
                    </div>
                </div>

                <div class="p-6 flex-grow bg-slate-50/20">
                    @if(isset($quiz->questions) && $quiz->questions->count() > 0)
                        <div class="space-y-4" id="questionsAccordion">
                            @foreach($quiz->questions as $index => $question)
                                <!-- Alpine.js Accordion for modern interactivity without explicit bootstrap JS dependency -->
                                <div class="bg-white border text-sm border-slate-200/80 rounded-xl overflow-hidden shadow-[0_1px_2px_rgba(0,0,0,0.03)]" x-data="{ open: false }">
                                    <div class="flex items-center justify-between px-5 py-3.5 cursor-pointer hover:bg-slate-50/50 transition-colors" @click="open = !open">
                                        <div class="flex items-center gap-4 flex-grow truncate">
                                            <span class="w-7 h-7 rounded-md bg-indigo-50 text-indigo-700 flex items-center justify-center text-xs font-bold shrink-0">{{ $index + 1 }}</span>
                                            <span class="font-medium text-slate-800 truncate">{!! \Illuminate\Support\Str::limit(strip_tags($question->content), 80) !!}</span>
                                        </div>
                                        <div class="flex items-center gap-4 pl-4 shrink-0">
                                            <div class="hidden sm:flex items-center gap-3 text-xs">
                                                <span class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-100 font-semibold">{{ $question->points }} pts</span>
                                                <span class="text-slate-500 border border-slate-200 px-2 py-0.5 rounded">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</span>
                                            </div>
                                            <!-- Action Button -->
                                            <button type="button" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors delete-question-btn shrink-0" data-id="{{ $question->id }}" @click.stop="deleteQuestion($event)">
                                                <i class="far fa-trash-alt text-sm"></i>
                                            </button>
                                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                                        </div>
                                    </div>
                                    
                                    <div x-show="open" x-collapse x-cloak>
                                        <div class="px-5 pb-5 pt-2 border-t border-slate-100 bg-slate-50/30">
                                            <div class="text-sm text-slate-600 mb-4 prose prose-sm max-w-none prose-slate">
                                                {!! $question->content !!}
                                            </div>
                                            <ul class="space-y-2">
                                                @foreach($question->answers as $answer)
                                                    <li class="flex items-center justify-between px-4 py-2.5 rounded-lg border {{ $answer->is_correct ? 'bg-emerald-50 border-emerald-200/70' : 'bg-white border-slate-200/70' }}">
                                                        <span class="text-sm {{ $answer->is_correct ? 'text-emerald-800 font-medium' : 'text-slate-600' }}">{{ $answer->answer_text }}</span>
                                                        @if($answer->is_correct)
                                                            <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-600 bg-white px-2 py-1 rounded shadow-sm border border-emerald-100">
                                                                <i class="fas fa-check"></i> Correct
                                                            </div>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="flex flex-col items-center justify-center py-16 px-4 bg-white border-2 border-dashed border-slate-200 rounded-2xl text-center h-full">
                            <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mb-4 text-indigo-400">
                                <i class="fas fa-clipboard-question text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-slate-900 tracking-tight">No Questions Yet</h4>
                            <p class="text-sm text-slate-500 mt-1.5 max-w-sm">Start building your assessment by adding your first multiple choice or true/false question.</p>
                            <button type="button" class="mt-6 bg-white hover:bg-slate-50 text-indigo-600 border border-indigo-200 hover:border-indigo-300 px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm" data-bs-toggle="modal" data-bs-target="#newQuestionModal">
                                + Create First Question
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.quizzes.components.question_modal')

<style>
/* Alpine cloak */
[x-cloak] { display: none !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Note: ensure Alpine.js is included in layout!
    
    // Delete Quiz Protocol
    window.deleteQuiz = function() {
        if (!confirm('Are you absolutely certain you want to delete this ENTIRE quiz? All questions and student attempts will be permanently erased.')) return;
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('quizzes.destroy', $quiz->id) }}";
        form.innerHTML = `@csrf @method('DELETE')`;
        document.body.appendChild(form);
        form.submit();
    };

    // Listen for delete question globally using event delegation
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-question-btn');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!confirm('Are you sure you want to discard this question?')) return;
            
            const id = btn.dataset.id;
            const url = `/questions/${id}`;
            const row = btn.closest('[x-data]'); // Finds the alpine accordion item
            
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (row) {
                        row.remove();
                        // Update counter
                        const countEl = document.getElementById('questionCount');
                        if (countEl) countEl.innerText = parseInt(countEl.innerText) - 1;
                        
                        // If 0, reload to show empty state
                        if (parseInt(countEl.innerText) === 0) window.location.reload();
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert('Error: ' + (data.message || 'Could not delete question'));
                }
            })
            .catch(err => {
                console.error(err);
                alert('A network error occurred.');
            });
        }
    });
});
</script>
@endsection
