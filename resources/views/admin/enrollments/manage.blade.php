@extends('layouts.admin')

@section('content')
<div class="max-w-full mx-auto p-6 md:p-10 font-inter text-slate-900 bg-white">
    
    <!-- Elegant Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 border-b border-slate-100 pb-10">
        <div>
            <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-[0.25em] mb-3">
                <i class="fas fa-link text-[9px] mr-1"></i> Access Management
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight" style="font-family: 'Open Sans', sans-serif !important;">{{ $department->department_name }}</h1>
            <p class="text-sm text-slate-500 mt-2 max-w-2xl leading-relaxed uppercase tracking-widest text-[11px]">Relationship Hub: Assigning Teachers, Students, and Subjects</p>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.enrollments.index') }}" class="h-10 px-6 bg-white border border-slate-200 text-slate-600 hover:text-indigo-600 hover:border-indigo-100 transition-all text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-arrow-left text-[9px]"></i> Back
            </a>
            <button type="button" class="h-10 px-6 bg-indigo-600 hover:bg-indigo-700 text-white transition-all text-[10px] font-bold uppercase tracking-widest flex items-center gap-2" data-bs-toggle="modal" data-bs-target="#enrolUsersModal">
                <i class="fas fa-plus text-[9px]"></i> Manage Access
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-white border border-emerald-100 rounded-2xl p-3.5 flex items-center justify-between shadow-sm relative overflow-hidden transition-all duration-300">
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
        <div class="flex items-center gap-3.5">
            <div class="w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100/50">
                <i class="fas fa-check text-emerald-500 text-xs"></i>
            </div>
            <div>
                <h4 class="text-[13px] font-bold text-slate-900 leading-tight">Action Successful</h4>
                <p class="text-[11px] font-medium text-slate-500">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="text-slate-400 hover:text-slate-600 px-2" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
    @endif

    <!-- Relationship Matrix Interface -->
    <div class="border border-slate-100 overflow-hidden mb-12">
        
        <!-- Control Bar -->
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row items-center justify-between gap-8 bg-slate-50/30">
            <div class="flex items-center gap-4">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Active Participants</h3>
                <div class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-widest" id="participantCountDisplay">
                    {{ $enrolledUsers->count() }} Members
                </div>
            </div>
            
            <div class="relative w-full sm:w-80">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                <input type="text" id="participantSearch" placeholder="Search by name or email..." 
                       class="w-full h-11 pl-10 pr-4 bg-white border border-slate-200 text-sm font-medium text-slate-900 outline-none focus:border-indigo-600 transition-all placeholder:text-slate-300">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="ps-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-12">#</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">User Details</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Contact Info</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Role Authority</th>
                        <th class="pe-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="participantsTableBody">
                    @forelse($enrolledUsers as $user)
                    <tr class="participant-row hover:bg-slate-50/50 transition-all group">
                        <td class="ps-8 py-5 text-[11px] font-bold text-slate-300 tabular-nums">{{ $loop->iteration }}</td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold border border-indigo-100 tabular-nums uppercase">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover" alt="">
                                    @else
                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="text-[14px] font-bold text-slate-900 uppercase tracking-tight">{{ $user->username }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2 text-slate-500 font-medium text-sm">
                                <i class="far fa-envelope text-[11px] text-indigo-400"></i>
                                <span class="searchable-cell">{{ $user->email }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-widest {{ (int)$user->role_id === 2 ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                {{ $user->role->role_name ?? 'Student' }}
                            </span>
                        </td>
                        <td class="pe-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <span class="w-1.5 h-1.5 bg-emerald-500"></span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Access</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users-slash text-2xl text-slate-100 mb-4"></i>
                                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em]">No participants identified</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Premium Matrix Modal -->
<div class="modal fade" id="enrolUsersModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl bg-white">
            <div class="modal-header border-b border-slate-100 px-6 py-4 bg-slate-50">
                <div>
                    <h5 class="text-lg font-bold text-slate-900 tracking-tight leading-none mb-1.5 uppercase">Manage Access Matrix</h5>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Assigning Permissions for {{ $department->department_name }}</p>
                </div>
                <button type="button" class="w-8 h-8 border border-slate-200 text-slate-400 hover:text-indigo-600 transition-all flex items-center justify-center p-0" data-bs-dismiss="modal">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            <div class="modal-body p-6 md:p-8">
                <form action="{{ route('admin.enrollments.update', $department->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        <!-- Personnel Node -->
                        <div class="lg:col-span-7">
                            <div class="flex items-center justify-between mb-4">
                                <h6 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.2em] flex items-center gap-2">
                                    <span class="w-1 h-3 bg-indigo-600"></span> Participants
                                </h6>
                                <div class="relative w-48">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-[9px]"></i>
                                    <input type="text" class="h-8 pl-8 pr-3 bg-slate-50 border border-slate-200 text-[10px] font-bold text-slate-900 outline-none focus:border-indigo-600 transition-all w-full placeholder:text-slate-300" id="userSearchInModal" placeholder="SEARCH USERS...">
                                </div>
                            </div>
                            
                            <div class="custom-scrollbar overflow-auto space-y-2 pr-2" style="max-height: 380px;" id="modalUsersList">
                                <!-- Faculty Section -->
                                <div class="px-3 py-1.5 bg-indigo-600 text-[8px] font-bold text-white uppercase tracking-widest">Teachers</div>
                                @foreach($teachers as $teacher)
                                <div class="flex items-center gap-3 py-2.5 px-4 border border-slate-100 hover:border-indigo-200 transition-all modal-user-item cursor-pointer group" data-name="{{ $teacher->username }}">
                                    <input class="w-4 h-4 border-slate-300 text-indigo-600 focus:ring-0 cursor-pointer" type="checkbox" name="teachers[]" value="{{ $teacher->id }}" {{ in_array($teacher->id, $assignedTeachers) ? 'checked' : '' }}>
                                    <div class="flex-grow">
                                        <div class="text-[12px] font-bold text-slate-900 group-hover:text-indigo-600 transition-colors uppercase tracking-tight">{{ $teacher->username }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $teacher->email }}</div>
                                    </div>
                                    <span class="text-[8px] font-bold text-slate-200 uppercase tracking-widest">FACULTY</span>
                                </div>
                                @endforeach

                                <!-- Students Section -->
                                <div class="px-3 py-1.5 bg-slate-900 text-[8px] font-bold text-white uppercase tracking-widest mt-4">Students</div>
                                @foreach($students as $student)
                                <div class="flex items-center gap-3 py-2.5 px-4 border border-slate-100 hover:border-indigo-200 transition-all modal-user-item cursor-pointer group" data-name="{{ $student->username }}">
                                    <input class="w-4 h-4 border-slate-300 text-indigo-600 focus:ring-0 cursor-pointer" type="checkbox" name="students[]" value="{{ $student->id }}" {{ in_array($student->id, $enrolledStudents) ? 'checked' : '' }}>
                                    <div class="flex-grow">
                                        <div class="text-[12px] font-bold text-slate-900 group-hover:text-indigo-600 transition-colors uppercase tracking-tight">{{ $student->username }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $student->email }}</div>
                                    </div>
                                    <span class="text-[8px] font-bold text-slate-200 uppercase tracking-widest">STUDENT</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Subjects Node -->
                        <div class="lg:col-span-5 border-l border-slate-100 pl-6">
                            <div class="flex items-center justify-between mb-4">
                                <h6 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.2em] flex items-center gap-2">
                                    <span class="w-1 h-3 bg-emerald-500"></span> Subjects
                                </h6>
                            </div>
                            <div class="relative mb-4">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-[9px]"></i>
                                <input type="text" class="h-8 pl-8 pr-3 bg-slate-50 border border-slate-200 text-[10px] font-bold text-slate-900 outline-none focus:border-indigo-600 transition-all w-full placeholder:text-slate-300" id="subjectSearchInModal" placeholder="SEARCH SUBJECTS...">
                            </div>
                            <div class="custom-scrollbar overflow-auto space-y-2 pr-1" style="max-height: 380px;" id="modalSubjectsList">
                                @foreach($subjects as $subject)
                                <div class="py-2.5 px-4 border border-slate-100 hover:border-emerald-200 transition-all modal-subject-item cursor-pointer group" data-name="{{ $subject->subject_name }}">
                                    <div class="flex items-start gap-3">
                                        <input class="w-4 h-4 border-slate-300 text-emerald-600 focus:ring-0 cursor-pointer mt-0.5" type="checkbox" name="subjects[]" value="{{ $subject->id }}" id="sub_{{ $subject->id }}" {{ in_array($subject->id, $assignedSubjects) ? 'checked' : '' }}>
                                        <label class="flex-grow cursor-pointer" for="sub_{{ $subject->id }}">
                                            <div class="text-[11px] font-bold text-slate-900 uppercase tracking-tight group-hover:text-emerald-600 transition-colors">{{ $subject->subject_name }}</div>
                                            <div class="text-[8px] font-bold text-slate-300 mt-0.5 uppercase tracking-widest">ID: #{{ str_pad($subject->id, 3, '0', STR_PAD_LEFT) }}</div>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between items-center mt-8 pt-6 border-t border-slate-100 gap-6">
                        <div class="flex items-center gap-2 text-slate-400">
                            <i class="fas fa-info-circle text-[10px]"></i>
                            <span class="text-[9px] font-bold uppercase tracking-widest">Review changes before save</span>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" class="h-9 px-6 border border-slate-200 text-slate-500 text-[9px] font-bold uppercase tracking-widest hover:bg-slate-50 transition-all" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="h-9 px-6 bg-indigo-600 hover:bg-indigo-700 text-white text-[9px] font-bold uppercase tracking-widest transition-all shadow-lg active:scale-95">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('participantSearch').addEventListener('keyup', function() {
        const query = this.value.toUpperCase();
        let matchCount = 0;
        document.querySelectorAll('.participant-row').forEach(row => {
            const hasMatch = Array.from(row.querySelectorAll('.searchable-cell')).some(td => td.textContent.toUpperCase().includes(query));
            row.style.display = hasMatch ? '' : 'none';
            if (hasMatch) matchCount++;
        });
        document.getElementById('participantCountDisplay').textContent = matchCount + ' Members';
    });

    document.getElementById('userSearchInModal').addEventListener('keyup', function() {
        const query = this.value.toUpperCase();
        document.querySelectorAll('.modal-user-item').forEach(item => {
            const name = item.getAttribute('data-name').toUpperCase();
            item.style.display = name.includes(query) ? 'flex' : 'none';
        });
    });

    document.getElementById('subjectSearchInModal').addEventListener('keyup', function() {
        const query = this.value.toUpperCase();
        document.querySelectorAll('.modal-subject-item').forEach(item => {
            const name = item.getAttribute('data-name').toUpperCase();
            item.style.display = name.includes(query) ? 'block' : 'none';
        });
    });

    // Handle Select All logic if the element exists
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.participant-row input[type="checkbox"]').forEach(cb => cb.checked = this.checked);
        });
    }
</script>
@endsection
