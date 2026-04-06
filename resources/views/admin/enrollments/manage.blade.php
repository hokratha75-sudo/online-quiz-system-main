@extends('layouts.admin')

@section('content')
<div class="max-w-[1400px] mx-auto p-6 md:p-10 font-inter text-slate-900">
    
    <!-- Header: Institutional Relationship Management -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-[24px] bg-slate-900 text-white flex items-center justify-center text-2xl shadow-xl shadow-slate-950/20 group">
                <i class="fas fa-building text-indigo-400 group-hover:scale-110 transition-transform"></i>
            </div>
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-semibold tracking-wide border border-indigo-100">Department: {{ $department->department_name }}</span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight leading-none">Manage {{ $department->department_name }}</h1>
                <p class="text-sm font-medium text-slate-500 mt-2 leading-none">Assign teachers, students, and subjects to this department.</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.enrollments.index') }}" class="h-12 px-6 bg-white border border-slate-200 text-slate-600 hover:text-indigo-600 hover:border-indigo-100 hover:bg-slate-50 rounded-xl text-sm font-semibold flex items-center gap-3 transition-all shadow-sm active:scale-95">
                <i class="fas fa-arrow-left text-xs"></i> Return
            </a>
            <button type="button" class="h-12 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold flex items-center gap-3 transition-all shadow-md active:scale-95" data-bs-toggle="modal" data-bs-target="#enrolUsersModal">
                <i class="fas fa-user-plus text-indigo-200"></i> Manage Users
            </button>
        </div>
    </header>

    @if(session('success'))
    <div class="mb-10 p-5 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-4 animate-fade-in shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/20">
            <i class="fas fa-check-circle"></i>
        </div>
        <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-widest">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Relationship Analytical Interface -->
    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden mb-12">
        
        <!-- Quick Action Sync Control -->
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row items-center justify-between gap-8 bg-slate-50/50">
            <div class="flex-grow max-w-xl relative group">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm group-focus-within:text-indigo-600 transition-colors"></i>
                <input type="text" id="participantSearch" placeholder="Search Users..." 
                       class="h-12 pl-12 pr-6 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-900 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all w-full placeholder:text-slate-400 shadow-sm">
            </div>
            
            <div class="text-right">
                <div class="text-3xl font-bold tracking-tight text-indigo-600 tabular-nums leading-none mb-1" id="participantCountDisplay">{{ $enrolledUsers->count() }}</div>
                <div class="text-xs font-semibold text-slate-500 tracking-wide">Enrolled Users</div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap" id="participantsTableBody">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="ps-8 py-5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <div class="flex items-center gap-4">
                                <input class="form-check-input w-4 h-4 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500/20" type="checkbox" id="selectAll">
                                <span>Select</span>
                            </div>
                        </th>
                        <th class="px-6 py-5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Last Login</th>
                        <th class="pe-8 py-5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($enrolledUsers as $user)
                    <tr class="participant-row hover:bg-slate-50/50 transition-all group">
                        <td class="ps-8 py-4">
                            <input class="form-check-input w-4 h-4 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500/20" type="checkbox">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold border border-indigo-100 group-hover:scale-110 transition-transform tabular-nums uppercase">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" class="rounded-xl w-full h-full object-cover" alt="">
                                    @else
                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="text-sm font-semibold text-slate-900">{{ $user->username }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-500 searchable-cell">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full {{ (int)$user->role_id === 2 ? 'bg-teal-50 text-teal-700 border border-teal-100' : 'bg-cyan-50 text-cyan-700 border border-cyan-100' }} text-xs font-semibold transition-all">
                                {{ $user->role->role_name ?? 'Student' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-500 tabular-nums">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                        <td class="pe-8 py-4 text-right">
                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-100">
                                Active
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr id="noResultsRow">
                        <td colspan="6" class="py-24 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users-slash text-4xl text-slate-200 mb-4"></i>
                                <p class="text-sm font-medium text-slate-500">No users enrolled in this department</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Evolution Modal: Manage Enrollment -->
<div class="modal fade" id="enrolUsersModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-2xl rounded-3xl overflow-hidden bg-white">
            <div class="modal-header border-b border-slate-100 px-8 py-6 flex items-center justify-between bg-slate-50">
                <div>
                    <h5 class="text-xl font-bold text-slate-900 leading-none mb-1">Manage Enrollments</h5>
                    <p class="text-sm font-medium text-slate-500 leading-none">Assign Teachers, Students, and Subjects</p>
                </div>
                <button type="button" class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all flex items-center justify-center p-0" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-8 bg-white">
                <form action="{{ route('admin.enrollments.update', $department->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <!-- Personnel Node: Teachers & Students -->
                        <div class="lg:col-span-8 bg-slate-50 p-8 rounded-3xl border border-slate-100 overflow-hidden">
                            <h6 class="text-sm font-bold text-slate-900 mb-6 flex items-center gap-3">
                                <i class="fas fa-users text-indigo-500"></i> Assign Users
                            </h6>
                            <div class="relative group mb-6">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors"></i>
                                <input type="text" class="h-12 pl-12 pr-6 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all w-full placeholder:text-slate-400 shadow-sm" id="userSearchInModal" placeholder="Search Users...">
                            </div>
                            
                            <div class="custom-scrollbar overflow-auto space-y-4 pr-3" style="max-height: 380px;" id="modalUsersList">
                                <!-- Faculty Vector -->
                                <div class="px-4 py-2 bg-indigo-50 text-xs font-bold text-indigo-700 uppercase tracking-widest rounded-lg flex items-center justify-between">
                                    <span>Teachers</span>
                                </div>
                                @foreach($teachers as $teacher)
                                <div class="flex items-center gap-4 p-4 bg-white hover:bg-slate-50 border border-slate-100 hover:border-indigo-200 rounded-xl transition-all group modal-user-item cursor-pointer shadow-sm" data-name="{{ $teacher->username }}">
                                    <input class="form-check-input w-5 h-5 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500" type="checkbox" name="teachers[]" value="{{ $teacher->id }}" {{ in_array($teacher->id, $assignedTeachers) ? 'checked' : '' }}>
                                    <div class="flex-grow">
                                        <div class="text-sm font-semibold text-slate-900">{{ $teacher->username }}</div>
                                        <div class="text-xs font-medium text-slate-500">{{ $teacher->email }}</div>
                                    </div>
                                    <span class="px-2 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-md border border-teal-100">Teacher</span>
                                </div>
                                @endforeach

                                <!-- Candidate Vector -->
                                <div class="px-4 py-2 bg-emerald-50 text-xs font-bold text-emerald-700 uppercase tracking-widest rounded-lg flex items-center justify-between mt-4">
                                    <span>Students</span>
                                </div>
                                @foreach($students as $student)
                                <div class="flex items-center gap-4 p-4 bg-white hover:bg-slate-50 border border-slate-100 hover:border-indigo-200 rounded-xl transition-all group modal-user-item cursor-pointer shadow-sm" data-name="{{ $student->username }}">
                                    <input class="form-check-input w-5 h-5 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500" type="checkbox" name="students[]" value="{{ $student->id }}" {{ in_array($student->id, $enrolledStudents) ? 'checked' : '' }}>
                                    <div class="flex-grow">
                                        <div class="text-sm font-semibold text-slate-900">{{ $student->username }}</div>
                                        <div class="text-xs font-medium text-slate-500">{{ $student->email }}</div>
                                    </div>
                                    <span class="px-2 py-1 bg-cyan-50 text-cyan-700 text-xs font-semibold rounded-md border border-cyan-100">Student</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Logic Node: Subjects -->
                        <div class="lg:col-span-4 bg-slate-50 p-8 rounded-3xl border border-slate-100 flex flex-col">
                            <h6 class="text-sm font-bold text-slate-900 mb-6 flex items-center gap-3">
                                <i class="fas fa-book text-emerald-500"></i> Assign Subjects
                            </h6>
                            <div class="relative group mb-6">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                <input type="text" class="h-12 pl-12 pr-6 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all w-full placeholder:text-slate-400 shadow-sm" id="subjectSearchInModal" placeholder="Search Subjects...">
                            </div>
                            <div class="custom-scrollbar overflow-auto flex-grow space-y-3 pr-3" style="max-height: 380px;" id="modalSubjectsList">
                                @foreach($subjects as $subject)
                                <div class="p-4 bg-white hover:bg-emerald-50 border border-slate-100 hover:border-emerald-200 rounded-xl transition-all group modal-subject-item cursor-pointer shadow-sm" data-name="{{ $subject->subject_name }}">
                                    <div class="flex items-start gap-4">
                                        <input class="form-check-input w-5 h-5 rounded-md border-slate-300 text-emerald-600 focus:ring-emerald-500 mt-0.5" type="checkbox" name="subjects[]" value="{{ $subject->id }}" id="sub_{{ $subject->id }}" {{ in_array($subject->id, $assignedSubjects) ? 'checked' : '' }}>
                                        <label class="flex-grow cursor-pointer" for="sub_{{ $subject->id }}">
                                            <div class="text-sm font-semibold text-slate-900 leading-snug">{{ $subject->subject_name }}</div>
                                            <div class="text-xs font-medium text-slate-400 mt-1">ID: #{{ str_pad($subject->id, 3, '0', STR_PAD_LEFT) }}</div>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between items-center mt-8 pt-6 border-t border-slate-100 gap-6">
                        <div class="flex items-center gap-4 text-slate-500">
                            <i class="fas fa-info-circle"></i>
                            <span class="text-sm font-medium">Verify your selections before saving</span>
                        </div>
                        <div class="flex gap-4">
                            <button type="button" class="h-12 px-8 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl text-sm font-semibold transition-all" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="h-12 px-8 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-all shadow-md active:scale-95">Save Changes</button>
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
    // Advanced Matrix Filtering
    document.getElementById('participantSearch').addEventListener('keyup', function() {
        const query = this.value.toUpperCase();
        let matchCount = 0;
        document.querySelectorAll('.participant-row').forEach(row => {
            const hasMatch = Array.from(row.querySelectorAll('.searchable-cell')).some(td => td.textContent.toUpperCase().includes(query));
            row.style.display = hasMatch ? '' : 'none';
            if (hasMatch) matchCount++;
        });
        document.getElementById('participantCountDisplay').textContent = matchCount;
    });

    // Sync Hub Searching
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

    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.participant-row input[type="checkbox"]').forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
