@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto p-6 md:p-10 font-inter text-slate-900 bg-slate-50/30 min-h-screen">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight leading-none">Academic Subjects</h1>
            <p class="text-sm font-medium text-slate-400 mt-2">Manage your course list and learning modules.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.subjects.export') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-6 py-3 rounded-2xl text-xs font-bold transition-all flex items-center gap-3 shadow-sm uppercase tracking-widest">
                <i class="far fa-file-export text-slate-400 text-sm"></i> Export
            </a>
            <button data-bs-toggle="modal" data-bs-target="#addSubjectModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-7 py-3 rounded-2xl text-xs font-bold transition-all flex items-center gap-3 shadow-xl shadow-indigo-600/20 active:scale-[0.98] uppercase tracking-widest">
                <i class="far fa-plus"></i> New Subject
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-10 bg-white border border-emerald-100 rounded-[24px] p-6 flex items-center justify-between shadow-xl shadow-emerald-500/5 relative overflow-hidden">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500"></div>
        <div class="flex items-center gap-5">
            <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100">
                <i class="far fa-circle-check text-emerald-500 text-lg"></i>
            </div>
            <div>
                <h4 class="text-base font-bold text-slate-900 leading-tight">Success</h4>
                <p class="text-sm font-medium text-slate-400 mt-1">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="text-slate-300 hover:text-slate-500 transition-colors px-4" onclick="this.parentElement.style.display='none'">
            <i class="far fa-xmark text-lg"></i>
        </button>
    </div>
    @endif

    <!-- Search & Control Bar -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-8 mb-10">
        <div class="relative w-full md:w-[480px] group">
            <i class="far fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
            <input type="text" id="subjectSearch" placeholder="SEARCH SUBJECTS..." 
                   class="w-full h-14 pl-14 pr-6 bg-white border border-slate-200 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-slate-300 rounded-[20px] shadow-sm uppercase tracking-widest">
        </div>
        
        <div class="flex items-center gap-6">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-6 py-2.5 bg-slate-100 rounded-full">
                {{ $subjects->count() }} Total Units
            </span>
            <div class="flex items-center gap-3">
                <button onclick="editSelected()" class="w-11 h-11 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-500 transition-all shadow-sm">
                    <i class="far fa-pen-to-square text-lg"></i>
                </button>
                <button onclick="deleteSelected()" class="w-11 h-11 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:border-rose-500 transition-all shadow-sm">
                    <i class="far fa-trash-can text-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Subject Table (Standard Clean Style) -->
    <div class="card-standard">
        <div class="card-header-standard">
            <h3>Academic Subject Directory</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="table-standard" id="subjectTable">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-slate-300 text-indigo-600">
                        </th>
                        <th style="width: 60px;">#</th>
                        <th>Subject Name & Code</th>
                        <th>Department</th>
                        <th style="width: 200px;">Progress (Students)</th>
                        <th style="width: 100px;">Label</th>
                        <th style="width: 150px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $index => $item)
                    <tr class="subject-row" data-title="{{ strtoupper($item->subject_name) }}" data-code="{{ strtoupper($item->code) }}">
                        <td style="text-align: center;">
                            <input type="checkbox" class="row-checkbox w-4 h-4 rounded border-slate-300 text-indigo-600" 
                                   value="{{ $item->id }}" data-name="{{ $item->subject_name }}" data-department="{{ $item->department_id }}" 
                                   data-major="{{ $item->major_id }}" data-classes="{{ json_encode($item->classes->pluck('id')->toArray()) }}">
                        </td>
                        <td>{{ $index + 1 }}.</td>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900">{{ $item->subject_name }}</span>
                                <span class="text-[11px] text-slate-400 font-bold tracking-widest">{{ $item->code ?? 'SUB-'.$item->id }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-[12px] font-medium text-slate-600">{{ $item->department->department_name ?? 'General' }}</span>
                        </td>
                        <td>
                            @php
                                $enrollmentRate = $item->enrollments_count > 0 ? min(($item->enrollments_count / 50) * 100, 100) : 0; // Simulated
                                $barColor = $enrollmentRate > 80 ? 'bg-indigo-500' : ($enrollmentRate > 50 ? 'bg-blue-500' : 'bg-slate-400');
                            @endphp
                            <div class="flex items-center gap-3">
                                <div class="progress-clean flex-1">
                                    <div class="progress-bar-clean {{ $barColor }}" style="width: {{ $enrollmentRate }}%"></div>
                                </div>
                                <span class="text-[11px] font-bold text-slate-500 w-8 text-right">{{ $item->enrollments_count ?? 0 }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="label-standard label-blue">CREDITS: {{ $item->credits ?? 3 }}</span>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.subjects.show', $item->id) }}" class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all shadow-sm" title="View">
                                    <i class="far fa-eye text-sm"></i>
                                </a>
                                <button onclick="editRecord({{ $item->id }}, '{{ addslashes($item->subject_name) }}', {{ $item->department_id }}, {{ $item->major_id ?? 'null' }}, {{ json_encode($item->classes->pluck('id')) }})"
                                        class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all shadow-sm" title="Edit">
                                    <i class="far fa-pen-to-square text-sm"></i>
                                </button>
                                <button onclick="deleteRecord({{ $item->id }}, '{{ addslashes($item->subject_name) }}')"
                                        class="w-9 h-9 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all shadow-sm" title="Delete">
                                    <i class="far fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-slate-400 font-medium">No subjects cataloged in the academic directory.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100 flex justify-end">
            <div class="pagination-clean">
                <a href="#"><i class="far fa-chevron-left text-[10px]"></i></a>
                <span class="active">1</span>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#"><i class="far fa-chevron-right text-[10px]"></i></a>
            </div>
        </div>
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
    // Modern Search Logic (Table Row Based)
    const subjectSearch = document.getElementById('subjectSearch');
    const subjectRows = document.querySelectorAll('.subject-row');

    subjectSearch.addEventListener('keyup', function() {
        const query = this.value.toUpperCase();
        subjectRows.forEach(row => {
            const title = row.getAttribute('data-title');
            const code = row.getAttribute('data-code');
            const hasMatch = title.includes(query) || code.includes(query);
            row.style.display = hasMatch ? '' : 'none';
        });
    });

    // Select All Logic
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => {
                if (rowIsVisible(cb)) {
                    cb.checked = selectAll.checked;
                }
            });
        });
    }

    function rowIsVisible(checkbox) {
        return checkbox.closest('tr').style.display !== 'none';
    }

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
