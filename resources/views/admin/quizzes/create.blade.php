@extends('layouts.admin')

@section('topbar-title', 'Create Assessment')

@section('content')
<div class="max-w-[1000px] mx-auto p-8 md:p-10 font-inter">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('quizzes.index') }}" class="w-10 h-10 rounded-full border border-slate-200/70 bg-white flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-colors shadow-sm">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Create New Quiz</h1>
                <p class="text-sm font-medium text-slate-500 mt-1">Design a new assessment and configure its rules.</p>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-3 shadow-sm">
        <i class="fas fa-exclamation-circle text-rose-500 text-base"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <!-- Form Card -->
    <form action="{{ route('quizzes.store') }}" method="POST" class="bg-white rounded-[20px] border border-slate-200/70 shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden">
        @csrf
        <div class="p-8 md:p-10 space-y-5">
            
            <!-- Standard Input Group -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Quiz Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Midterm Physics" 
                       class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="3" placeholder="Briefly describe the quiz..." 
                          class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 resize-none"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="relative">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Subject <span class="text-rose-500">*</span></label>
                    <select name="subject_id" required class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer">
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-[42px] text-[10px] text-slate-400 pointer-events-none"></i>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Time Limit (Minutes) <span class="text-rose-500">*</span></label>
                    <input type="number" name="time_limit" value="30" required min="1" 
                           class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200/70 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="relative">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none cursor-pointer">
                        <option value="draft">Draft (Hidden from students)</option>
                        <option value="published" selected>Published (Visible)</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-[42px] text-[10px] text-slate-400 pointer-events-none"></i>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pass Percentage (%)</label>
                    <input type="number" name="pass_percentage" value="60" min="0" max="100" 
                           class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200/70 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                </div>
            </div>

            <!-- Custom Toggle -->
            <label class="block p-5 border border-slate-200/70 rounded-xl bg-slate-50/30 flex items-start gap-4 hover:border-indigo-200 transition-colors cursor-pointer group">
                <div class="pt-0.5">
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="shuffle_questions" id="shuffleCheck" value="1" class="sr-only peer" checked>
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 group-hover:text-indigo-700 transition-colors">Shuffle Questions & Answers</h4>
                    <p class="text-[13px] text-slate-500 mt-0.5 leading-relaxed">Each student will see questions in a different random order (Full Option Security).</p>
                </div>
            </label>

        </div>
        
        <!-- Footer Actions -->
        <div class="px-6 md:px-8 py-5 border-t border-slate-100 bg-slate-50/30 flex items-center justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-sm flex items-center gap-2 {{ $subjects->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $subjects->isEmpty() ? 'disabled' : '' }}>
                Save & Open Builder <i class="fas fa-arrow-right text-xs"></i>
            </button>
        </div>
    </form>
</div>
@endsection
