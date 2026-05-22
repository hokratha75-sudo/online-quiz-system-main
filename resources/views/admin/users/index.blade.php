@extends('layouts.admin')

@section('topbar-title', 'User Management')

@section('content')
<style>
    /* Custom Scrollbar for sleek aesthetic */
    .custom-scrollbar::-webkit-scrollbar { width: 7px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #4f46e5; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #4f46e5; }
</style>
{{-- Success Toast --}}
@if(session('success'))
<div id="toast-success"
     class="fixed top-4 right-3 sm:top-6 sm:right-6 z-[100] flex items-center w-full max-w-xs sm:max-w-sm p-2.5 sm:p-3.5 bg-white rounded-lg sm:rounded-2xl shadow-2xl shadow-emerald-500/20 border border-emerald-100 overflow-hidden backdrop-blur-sm transition-all duration-500 ease-out">

    <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>

    <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 sm:w-10 sm:h-10 text-emerald-600 bg-emerald-50 rounded-lg sm:rounded-xl border border-emerald-100 ml-1">
        <i class="fas fa-check-circle text-xs sm:text-base"></i>
    </div>

    <div class="ml-2 sm:ml-3 flex-1 min-w-0">
        <h4 class="text-xs sm:text-[13px] font-bold text-slate-900 leading-tight truncate">
            Success
        </h4>

        <p class="text-[10px] sm:text-[11px] font-medium text-slate-500 mt-0.5 truncate">
            {{ session('success') }}
        </p>
    </div>

    <button type="button"
            class="group relative w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-emerald-100 border border-slate-200 text-emerald-600 hover:bg-emerald-200 hover:scale-110 focus:outline-none shadow-sm flex-shrink-0 ml-2"
            onclick="closeToastSuccess()">

        <i class="fas fa-times text-[10px] sm:text-xs"></i>
    </button>
</div>
@endif


{{-- Error Toast --}}
@if($errors->any())
<div id="toast-error"
     class="fixed top-16 sm:top-24 right-3 sm:right-6 z-[100] flex items-center w-full max-w-xs sm:max-w-sm p-2.5 sm:p-3.5 bg-white rounded-lg sm:rounded-2xl shadow-2xl shadow-rose-500/20 border border-rose-100 overflow-hidden backdrop-blur-sm transition-all duration-500 ease-out">

    <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>

    <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 sm:w-10 sm:h-10 text-rose-600 bg-rose-50 rounded-lg sm:rounded-xl border border-rose-100 ml-1">
        <i class="fas fa-exclamation-triangle text-xs sm:text-sm"></i>
    </div>

    <div class="ml-2 sm:ml-3 flex-1 min-w-0">
        <h4 class="text-xs sm:text-[13px] font-bold text-slate-900 leading-tight truncate">
            Action Failed
        </h4>

        <p class="text-[10px] sm:text-[11px] font-medium text-slate-500 mt-0.5 truncate">
            {{ $errors->first() }}
        </p>
    </div>

    <button type="button"
            class="group relative w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-rose-100 border border-slate-200 text-rose-600 hover:bg-rose-200 focus:outline-none shadow-sm flex-shrink-0 ml-2"
            onclick="closeToastError()">

        <i class="fas fa-times text-[10px] sm:text-xs"></i>
    </button>
</div>
@endif


<script>
    function hideToast(elementId) {
        const toast = document.getElementById(elementId);

        if (!toast) return;

        toast.classList.add(
            'opacity-0',
            '-translate-y-3',
            'scale-95'
        );

        setTimeout(() => {
            toast.remove();
        }, 500);
    }

    function closeToastSuccess() {
        hideToast('toast-success');
    }

    function closeToastError() {
        hideToast('toast-error');
    }

    document.addEventListener('DOMContentLoaded', () => {

        const successToast = document.getElementById('toast-success');
        const errorToast = document.getElementById('toast-error');

        if (successToast) {
            setTimeout(() => {
                hideToast('toast-success');
            }, 5000);
        }

        if (errorToast) {
            setTimeout(() => {
                hideToast('toast-error');
            }, 5000);
        }
    });
</script>
<div class="max-w-[1600px] mx-auto p-5 md:p-8 font-sans text-slate-800 bg-gradient-to-br from-slate-50 via-white to-slate-100/50 min-h-[calc(100vh-110px)]">

    

    {{-- Header: Tabs + Create Button --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 mb-8">
        <div class="flex flex-wrap gap-2.5">
            <a href="{{ route('admin.users.index', ['search' => $search]) }}" 
               class="no-underline group relative px-3 py-2.5 rounded-full text-[11px] font-black uppercase tracking-wider transition-all duration-300 {{ empty($roleName) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-white/70 backdrop-blur-sm text-slate-600 hover:bg-white hover:shadow-md border border-slate-200/80' }}">
                <span>All Users</span>
                <span class="ml-2 px-2 py-1 rounded-full text-[10px] {{ empty($roleName) ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['total'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin', 'search' => $search]) }}" 
               class="no-underline group relative px-3 py-2.5 rounded-full text-[11px] font-black uppercase tracking-wider transition-all duration-300 {{ $roleName === 'admin' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/30' : 'bg-white/70 backdrop-blur-sm text-slate-600 hover:bg-white hover:shadow-md border border-slate-200/80' }}">
                Admins <span class="ml-2 px-2 py-1 rounded-full text-[10px] {{ $roleName === 'admin' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['admin'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'teacher', 'search' => $search]) }}" 
               class="no-underline group relative px-3 py-2.5 rounded-full text-[11px] font-black uppercase tracking-wider transition-all duration-300 {{ $roleName === 'teacher' ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/30' : 'bg-white/70 backdrop-blur-sm text-slate-600 hover:bg-white hover:shadow-md border border-slate-200/80' }}">
                Teachers <span class="ml-2 px-2 py-1 rounded-full text-[10px] {{ $roleName === 'teacher' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['teacher'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'student', 'search' => $search]) }}" 
               class="no-underline group relative px-3 py-2.5 rounded-full text-[11px] font-black uppercase tracking-wider transition-all duration-300 {{ $roleName === 'student' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-white/70 backdrop-blur-sm text-slate-600 hover:bg-white hover:shadow-md border border-slate-200/80' }}">
                Students <span class="ml-2 px-2 py-1 rounded-full text-[10px] {{ $roleName === 'student' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['student'] ?? 0 }}</span>
            </a>
        </div>
        
        <button type="button" data-bs-toggle="modal" data-bs-target="#createUserModal" class="group inline-flex items-center gap-2 border-none bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white px-10 py-2.5 rounded-lg text-[11px] font-black transition-all duration-200 shadow-lg shadow-indigo-500/20 active:scale-95 uppercase tracking-wider">
            <i class="fas fa-plus-circle text-sm group-hover:rotate-90 transition-transform duration-300"></i>
            Create User
        </button>
    </div>

    {{-- Main Card --}}
    <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 overflow-hidden transition-all duration-300 ">
        {{-- Table Header with Search --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-3 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center shadow-md">
                    <i class="fas fa-users text-xs"></i>
                </div>
                <h3 class="text-sm font-black text-slate-800 tracking-tight mt-2">Member Directory</h3>
                <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black border border-indigo-100">{{ $users->total() }} total</span>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">

                <!-- Bulk Delete Button -->
                    <button class="bg-white hover:bg-rose-200 text-rose-600 border border-slate-100 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-sm" onclick="deleteSelected()">
                    <i class="fas fa-trash-alt text-[10px]"></i> Delete Selected
                </button>
                <form action="{{ route('admin.users.index') }}" method="GET" id="searchForm" class="w-full sm:w-80">
                    @if($roleName)
                        <input type="hidden" name="role" value="{{ $roleName }}">
                    @endif
                    <div class="relative group">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs group-focus-within:text-indigo-500 transition-colors"></i>
                        <input type="text" name="search" id="searchInput" value="{{ $search ?? '' }}" 
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium placeholder:text-slate-300 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none" 
                               placeholder="Search by name or email..." autocomplete="off">
                    </div>
                </form>
            </div>
        </div>

        {{-- Scrollable Table Body --}}
        <div class="overflow-x-auto custom-scrollbar">
            <div class="h-[calc(100vh-405px)] overflow-y-auto custom-scrollbar">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-100/80 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="pl-6 pr-3 py-3 text-left text-[10px] font-black uppercase tracking-wider text-slate-400">#</th>
                            <th class="px-3 py-3 text-left text-[10px] font-black uppercase tracking-wider text-slate-400">User Identity</th>
                            <th class="px-3 py-3 text-left text-[10px] font-black uppercase tracking-wider text-slate-400">Contact</th>
                            <th class="px-3 py-3 text-left text-[10px] font-black uppercase tracking-wider text-slate-400">Role</th>
                            <th class="px-3 py-3 text-left text-[10px] font-black uppercase tracking-wider text-slate-400">Joined</th>
                            <th class="pr-6 py-3 text-center text-[10px] font-black uppercase tracking-wider text-slate-400">Actions</th>
                            <th class="px-4 py-3 w-12 text-right ">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500 cursor-pointer transition-colors shadow-sm">
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($users as $index => $user)
                        <tr class="hover:bg-gradient-to-r hover:from-indigo-50/30 hover:to-transparent transition-all duration-200 group">
                            <td class="pl-6 pr-3 py-3">
                                <span class="text-xs font-bold text-slate-300">{{ $users->firstItem() + $index }}</span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center text-sm font-black shadow-md shadow-indigo-200">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover rounded-xl">
                                        @else
                                            {{ substr($user->username ?? 'U', 0, 1) }}
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold text-slate-800">{{ $user->username }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <span class="text-xs text-slate-500 font-mono">{{ $user->email ?? '--' }}</span>
                            </td>
                            <td class="px-3 py-3">
                                @php $r = strtolower($user->role->role_name ?? 'student'); @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm
                                    {{ $r == 'admin' ? 'bg-rose-100 text-rose-700 border border-rose-200' : ($r == 'teacher' ? 'bg-sky-100 text-sky-700 border border-sky-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200') }}">
                                    <i class="fas {{ $r == 'admin' ? 'fa-shield-alt' : ($r == 'teacher' ? 'fa-chalkboard-user' : 'fa-graduation-cap') }} mr-1.5 text-[9px]"></i>
                                    {{ strtoupper($r) }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <span class="text-xs font-semibold text-slate-500">{{ optional($user->created_at)->format('M d, Y') }}</span>
                            </td>
                            <td class="pr-6 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-white bg-indigo-600 hover:bg-indigo-700 transition-colors tooltip-trigger border-none" title="Edit">
                                        <i class="far fa-edit text-[13px]"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('⚠️ Permanently delete this user?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-white bg-rose-700 hover:bg-rose-700 transition-colors btn-delete border-none">
                                            <i class="far fa-trash-alt text-[13px]"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                @php
                                    $role = strtolower($user->role->role_name ?? '');
                                @endphp

                                @if($role !== 'admin')
                                    <input type="checkbox"
                                           class="row-checkbox w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500 cursor-pointer shadow-sm transition-colors"
                                            value="{{ $user->id }}">
                                    @endif
                                </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-300">
                                        <i class="fas fa-user-slash text-2xl"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">No members found</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/40" id="paginationContainer">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results
                </div>
                <div class="flex gap-1.5">
                    @if ($users->onFirstPage())
                        <span class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-300 text-xs font-bold cursor-not-allowed shadow-sm"><i class="fas fa-chevron-left mr-1 text-[9px]"></i> Prev</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-xs font-bold transition-all shadow-sm"><i class="fas fa-chevron-left mr-1 text-[9px]"></i> Prev</a>
                    @endif

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <span class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-bold shadow-md shadow-indigo-200">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 text-xs font-bold transition-all">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-xs font-bold transition-all shadow-sm">Next <i class="fas fa-chevron-right ml-1 text-[9px]"></i></a>
                    @else
                        <span class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-300 text-xs font-bold cursor-not-allowed shadow-sm">Next <i class="fas fa-chevron-right ml-1 text-[9px]"></i></span>
                    @endif
                </div>
            </div>
        </div>
        @else
            @if($users->total() > 0)
            <div class="p-4 border-t border-slate-50 bg-slate-100/80 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest tabular-nums">
                    Total {{ $users->total() }} member(s)
                </div>
            </div>
            @endif
        @endif
    </div>
</div>
<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
{{-- ============================================================ --}}
{{-- RESIZABLE + DRAGGABLE MODAL (WITH modal-content FIX)        --}}
{{-- ============================================================ --}}
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" id="createUserModalDialog" style="max-width: 880px;">
        <div class="modal-content border-0 shadow-2xl overflow-hidden bg-white/95 backdrop-blur-md">
            {{-- Modal Header with drag handle --}}
            <div class="relative px-7 py-3 bg-gradient-to-r from-[#5f60ef] to-[#9a4ce7] border-b border-slate-100 flex items-center justify-between cursor-grab active:cursor-grabbing" id="createUserModalHeader">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-user-plus text-sm text-white"></i>
                    </div>
                    <h5 class="text-lg font-black text-white tracking-tight mt-2" id="createUserModalLabel">New Member Registration</h5>
                </div>
                <button type="button" class="group relative w-10 h-10 rounded-full bg-blue-100 border-none text-red-600 hover:bg-blue-200 focus:outline-none" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="modal-body p-0">
                <form id="createUserForm" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="divide-y divide-slate-100">
                    @csrf
                    <div class="p-7 grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- LEFT COLUMN: Authentication --}}
                        <div class="space-y-5">
                            <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                                <i class="fas fa-key text-indigo-400 text-sm"></i>
                                <h6 class="text-[15px] font-black text-slate-400 uppercase tracking-widest mt-2">Access Credentials</h6>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-600 mb-1.5">Username <span class="text-rose-400">*</span></label>
                                <input placeholder="Enter Username" type="text" name="username" value="{{ old('username') }}" required class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-600 mb-1.5">Email <span class="text-rose-400">*</span></label>
                                <input placeholder="Enter Email" type="email" name="email" value="{{ old('email') }}" required class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                            </div>
                            <div class="relative w-full">
                                <label class="block text-sm font-bold text-slate-600 mb-1.5">System Role</label>
                                <select name="role_id"
                                    class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none appearance-none">
        
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ ucfirst($role->role_name) }}</option>
                                    @endforeach

                                </select>

                                <!-- Arrow icon -->
                                <div class="pointer-events-none absolute top-5 inset-y-0 right-3 flex items-center text-slate-400">
                                    <i class="fas fa-chevron-down text-sm"></i>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-bold text-slate-600 mb-1.5">Password <span class="text-rose-400">*</span></label>
                                    <input placeholder="Enter password" type="password" name="password" required class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-600 mb-1.5">Confirm</label>
                                    <input placeholder="Confirm password" type="password" name="password_confirmation" required class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: Personal Info + Avatar --}}
                        <div class="space-y-5">
                            <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                                <i class="fas fa-id-card text-indigo-400 text-sm"></i>
                                <h6 class="text-[15px] font-black text-slate-400 uppercase tracking-widest mt-2">Personal Profile</h6>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div><label class="block text-sm font-bold text-slate-600 mb-1.5">First Name</label>
                                    <input placeholder="Enter FirstName" type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                                </div>
                                <div><label class="block text-sm font-bold text-slate-600 mb-1.5">Last Name</label>
                                    <input placeholder="Enter Last Name" type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div><label class="block text-sm font-bold text-slate-600 mb-1.5">Phone</label>
                                    <input placeholder="Enter Phone Number" type="text" name="phone" value="{{ old('phone') }}" class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                                </div>
                                <div><label class="block text-sm font-bold text-slate-600 mb-1.5">Birthday</label>
                                    <input type="date" name="birthday" value="{{ old('birthday') }}" class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div><label class="block text-sm font-bold text-slate-600 mb-1.5">Address</label>
                                    <input placeholder="Enter Address" type="text" name="address" value="{{ old('address') }}" class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-600 mb-1.5">
                                        Sex
                                    </label>

                                    <div class="relative">
                                        <select name="sex"
                                            class="w-full px-2 py-2.5 bg-slate-50 border border-slate-200 rounded text-sm focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all outline-none appearance-none">
                                            <option value="other">Other</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                    
                                        </select>
                                    
                                        <!-- Arrow icon -->
                                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">
                                            <i class="fas fa-chevron-down text-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-600 mb-1.5">Profile Photo</label>
                                <div class="relative group cursor-pointer">
                                    <input type="file" name="profile_photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="photoInput">
                                    <div class="w-full px-4 py-3 border-2 border-dashed border-slate-200 rounded flex items-center justify-center gap-3 group-hover:border-indigo-400 group-hover:bg-indigo-50/30 transition-all bg-slate-50/30" id="photoPreviewArea">
                                        <i class="fas fa-cloud-upload-alt text-slate-400 group-hover:text-indigo-500"></i>
                                        <span class="text-xs font-medium text-slate-500" id="uploadLabel">Click to upload avatar</span>
                                    </div>
                                </div>
                                <div id="imagePreview" class="mt-2 hidden"><img src="" class="w-10 h-10 rounded-lg object-cover shadow-sm"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-100 p-6">
                        <button type="button" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white px-6 py-2.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-500/20 active:scale-95 flex items-center justify-center gap-2 border-none">
                            <i class="fas fa-save text-xs"></i> Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript: Live Search, Image Preview, Modal Drag/Resize --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.row-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
            });
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {

            // បើ unchecked មួយ -> selectAll អត់ checked
            if (!this.checked) {
                selectAll.checked = false;
                return;
            }

            // បើ checked ទាំងអស់ -> selectAll checked
            const allChecked = Array.from(checkboxes).every(c => c.checked);
            selectAll.checked = allChecked;
        });
    });

});
    function deleteSelected() {

    let selected = document.querySelectorAll('.row-checkbox:checked');

    if (selected.length === 0) {
        alert('Please select at least one user.');
        return;
    }

    let confirmDelete = confirm(
        `Are you sure you want to delete ${selected.length} user(s)?`
    );

    if (!confirmDelete) return;

    let form = document.getElementById('deleteForm');

    // 👉 IMPORTANT: change route to users
    form.action = '{{ route("admin.users.bulkDelete") }}';

    // reset form content
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
    `;

    selected.forEach(function (item) {

        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = item.value;

        form.appendChild(input);
    });

    form.submit();
}
    document.addEventListener('DOMContentLoaded', function() {
        // --- Live AJAX Search ---
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        const tableBody = document.querySelector('table tbody');
        const paginationWrap = document.getElementById('paginationContainer');
        const searchEndpoint = '{{ route('admin.users.search') }}';
        
        if(searchInput && tableBody) {
            if(searchForm) searchForm.addEventListener('submit', e => e.preventDefault());
            let debounceTimer;
            const fetchResults = (query) => {
                let roleParam = '';
                const roleHidden = searchForm ? searchForm.querySelector('input[name="role"]') : null;
                if(roleHidden) roleParam = `&role=${encodeURIComponent(roleHidden.value)}`;
                fetch(`${searchEndpoint}?q=${encodeURIComponent(query)}${roleParam}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.json())
                .then(json => {
                    const users = json.data || [];
                    if(users.length === 0) {
                        tableBody.innerHTML = `<tr id="emptyStateRow">
                        <td colspan="7">
                            <div class="p-12 text-center flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-search text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-base font-semibold text-slate-800 tracking-tight">No Users Found</h3>
                                <p class="text-sm text-slate-500 mt-1 max-w-sm">There are currently no users. Click "New User" to get started.</p>
                            </div>
                        </td>
                    </tr>`;
                        if(paginationWrap) paginationWrap.style.display = 'none';
                    } else {
                        let rows = '';
                        users.forEach((u, idx) => {
                            let roleLower = (u.role || 'student').toLowerCase();
                            let badge = roleLower === 'admin' ? `<span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase bg-rose-100 text-rose-700"><i class="fas fa-shield-alt mr-1"></i> ADMIN</span>` : 
                                        (roleLower === 'teacher' ? `<span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase bg-sky-100 text-sky-700"><i class="fas fa-chalkboard-user mr-1"></i> TEACHER</span>` :
                                        `<span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-700"><i class="fas fa-graduation-cap mr-1"></i> STUDENT</span>`);
                            rows += `<tr class="hover:bg-gradient-to-r hover:from-indigo-50/30 transition-all">
                                        <td class="pl-6 pr-3 py-5"><span class="text-xs font-bold text-slate-300">${idx+1}</span></td>
                                        <td class="px-3 py-5"><div class="flex items-center gap-3"><div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-sm font-black shadow">${(u.username || 'U').charAt(0).toUpperCase()}</div><span class="text-sm font-bold text-slate-800">${escapeHtml(u.username)}</span></div></td>
                                        <td class="px-3 py-5"><span class="text-xs text-slate-500">${escapeHtml(u.email || '--')}</span></td>
                                        <td class="px-3 py-5">${badge}</td>
                                        <td class="px-3 py-5"><span class="text-xs font-semibold text-slate-500">${u.created_at || ''}</span></td>
                                        <td class="pr-6 py-5 text-center"><div class="flex justify-center gap-2"><a href="/admin/users/${u.id}/edit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-indigo-600"><i class="fas fa-pen-simple"></i></a></div></td>
                                     </tr>`;
                        });
                        tableBody.innerHTML = rows;
                       if(paginationWrap) paginationWrap.style.display = 'none';
                    }
                }).catch(err => console.warn(err));
            };
            searchInput.addEventListener('input', function () {

    clearTimeout(debounceTimer);

    debounceTimer = setTimeout(() => {

        const query = this.value.trim();

        // If empty search -> reload normal pagination
        if(query === '') {

            if(paginationWrap) {
                paginationWrap.style.display = 'block';
            }

            window.location.href = window.location.pathname;
            return;
        }

        fetchResults(query);

    }, 280);

});
        }

        // --- Image Preview in Modal ---
        const photoInput = document.getElementById('photoInput');
        const previewDiv = document.getElementById('imagePreview');
        if(photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if(file) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        if(previewDiv) {
                            previewDiv.innerHTML = `<img src="${ev.target.result}" class="w-10 h-10 rounded-lg object-cover shadow-sm border border-indigo-200">`;
                            previewDiv.classList.remove('hidden');
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        function escapeHtml(str) {
            return String(str || '').replace(/[&<>]/g, function(m) {
                if(m === '&') return '&amp;';
                if(m === '<') return '&lt;';
                if(m === '>') return '&gt;';
                return m;
            });
        }

        // --- Show modal if validation errors exist ---
        @if($errors->any())
            new bootstrap.Modal(document.getElementById('createUserModal')).show();
        @endif
    });

    // --- DRAG & RESIZE MODAL (independent) ---
    (function() {
        const modal = document.getElementById('createUserModal');
        const dialog = document.getElementById('createUserModalDialog');
        const content = dialog ? dialog.querySelector('.modal-content') : null;
        const header = document.getElementById('createUserModalHeader');
        if(!dialog || !content || !header) return;

        const edges = ['n','s','e','w','ne','nw','se','sw'];
        const cursors = { n:'n-resize', s:'s-resize', e:'e-resize', w:'w-resize', ne:'ne-resize', nw:'nw-resize', se:'se-resize', sw:'sw-resize' };
        
        edges.forEach(dir => {
            const grip = document.createElement('div');
            grip.dataset.dir = dir;
            grip.style.position = 'absolute';
            grip.style.zIndex = '15';
            let isN = dir.includes('n'), isS = dir.includes('s'), isE = dir.includes('e'), isW = dir.includes('w');
            if((isN||isS) && (isE||isW)) {
                grip.style.width = '12px'; grip.style.height = '12px';
                if(isN) grip.style.top = '0';
                if(isS) grip.style.bottom = '0';
                if(isW) grip.style.left = '0';
                if(isE) grip.style.right = '0';
            } else if(isN || isS) {
                grip.style.left = '14px'; grip.style.right = '14px'; grip.style.height = '5px';
                if(isN) grip.style.top = '0';
                else grip.style.bottom = '0';
            } else {
                grip.style.top = '14px'; grip.style.bottom = '14px'; grip.style.width = '5px';
                if(isW) grip.style.left = '0';
                else grip.style.right = '0';
            }
            grip.style.cursor = cursors[dir];
            content.appendChild(grip);
        });

        let mode = null, startX, startY, origL, origT, origW, origH;
        const MIN_W = 520, MIN_H = 500;
        
        function fixRect() {
            const rect = dialog.getBoundingClientRect();
            dialog.style.position = 'fixed';
            dialog.style.margin = '0';
            dialog.style.left = rect.left + 'px';
            dialog.style.top = rect.top + 'px';
            dialog.style.width = rect.width + 'px';
            content.style.height = rect.height + 'px';
            return rect;
        }

        header.addEventListener('mousedown', e => {
            if(e.target.closest('button')) return;
            mode = 'drag';
            header.style.cursor = 'grabbing';
            const r = fixRect();
            startX = e.clientX; startY = e.clientY;
            origL = r.left; origT = r.top;
            e.preventDefault();
        });

        content.addEventListener('mousedown', e => {
            const handle = e.target.closest('[data-dir]');
            if(!handle) return;
            mode = handle.dataset.dir;
            const r = fixRect();
            startX = e.clientX; startY = e.clientY;
            origL = r.left; origT = r.top;
            origW = r.width; origH = r.height;
            e.preventDefault();
        });

        document.addEventListener('mousemove', e => {
            if(!mode) return;
            let dx = e.clientX - startX, dy = e.clientY - startY;
            let vw = window.innerWidth, vh = window.innerHeight;
            if(mode === 'drag') {
                let nl = Math.min(vw - dialog.offsetWidth, Math.max(0, origL + dx));
                let nt = Math.min(vh - dialog.offsetHeight, Math.max(0, origT + dy));
                dialog.style.left = nl + 'px'; dialog.style.top = nt + 'px';
                return;
            }
            let newL = origL, newT = origT, newW = origW, newH = origH;
            if(mode.includes('e')) newW = Math.max(MIN_W, Math.min(vw - origL, origW + dx));
            if(mode.includes('s')) newH = Math.max(MIN_H, Math.min(vh - origT, origH + dy));
            if(mode.includes('w')) { newW = Math.max(MIN_W, Math.min(origL+origW, origW - dx)); newL = origL + origW - newW; }
            if(mode.includes('n')) { newH = Math.max(MIN_H, Math.min(origT+origH, origH - dy)); newT = origT + origH - newH; }
            dialog.style.left = newL + 'px'; dialog.style.top = newT + 'px';
            dialog.style.width = newW + 'px'; content.style.height = newH + 'px';
        });

        document.addEventListener('mouseup', () => {
            if(mode === 'drag') header.style.cursor = 'grab';
            mode = null;
        });

        modal.addEventListener('show.bs.modal', () => {
            dialog.style.position = '';
            dialog.style.left = '';
            dialog.style.top = '';
            dialog.style.width = '';
            dialog.style.margin = 'auto';
            content.style.height = '';
        });
    })();
</script>
@endsection