@extends('layouts.admin')

@section('topbar-title', 'Assessment Directory')

@section('content')
<div class="max-w-7xl mx-auto p-8 md:p-10 font-inter">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
                                                                        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight" style="font-family: 'Open Sans', Helvetica, Arial, sans-serif !important;">Master Data Quizzes</h1>
                                                <p class="text-sm text-slate-500 mt-1">Manage and track all assessments, midterms, and final exams.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.location.reload()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 shadow-sm">
                                                <i class="fas fa-sync-alt text-slate-400 text-xs"></i> Refresh
            </button>
            <a href="{{ route('quizzes.export') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 shadow-sm">
                                                <i class="fas fa-file-excel text-emerald-500 text-xs"></i> Export
            </a>
            <a href="{{ route('quizzes.create') }}" class="no-underline bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 shadow-lg shadow-indigo-600/20 active:scale-[0.98] group">
                <i class="fas fa-plus text-[10px] group-hover:rotate-90 transition-transform duration-300"></i>
                                                <span>Create Quiz</span>
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-blue-50 border border-emerald-200 text-blue-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-3 shadow-sm">
        <i class="fas fa-check-circle text-blue-500"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-3 shadow-sm">
        <i class="fas fa-exclamation-triangle text-rose-500"></i>
        {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-3 shadow-sm">
        <i class="fas fa-exclamation-circle text-rose-500 text-base"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <!-- Advanced Grid Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
        @forelse($quizzes as $item)
                                                                <div class="group bg-white rounded-3xl border border-slate-100 p-7 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 relative">
            <!-- Meatball Menu (Top Right) -->
            <div class="absolute top-7 right-7" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:bg-slate-50 hover:text-slate-600 transition-all">
                    <i class="fas fa-ellipsis-v text-sm"></i>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100" 
                     x-transition:enter-start="opacity-0 scale-95" 
                     x-transition:enter-end="opacity-100 scale-100" 
                     class="absolute right-0 mt-2 w-44 bg-white rounded-2xl shadow-xl border border-slate-100 z-30 py-2 overflow-hidden"
                     style="display: none;">
                    <a href="{{ route('quizzes.show', $item->id) }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-chart-line w-4"></i> View Intelligence
                    </a>
                    <a href="{{ route('quizzes.edit', $item->id) }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-pencil-alt w-4"></i> Edit Settings
                    </a>
                    <div class="h-px bg-slate-50 my-1"></div>
                    <button type="button" class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors btn-delete-quiz" data-id="{{ $item->id }}" data-title="{{ $item->title }}">
                        <i class="fas fa-trash-alt w-4"></i> Delete Module
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex flex-col h-full">
                <!-- Title Tier -->
                <div class="mb-5 pr-10">
                    <h3 class="text-3xl font-extrabold text-slate-900 leading-tight mb-2 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $item->title }}</h3>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.15em]">{{ $item->subject?->subject_name ?? 'Module' }}</span>
                        <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                        <div class="flex items-center gap-2 text-[11px] font-bold text-slate-600">
                            <i class="far fa-calendar-alt text-indigo-500 text-[10px]"></i>
                            <span>{{ \Carbon\Carbon::parse($item->opened_at)->format('M d') }} - {{ \Carbon\Carbon::parse($item->closed_at)->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Description (Clamped) -->
                <p class="text-[13px] font-medium text-slate-500 leading-relaxed line-clamp-2 mb-8">
                    {{ $item->description ?: 'No detailed instructional briefing has been provided for this assessment module.' }}
                </p>

                <!-- Bottom Tier: Stats Icons -->
                <div class="mt-auto flex items-center justify-between pt-6 border-t border-slate-50">
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2.5 text-slate-400 group/stat" title="Total Questions">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 group-hover/stat:bg-blue-500 group-hover/stat:text-white transition-all">
                                <i class="fas fa-list-ul text-[10px]"></i>
                            </div>
                            <span class="text-[11px] font-black tracking-tight text-slate-700">{{ $item->questions_count ?? 0 }} Items</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-slate-400 group/stat" title="Student Attempts">
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover/stat:bg-emerald-500 group-hover/stat:text-white transition-all">
                                <i class="fas fa-check-circle text-[10px]"></i>
                            </div>
                            <span class="text-[11px] font-black tracking-tight text-slate-700">{{ $item->attempts_count ?? 0 }} Enrolled</span>
                        </div>
                    </div>

                    @if($item->status == 'published')
                        <div class="flex items-center gap-2 px-3 py-1 bg-emerald-50 rounded-full border border-emerald-100">
                            <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                            <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Active</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-slate-50 rounded-[40px] border-2 border-dashed border-slate-200 p-20 text-center">
            <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-sm">
                <i class="fas fa-inbox text-slate-300 text-4xl"></i>
            </div>
                                                                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight mb-2" style="font-family: 'Open Sans', Helvetica, Arial, sans-serif !important;">No Quizzes Found</h3>
                                    <p class="text-slate-500 font-medium max-w-sm mx-auto mb-10 text-sm">You haven't added any quizzes yet. Start by creating your first quiz.</p>
            <a href="{{ route('quizzes.create') }}" class="inline-flex items-center gap-3 bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-3.5 rounded-xl font-semibold shadow-md transition-all">
                                                <i class="fas fa-plus"></i> Create Quiz
            </a>
        </div>
        @endforelse
    </div>
</div>

{{-- Single-delete hidden form: action is set by JS, @csrf and @method are permanent --}}
<form id="singleDeleteForm" method="POST" style="display:none;" action="">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
    // Helper: get fresh CSRF token from the meta tag (always valid)
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    // ─── Live Search ───────────────────────────────────────────────
    const searchEl = document.getElementById('tableSearch');
    if (searchEl) {
        searchEl.addEventListener('keyup', function () {
            const query = this.value.toLowerCase();
            document.querySelectorAll('#dataTable tbody .table-row').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
            });
        });
    }

    // ─── Select All ────────────────────────────────────────────────
    const selectAllEl = document.getElementById('selectAll');
    if (selectAllEl) {
        selectAllEl.addEventListener('change', function () {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
        });
    }

    // ─── Bulk Delete ───────────────────────────────────────────────
    // Uses a fresh temporary form every time so it never conflicts
    // with the single-delete form.
        window.premiumConfirm(
            'Are you sure you want to remove these ' + selected.length + ' quizzes? All student results for these quizzes will be lost forever.',
            function() {
                // Build a fresh temporary form using the meta CSRF token
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("quizzes.bulkDelete") }}';
                form.style.display = 'none';

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = getCsrfToken();
                form.appendChild(tokenInput);

                selected.forEach(function (cb) {
                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'ids[]';
                    idInput.value = cb.value;
                    form.appendChild(idInput);
                });

                document.body.appendChild(form);
                form.submit();
            },
            'Remove Multiple Quizzes?'
        );

    // ─── Single Delete ─────────────────────────────────────────────
    // Only mutates the `action` attribute — the @csrf / @method('DELETE')
    // inputs inside #singleDeleteForm are NEVER touched.
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-quiz');
        if (!btn) return;

        const id    = btn.dataset.id;
        const title = btn.dataset.title;

        window.premiumConfirm(
            'Permanently remove "' + title + '"? This will also delete all student answers and results for this quiz.',
            function() {
                const form = document.getElementById('singleDeleteForm');
                form.action = '{{ url("quizzes") }}/' + id;
                // Update CSRF token to fresh value
                const tokenInput = form.querySelector('input[name="_token"]');
                if (tokenInput) {
                    tokenInput.value = getCsrfToken();
                }
                form.submit();
            },
            'Remove Quiz?'
        );
    });
</script>
@endsection
