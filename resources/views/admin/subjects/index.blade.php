@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto p-6 md:p-10 font-inter text-slate-900">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-8">
        <div>
            <h1 class="text-2xl md:text-[28px] font-bold text-slate-900 tracking-tight">Academic Subjects</h1>
            <p class="text-[14px] font-medium text-slate-500 mt-1.5">Manage subject control, modules and distribution matrix.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.subjects.export') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2.5 rounded-xl text-[13px] font-semibold transition-all flex items-center gap-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-100">
                <i class="fas fa-file-excel text-emerald-500"></i> Export
            </a>
            <button data-bs-toggle="modal" data-bs-target="#addSubjectModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[13px] font-semibold transition-all flex items-center gap-2 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <i class="fas fa-plus text-indigo-200 text-xs"></i> New Subject
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-8 bg-white border border-emerald-100 rounded-[20px] p-4 flex items-center justify-between shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden transition-all duration-300">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500"></div>
        <div class="flex items-center gap-4 ml-2">
            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100/50">
                <i class="fas fa-check text-emerald-500 text-sm"></i>
            </div>
            <div>
                <h4 class="text-[14px] font-bold text-slate-900 tracking-tight leading-none mb-1">Action Successful</h4>
                <p class="text-[13px] font-medium text-slate-500">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="w-8 h-8 mr-1 rounded-full hover:bg-slate-50 flex items-center justify-center text-slate-400 transition-colors focus:outline-none" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
    @endif

    <!-- Data Analytics Interface -->
    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
        
        <!-- Action Control Matrix -->
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row items-center justify-between gap-6 bg-slate-50/10">
            <div class="flex items-center gap-6">
                <div class="relative group">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-indigo-500 text-[10px] group-focus-within:text-indigo-600 transition-colors"></i>
                    <input type="text" id="tableSearch" placeholder="IDENTIFY UNIT..." 
                           class="h-12 pl-12 pr-6 bg-slate-50 border border-slate-100 rounded-2xl text-[10px] font-bold text-slate-900 outline-none focus:border-indigo-600 focus:bg-white focus:ring-4 focus:ring-indigo-100 transition-all w-72 uppercase tracking-widest placeholder:text-slate-300">
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="editSelected()" class="h-10 px-5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:border-indigo-200 hover:text-indigo-600 hover:bg-indigo-50 transition-all flex items-center gap-2 shadow-sm shrink-0">
                    <i class="fas fa-pen-nib text-indigo-500"></i> Edit Selected
                </button>
                <button onclick="deleteSelected()" class="h-10 px-5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:border-rose-200 hover:text-rose-600 hover:bg-rose-50 transition-all flex items-center gap-2 shadow-sm shrink-0">
                    <i class="fas fa-trash text-rose-500"></i> Delete Selected
                </button>
            </div>
        </div>

        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left border-collapse whitespace-nowrap" id="dataTable">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200/70">
                        <th class="ps-8 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-left">
                            <div class="flex items-center gap-4">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500/20 shadow-sm cursor-pointer mt-0.5">
                                <span>Code</span>
                            </div>
                        </th>
                        <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-left">Subject Details</th>
                        <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-left">Department</th>
                        <th class="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Classes</th>
                        <th class="pe-8 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($subjects as $item)
                    <tr class="table-row hover:bg-slate-50/50 transition-all group">
                        <td class="ps-8 py-4">
                            <div class="flex items-center gap-4">
                                <input type="checkbox" class="row-checkbox w-4 h-4 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500/20 shadow-sm transition-colors cursor-pointer" 
                                       value="{{ $item->id }}" data-name="{{ $item->subject_name }}" data-department="{{ $item->department_id }}" 
                                       data-major="{{ $item->major_id }}" data-classes="{{ json_encode($item->classes->pluck('id')->toArray()) }}">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 tracking-wide">
                                    {{ $item->code ?? 'SUB-' . str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <a href="{{ route('admin.subjects.show', $item->id) }}" class="flex items-center gap-3.5 transition-all group-hover:translate-x-1">
                                <div class="w-10 h-10 rounded-xl bg-amber-50/80 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100/50 shadow-sm">
                                    <i class="fas fa-book text-sm"></i>
                                </div>
                                <div class="flex flex-col">
                                    <h4 class="text-sm font-semibold text-slate-900 tracking-tight">{{ $item->subject_name }}</h4>
                                    <span class="text-xs text-slate-500 font-medium mt-0.5">{{ $item->major ? $item->major->name : 'Global Division' }}</span>
                                </div>
                            </a>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-xs font-medium text-slate-500">
                                {{ $item->department->department_name ?? 'System Unit' }}
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center justify-center min-w-[32px] px-2 py-1 rounded-md text-xs font-bold {{ $item->classes->count() > 0 ? 'bg-sky-50 text-sky-700 border border-sky-200 shadow-sm' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                {{ $item->classes->count() }}
                            </span>
                        </td>
                        <td class="pe-8 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5 opacity-70 group-hover:opacity-100 transition-opacity">
                                <button onclick="editRecord({{ $item->id }}, '{{ addslashes($item->subject_name) }}', {{ $item->department_id }}, {{ $item->major_id ?? 'null' }}, {{ json_encode($item->classes->pluck('id')) }})" 
                                        class="w-8 h-8 rounded-lg border border-slate-100 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all flex items-center justify-center shadow-sm" title="Edit Unit">
                                    <i class="fas fa-edit text-[13px]"></i>
                                </button>
                                <button onclick="deleteRecord({{ $item->id }}, '{{ addslashes($item->subject_name) }}')"
                                        class="w-8 h-8 rounded-lg border border-slate-100 text-slate-400 hover:text-rose-600 hover:border-rose-100 hover:bg-rose-50 transition-all flex items-center justify-center shadow-sm" title="Delete Unit">
                                    <i class="fas fa-trash-alt text-[13px]"></i>
                                </button>
                                <a href="{{ route('admin.subjects.show', $item->id) }}" 
                                   class="w-8 h-8 rounded-lg border border-slate-100 text-slate-400 hover:text-emerald-600 hover:border-emerald-100 hover:bg-emerald-50 transition-all flex items-center justify-center shadow-sm" title="View Details">
                                    <i class="fas fa-arrow-right text-[13px]"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center text-xs font-semibold text-slate-400 uppercase tracking-widest">
                            No subjects found in the database
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Single-delete hidden form: action is set by JS, @csrf and @method are permanent --}}
<form id="singleDeleteForm" method="POST" style="display:none;" action="">
    @csrf
    @method('DELETE')
</form>

<!-- Evolution Modals: High Fidelity -->
@include('admin.subjects.modals')

@endsection

@section('scripts')
<script>
    // Modern Search Logic
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        const query = this.value.toUpperCase();
        document.querySelectorAll('#dataTable tbody tr.table-row').forEach(row => {
            const hasMatch = Array.from(row.getElementsByTagName('td')).some(td => td.textContent.toUpperCase().includes(query));
            row.style.display = hasMatch ? '' : 'none';
        });
    });

    // Bulk Logic Control
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
    });

    function editRecord(id, name, dept_id, major_id, class_ids) {
        const modal = document.getElementById('addSubjectModal');
        const form = modal.querySelector('form');
        form.action = `/admin/subjects/${id}`;
        
        const methodInput = form.querySelector('input[name="_method"]') || document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        if (!form.querySelector('input[name="_method"]')) form.appendChild(methodInput);

        modal.querySelector('input[name="subject_name"]').value = name;
        modal.querySelector('select[name="department_id"]').value = dept_id;
        
        // Trigger major population
        updateMajors(dept_id, major_id);
        
        const classSelect = modal.querySelector('select[name="classes[]"]');
        Array.from(classSelect.options).forEach(opt => opt.selected = class_ids.includes(parseInt(opt.value)));
        
        modal.querySelector('h5').textContent = 'MUTATE LOGIC UNIT';
        modal.querySelector('button[type="submit"]').textContent = 'APPLY MUTATION';
        
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }

    // Helper: get fresh CSRF token from the meta tag
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    function deleteRecord(id, name) {
        if (confirm('Permanently decommission Unit: "' + name + '"? This action will sever all academic links.')) {
            const form = document.getElementById('singleDeleteForm');
            form.action = '/admin/subjects/' + id;
            // Update CSRF token to fresh value
            const tokenInput = form.querySelector('input[name="_token"]');
            if (tokenInput) {
                tokenInput.value = getCsrfToken();
            }
            form.submit();
        }
    }

    function deleteSelected() {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        if (selected.length === 0) {
            alert('Select units for decommissioning.');
            return;
        }

        if (confirm('Execute bulk decommissioning of ' + selected.length + ' unit(s)?')) {
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
        }
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
</script>
@endsection
