<!-- Unit Modal: Central Logic Configuration -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-[32px] border-0 shadow-2xl overflow-hidden">
            <div class="bg-indigo-600 px-8 py-6 flex items-center justify-between">
                <h5 class="text-xl font-bold text-white tracking-tight flex items-center gap-3" id="modalTitle">
                    <i class="fas fa-book text-indigo-200"></i> Add Subject
                </h5>
                <button type="button" class="text-indigo-200 hover:text-white transition-colors" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.subjects.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Subject Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="subject_name" required 
                                   class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 shadow-sm" 
                                   placeholder="e.g. Advanced Mathematics">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Subject Code <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <input type="text" name="code" 
                                   class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 shadow-sm uppercase" 
                                   placeholder="e.g. MATH-101">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Credits <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <input type="number" name="credits" value="3"
                                   class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Department Alignment <span class="text-rose-500">*</span></label>
                            <select name="department_id" id="departmentSelect" required
                                    class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm appearance-none cursor-pointer">
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Specialization (Major) <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <select name="major_id" id="majorSelect"
                                    class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm appearance-none cursor-pointer">
                                <option value="">-- Select Major --</option>
                                @foreach($majors as $major)
                                    <option value="{{ $major->id }}" data-department="{{ $major->department_id }}">{{ $major->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2 mt-2">
                            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-1.5">
                                Assigned Classes 
                                <span class="text-xs font-normal text-slate-400">(Hold Ctrl/Cmd to select multiple)</span>
                            </label>
                            <select name="classes[]" multiple
                                    class="w-full p-3 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm h-32 custom-scrollbar">
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" class="py-1.5 px-3 rounded-md mb-1 hover:bg-slate-50 cursor-pointer">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                
                <div class="mt-8 flex items-center justify-end gap-3 pt-5 border-t border-slate-100">
                    <button type="button" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 shadow-sm">
                        Save Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateMajors(deptId, selectedMajorId = null) {
    const majorSelect = document.getElementById('majorSelect');
    if(!majorSelect) return;
    
    Array.from(majorSelect.options).forEach(opt => {
        if (!opt.value) return; 
        const optDept = opt.getAttribute('data-department');
        opt.style.display = (optDept == deptId || !deptId) ? '' : 'none';
    });
    if (selectedMajorId) majorSelect.value = selectedMajorId;
}

document.getElementById('departmentSelect')?.addEventListener('change', function() {
    updateMajors(this.value);
});
</script>
