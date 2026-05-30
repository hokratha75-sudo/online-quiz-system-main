@extends('layouts.admin')

@php
    /** @var \App\Models\Quiz $quiz */
@endphp

@section('topbar-title', 'Manage Assessment')

@section('content')
<style>
    /* Custom Scrollbar for sleek aesthetic */
    .custom-scrollbar::-webkit-scrollbar { width: 7px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #4f46e5; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #4f46e5; }
    
    /* Alpine cloak */
    [x-cloak] { display: none !important; }
</style>

<div class="max-w-[1400px] mx-auto p-8 md:p-10 font-inter">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.quizzes.index') }}" class="w-12 h-12 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-all shadow-sm no-underline">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">{{ $quiz->title }}</h1>
                <p class="text-sm font-medium text-slate-500 mt-1">Edit quiz settings and manage questions</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-lg flex items-center gap-2 border-none" data-bs-toggle="modal" data-bs-target="#newQuestionModal">
                <i class="fas fa-plus text-[10px]"></i> Add Question
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
            <form action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST" class="bg-white rounded-[20px] border border-slate-200/70 shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden flex flex-col h-full">
                @csrf
                @method('PUT')
                
                <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex justify-center items-center shadow-sm">
                        <i class="fas fa-cog text-[10px]"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-900 tracking-widest uppercase">Quiz Settings</h3>
                </div>

                <div class="p-6 space-y-6 flex-grow">
                    <div>
                        <label class="block text-xs font-bold tracking-widest text-indigo-600 uppercase mb-3">Quiz Title</label>
                        <input type="text" name="title" value="{{ $quiz->title }}" required 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold tracking-widest text-indigo-600 uppercase mb-3">Description</label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none resize-none">{{ $quiz->description }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="relative">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Subject <span class="text-rose-500">*</span></label>
                            <select name="subject_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none appearance-none cursor-pointer">
                                <option value="">Select...</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ $quiz->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-3.5 top-[42px] text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Time Limit <span class="text-slate-400 font-normal">(Min)</span></label>
                            <input type="number" name="time_limit" value="{{ $quiz->time_limit ?? 30 }}" required min="1" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="relative">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                            <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none appearance-none cursor-pointer">
                                <option value="draft" {{ $quiz->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $quiz->status == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3.5 top-[42px] text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pass Mark (%)</label>
                            <input type="number" name="pass_percentage" value="{{ $quiz->pass_percentage ?? 60 }}" required min="1" max="100" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                        </div>
                    </div>

                    <label class="p-6 border border-slate-100 rounded-2xl bg-white flex items-center gap-5 hover:border-indigo-500 transition-all cursor-pointer group mt-4 shadow-sm">
                        <div class="pt-0.5">
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="shuffle_questions" id="shuffleCheck" value="1" class="sr-only peer" {{ $quiz->shuffle_questions ? 'checked' : '' }}>
                                <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-widest group-hover:text-indigo-600 transition-all">Shuffle Questions</h4>
                            <p class="text-[10px] text-slate-500 mt-0.5 leading-relaxed">Randomize the question order each time a student takes the quiz</p>
                        </div>
                    </label>

                    {{-- Deadline Section --}}
                    <div class="mt-4 rounded-2xl border border-amber-100 bg-amber-50/60 p-5 space-y-4">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-calendar-alt text-amber-500 text-xs"></i>
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-widest">Quiz Availability</h4>
                            <span class="text-[10px] text-slate-400 font-medium ml-auto">Optional</span>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                <i class="fas fa-door-open text-emerald-500 mr-1"></i> Opens At
                            </label>
                            <input type="datetime-local" name="opened_at"
                                   value="{{ $quiz->opened_at ? \Carbon\Carbon::parse($quiz->opened_at)->format('Y-m-d\TH:i') : '' }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                <i class="fas fa-door-closed text-rose-500 mr-1"></i> Closes At
                            </label>
                            <input type="datetime-local" name="closed_at"
                                   value="{{ $quiz->closed_at ? \Carbon\Carbon::parse($quiz->closed_at)->format('Y-m-d\TH:i') : '' }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                        </div>
                        <p class="text-[10px] text-slate-400 leading-relaxed">
                            <i class="fas fa-info-circle mr-0.5"></i>
                            Students can only attempt this quiz within the set time window. Leave blank for no restriction.
                        </p>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex flex-col gap-3">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm flex justify-center items-center gap-2 border-none">
                        <i class="fas fa-save text-xs"></i> Save Settings
                    </button>
                    <button type="button" onclick="deleteQuiz()" class="w-full bg-white hover:bg-rose-50 border border-slate-200/80 hover:border-rose-200 text-rose-600 px-6 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex justify-center items-center gap-2">
                        <i class="far fa-trash-alt text-xs"></i> Delete Quiz
                    </button>
                </div>
            </form>
        </div>

        <!-- Questions Builder -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[20px] border border-slate-200/70 shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden h-full flex flex-col">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex justify-center items-center shadow-sm">
                            <i class="fas fa-list-ul text-[10px]"></i>
                        </div>
                        <h3 class="text-xs font-bold text-slate-900 tracking-widest uppercase">Questions (<span id="questionCount" class="text-indigo-600 tabular-nums">{{ $quiz->questions->count() }}</span> total)</h3>
                    </div>
                </div>

                <div class="p-6 flex-grow bg-slate-50/20">
                    @if(isset($quiz->questions) && $quiz->questions->count() > 0)
                        <div class="space-y-4" id="questionsAccordion">
                            @foreach($quiz->questions as $index => $question)
                                <div class="bg-white border text-sm border-slate-200/80 rounded-xl overflow-hidden shadow-[0_1px_2px_rgba(0,0,0,0.03)]" x-data="{ open: false }">
                                    <div class="flex items-center justify-between px-4 py-3.5 cursor-pointer hover:bg-slate-50/50 transition-colors" @click="open = !open">
                                        <div class="flex items-center gap-4 flex-grow truncate">
                                            <span class="w-7 h-7 rounded-md bg-indigo-50 text-indigo-700 flex items-center justify-center text-xs font-bold shrink-0">{{ $index + 1 }}</span>
                                            <span class="font-medium text-slate-800 truncate">{!! \Illuminate\Support\Str::limit(strip_tags($question->content), 80) !!}</span>
                                        </div>
                                        <div class="flex items-center gap-4 pl-4 shrink-0">
                                            <div class="hidden sm:flex items-center gap-3 text-xs">
                                                <span class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-100 font-semibold">{{ $question->points }} pts</span>
                                                <span class="text-slate-500 border border-slate-200 px-2 py-0.5 rounded">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</span>
                                            </div>
                                            <button type="button"
                                                onclick="editQuestion(this)"
                                                class="edit-question-btn w-8 h-8 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 flex items-center justify-center transition-colors border-none"
                                                data-id="{{ $question->id }}"
                                                data-content="{{ addslashes(strip_tags($question->content)) }}"
                                                data-type="{{ $question->type }}"
                                                data-points="{{ $question->points }}"
                                                data-options='@json($question->answers->pluck("answer_text")->values())'
                                                data-correct='@json($question->answers->pluck("is_correct")->values())'>
                                                <i class="fas fa-pen text-sm"></i>
                                            </button>
                                            <button type="button" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors delete-question-btn shrink-0 border-none" data-id="{{ $question->id }}">
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

<!-- Add Question Modal -->
<div class="modal fade" id="newQuestionModal" tabindex="-1" aria-labelledby="newQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div id="draggableBox" class="modal-content rounded border-none shadow-2xl overflow-hidden flex flex-col h-[90vh] w-[1000px]">
            
            <div class="bg-gradient-to-r from-[#5f60ef] to-[#9a4ce7] px-6 py-2 text-white relative shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 blur-3xl rounded-full"></div>
                <div class="flex items-center justify-between relative z-10 mt-2">
                    <div>
                        <h3 class="text-xl font-bold tracking-tight" id="newQuestionModalLabel">Add New Question</h3>
                        <p class="text-[11px] font-medium text-indigo-200 mt-0.5">Fill in the details below, then click Save</p>
                    </div>
                    <button type="button" class="group relative w-10 h-10 rounded-full bg-white/20 border-none text-white hover:bg-white/30 focus:outline-none" data-bs-dismiss="modal">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="flex-1 px-6 py-4 space-y-2 overflow-y-auto custom-scrollbar min-h-0">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Question Type</label>
                        <div class="relative">
                            <select id="questionTypeDropdown" class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none appearance-none cursor-pointer">
                                <option value="single_choice">Single Choice — one correct answer</option>
                                <option value="multiple_choice">Multiple Choice — many correct answers</option>
                                <option value="true_false">True / False</option>
                                <option value="short_answer">Short Answer — graded manually by teacher</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Points</label>
                        <input type="number" id="questionPoints"
                               class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none"
                               value="1" min="1">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Question Text <span class="text-rose-500">*</span></label>
                    <textarea id="questionEditor"
                              class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none min-h-[80px] resize-none"
                              placeholder="Type your question here..."></textarea>
                </div>

                <div id="shortAnswerNote" class="hidden bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 flex items-start gap-3">
                    <i class="fas fa-pen-to-square text-blue-400 mt-0.5 text-sm shrink-0"></i>
                    <p class="text-xs font-medium text-blue-700 leading-relaxed">
                        Students will type their own answer. The teacher must review and assign points manually after submission.
                    </p>
                </div>

                <div id="optionsSection">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-xs font-semibold text-slate-500">Answer Options <span class="text-rose-500">*</span></label>
                        <button type="button" id="addOptionBtn" class="group inline-flex items-center gap-2 border-none bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white px-10 py-2.5 rounded-lg text-[11px] font-black transition-all duration-200 shadow-lg shadow-indigo-500/20 active:scale-95 uppercase tracking-wider">
                            <i class="fas fa-plus text-[10px]"></i> Add Option
                        </button>
                    </div>
                    
                    <div id="optionsContainer" class="space-y-2.5"></div>
                </div>

                <div class="px-4 py-3 border border-slate-100 rounded-xl bg-slate-50/50">
                    <div class="flex items-center justify-between group cursor-pointer" onclick="document.getElementById('isReusableCheck').click()">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 shadow-sm flex items-center justify-center text-indigo-500">
                                <i class="fas fa-bookmark text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-slate-800">Save to Question Bank</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">Reuse this question in other quizzes later</p>
                            </div>
                        </div>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="isReusableCheck" class="sr-only peer">
                            <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col md:flex-row items-center gap-2.5 shrink-0">
                <input type="hidden" id="quizId" value="{{ $quiz->id }}">
                <button type="button" class="w-full md:w-auto px-5 h-10 rounded-xl text-sm font-semibold text-slate-500 hover:text-slate-900 hover:bg-white transition-all border-none" data-bs-dismiss="modal">Cancel</button>
                <div class="flex-grow"></div>
                <button type="button" id="saveAndAddBtn" class="w-full md:w-auto px-5 h-10 bg-white border border-slate-200 hover:border-indigo-300 hover:text-indigo-600 rounded-xl text-sm font-semibold transition-all shadow-sm">
                    <i class="fas fa-plus text-xs mr-1.5"></i>Save &amp; Add Another
                </button>
                <button type="button" id="saveQuestionBtn" class="w-full md:w-auto px-6 h-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-all shadow-md shadow-indigo-600/20 border-none">
                    <i class="fas fa-check text-xs mr-1.5"></i>Save Question
                </button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================
    // DOM ELEMENTS
    // =========================
    const modal = document.getElementById('newQuestionModal');
    const box = document.getElementById('draggableBox');
    const header = modal.querySelector('.bg-gradient-to-r');

    const questionEditor = document.getElementById('questionEditor');
    const questionPoints = document.getElementById('questionPoints');
    const typeDropdown = document.getElementById('questionTypeDropdown');
    const optionsSection = document.getElementById('optionsSection');
    const optionsContainer = document.getElementById('optionsContainer');
    const addOptionBtn = document.getElementById('addOptionBtn');
    const shortAnswerNote = document.getElementById('shortAnswerNote');

    const saveQuestionBtn = document.getElementById('saveQuestionBtn');
    const saveAndAddBtn = document.getElementById('saveAndAddBtn');
    const quizId = document.getElementById('quizId').value;

    // Track edit state
    let editMode = false;
    let editQuestionId = null;

    // =========================
    // DRAG MODAL
    // =========================
    let dragging = false;
    let offsetX = 0;
    let offsetY = 0;

    function centerModal() {
        if (!box) return;
        box.style.position = 'fixed';
        box.style.left = '50%';
        box.style.top = '50%';
        box.style.transform = 'translate(-50%, -50%)';
    }

    centerModal();
    if (header) header.style.cursor = 'grab';

    if (header) {
        header.addEventListener('mousedown', (e) => {
            if (e.target.closest('button')) return;
            dragging = true;
            header.style.cursor = 'grabbing';
            const rect = box.getBoundingClientRect();
            offsetX = e.clientX - rect.left;
            offsetY = e.clientY - rect.top;
            document.body.style.userSelect = 'none';
        });
    }

    document.addEventListener('mousemove', (e) => {
        if (!dragging || !box) return;
        let left = e.clientX - offsetX;
        let top = e.clientY - offsetY;
        const maxX = window.innerWidth - box.offsetWidth;
        const maxY = window.innerHeight - box.offsetHeight;
        left = Math.max(0, Math.min(left, maxX));
        top = Math.max(0, Math.min(top, maxY));
        box.style.left = left + 'px';
        box.style.top = top + 'px';
        box.style.transform = 'none';
    });

    document.addEventListener('mouseup', () => {
        dragging = false;
        if (header) header.style.cursor = 'grab';
        document.body.style.userSelect = '';
    });

    if (modal) {
        modal.addEventListener('show.bs.modal', centerModal);
    }

    // =========================
    // CREATE OPTION HTML
    // =========================
    function createOptionHtml(index, value = '', isCorrect = false) {
        const div = document.createElement('div');
        div.className = 'flex items-center gap-3 option-row group';
        const letter = String.fromCharCode(65 + index);
        div.innerHTML = `
            <div class="flex-grow flex items-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-3 text-slate-300"><i class="far fa-circle text-[10px]"></i></div>
                <input type="text" class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none" placeholder="Option ${letter}..." value="${escapeHtml(value)}" />
                <div class="px-4 border-l border-slate-200 bg-slate-50/50">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="correct-checkbox w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500" ${isCorrect ? 'checked' : ''} />
                        <span class="text-[10px] font-semibold text-slate-400 group-hover:text-emerald-600 transition-all whitespace-nowrap">Correct</span>
                    </label>
                </div>
            </div>
            <button type="button" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-300 hover:text-rose-500 remove-option"><i class="fas fa-times text-xs"></i></button>
        `;
        return div;
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function resetToCreateMode() {
        editMode = false;
        editQuestionId = null;
        const modalLabel = document.getElementById('newQuestionModalLabel');
        if (modalLabel) modalLabel.innerHTML = '<i class="fas fa-building"></i> Add New Question';
        if (questionEditor) questionEditor.value = '';
        if (questionPoints) questionPoints.value = '1';
        if (typeDropdown) typeDropdown.value = 'single_choice';
        const isReusableCheck = document.getElementById('isReusableCheck');
        if (isReusableCheck) isReusableCheck.checked = false;
        if (shortAnswerNote) shortAnswerNote.classList.add('hidden');
        if (optionsSection) optionsSection.style.display = 'block';
        if (optionsContainer) {
            optionsContainer.innerHTML = '';
            optionsContainer.appendChild(createOptionHtml(0, '', false));
            optionsContainer.appendChild(createOptionHtml(1, '', false));
        }
    }

    // =========================
    // EDIT QUESTION
    // =========================
    window.editQuestion = (btn) => {
        editMode = true;
        editQuestionId = btn.dataset.id;
        
        const modalLabel = document.getElementById('newQuestionModalLabel');
        if (modalLabel) modalLabel.innerHTML = '<i class="fas fa-edit"></i> Edit Question';
        
        const content = btn.dataset.content || '';
        const type = btn.dataset.type || 'single_choice';
        const points = btn.dataset.points || '1';
        
        let options = [], correct = [];
        try {
            options = JSON.parse(btn.dataset.options || '[]');
            correct = JSON.parse(btn.dataset.correct || '[]');
        } catch(e) { 
            console.error('Parse error:', e); 
        }
        
        if (questionEditor) questionEditor.value = content;
        if (questionPoints) questionPoints.value = points;
        if (typeDropdown) typeDropdown.value = type;
        
        if (type === 'short_answer') {
            if (shortAnswerNote) shortAnswerNote.classList.remove('hidden');
            if (optionsSection) optionsSection.style.display = 'none';
            if (optionsContainer) optionsContainer.innerHTML = '';
        } else {
            if (shortAnswerNote) shortAnswerNote.classList.add('hidden');
            if (optionsSection) optionsSection.style.display = 'block';
            if (optionsContainer) {
                optionsContainer.innerHTML = '';
                if (options.length === 0) {
                    optionsContainer.appendChild(createOptionHtml(0, '', false));
                    optionsContainer.appendChild(createOptionHtml(1, '', false));
                } else {
                    options.forEach((opt, i) => {
                        const isCorrectFlag = correct[i] === true || correct[i] === 1 || correct[i] === '1';
                        optionsContainer.appendChild(createOptionHtml(i, opt, isCorrectFlag));
                    });
                }
            }
        }
        
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    };

    // =========================
    // ADD / REMOVE OPTION
    // =========================
    if (addOptionBtn) {
        addOptionBtn.addEventListener('click', () => {
            if (!optionsContainer) return;
            const count = optionsContainer.querySelectorAll('.option-row').length;
            optionsContainer.appendChild(createOptionHtml(count, '', false));
        });
    }
    
    if (optionsContainer) {
        optionsContainer.addEventListener('click', (e) => {
            if (e.target.closest('.remove-option')) {
                const row = e.target.closest('.option-row');
                if (optionsContainer.querySelectorAll('.option-row').length > 1) row.remove();
            }
        });
    }

    // =========================
    // TYPE CHANGE
    // =========================
    if (typeDropdown) {
        typeDropdown.addEventListener('change', function() {
            if (this.value === 'short_answer') {
                if (shortAnswerNote) shortAnswerNote.classList.remove('hidden');
                if (optionsSection) optionsSection.style.display = 'none';
                if (optionsContainer) optionsContainer.innerHTML = '';
            } else {
                if (shortAnswerNote) shortAnswerNote.classList.add('hidden');
                if (optionsSection) optionsSection.style.display = 'block';
                if (optionsContainer && optionsContainer.querySelectorAll('.option-row').length === 0) {
                    optionsContainer.appendChild(createOptionHtml(0, '', false));
                    optionsContainer.appendChild(createOptionHtml(1, '', false));
                }
            }
        });
    }

    // =========================
    // SAVE QUESTION
    // =========================
    window.saveQuestion = async function(stayOpen = false) {
        const questionText = questionEditor?.value.trim();
        const points = questionPoints?.value;
        const type = typeDropdown?.value;
        const isReusable = document.getElementById('isReusableCheck')?.checked || false;
        
        if (!questionText) {
            alert('Please enter question text');
            return;
        }
        
        let options = [], correct = [];
        
        if (type !== 'short_answer' && optionsContainer) {
            const rows = optionsContainer.querySelectorAll('.option-row');
            rows.forEach((row) => {
                const text = row.querySelector('input[type="text"]')?.value.trim();
                const checked = row.querySelector('.correct-checkbox')?.checked;
                if (text && text !== '') {
                    options.push(text);
                    if (checked) correct.push(options.length - 1);
                }
            });
            
            options = options.filter(opt => opt.trim() !== '');
            
            if (options.length < 2) {
                alert('Need at least 2 non-empty options');
                return;
            }
            if (correct.length === 0) {
                alert('Select at least 1 correct answer');
                return;
            }
        }
        
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        let url;
        let formData = new FormData();
        
        if (editMode === true && editQuestionId) {
            url = `/questions/${editQuestionId}`;
            formData.append('_method', 'PUT');
        } else {
            url = "{{ route('questions.store') }}";
        }
        
        formData.append('_token', CSRF);
        formData.append('quiz_id', quizId);
        formData.append('content', questionText);
        formData.append('points', points);
        formData.append('type', type);
        formData.append('is_reusable', isReusable ? 1 : 0);
        
        options.forEach((o, i) => formData.append(`options[${i}]`, o));
        correct.forEach((c, i) => formData.append(`correct[${i}]`, c));
        
        if (saveQuestionBtn) {
            saveQuestionBtn.disabled = true;
            saveQuestionBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        }
        if (saveAndAddBtn) saveAndAddBtn.disabled = true;
        
        try {
            const response = await fetch(url, { 
                method: 'POST', 
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                if (response.status === 422 && data.errors) {
                    const errorList = Object.values(data.errors).flat().join('\n- ');
                    alert('Validation Error:\n- ' + errorList);
                } else {
                    alert(data.message || 'Error saving question');
                }
                return;
            }
            
            if (data.success) {
                if (stayOpen) {
                    resetToCreateMode();
                    alert('Question saved successfully!');
                } else {
                    location.reload();
                }
            } else {
                alert(data.message || 'Error saving question');
            }
        } catch (error) {
            console.error('Save error:', error);
            alert('Network error: ' + error.message);
        } finally {
            if (saveQuestionBtn) {
                saveQuestionBtn.disabled = false;
                saveQuestionBtn.innerHTML = '<i class="fas fa-check"></i> Save Question';
            }
            if (saveAndAddBtn) saveAndAddBtn.disabled = false;
        }
    };
    
    if (saveQuestionBtn) {
        saveQuestionBtn.addEventListener('click', () => window.saveQuestion(false));
    }
    if (saveAndAddBtn) {
        saveAndAddBtn.addEventListener('click', () => window.saveQuestion(true));
    }
    
    if (modal) {
        modal.addEventListener('hidden.bs.modal', resetToCreateMode);
    }
    
    // =========================
    // DELETE QUESTION
    // =========================
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.delete-question-btn');
        if (!btn) return;
        if (!confirm('Delete this question?')) return;
        
        const id = btn.dataset.id;
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        
        try {
            const res = await fetch(`/questions/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await res.json();
            if (!data.success) return alert(data.message || 'Delete failed');
            
            const item = btn.closest('[x-data]');
            if (item) item.remove();
            const countEl = document.getElementById('questionCount');
            if (countEl) {
                countEl.innerText = parseInt(countEl.innerText || 0) - 1;
            }
        } catch (err) { 
            console.error(err);
            alert('Delete failed');
        }
    });
    
    // =========================
    // DELETE QUIZ
    // =========================
    window.deleteQuiz = () => {
        if (!confirm('Delete this quiz?')) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.quizzes.destroy', $quiz->id) }}";
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(form);
        form.submit();
    };
    
    resetToCreateMode();
});
</script>
@endsection