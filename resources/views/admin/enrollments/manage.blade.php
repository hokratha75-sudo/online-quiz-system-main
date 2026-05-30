{{-- resources/views/admin/enrollments/manage.blade.php --}}
@extends('layouts.admin')

@section('content')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    .class-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .class-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .class-card.selected {
        border-color: #4f46e5;
        background: linear-gradient(135deg, #eef2ff 0%, #ffffff 100%);
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .toast-notification {
        animation: slideIn 0.3s ease-out, fadeOut 0.3s ease-out 2.7s forwards;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }
    
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes fadeOut {
        to { opacity: 0; visibility: hidden; }
    }
</style>

<div class="max-w-full mx-auto p-6 md:p-10 bg-gradient-to-br from-white to-slate-50 min-h-screen">
    
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-[10px] text-slate-400 mb-4">
            <a href="{{ route('admin.enrollments.index') }}" class="hover:text-indigo-600">Enrollments</a>
            <i class="fas fa-chevron-right text-[8px]"></i>
            <span class="text-indigo-600 font-bold">{{ $department->department_name }}</span>
        </div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">{{ $department->department_name }}</h1>
                <p class="text-sm text-slate-500 mt-2">Manage class assignments, teacher allocations, and student enrollments</p>
            </div>
            
            <div class="flex gap-3">
                <button onclick="openEnrollmentModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all flex items-center gap-2 shadow-md">
                    <i class="fas fa-edit text-[10px]"></i> Edit Enrollment
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 animate-fade-in">
        <div class="flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-500"></i>
            <p class="text-[13px] text-emerald-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 animate-fade-in">
        <div class="flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-500"></i>
            <p class="text-[13px] text-red-700">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Stats Summary -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl p-4 border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[9px] text-slate-400 uppercase">Classes</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $classes->count() }}</p>
                </div>
                <i class="fas fa-door-open text-indigo-400 text-xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[9px] text-slate-400 uppercase">Students</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $enrolledUsers->where('role_id', 3)->count() }}</p>
                </div>
                <i class="fas fa-user-graduate text-emerald-400 text-xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[9px] text-slate-400 uppercase">Teachers</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $enrolledUsers->where('role_id', 2)->count() }}</p>
                </div>
                <i class="fas fa-chalkboard-user text-amber-400 text-xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[9px] text-slate-400 uppercase">Subjects</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $subjects->count() }}</p>
                </div>
                <i class="fas fa-book-open text-rose-400 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Class Cards Section -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
                <div class="w-1 h-6 bg-indigo-500 rounded-full"></div>
                <h2 class="text-lg font-bold text-slate-900">Class Groups</h2>
                <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[9px] font-bold rounded-full">{{ $classes->count() }} Classes</span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5" id="classesGrid">
            @foreach($classes as $class)
            <div class="class-card border-2 border-slate-200 rounded-2xl p-5 bg-white hover:shadow-lg transition-all" data-class-id="{{ $class->id }}">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center">
                            <i class="fas fa-users text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">{{ $class->class_name }}</h3>
                            <p class="text-[9px] text-slate-500 mt-0.5">
                                <i class="fas fa-graduation-cap text-[8px]"></i>
                                {{ $class->students->count() }} Students | {{ $class->teachers->count() }} Teachers
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-[10px]">
                        <span class="text-slate-500">Enrollment:</span>
                        <span class="font-bold text-slate-700">{{ $class->students->count() }}/{{ $class->capacity ?? '∞' }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-indigo-500 h-2 rounded-full transition-all" style="width: {{ min(100, ($class->students->count() / max(1, $class->capacity ?? 30)) * 100) }}%"></div>
                    </div>
                </div>
                
                @if($class->students->count() > 0)
                <div class="flex flex-wrap gap-1 mt-3 pt-3 border-t border-slate-100">
                    <span class="text-[8px] font-medium text-slate-400">Students:</span>
                    @foreach($class->students->take(3) as $student)
                    <span class="text-[8px] font-medium text-slate-600 bg-slate-50 px-2 py-0.5 rounded-full">{{ substr($student->username, 0, 12) }}</span>
                    @endforeach
                    @if($class->students->count() > 3)
                    <span class="text-[8px] font-medium text-indigo-500">+{{ $class->students->count() - 3 }} more</span>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
        
        @if($classes->isEmpty())
        <div class="text-center py-12 bg-white rounded-xl border border-slate-200">
            <i class="fas fa-school text-4xl text-slate-300 mb-3"></i>
            <p class="text-slate-400">No classes found in this department</p>
        </div>
        @endif
    </div>

    <!-- Participants Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-users text-indigo-500"></i>
                    <h3 class="font-bold text-slate-900">Enrolled Participants</h3>
                    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[9px] font-bold rounded-full">{{ $enrolledUsers->count() }} Total</span>
                </div>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                    <input type="text" id="searchParticipants" placeholder="Search by name or email..." 
                           class="pl-8 pr-3 py-2 border border-slate-200 rounded-lg text-xs w-56 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none">
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-[9px] font-bold text-slate-400 uppercase">User</th>
                        <th class="px-6 py-3 text-[9px] font-bold text-slate-400 uppercase">Email</th>
                        <th class="px-6 py-3 text-[9px] font-bold text-slate-400 uppercase">Role</th>
                        <th class="px-6 py-3 text-[9px] font-bold text-slate-400 uppercase">Classes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="participantsTable">
                    @forelse($enrolledUsers as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors participant-row">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-100 to-indigo-200 text-indigo-700 flex items-center justify-center text-xs font-bold">
                                    {{ substr($user->username, 0, 1) }}
                                </div>
                                <span class="text-[13px] font-semibold text-slate-800">{{ $user->username }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-[11px] text-slate-500">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 rounded-full text-[8px] font-bold uppercase {{ $user->role_id == 2 ? 'bg-indigo-50 text-indigo-600' : 'bg-emerald-50 text-emerald-600' }}">
                                {{ $user->role_id == 2 ? 'Teacher' : 'Student' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->classes as $class)
                                <span class="text-[8px] px-2 py-0.5 bg-slate-100 rounded-full text-slate-600">{{ $class->class_name }}</span>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <i class="fas fa-user-slash text-3xl text-slate-300 mb-3 block"></i>
                            <p class="text-slate-400">No participants enrolled yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Enrollment Modal -->
<div id="enrollmentModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl mx-4 max-h-[85vh] overflow-hidden animate-fade-in">
        <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-4 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Manage Class Enrollments</h3>
                <p class="text-[10px] text-slate-500">Assign teachers and students to each class</p>
            </div>
            <button onclick="closeEnrollmentModal()" class="w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition-all">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        
        <form id="enrollmentForm" action="{{ route('admin.enrollments.update', $department->id) }}" method="POST" class="overflow-y-auto" style="max-height: calc(85vh - 70px);">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                @foreach($classes as $class)
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <div class="bg-slate-50 px-5 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-door-open text-indigo-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">{{ $class->class_name }}</h4>
                                <p class="text-[9px] text-slate-500">Major: {{ $class->major->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <span class="text-[9px] text-slate-400">ID: {{ $class->id }}</span>
                    </div>
                    
                    <div class="p-5 space-y-5">
                        <!-- Teachers Selection -->
                        <div>
                            <label class="text-[10px] font-bold text-slate-600 block mb-3">👨‍🏫 Assign Teachers</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-32 overflow-y-auto custom-scrollbar">
                                @foreach($allTeachers as $teacher)
                                <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-indigo-50 transition-colors cursor-pointer">
                                    <input type="checkbox" 
                                           name="class_assignments[{{ $class->id }}][teachers][]" 
                                           value="{{ $teacher->id }}"
                                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-0"
                                           {{ $class->teachers->contains($teacher->id) ? 'checked' : '' }}>
                                    <span class="text-[11px] text-slate-700">{{ $teacher->username }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Students Selection -->
                        <div>
                            <label class="text-[10px] font-bold text-slate-600 block mb-3">🎓 Assign Students</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-48 overflow-y-auto custom-scrollbar">
                                @foreach($allStudents as $student)
                                <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-indigo-50 transition-colors cursor-pointer">
                                    <input type="checkbox" 
                                           name="class_assignments[{{ $class->id }}][students][]" 
                                           value="{{ $student->id }}"
                                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-0"
                                           {{ $class->students->contains($student->id) ? 'checked' : '' }}>
                                    <span class="text-[11px] text-slate-700">{{ $student->username }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                @if($classes->isEmpty())
                <div class="text-center py-8">
                    <p class="text-slate-400">No classes available. Please create classes first.</p>
                </div>
                @endif
                
                <!-- Subjects Section -->
                <div class="border-t border-slate-100 pt-6">
                    <label class="text-[10px] font-bold text-slate-600 block mb-3">📚 Department Subjects</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-40 overflow-y-auto custom-scrollbar p-3 bg-slate-50 rounded-xl">
                        @foreach($subjects as $subject)
                        <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-emerald-50 transition-colors cursor-pointer">
                            <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" 
                                   class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-0"
                                   {{ in_array($subject->id, $assignedSubjects ?? []) ? 'checked' : '' }}>
                            <span class="text-[11px] text-slate-700">{{ $subject->subject_name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="sticky bottom-0 bg-white border-t border-slate-100 px-6 py-4 flex justify-end gap-3">
                <button type="button" onclick="closeEnrollmentModal()" class="px-5 py-2 border border-slate-200 text-slate-600 text-[11px] font-bold rounded-lg hover:bg-slate-50 transition-all">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold rounded-lg transition-all shadow-md">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('searchParticipants')?.addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.participant-row').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
    
    function openEnrollmentModal() {
        document.getElementById('enrollmentModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeEnrollmentModal() {
        document.getElementById('enrollmentModal').style.display = 'none';
        document.body.style.overflow = '';
    }
    
    document.getElementById('enrollmentModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeEnrollmentModal();
    });
    
    function showNotification(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast-notification px-4 py-3 rounded-lg text-white text-xs font-bold shadow-lg ${
            type === 'success' ? 'bg-emerald-500' : type === 'error' ? 'bg-red-500' : 'bg-indigo-500'
        }`;
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} mr-2"></i>${message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
    
    document.getElementById('enrollmentForm')?.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
    });
</script>
@endsection