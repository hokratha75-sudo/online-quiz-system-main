@extends('layouts.admin')

@section('topbar-title', 'User Management')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 py-8 md:px-10 lg:py-10 font-inter">

    <!-- Header Section -->
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-8">
        <div>
            <h1 class="text-2xl md:text-[28px] font-bold text-slate-900 tracking-tight">System Users</h1>
            <p class="text-[14px] font-medium text-slate-500 mt-1.5">Manage platform administrators, teachers, and enrolled students.</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" data-bs-toggle="modal" data-bs-target="#createUserModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[13px] font-semibold transition-all flex items-center gap-2 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <i class="fas fa-plus text-indigo-200 text-xs"></i> Create User
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

    <!-- Data Table Card -->
    <div class="bg-white rounded-[20px] border border-slate-200/70 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-6 border-b border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <h3 class="text-sm font-bold text-slate-900">User Directory</h3>
                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-white border border-slate-200 text-indigo-600 text-xs font-semibold shadow-sm tabular-nums">{{ $users->total() }} Records</span>
            </div>
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full sm:w-80">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by name or email..." 
                       class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all placeholder:text-slate-400 shadow-sm">
            </form>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-white border-b border-slate-50">
                        <th class="px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider w-16">#</th>
                        <th class="px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">User Profile</th>
                        <th class="px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Joined Date</th>
                        <th class="px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
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
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-slate-600 tabular-nums">{{ optional($user->created_at)->format('M d, Y') }}</span>
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
            <span class="text-xs font-medium text-slate-500 tabular-nums">
                Displaying {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} Users
            </span>
            <div class="flex justify-end custom-pagination">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
        @else
        <div class="px-8 py-4 border-t border-slate-50 bg-slate-50/50 flex justify-start">
            <span class="text-xs font-medium text-slate-500 tabular-nums">
                Total Records: {{ $users->total() }} Users
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

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-2xl rounded-3xl overflow-hidden bg-white">
            <div class="modal-header border-b border-slate-100 bg-slate-50 px-8 py-6 flex items-center justify-between">
                <div>
                    <h5 class="text-xl font-bold text-slate-900 tracking-tight" id="createUserModalLabel">Register New User</h5>
                    <p class="text-sm font-medium text-slate-500 mt-1">Create an administrative node, teacher, or student profile.</p>
                </div>
                <button type="button" class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all flex items-center justify-center p-0" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-8">
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        <!-- Authentication Matrix -->
                        <div class="bg-slate-50 p-6 rounded-[24px] border border-slate-100 shadow-sm">
                            <h6 class="text-xs font-bold text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="fas fa-shield-alt text-indigo-500"></i> Authentication
                            </h6>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Username <span class="text-rose-500">*</span></label>
                                    <input type="text" name="username" value="{{ old('username') }}" required
                                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Email <span class="text-rose-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Role <span class="text-rose-500">*</span></label>
                                    <select name="role_id" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all cursor-pointer appearance-none">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst($role->role_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-2">Password <span class="text-rose-500">*</span></label>
                                        <input type="password" name="password" required
                                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-2">Confirm <span class="text-rose-500">*</span></label>
                                        <input type="password" name="password_confirmation" required
                                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="bg-slate-50 p-6 rounded-[24px] border border-slate-100 shadow-sm">
                            <h6 class="text-xs font-bold text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="fas fa-user-tag text-indigo-500"></i> Personal Info
                            </h6>
                            <div class="space-y-5">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-2">First Name <span class="text-rose-500">*</span></label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-2">Last Name <span class="text-rose-500">*</span></label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-2">Phone</label>
                                        <input type="text" name="phone" value="{{ old('phone') }}"
                                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-2">Birthday</label>
                                        <input type="date" name="birthday" value="{{ old('birthday') }}"
                                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Address</label>
                                    <input type="text" name="address" value="{{ old('address') }}"
                                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-2">Gender</label>
                                        <select name="sex" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all cursor-pointer appearance-none">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-2">Profile Photo (Optional)</label>
                                        <input type="file" name="profile_photo" accept="image/*"
                                               class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 mt-8">
                        <button type="button" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-sm font-semibold transition-all" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-all shadow-md active:scale-95">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Script Initialization -->
@if($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('createUserModal'), {
            keyboard: false
        });
        myModal.show();
    });
</script>
@endif

@endsection
