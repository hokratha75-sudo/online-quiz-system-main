@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto p-6 md:p-10 font-inter text-slate-900 bg-slate-50/30 min-h-screen">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-10">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Academic Subjects</h1>
            <p class="text-[14px] font-medium text-slate-500 mt-1.5">Manage your course list and learning modules.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.subjects.export') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all flex items-center gap-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-100">
                <i class="fas fa-file-export text-slate-400"></i> Export
            </a>
            <button data-bs-toggle="modal" data-bs-target="#addSubjectModal" class="bg-indigo-600 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[13px] font-bold transition-all flex items-center gap-2 shadow-lg shadow-indigo-200 active:scale-[0.98]">
                <i class="fas fa-plus text-indigo-200 text-[10px]"></i> New Subject
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-white border border-emerald-100 rounded-2xl p-3.5 flex items-center justify-between shadow-sm relative overflow-hidden">
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
        <div class="flex items-center gap-3.5">
            <div class="w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100">
                <i class="fas fa-check text-emerald-500 text-xs"></i>
            </div>
            <div>
                <h4 class="text-[13px] font-bold text-slate-900 leading-tight">Success</h4>
                <p class="text-[11px] font-medium text-slate-500">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="text-slate-400 hover:text-slate-600 px-2" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
    @endif

    <!-- Search & Control Bar -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-8">
        <div class="relative w-full md:w-96 group">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs group-focus-within:text-indigo-600 transition-colors"></i>
            <input type="text" id="subjectSearch" placeholder="SEARCH SUBJECTS..." 
                   class="w-full h-12 pl-11 pr-4 bg-white border border-slate-200 text-xs font-bold text-slate-900 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all placeholder:text-slate-300 rounded-2xl shadow-sm uppercase tracking-widest">
        </div>
        
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest px-4 mr-2 border-r border-slate-200">
                {{ $subjects->count() }} total units
            </span>
            <div class="flex items-center gap-2">
                <button onclick="editSelected()" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm">
                    <i class="fas fa-pen-nib"></i>
                </button>
                <button onclick="deleteSelected()" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Subject List-->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="subjectGrid">
        @forelse($subjects as $item)
        <div class="subject-card group bg-white rounded-[30px] border border-slate-100 shadow-sm p-6 hover:shadow-2xl hover:shadow-slate-200/40 transition-all duration-500 flex flex-col relative overflow-hidden h-full"
             data-title="{{ strtoupper($item->subject_name) }}" data-code="{{ strtoupper($item->code) }}">
            
            <!-- Selection Checkbox (Top Left Overlay) -->
            <div class="absolute top-5 left-5 z-10">
                <input type="checkbox" class="row-checkbox w-5 h-5 rounded-md border-slate-200 text-indigo-600 focus:ring-indigo-500/20 cursor-pointer transition-all bg-white shadow-sm" 
                       value="{{ $item->id }}" data-name="{{ $item->subject_name }}" data-department="{{ $item->department_id }}" 
                       data-major="{{ $item->major_id }}" data-classes="{{ json_encode($item->classes->pluck('id')->toArray()) }}">
            </div>

            <!-- Top Section: Header -->
            <div class="flex items-start justify-end mb-5">
                <div class="flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full border border-emerald-100 text-[9px] font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    ACTIVE
                </div>
            </div>

            <!-- Center Section: Brand Identity -->
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 mb-4 group-hover:scale-105 transition-transform duration-500 border border-indigo-100/50">
                    <i class="fas fa-book-open text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-3 px-2 group-hover:text-indigo-600 transition-colors line-clamp-1">{{ $item->subject_name }}</h3>
                
                <div class="flex items-center gap-2">
                    <span class="px-3 py-0.5 bg-slate-50 border border-slate-100 text-[9px] font-bold text-slate-400 rounded-full tracking-wider uppercase">{{ $item->code ?? 'SUB-' . $item->id }}</span>
                    <span class="px-3 py-0.5 bg-slate-50 border border-slate-100 text-[9px] font-bold text-slate-400 rounded-full tracking-wider uppercase">{{ $item->credits ?? '3' }} CREDITS</span>
                </div>
            </div>

            <!-- Data Dashboard Box -->
            <div class="bg-slate-50/50 rounded-2xl p-4 grid grid-cols-2 gap-y-4 gap-x-2 mb-6 border border-slate-100">
                <div>
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Department</span>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-[9px] text-slate-300"></i>
                        <span class="text-[11px] font-bold text-slate-600 truncate">{{ $item->department->department_name ?? 'General' }}</span>
                    </div>
                </div>
                <div>
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Students</span>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-users text-[9px] text-slate-300"></i>
                        <span class="text-[11px] font-bold text-slate-600">{{ $item->enrollments_count ?? 0 }} enrolled</span>
                    </div>
                </div>
                <div>
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Instructor</span>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-user-tie text-[9px] text-slate-300"></i>
                        <span class="text-[11px] font-bold text-slate-600">Not assigned</span>
                    </div>
                </div>
                <div>
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Quizzes</span>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-double text-[9px] text-slate-300"></i>
                        <span class="text-[11px] font-bold text-slate-600">{{ $item->quizzes_count ?? 0 }} created</span>
                    </div>
                </div>
            </div>

            <!-- Card Actions -->
            <div class="pt-5 border-t border-slate-100 flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <button onclick="editRecord({{ $item->id }}, '{{ addslashes($item->subject_name) }}', {{ $item->department_id }}, {{ $item->major_id ?? 'null' }}, {{ json_encode($item->classes->pluck('id')) }})"
                            class="w-10 h-10 rounded-xl border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition-all flex items-center justify-center" title="Edit Unit">
                        <i class="fas fa-pen-to-square text-xs"></i>
                    </button>
                    <button onclick="deleteRecord({{ $item->id }}, '{{ addslashes($item->subject_name) }}')"
                            class="w-10 h-10 rounded-xl border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center" title="Remove Unit">
                        <i class="fas fa-trash-can text-xs"></i>
                    </button>
                </div>
                
                <a href="{{ route('admin.subjects.show', $item->id) }}" class="flex-1 h-10 bg-[#1e212d] hover:bg-indigo-600 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all group/btn">
                    <span>View course</span>
                    <i class="fas fa-arrow-right text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-32 flex flex-col items-center justify-center bg-white rounded-[40px] border border-dashed border-slate-200">
            <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mb-6">
                <i class="fas fa-inbox text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2 tracking-tight">No Subjects Cataloged</h3>
            <p class="text-slate-500 text-sm mb-8">Start your academic directory by adding your first unit.</p>
            <button data-bs-toggle="modal" data-bs-target="#addSubjectModal" class="bg-indigo-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-100">
                New Subject
            </button>
        </div>
        @endforelse
    </div>
