@extends('layouts.admin')

@section('topbar-title', 'User Management')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 py-8 md:px-10 lg:py-10 font-inter">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight uppercase">System Users</h1>
            <p class="text-xs font-bold text-indigo-600 mt-1 uppercase tracking-widest">Manage platform administrators, teachers, and enrolled students</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2 shadow-lg">
                <i class="fas fa-plus text-[10px]"></i> Register Entity
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-indigo-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-3">
        <i class="fas fa-check-circle text-indigo-500"></i>
        {{ session('success') }}
    </div>
    @endif

    <!-- Data Table Card -->
    <div class="bg-white rounded-[20px] border border-slate-200/70 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-6 border-b border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">User Directory</h3>
                <span class="px-2.5 py-1 rounded-md bg-white border border-slate-100 text-indigo-600 text-[10px] font-bold uppercase tracking-widest shadow-sm tabular-nums">{{ $users->total() }} Records</span>
            </div>
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full sm:w-80">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-indigo-600 text-xs"></i>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by identity..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-100 rounded-xl text-xs font-bold uppercase tracking-widest text-slate-900 focus:outline-none focus:border-indigo-500 transition-all placeholder:text-slate-300 shadow-sm">
            </form>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-white border-b border-slate-50">
                        <th class="px-6 py-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest w-16">#</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">User Profile</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Contact</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Role</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Joined Date</th>
                        <th class="px-6 py-5 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/80 bg-white">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-5">
                            <span class="text-xs font-bold text-slate-400 tabular-nums">{{ $users->firstItem() + $loop->index }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-lg shadow-indigo-600/20 border border-indigo-500/30 uppercase tabular-nums">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" class="w-full h-full object-cover rounded-xl">
                                    @else
                                        {{ substr($user->username ?? 'U', 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors tabular-nums tracking-tight">{{ $user->username }}</h4>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                                <i class="far fa-envelope text-indigo-500"></i>
                                {{ $user->email ?? '--' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $roleName = strtolower($user->role->role_name ?? 'admin');
                                $badgeClass = 'bg-slate-50 text-slate-600 border-slate-200';
                                
                                if ($roleName === 'admin') {
                                    $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                } elseif ($roleName === 'teacher' || $roleName === 'instructor') {
                                    $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                } elseif ($roleName === 'student') {
                                    $badgeClass = 'bg-emerald-50 text-indigo-700 border-emerald-200';
                                }
                            @endphp
                            <span class="px-2.5 py-1 rounded-md border text-[11px] font-semibold tracking-wide flex inline-flex items-center gap-1.5 {{ $badgeClass }}">
                                {{ ucfirst($roleName) }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-[11px] text-indigo-600 font-bold uppercase tracking-widest tabular-nums">{{ optional($user->created_at)->format('d M, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 transition-opacity">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-indigo-500 hover:bg-emerald-50 transition-colors tooltip-trigger" title="Edit">
                                    <i class="far fa-edit text-sm"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors tooltip-trigger" title="Delete">
                                        <i class="far fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                                @else
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-200 cursor-not-allowed tooltip-trigger" title="Cannot delete your own account">
                                    <i class="far fa-trash-alt text-sm"></i>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="p-12 text-center flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-users-slash text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-base font-semibold text-slate-800 tracking-tight">No Users Found</h3>
                                <p class="text-sm text-slate-500 mt-1 max-w-sm">We couldn't find any users matching your criteria. Try adjusting your search.</p>
                                @if(request('search'))
                                    <a href="{{ route('admin.users.index') }}" class="mt-4 text-sm font-medium text-indigo-500 hover:text-indigo-700">Clear Search</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-8 py-5 border-t border-slate-50 bg-slate-50/50 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest tabular-nums">
                Displaying {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} User Entries
            </span>
            <div class="flex justify-end custom-pagination">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
        @else
        <div class="px-8 py-4 border-t border-slate-50 bg-slate-50/50 flex justify-start">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest tabular-nums">
                Total Authorized: {{ $users->total() }} Records
            </span>
        </div>
        @endif
    </div>
</div>

<style>
/* Custom internal styles specifically for overriding default Laravel pagination to match our Premium Tailwind look without changing global code */
.custom-pagination nav > div:first-child {
    display: none;
}
.custom-pagination nav > div:last-child {
    display: flex;
    justify-content: flex-end;
}
.custom-pagination nav {
    background: transparent !important;
}
.custom-pagination nav p {
    display: none;
}
.custom-pagination nav .relative.z-0.inline-flex {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    border-radius: 0.5rem;
}
.custom-pagination nav .relative.z-0.inline-flex > span,
.custom-pagination nav .relative.z-0.inline-flex > a {
    border-color: #f1f5f9;
    color: #64748b;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
}
.custom-pagination nav .relative.z-0.inline-flex > a:hover {
    background-color: #f8fafc;
    color: #0f172a;
}
.custom-pagination nav .relative.z-0.inline-flex > span[aria-current="page"] > span {
    background-color: #e0e7ff;
    color: #4f46e5;
    border-color: #e0e7ff;
}
.custom-pagination .pagination {
    display: flex;
    padding-left: 0;
    list-style: none;
    margin: 0;
    gap: 0.25rem;
}
.custom-pagination .page-item .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 2rem;
    height: 2rem;
    padding: 0 0.5rem;
    color: #64748b;
    background-color: transparent;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
}
.custom-pagination .page-item.active .page-link {
    background-color: #0f172a;
    color: #fff;
    border-color: #0f172a;
}
.custom-pagination .page-item .page-link:hover:not(.active) {
    background-color: #f1f5f9;
    color: #0f172a;
}
.custom-pagination .page-item.disabled .page-link {
    color: #cbd5e1;
    pointer-events: none;
}
</style>
@endsection
