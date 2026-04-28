@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto p-8 md:p-10 font-inter text-slate-900 bg-slate-50/30 min-h-screen">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                Question Bank
            </h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Centralized management and reuse of student assessment questions</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('questions.export') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-file-export text-slate-400"></i> Export CSV
            </a>
            <a href="{{ route('quizzes.create') }}" class="bg-indigo-600 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-lg shadow-indigo-200">
                <i class="fas fa-plus text-[10px]"></i> Create Question
            </a>
        </div>
    </div>

    <!-- Quick Insights: Question Composition Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @php $total = $stats['total'] ?: 1; @endphp
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Multiple Choice</span>
            <div class="flex items-end gap-2">
                <span class="text-xl font-bold text-slate-900 leading-none">{{ round(($stats['mc']/$total)*100) }}%</span>
                <span class="text-xs font-medium text-slate-400 mb-0.5">of bank</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Single Choice</span>
            <div class="flex items-end gap-2">
                <span class="text-xl font-bold text-slate-900 leading-none">{{ round(($stats['sc']/$total)*100) }}%</span>
                <span class="text-xs font-medium text-slate-400 mb-0.5">of bank</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">True / False</span>
            <div class="flex items-end gap-2">
                <span class="text-xl font-bold text-slate-900 leading-none">{{ round(($stats['tf']/$total)*100) }}%</span>
                <span class="text-xs font-medium text-slate-400 mb-0.5">of bank</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Assets</span>
            <div class="flex items-end gap-2 text-indigo-600">
                <span class="text-xl font-bold leading-none">{{ $stats['total'] }}</span>
                <span class="text-xs font-medium mb-0.5">Verified Items</span>
            </div>
        </div>
    </div>

    <!-- Controls & Filters -->
    <form action="{{ route('questions.bank') }}" method="GET" id="filterForm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-6">
            <div class="flex items-center p-1 bg-slate-100 rounded-xl w-fit">
                <input type="radio" name="type" value="all" id="type_all" class="hidden peer/all" {{ request('type', 'all') == 'all' ? 'checked' : '' }} onchange="this.form.submit()">
                <label for="type_all" class="px-4 py-2 text-xs font-bold cursor-pointer rounded-lg transition-all {{ request('type', 'all') == 'all' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">All Questions</label>

                <input type="radio" name="type" value="multiple_choice" id="type_mc" class="hidden peer/mc" {{ request('type') == 'multiple_choice' ? 'checked' : '' }} onchange="this.form.submit()">
                <label for="type_mc" class="px-4 py-2 text-xs font-bold cursor-pointer rounded-lg transition-all {{ request('type') == 'multiple_choice' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Multiple Choice</label>

                <input type="radio" name="type" value="true_false" id="type_tf" class="hidden peer/tf" {{ request('type') == 'true_false' ? 'checked' : '' }} onchange="this.form.submit()">
                <label for="type_tf" class="px-4 py-2 text-xs font-bold cursor-pointer rounded-lg transition-all {{ request('type') == 'true_false' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">True/False</label>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative group">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs group-focus-within:text-indigo-600 transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="SEARCH CONTENT..." 
                           class="h-10 pl-11 pr-4 bg-white border border-slate-200 text-[10px] font-bold text-slate-900 outline-none focus:border-indigo-500 rounded-xl shadow-sm uppercase tracking-widest placeholder:text-slate-300 w-48 lg:w-64">
                </div>

                <div class="relative">
                    <select name="subject_id" onchange="this.form.submit()" class="h-10 pl-4 pr-10 bg-white border border-slate-200 rounded-xl text-[10px] font-bold text-slate-600 appearance-none outline-none focus:border-indigo-500 transition-all cursor-pointer shadow-sm uppercase tracking-widest">
                        <option value="">ALL SUBJECTS</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>

                <button type="button" id="bulkDeleteBtn" style="display: none;" class="h-10 px-4 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-trash-can"></i> Delete Selected
                </button>
            </div>
        </div>
    </form>

    <!-- Content Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap" id="bankTable">
                <thead>
                    <tr class="bg-slate-50/30 border-b border-slate-100">
                        <th width="40" class="ps-8 py-5">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-slate-200 text-indigo-600 focus:ring-indigo-500/20 shadow-sm cursor-pointer">
                        </th>
                        <th class="px-6 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Question Content</th>
                        <th width="140" class="px-6 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Subject</th>
                        <th width="140" class="px-6 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Type</th>
                        <th width="100" class="px-6 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Score</th>
                        <th width="180" class="pe-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($questions as $question)
                        <tr class="hover:bg-slate-50/30 transition-all group">
                            <td class="ps-8 py-4">
                                <input type="checkbox" class="question-checkbox w-4 h-4 rounded border-slate-200 text-indigo-600 focus:ring-indigo-500/20 shadow-sm cursor-pointer" value="{{ $question->id }}">
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-[500px]">
                                    <p class="text-sm font-bold text-slate-900 line-clamp-1 mb-1 tracking-tight">
                                        {!! strip_tags($question->content) !!}
                                    </p>
                                    <div class="flex items-center gap-3">
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[9px] font-bold rounded uppercase tracking-wider">
                                            {{ $question->quiz->title ?? 'N/A' }}
                                        </span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Added {{ $question->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wide bg-amber-50 text-amber-700 px-3 py-1 rounded-full border border-amber-100">
                                    {{ $question->quiz->subject->subject_name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $typeStyles = [
                                        'multiple_choice' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                        'single_choice' => 'bg-teal-50 text-teal-600 border-teal-100',
                                        'true_false' => 'bg-amber-50 text-amber-600 border-amber-100'
                                    ];
                                    $style = $typeStyles[$question->type] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $style }} border">
                                    {{ str_replace('_', ' ', $question->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-bold text-slate-900 tabular-nums">{{ $question->points }} pts</span>
                            </td>
                            <td class="pe-8 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="w-9 h-9 rounded-xl bg-[#1e212d] text-white hover:bg-indigo-600 transition-all shadow-md flex items-center justify-center" title="View Question">
                                        <i class="fas fa-eye text-[11px]"></i>
                                    </button>
                                    <button type="button" class="w-9 h-9 rounded-xl border border-slate-200 bg-white text-slate-400 hover:text-rose-600 hover:border-rose-100 hover:bg-rose-50 transition-all flex items-center justify-center btn-delete-question" data-id="{{ $question->id }}" title="Delete">
                                        <i class="fas fa-trash-can text-[11px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-32 text-center">
                                <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-[24px] flex items-center justify-center mx-auto mb-6 text-slate-200">
                                    <i class="fas fa-database text-3xl"></i>
                                </div>
                                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No questions found matching your criteria</h4>
                                <p class="text-xs text-slate-400 mt-2">Try adjusting your filters or search terms.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Footer -->
        <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs font-medium text-slate-500">
                Showing {{ $questions->firstItem() }} to {{ $questions->lastItem() }} of {{ $questions->total() }} results
            </p>
            <div class="flex items-center gap-2">
                {{ $questions->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Hidden Delete Forms --}}
<form id="deleteQuestionForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<form id="bulkDeleteForm" method="POST" style="display:none;" action="{{ route('questions.bulkDelete') }}">
    @csrf
</form>

<script>
    // Bulk Select Toggle
    const selectAll = document.getElementById('selectAll');
    const questionCheckboxes = document.querySelectorAll('.question-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    function updateBulkBtn() {
        const checkedCount = document.querySelectorAll('.question-checkbox:checked').length;
        bulkDeleteBtn.style.display = checkedCount > 0 ? 'flex' : 'none';
    }
    
    if(selectAll) {
        selectAll.addEventListener('change', function() {
            questionCheckboxes.forEach(cb => cb.checked = this.checked);
            updateBulkBtn();
        });
    }

    questionCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBtn);
    });

    // Bulk Delete Action
    bulkDeleteBtn.addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.question-checkbox:checked')).map(cb => cb.value);
        if (selected.length === 0) return;

        window.premiumConfirm(
            'Are you sure you want to permanently remove these ' + selected.length + ' questions? This action cannot be undone.',
            function() {
                const form = document.getElementById('bulkDeleteForm');
                // Clear previous hidden inputs
                form.querySelectorAll('input[name="ids[]"]').forEach(input => input.remove());
                // Add new hidden inputs
                selected.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                form.submit();
            },
            'Bulk Delete Questions?'
        );
    });

    // Delete Interaction
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete-question');
        if (!btn) return;

        window.premiumConfirm(
            'Are you sure you want to remove this question? This will not affect existing quizzes, but the question will be gone from the bank.',
            function() {
                const id = btn.dataset.id;
                const form = document.getElementById('deleteQuestionForm');
                form.action = `/questions/${id}`; 
                form.submit();
            },
            'Remove Question?'
        );
    });
</script>
@endsection
