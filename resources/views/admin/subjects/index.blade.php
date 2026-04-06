@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-20 pt-8">
    <div class="max-w-[1400px] mx-auto px-6">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-[.2em] italic">Academic Infrastructure</span>
                </div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none uppercase italic tracking-tighter">Departmental Units</h1>
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[.2em] mt-3 italic">Manage subjects, credits, and module distribution. Secure Index v2.5.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button data-bs-toggle="modal" data-bs-target="#addSubjectModal" class="h-14 px-8 bg-indigo-600 hover:bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest flex items-center gap-3 transition-all shadow-xl shadow-indigo-500/20 active:scale-95">
                    <i class="fas fa-plus text-xs"></i> New Subject
                </button>
                <div class="h-14 w-px bg-slate-200 mx-2"></div>
                <a href="{{ route('admin.subjects.export') }}" class="w-14 h-14 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-400 hover:text-emerald-500 hover:border-emerald-100 hover:bg-emerald-50 transition-all shadow-sm">
                    <i class="fas fa-file-excel"></i>
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 animate-fade-in shadow-sm">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
            <span class="text-sm font-bold text-emerald-800 uppercase tracking-tight">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Data Interface -->
        <div class="bg-white rounded-[40px] border border-slate-200/60 shadow-xl shadow-slate-200/40 overflow-hidden">
            
            <!-- Quick Actions Toolbar -->
            <div class="p-8 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6 bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div class="relative group">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
                        <input type="text" id="tableSearch" placeholder="Filter Unit Index..." 
                               class="h-12 pl-12 pr-6 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 transition-all w-64 uppercase tracking-widest">
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <button onclick="editSelected()" class="h-12 px-6 bg-white border border-slate-100 rounded-xl text-[10px] font-black text-slate-900 uppercase tracking-widest hover:border-indigo-200 hover:text-indigo-600 hover:bg-indigo-50 transition-all flex items-center gap-2 shadow-sm italic">
                        <i class="fas fa-pen-nib text-indigo-500"></i> Mutate Record
                    </button>
                    <button onclick="deleteSelected()" class="h-12 px-6 bg-white border border-slate-100 rounded-xl text-[10px] font-black text-slate-900 uppercase tracking-widest hover:border-rose-200 hover:text-rose-600 hover:bg-rose-50 transition-all flex items-center gap-2 shadow-sm italic">
                        <i class="fas fa-trash text-rose-500"></i> Purge Record
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap" id="dataTable">
                    <thead>
                        <tr class="bg-white border-b border-slate-50">
                            <th class="px-8 py-5 text-[9px] font-black text-indigo-600 uppercase tracking-widest italic">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" id="selectAll" class="w-4 h-4 rounded-md border-slate-200 text-indigo-600 focus:ring-indigo-500/20">
                                    <span>Sync-Code</span>
                                </div>
                            </th>
                            <th class="px-8 py-5 text-[9px] font-black text-indigo-600 uppercase tracking-widest italic">Subject Distinction</th>
                            <th class="px-8 py-5 text-[9px] font-black text-indigo-600 uppercase tracking-widest italic">Module Info</th>
                            <th class="px-8 py-5 text-[9px] font-black text-indigo-600 uppercase tracking-widest text-center italic">Unit Stats</th>
                            <th class="px-8 py-5 text-[9px] font-black text-indigo-600 uppercase tracking-widest text-right italic">Navigation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80">
                        @forelse($subjects as $item)
                        <tr class="table-row hover:bg-slate-50/80 transition-all group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" class="row-checkbox w-4 h-4 rounded-md border-slate-200 text-indigo-600 focus:ring-indigo-500/20" 
                                           value="{{ $item->id }}" data-name="{{ $item->subject_name }}" data-department="{{ $item->department_id }}" 
                                           data-major="{{ $item->major_id }}" data-classes="{{ json_encode($item->classes->pluck('id')->toArray()) }}">
                                    <span class="text-xs font-black text-slate-900 group-hover:text-indigo-600 transition-colors uppercase tracking-tight tabular-nums">
                                        {{ $item->code ?? 'SUB-' . str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div>
                                    <a href="{{ route('admin.subjects.show', $item->id) }}" class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition-colors uppercase tracking-tight antialiased italic tabular-nums">
                                        {{ $item->subject_name }}
                                    </a>
                                    <div class="text-[9px] font-black text-indigo-600 uppercase tracking-widest mt-1 italic italic tracking-tighter opacity-70">
                                        {{ $item->major ? $item->major->name : 'General Division' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2">
                                    <div class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded text-[9px] font-black uppercase tracking-widest border border-indigo-100">
                                        {{ $item->credits ?? 3 }} Credits
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center gap-4">
                                    <div class="text-center group/stat">
                                        <div class="text-xs font-black text-slate-900 mb-0.5 tabular-nums italic">{{ $item->classes_count ?? 0 }}</div>
                                        <div class="text-[8px] font-black text-indigo-600 uppercase tracking-widest italic">Groups</div>
                                    </div>
                                    <div class="w-px h-6 bg-slate-100"></div>
                                    <div class="text-center">
                                        <div class="text-xs font-black text-slate-900 mb-0.5 tabular-nums italic">{{ $item->quizzes_count ?? 0 }}</div>
                                        <div class="text-[8px] font-black text-indigo-600 uppercase tracking-widest italic">Assessments</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('admin.subjects.show', $item->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all shadow-sm">
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-[32px] flex items-center justify-center mx-auto mb-6 text-slate-200 text-2xl">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest italic">Unit Index Empty</h4>
                                <p class="text-[11px] font-semibold text-slate-400 mt-1 uppercase tracking-widest">Initialize academic modules to begin.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Dashboard Footer -->
            <div class="p-8 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between">
                <div class="text-[9px] font-black text-indigo-600 uppercase tracking-widest tabular-nums italic">
                    Infrastructure distribution verified • {{ $subjects->total() }} Sync Nodes Detected
                </div>
                <div class="flex items-center gap-2">
                    {{ $subjects->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Immersive Architect Modal (Add/Edit) -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-[40px] border-none shadow-2xl shadow-indigo-900/20 overflow-hidden">
            <div class="bg-slate-950 p-10 text-white relative italic">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500/10 blur-3xl rounded-full"></div>
                <h3 class="text-2xl font-black uppercase tracking-tight italic" id="modalTitle">Mutate unit architecture</h3>
                <p class="text-[9px] font-black text-indigo-400 uppercase tracking-[.2em] mt-2 italic">Academic Integrity Protocol Locked</p>
            </div>
            
            <form action="{{ route('admin.subjects.store') }}" method="POST" id="mainForm" class="p-10 space-y-10">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">
                
                <div class="grid grid-cols-2 gap-8">
                    <div class="group">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 ml-1 group-focus-within:text-indigo-500 transition-colors">Unit Code</label>
                        <input type="text" name="code" id="itemCode" required placeholder="e.g. CS101" 
                               class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all uppercase tracking-widest tabular-nums">
                    </div>
                    <div class="group">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 ml-1 group-focus-within:text-indigo-500 transition-colors">Formal Name</label>
                        <input type="text" name="subject_name" id="itemName" required placeholder="Intro to Logic" 
                               class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all uppercase tracking-tight">
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="group">
                        <label class="block text-[9px] font-black uppercase tracking-widest text-indigo-600 mb-3 ml-1 group-focus-within:text-slate-950 transition-colors italic">Department Cluster Authorization</label>
                        <select name="department_id" id="itemDept" required class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">-- Sector Recognition --</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->department_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 ml-1 group-focus-within:text-indigo-500 transition-colors">Academic Major Alignment</label>
                        <select name="major_id" id="itemMajor" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">-- Optional Alignment --</option>
                            @foreach($majors as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 ml-1 group-focus-within:text-indigo-500 transition-colors">Group Distribution (Classes)</label>
                        <select name="class_ids[]" id="itemClasses" multiple class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all min-h-[120px]">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" class="p-2 border-b border-slate-100">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-3 text-[9px] font-black text-slate-400 uppercase tracking-widest italic flex items-center gap-2">
                            <i class="fas fa-info-circle text-indigo-400"></i> Multi-select permitted via meta-key (Ctrl/Cmd)
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-10 border-t border-slate-100">
                    <button type="button" data-bs-dismiss="modal" class="flex-grow h-16 rounded-2xl font-black uppercase tracking-widest text-[10px] text-slate-400 hover:text-slate-900 hover:bg-slate-50 transition-all italic">Discard Changes</button>
                    <button type="submit" id="btnSubmit" class="flex-grow h-16 bg-slate-900 hover:bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-[.2em] text-[10px] transition-all shadow-xl shadow-slate-900/10 active:scale-95 italic">Authorize Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden Internal Delete Protocol -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
    // Search Optimization
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        let query = this.value.toLowerCase();
        let rows = document.querySelectorAll('#dataTable tbody .table-row');
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.classList.toggle('hidden', !text.includes(query));
        });
    });

    // Mass Selection Logic
    document.getElementById('selectAll').addEventListener('change', function() {
        let checked = this.checked;
        document.querySelectorAll('.row-checkbox').forEach(cb => { cb.checked = checked; });
    });

    // Security Protocol: Bulk Delete
    function deleteSelected() {
        let selected = document.querySelectorAll('.row-checkbox:checked');
        if (selected.length === 0) return alert('No vector selected for deletion.');

        if (confirm('Critical: Proceed with authorization to purge ' + selected.length + ' academic records?')) {
            let form = document.getElementById('deleteForm');
            form.action = '{{ route("admin.subjects.bulkDelete") }}';
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
            selected.forEach(function(item) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = item.value;
                form.appendChild(input);
            });
            form.submit();
        }
    }

    // Record Mutation Protocol (Edit)
    function editSelected() {
        let selected = document.querySelectorAll('.row-checkbox:checked');
        if (selected.length !== 1) return alert('Operation restricted: Select exactly ONE record to mutate.');

        let cb = selected[0];
        let id = cb.value;
        let name = cb.getAttribute('data-name');
        let dept = cb.getAttribute('data-department');
        let major = cb.getAttribute('data-major');
        let classesStr = cb.getAttribute('data-classes');
        let classArray = classesStr ? JSON.parse(classesStr) : [];
        let row = cb.closest('tr');
        let code = row.querySelector('.text-xs.font-black').innerText.trim();
        
        document.getElementById('modalTitle').innerText = 'Mutate Record';
        document.getElementById('mainForm').action = '/admin/subjects/' + id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('itemCode').value = code;
        document.getElementById('itemName').value = name;
        document.getElementById('itemDept').value = dept;
        document.getElementById('itemMajor').value = major || '';
        
        let classSelect = document.getElementById('itemClasses');
        Array.from(classSelect.options).forEach(opt => {
            opt.selected = classArray.includes(parseInt(opt.value)) || classArray.includes(String(opt.value));
        });

        document.getElementById('btnSubmit').innerText = 'Apply Mutation';
        new bootstrap.Modal(document.getElementById('addSubjectModal')).show();
    }
    
    // Architect Protocol: Modal Reset
    document.getElementById('addSubjectModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('modalTitle').innerText = 'Configure Subject';
        document.getElementById('mainForm').action = '{{ route("admin.subjects.store") }}';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('itemCode').value = '';
        document.getElementById('itemName').value = '';
        document.getElementById('itemDept').value = '';
        document.getElementById('itemMajor').value = '';
        Array.from(document.getElementById('itemClasses').options).forEach(opt => opt.selected = false);
        document.getElementById('btnSubmit').innerText = 'Authorize Record';
    });
</script>
@endsection
