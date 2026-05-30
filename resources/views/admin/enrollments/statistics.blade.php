{{-- resources/views/admin/enrollments/statistics.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="max-w-full mx-auto p-6 md:p-10 font-inter text-slate-900 bg-white">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 border-b border-slate-100 pb-10">
        <div>
            <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-[0.25em] mb-3">
                <i class="fas fa-chart-bar text-[9px] mr-1"></i> Enrollment Analytics
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">{{ $department->department_name }}</h1>
            <p class="text-sm text-slate-500 mt-2 uppercase tracking-widest text-[11px]">Real-time enrollment statistics and metrics</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('enrollments.index') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-5 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-arrow-left text-[10px]"></i> Back
            </a>
            <a href="{{ route('enrollments.manage', $department->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-sm">
                <i class="fas fa-edit text-[10px]"></i> Manage
            </a>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-12">
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl p-6 border border-indigo-200">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest mb-1">Classes</p>
                    <p class="text-3xl font-bold text-indigo-900">{{ $stats['classes_count'] }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-door-open text-indigo-600 text-lg"></i>
                </div>
            </div>
            <div class="text-[9px] text-indigo-700 font-medium">Total classes in department</div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-6 border border-emerald-200">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest mb-1">Students</p>
                    <p class="text-3xl font-bold text-emerald-900">{{ $stats['total_students'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-emerald-600 text-lg"></i>
                </div>
            </div>
            <div class="text-[9px] text-emerald-700 font-medium">Total enrolled students</div>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-6 border border-orange-200">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-[9px] font-bold text-orange-600 uppercase tracking-widest mb-1">Teachers</p>
                    <p class="text-3xl font-bold text-orange-900">{{ $stats['total_teachers'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chalkboard-user text-orange-600 text-lg"></i>
                </div>
            </div>
            <div class="text-[9px] text-orange-700 font-medium">Total assigned teachers</div>
        </div>

        <div class="bg-gradient-to-br from-rose-50 to-rose-100 rounded-2xl p-6 border border-rose-200">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-[9px] font-bold text-rose-600 uppercase tracking-widest mb-1">Subjects</p>
                    <p class="text-3xl font-bold text-rose-900">{{ $stats['total_subjects'] }}</p>
                </div>
                <div class="w-12 h-12 bg-rose-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book-open text-rose-600 text-lg"></i>
                </div>
            </div>
            <div class="text-[9px] text-rose-700 font-medium">Total assigned subjects</div>
        </div>

        <div class="bg-gradient-to-br from-sky-50 to-sky-100 rounded-2xl p-6 border border-sky-200">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-[9px] font-bold text-sky-600 uppercase tracking-widest mb-1">Avg per Class</p>
                    <p class="text-3xl font-bold text-sky-900">{{ $stats['average_students_per_class'] }}</p>
                </div>
                <div class="w-12 h-12 bg-sky-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-sky-600 text-lg"></i>
                </div>
            </div>
            <div class="text-[9px] text-sky-700 font-medium">Students per class</div>
        </div>
    </div>

    <!-- Class Breakdown Table -->
    <div class="border border-slate-100 rounded-2xl overflow-hidden mb-12">
        <div class="bg-slate-50 px-8 py-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <i class="fas fa-chalkboard"></i> Class Breakdown
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="ps-8 py-4 text-[10px] font-bold text-slate-400 uppercase">Class Name</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase">Students</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase">Teachers</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase">Capacity</th>
                        <th class="pe-8 py-4 text-[10px] font-bold text-slate-400 uppercase">Utilization</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($classes as $class)
                    <tr class="hover:bg-slate-50/50">
                        <td class="ps-8 py-4 text-[13px] font-bold text-slate-800">{{ $class->class_name }}</td>
                        <td class="px-6 py-4 text-[12px] text-slate-600">{{ $class->students->count() }}</td>
                        <td class="px-6 py-4 text-[12px] text-slate-600">{{ $class->teachers->count() }}</td>
                        <td class="px-6 py-4 text-[12px] text-slate-600">{{ $class->capacity ?? '∞' }}</td>
                        <td class="pe-8 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-24 bg-slate-100 rounded-full h-2">
                                    <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ min(100, ($class->students->count() / max(1, $class->capacity ?? 30)) * 100) }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500">{{ round(min(100, ($class->students->count() / max(1, $class->capacity ?? 30)) * 100)) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">No classes found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="border border-slate-100 rounded-2xl overflow-hidden mb-12">
        <div class="bg-slate-50 px-8 py-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <i class="fas fa-history text-indigo-600"></i> Recent Activity
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="ps-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date & Time</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">User/Subject</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Details</th>
                        <th class="pe-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($history as $record)
                    <tr class="hover:bg-slate-50/50 transition-all group">
                        <td class="ps-8 py-4 text-[11px] font-medium text-slate-500">
                            {{ $record->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-[11px]">
                            <span class="px-2 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest
                                {{ $record->action === 'enrolled' ? 'bg-emerald-50 text-emerald-600' : 
                                   ($record->action === 'unenrolled' ? 'bg-red-50 text-red-600' : 
                                   ($record->action === 'subject_assigned' ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-50 text-slate-600')) }}">
                                {{ ucfirst(str_replace('_', ' ', $record->action)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-[11px] font-medium text-slate-900">
                            @if($record->user)
                                {{ $record->user->username }}
                            @elseif($record->subject)
                                {{ $record->subject->subject_name }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[11px] text-slate-600">
                            @if($record->class)
                                Class: {{ $record->class->class_name }}
                            @endif
                            @if($record->reason)
                                <br><span class="text-[9px]">{{ $record->reason }}</span>
                            @endif
                        </td>
                        <td class="pe-8 py-4 text-[11px] font-medium text-slate-600">
                            {{ $record->admin?->username ?? '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <p class="text-[11px] font-bold text-slate-300 uppercase tracking-widest">No activity yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Full History Link -->
    <div class="text-center">
        <a href="{{ route('enrollments.history', $department->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold text-[11px] uppercase tracking-widest rounded-lg transition-all">
            <i class="fas fa-list text-[10px]"></i> View Full History
        </a>
    </div>
</div>
@endsection