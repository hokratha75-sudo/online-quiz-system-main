@extends('layouts.admin')

@section('topbar-title', 'Assessment Directory')

@section('content')
<style>
    /* Custom Scrollbar for sleek aesthetic */
    .custom-scrollbar::-webkit-scrollbar { width: 7px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #4f46e5; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #4f46e5; }
</style>
<div class="w-full max-w-none sm:max-w-[1600px] mx-auto py-3 sm:py-4 md:py-8 px-3 sm:px-5 md:px-8 font-sans text-slate-800 bg-gradient-to-br from-slate-50 via-white to-slate-100/50 min-h-[calc(100vh-530px)]">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-4 md:gap-6 mb-4 sm:mb-6">
        <div class="min-w-0 flex-1">
            <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-slate-900 tracking-tight uppercase truncate" style="font-family: 'Open Sans', Helvetica, Arial, sans-serif !important;">Master Data Quizzes</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 truncate">Manage and track all assessments, midterms, and final exams.</p>
        </div>
        <div class="flex items-center gap-1 sm:gap-2 md:gap-4 flex-wrap justify-end sm:flex-nowrap">
            <button onclick="window.location.reload()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-3 sm:px-5 py-1.5 sm:py-2.5 rounded-lg text-[9px] sm:text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-1.5 sm:gap-2 shadow-sm flex-shrink-0">
                <i class="fas fa-rotate text-slate-400 text-xs sm:text-sm"></i> 
                <span class="hidden sm:inline">Refresh</span>
            </button>
            <a href="{{ route('quizzes.export') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-3 sm:px-5 py-1.5 sm:py-2.5 rounded-lg text-[9px] sm:text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-1.5 sm:gap-2 shadow-sm no-underline flex-shrink-0">
                <i class="far fa-file-excel text-emerald-500 text-xs sm:text-sm"></i> 
                <span class="hidden sm:inline">Export</span>
            </a>
            <a href="{{ route('quizzes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg text-[9px] sm:text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-1.5 sm:gap-2 shadow-lg shadow-indigo-600/20 active:scale-[0.98] border-none no-underline flex-shrink-0 whitespace-nowrap">
                <i class="far fa-plus"></i>
                <span class="hidden sm:inline">Create Quiz</span>
                <span class="sm:hidden">New</span>
            </a>
        </div>
    </div>

    

    <!-- Quiz Table (Standard Clean Style) -->
    <div class="card-standard">
        <!-- Filter & Search Bar (Modern Style from Image) -->
    <div class="flex flex-col gap-3 sm:gap-4 md:gap-6 p-3 sm:p-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 md:gap-6">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-4 md:gap-6 w-full md:flex-1">
                <div class="relative w-full sm:flex-1 max-w-xs group">
                    <i class="fas fa-search absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-indigo-600 text-[8px] sm:text-[10px]"></i>
                    <input type="text" id="quizSearch" placeholder="Search quizzes.." 
                           class="w-full pl-8 sm:pl-10 pr-3 sm:pr-4 py-2 sm:py-2.5 bg-slate-50 border border-slate-200 rounded-lg sm:rounded-xl text-xs font-medium placeholder:text-slate-300 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                </div>
                
                <div class="flex items-center gap-2 sm:gap-4 w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <div class="relative w-full">
        
                        <!-- Selected -->
                        <button 
                            type="button"
                            onclick="toggleSubjects()"
                            class="w-full flex items-center justify-between px-3 sm:px-4 py-2 sm:py-2.5 bg-white border border-slate-200 rounded-lg sm:rounded-xl text-xs font-medium placeholder:text-slate-300 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none truncate"
                        >
                            <span id="selectedSubject" class="text-xs font-semibold text-slate-700 truncate">
                                ALL SUBJECTS
                            </span>

                            <i class="fas fa-chevron-down text-xs text-slate-400 flex-shrink-0 ml-2"></i>
                        </button>

                        <!-- Dropdown -->
                        <div 
                            id="subjectsDropdown"
                            class="hidden absolute z-50 mt-2 w-full bg-white border border-slate-200 rounded-lg sm:rounded-2xl shadow-xl overflow-hidden"
                        >
                        <div 
                            onclick="selectSubject('', 'ALL SUBJECTS')"
                            class="px-3 sm:px-4 py-2 sm:py-3 text-xs text-slate-700 hover:bg-indigo-50 cursor-pointer transition"
                        >
                            ALL SUBJECTS
                        </div>

                        @foreach($subjects as $subject)
                            <div 
                            onclick="selectSubject('{{ $subject->id }}', '{{ strtoupper($subject->subject_name) }}')"
                            class="px-4 py-3 text-xs text-slate-700 hover:bg-indigo-50 cursor-pointer transition border-t border-slate-100"
                        >
                            {{ strtoupper($subject->subject_name) }}
                        </div>
                    @endforeach
                </div>
                <!-- Hidden Input -->
                <input type="hidden" name="subject_id" id="subjectInput">
                </div>
                </div>
                
                <div class="relative w-full md:w-56">
    
    <!-- Selected -->
    <button
        type="button"
        onclick="toggleStatusDropdown()"
        class="w-full flex items-center justify-between px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium placeholder:text-slate-300 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none"
    >
        <span id="selectedStatus" class="text-xs font-semibold text-slate-700">ALL STATUSES</span>

        <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
    </button>

    <!-- Dropdown -->
    <div
        id="statusDropdown"
        class="hidden absolute z-50 mt-2 w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl"
    >

        <div
            onclick="selectStatus('', 'ALL STATUSES')"
            class="px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-600 hover:bg-indigo-50 cursor-pointer transition-all"
        >
            ALL STATUSES
        </div>

        <div
            onclick="selectStatus('published', 'ACTIVE')"
            class="border-t border-slate-100 px-5 py-3 text-xs font-semibold uppercase tracking-[0.15em] text-emerald-600 hover:bg-emerald-50 cursor-pointer transition-all"
        >
            ACTIVE
        </div>

        <div
            onclick="selectStatus('draft', 'DRAFT')"
            class="border-t border-slate-100 px-5 py-3 text-xs font-semibold uppercase tracking-[0.15em] text-amber-600 hover:bg-amber-50 cursor-pointer transition-all"
        >
            DRAFT
        </div>

    </div>

    <!-- Hidden Input -->
    <input type="hidden" id="statusFilter" name="status">

</div>
            </div>
        </div>
        
        <div class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-4 py-2 bg-indigo-100 rounded-full">
            <span id="quizCount">{{ $quizzes->count() }}</span> Assessments
        </div>
    </div>
        <div class="overflow-x-auto">
            <div class="h-[calc(100vh-370px)]  overflow-y-auto custom-scrollbar">
            <table class="table-standard" id="quizTable">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="py-3">#</th>
                        <th>Quiz Module & Subject</th>
                        <th style="width: 250px;">Engagement (Attempts)</th>
                        <th style="width: 100px;">Label</th>
                        <th style="width: 150px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizzes as $index => $item)
                    <tr class="quiz-row" data-subject="{{ $item->subject_id }}" data-status="{{ $item->status }}" data-title="{{ strtolower($item->title) }}">
                        <td>{{ $index + 1 }}.</td>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900">{{ $item->title }}</span>
                                <span class="text-[11px] text-slate-400 font-medium tracking-tight uppercase">{{ $item->subject?->subject_name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $attemptRate = $item->attempts_count > 0 ? min(($item->attempts_count / 100) * 100, 100) : 0; // Simulated for demo
                                $barColor = $attemptRate > 80 ? 'bg-green-500' : ($attemptRate > 50 ? 'bg-blue-500' : ($attemptRate > 20 ? 'bg-yellow-500' : 'bg-rose-500'));
                            @endphp
                            <div class="flex items-center gap-3">
                                <div class="progress-clean flex-1">
                                    <div class="progress-bar-clean {{ $barColor }}" style="width: {{ $attemptRate }}%"></div>
                                </div>
                                <span class="text-[11px] font-bold text-slate-500 w-8 text-right">{{ $item->attempts_count }}</span>
                            </div>
                        </td>
                        <td>
                            @if($item->status == 'published')
                                <span class="label-standard label-green">ACTIVE</span>
                            @else
                                <span class="label-standard label-yellow">DRAFT</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('quizzes.show', $item->id) }}" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all shadow-sm" title="View">
                                    <i class="far fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('quizzes.edit', $item->id) }}" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all shadow-sm" title="Edit">
                                    <i class="far fa-pen-to-square text-sm"></i>
                                </a>
                                <button type="button" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all shadow-sm btn-delete-quiz" 
                                        data-id="{{ $item->id }}" data-title="{{ $item->title }}" title="Delete">
                                    <i class="far fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-slate-400 font-medium">No quiz modules found in this directory.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
        
        <!-- Footer -->
@if($quizzes->hasPages())
<div class="px-4 py-3 border-t border-slate-100 bg-slate-50 flex flex-col md:flex-row items-center justify-between gap-4">

    <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest tabular-nums">
        Showing
        {{ $quizzes->firstItem() ?? 0 }}
        -
        {{ $quizzes->lastItem() ?? 0 }}
        of
        {{ $quizzes->total() }}
        Assessments
    </span>

    <div class="custom-pagination flex items-center">
        {{ $quizzes->links('pagination::bootstrap-5') }}
    </div>

</div>
@else
<div class="px-4 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between">

    <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">
        Displaying all
        {{ $quizzes->count() }}
        Assessments
    </span>

</div>
@endif
<style>
/* layout fix */
.custom-pagination nav {
    background: transparent !important;
}

.custom-pagination nav > div:first-child {
    display: none;
}

.custom-pagination nav > div:last-child {
    display: flex;
    justify-content: flex-end;
    align-items: center;
}

/* remove text */
.custom-pagination nav p {
    display: none;
}

/* reset pagination */
.custom-pagination .pagination {
    margin: 0 !important;
}

/* base button */
.custom-pagination .page-link {
    padding: 7px 10px !important;
    font-size: 15px;
    line-height: 1.2;

    border: 1px solid #e5e7eb !important;

    box-shadow: none !important;
    outline: none !important;
}

/* hover */
.custom-pagination .page-link:hover {
    background: #eef2ff;
}

/* active */
.custom-pagination .page-item.active .page-link {
    background: #4f46e5;
    color: white;
    border: 1px solid #4f46e5 !important;
    box-shadow: none !important;
}

/* click/focus fix (IMPORTANT) */
.custom-pagination .page-link:focus,
.custom-pagination .page-link:focus-visible {
    outline: none !important;
    box-shadow: none !important;
}

/* remove bootstrap weird inline wrapper shadow */
.custom-pagination nav .relative.inline-flex {
    box-shadow: none !important;
}
</style>    
    </div>
</div>

{{-- Single-delete hidden form --}}
<form id="singleDeleteForm" method="POST" style="display:none;" action="">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>

document.addEventListener('DOMContentLoaded',()=>{

    // ===========================
    // ELEMENTS
    // ===========================

    const searchInput=
    document.getElementById(
    'quizSearch'
    );

    const subjectFilter=
    document.getElementById(
    'subjectInput'
    );

    const statusFilter=
    document.getElementById(
    'statusFilter'
    );

    const countDisplay=
    document.getElementById(
    'quizCount'
    );

    const tableBody=
    document.querySelector(
    '#quizTable tbody'
    );

    let quizRows=[
        ...document.querySelectorAll(
        '.quiz-row'
        )
    ];



    // ===========================
    // TOAST
    // ===========================

    function toast(message,type='success'){

        let div=
        document.createElement(
        'div'
        );

        div.className=`
        fixed top-5 right-5 z-[9999]
        px-5 py-3 rounded-xl
        text-white font-semibold
        shadow-xl transition-all
        duration-500
        ${type==='success'
        ?'bg-emerald-500'
        :'bg-rose-500'}
        `;

        div.innerHTML=message;

        document.body.appendChild(
        div
        );

        setTimeout(()=>{

            div.classList.add(
            'opacity-0',
            'translate-x-10'
            );

        },2500);

        setTimeout(()=>{

            div.remove();

        },3000);

    }



    // ===========================
    // SEARCH DEBOUNCE
    // ===========================

    function debounce(
        func,
        delay
    ){

        let timer;

        return function(){

            clearTimeout(timer);

            timer=setTimeout(
                ()=>func(),
                delay
            );

        };

    }



    // ===========================
    // EMPTY ROW
    // ===========================

    function emptyState(){

        let existing=
        document.getElementById(
        'emptyResultRow'
        );

        if(existing){

            existing.remove();

        }

        const visible=
        quizRows.filter(
        row=>
        row.style.display!==
        'none'
        );

        if(
            visible.length===0
        ){

            const tr=
            document.createElement(
            'tr'
            );

            tr.id=
            'emptyResultRow';

            tr.innerHTML=`

            <td colspan="5"
            class="py-12 text-center">

            <div class="flex flex-col items-center">

            <i class="fas fa-folder-open text-3xl text-slate-300 mb-3"></i>

            <p class="font-semibold text-slate-500">
            No quizzes found
            </p>

            </div>

            </td>

            `;

            tableBody.appendChild(
            tr
            );

        }

    }



    // ===========================
    // FILTER
    // ===========================

    function applyFilters(){

        const search=
        searchInput.value
        .toLowerCase()
        .trim();

        const subject=
        subjectFilter.value;

        const status=
        statusFilter.value;

        let visibleCount=0;
        let rowNumber=1;

        quizRows.forEach(row=>{

            let title=
            row.dataset.title||'';

            let subjectId=
            row.dataset.subject||'';

            let statusData=
            row.dataset.status||'';

            let subjectText=
            row.querySelector(
            'span.text-\\[11px\\]'
            )
            ?.innerText
            .toLowerCase()
            ||'';

            let matchSearch=

            title.includes(search)
            ||
            subjectText.includes(
            search
            );

            let matchSubject=

            subject==='' ||
            subject===subjectId;

            let matchStatus=

            status==='' ||
            status===statusData;

            if(
            matchSearch &&
            matchSubject &&
            matchStatus
            ){

                row.style.display='';

                row.style.opacity=1;

                row.cells[0]
                .innerText=
                rowNumber++ +'.';

                visibleCount++;

            }

            else{

                row.style.display=
                'none';

            }

        });

        countDisplay.innerText=
        visibleCount;

        emptyState();

    }



    searchInput.addEventListener(

    'input',

    debounce(
    applyFilters,
    250
    )

    );



    // ===========================
    // SUBJECT
    // ===========================

    window.toggleSubjects=
    ()=>{

        document
        .getElementById(
        'subjectsDropdown'
        )
        .classList.toggle(
        'hidden'
        );

    };


    window.selectSubject=
    (id,name)=>{

        document
        .getElementById(
        'selectedSubject'
        )
        .innerText=name;

        subjectFilter.value=id;

        document
        .getElementById(
        'subjectsDropdown'
        )
        .classList.add(
        'hidden'
        );

        applyFilters();

    };



    // ===========================
    // STATUS
    // ===========================

    window.toggleStatusDropdown=
    ()=>{

        document
        .getElementById(
        'statusDropdown'
        )
        .classList.toggle(
        'hidden'
        );

    };


    window.selectStatus=
    (value,label)=>{

        statusFilter.value=
        value;

        document
        .getElementById(
        'selectedStatus'
        )
        .innerText=
        label;

        document
        .getElementById(
        'statusDropdown'
        )
        .classList.add(
        'hidden'
        );

        applyFilters();

    };



    // ===========================
    // CLICK OUTSIDE
    // ===========================

    document.addEventListener(
    'click',
    e=>{

        if(
        !e.target.closest(
        '#subjectsDropdown,#selectedSubject'
        )
        ){

            document
            .getElementById(
            'subjectsDropdown'
            )
            .classList.add(
            'hidden'
            );

        }

        if(
        !e.target.closest(
        '#statusDropdown,#selectedStatus'
        )
        ){

            document
            .getElementById(
            'statusDropdown'
            )
            .classList.add(
            'hidden'
            );

        }

    });



    // ===========================
    // DELETE
    // ===========================

    let deleting=false;

    document.addEventListener(
    'click',
    function(e){

        const btn=
        e.target.closest(
        '.btn-delete-quiz'
        );

        if(
        !btn ||
        deleting
        ) return;

        deleting=true;

        const id=
        btn.dataset.id;

        const title=
        btn.dataset.title;

        window.premiumConfirm(

        `Delete "${title}"? Student attempts and results will also be removed.`,

        function(){

            const form=
            document.getElementById(
            'singleDeleteForm'
            );

            form.action=
            `{{ url('quizzes') }}/${id}`;

            form.submit();

        },

        'Delete Quiz'

        );

        setTimeout(()=>{

            deleting=false;

        },1000);

    });



    // initial

    applyFilters();

});

</script>
@endsection