@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto p-8 md:p-10 font-inter text-slate-900">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight uppercase flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-xl shadow-indigo-600/20 leading-none">
                    <i class="fas fa-database text-xl"></i>
                </div>
                Resource Library
            </h1>
            <p class="text-[10px] font-bold text-indigo-600 mt-2 uppercase tracking-widest leading-none">Manage and reuse questions across all subjects</p>
        </div>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap" id="bankTable">
                <thead>
                    <tr class="bg-slate-50/10 border-b border-slate-50">
                        <th width="80" class="ps-8 py-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Question Ref#</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Question Content</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">From Which Quiz</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Type</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Score Value</th>
                        <th width="120" class="pe-8 py-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Manage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($questions as $question)
                        <tr class="hover:bg-slate-50/50 transition-all group">
                            <td class="ps-8 py-4 text-[10px] font-bold text-slate-400 tabular-nums">#{{ $question->id }}</td>
                            <td class="px-6 py-4">
                                <div class="max-w-[500px]">
                                    <p class="text-sm font-bold text-slate-900 line-clamp-1 mb-0 uppercase tracking-tight">
                                        {!! strip_tags($question->content) !!}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-2 text-[10px] font-bold text-indigo-600 uppercase tracking-widest opacity-70">
                                    <i class="fas fa-link text-[8px]"></i>
                                    {{ $question->quiz->title ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-[9px] font-bold uppercase tracking-widest bg-slate-50 text-slate-600 border border-slate-100/50">
                                    {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-xs font-bold text-slate-900 tabular-nums">{{ $question->points }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Points</span>
                                </div>
                            </td>
                            <td class="pe-8 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="w-8 h-8 rounded-lg border border-slate-100 bg-white text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all shadow-sm flex items-center justify-center">
                                        <i class="fas fa-eye text-[10px]"></i>
                                    </button>
                                    <button type="button" class="w-8 h-8 rounded-lg border border-slate-100 bg-white text-slate-400 hover:text-rose-600 hover:border-rose-100 hover:bg-rose-50 transition-all shadow-sm flex items-center justify-center btn-delete-question" data-id="{{ $question->id }}">
                                        <i class="fas fa-trash text-[10px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-32 text-center">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                                    <i class="fas fa-database text-2xl"></i>
                                </div>
                                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Library is Empty</h4>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Hidden Delete Form --}}
<form id="deleteQuestionForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>

<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete-question');
        if (!btn) return;

        window.premiumConfirm(
            'Are you sure you want to remove this question from the library? This will not affect existing quizzes, but the question will be gone from the bank.',
            function() {
                const id = btn.dataset.id;
                const form = document.getElementById('deleteQuestionForm');
                form.action = `/admin/questions/${id}/destroy`; 
                form.submit();
            },
            'Remove Question?'
        );
    });
</script>
@endsection