</div>

{{-- Single-delete hidden form --}}
<form id="singleDeleteForm" method="POST" style="display:none;" action="">
    @csrf
    @method('DELETE')
</form>

@include('admin.subjects.modals')

@endsection

@section('scripts')
<script>
    // Modern Search Logic (Card Based)
    const subjectSearch = document.getElementById('subjectSearch');
    const subjectCards = document.querySelectorAll('.subject-card');

    subjectSearch.addEventListener('keyup', function() {
        const query = this.value.toUpperCase();
        subjectCards.forEach(card => {
            const title = card.getAttribute('data-title');
            const code = card.getAttribute('data-code');
            const hasMatch = title.includes(query) || code.includes(query);
            card.style.display = hasMatch ? '' : 'none';
        });
    });

    function editRecord(id, name, dept_id, major_id, class_ids) {
        const modal = document.getElementById('addSubjectModal');
        const form = modal.querySelector('form');
        form.action = `/admin/subjects/${id}`;
        
        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';

        modal.querySelector('input[name="subject_name"]').value = name;
        modal.querySelector('select[name="department_id"]').value = dept_id;
        
        if (typeof updateMajors === 'function') updateMajors(dept_id, major_id);
        
        const classSelect = modal.querySelector('select[name="classes[]"]');
        if (classSelect) {
            Array.from(classSelect.options).forEach(opt => opt.selected = class_ids.includes(parseInt(opt.value)));
        }
        
        modal.querySelector('#modalTitle').innerHTML = '<i class="fas fa-edit text-indigo-200"></i> Edit Subject Details';
        modal.querySelector('button[type="submit"]').textContent = 'Save Changes';
        
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    function deleteSelected() {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select at least one unit for mutation.');
            return;
        }

        window.premiumConfirm(
            'Are you sure you want to remove these ' + selected.length + ' subjects? This data cannot be recovered and will affect student records.',
            function() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.subjects.bulkDelete") }}';
                form.style.display = 'none';

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = getCsrfToken();
                form.appendChild(tokenInput);

                selected.forEach(cb => {
                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'ids[]';
                    idInput.value = cb.value;
                    form.appendChild(idInput);
                });

                document.body.appendChild(form);
                form.submit();
            },
            'Remove Multiple Subjects?'
        );
    }

    function editSelected() {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        if (selected.length !== 1) {
            alert('Identify exactly one unit for mutation.');
            return;
        }
        const cb = selected[0];
        const data = cb.dataset;
        editRecord(cb.value, data.name, data.department, data.major, JSON.parse(data.classes));
    }

    function deleteRecord(id, name) {
        window.premiumConfirm(
            'Are you sure you want to permanently remove "' + name + '"? This will impact all quizzes and enrollments associated with this subject.',
            function() {
                const form = document.getElementById('singleDeleteForm');
                form.action = '/admin/subjects/' + id;
                const tokenInput = form.querySelector('input[name="_token"]');
                if (tokenInput) tokenInput.value = getCsrfToken();
                form.submit();
            },
            'Remove Subject Module?'
        );
    }
</script>
@endsection
