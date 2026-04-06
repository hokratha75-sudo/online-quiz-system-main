<!-- Unit Modal: Central Logic Configuration -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-[32px] overflow-hidden bg-white">
            <div class="modal-header bg-slate-900 border-0 px-8 py-6 items-center">
                <div>
                    <h5 class="text-lg font-bold text-white uppercase tracking-tight" id="modalTitle">Instantiate Logic Unit</h5>
                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mt-1">Configure academic subject parameters</p>
                </div>
                <button type="button" class="w-8 h-8 rounded-xl bg-white/10 border border-white/10 text-white/50 hover:text-white flex items-center justify-center transition-all p-0" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                <div class="modal-body p-8 bg-slate-50/50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-book text-indigo-500"></i> Subject Designation <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="subject_name" required 
                                   class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-4 text-xs font-bold text-slate-900 uppercase tracking-widest focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-300" 
                                   placeholder="ENTER UNIT NAME...">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-hashtag text-indigo-500"></i> Protocol Code
                            </label>
                            <input type="text" name="code" 
                                   class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-4 text-xs font-bold text-slate-900 uppercase tracking-widest focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-300" 
                                   placeholder="OPTIONAL CODE...">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-star text-indigo-500"></i> Credits
                            </label>
                            <input type="number" name="credits" value="3"
                                   class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-4 text-xs font-bold text-slate-900 uppercase tracking-widest focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-building text-indigo-500"></i> Sector Alignment
                            </label>
                            <select name="department_id" id="departmentSelect"
                                    class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-4 text-[10px] font-bold text-slate-900 uppercase tracking-widest focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all appearance-none outline-none">
                                <option value="">SELECT SECTOR NODE...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ strtoupper($dept->department_name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-graduation-cap text-indigo-500"></i> Major Node
                            </label>
                            <select name="major_id" id="majorSelect"
                                    class="w-full h-12 bg-white border border-slate-200 rounded-2xl px-4 text-[10px] font-bold text-slate-900 uppercase tracking-widest focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all appearance-none outline-none">
                                <option value="">SELECT MAJOR NODE...</option>
                                @foreach($majors as $major)
                                    <option value="{{ $major->id }}" data-department="{{ $major->department_id }}">{{ strtoupper($major->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2 space-y-2 mt-2">
                            <label class="text-[10px] font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-users text-indigo-500"></i> Synced Classes
                            </label>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-2">Hold CMD/CTRL to select multiple classes</p>
                            <select name="classes[]" multiple
                                    class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-3 text-[10px] font-bold text-slate-900 uppercase tracking-widest focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all outline-none h-32 custom-scrollbar">
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" class="py-1 border-b border-slate-50 last:border-0 hover:bg-slate-50">{{ strtoupper($class->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-t border-slate-100 px-8 py-6 bg-white justify-between">
                    <button type="button" class="h-12 px-6 bg-slate-50 border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-2xl text-[10px] font-bold uppercase tracking-widest transition-all" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="h-12 px-8 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-[10px] font-bold uppercase tracking-widest transition-all shadow-xl shadow-indigo-600/20 active:scale-95 flex items-center gap-2">
                        <i class="fas fa-save"></i> Execute Provision
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
