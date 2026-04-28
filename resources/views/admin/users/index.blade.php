@extends('layouts.admin')

@section('topbar-title', 'User Management')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 py-8 md:px-10 lg:py-10 font-inter">

    <!-- Header Section -->
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-8">
        <div>
                                                                                                <h1 class="text-2xl md:text-[28px] font-bold text-slate-900 tracking-tight" style="font-family: 'Open Sans', Helvetica, Arial, sans-serif !important;">School Community</h1>
                                                <p class="text-[14px] font-medium text-slate-500 mt-1.5">Manage everyone who uses the app, from teachers to students.</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" data-bs-toggle="modal" data-bs-target="#createUserModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[13px] font-semibold transition-all flex items-center gap-2 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                                <i class="fas fa-plus text-indigo-200 text-xs"></i> Create User
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

    <!-- Data Table Card -->
    <div class="bg-white rounded-[20px] border border-slate-200/70 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex flex-col overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-6 border-b border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center gap-3">
                                                                <h3 class="text-sm font-bold text-slate-900">Member List</h3>
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-white border border-slate-200 text-indigo-600 text-xs font-semibold shadow-sm tabular-nums">{{ $users->total() }} Members</span>
            </div>
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full sm:w-80">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                                                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by name or email..." 
                       class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all placeholder:text-slate-400 shadow-sm">
            </form>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-left table-fixed whitespace-nowrap">
                <colgroup>
                    <col class="w-[5%]">
                    <col class="w-[22%]">
                    <col class="w-[28%]">
                    <col class="w-[13%]">
                    <col class="w-[17%]">
                    <col class="w-[15%]">
                </colgroup>
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="pl-6 pr-2 py-4 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">#</th>
                                                                                                <th class="px-4 py-4 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Name</th>
                                                                                                <th class="px-4 py-4 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Contact</th>
                                                                                                <th class="px-4 py-4 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Role</th>
                                                                                                <th class="px-4 py-4 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Member Since</th>
                                                                                                <th class="px-4 pr-6 py-4 text-[11px] font-semibold text-slate-400 uppercase tracking-wider text-center">Manage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/60 bg-white">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/60 transition-colors duration-150 group">
                        <td class="pl-6 pr-2 py-4">
                            <span class="text-xs font-bold text-slate-400 tabular-nums">{{ $users->firstItem() + $loop->index }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-lg shadow-indigo-600/20 border border-indigo-500/30 uppercase tabular-nums">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" class="w-full h-full object-cover rounded-xl">
                                    @else
                                        {{ substr($user->username ?? 'U', 0, 1) }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors tracking-tight truncate">{{ $user->username }}</h4>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-500 min-w-0">
                                <i class="far fa-envelope text-indigo-400 shrink-0"></i>
                                <span class="truncate">{{ $user->email ?? '--' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
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
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md border text-[11px] font-semibold tracking-wide {{ $badgeClass }}">
                                {{ ucfirst($roleName) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-xs font-medium text-slate-500 tabular-nums">{{ optional($user->created_at)->format('M d, Y') }}</span>
                        </td>
                        <td class="px-4 pr-6 py-4">
                            <div class="flex items-center justify-center gap-1 transition-opacity">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-indigo-500 hover:bg-indigo-50 transition-colors" title="Edit">
                                    <i class="far fa-edit text-sm"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this member? This will permanently delete their account and all associated progress.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Delete">
                                        <i class="far fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                                @else
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-200 cursor-not-allowed" title="Cannot delete your own account">
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
                Showing {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} Members
            </span>
            <div class="flex justify-end custom-pagination">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
        @else
        <div class="px-8 py-4 border-t border-slate-50 bg-slate-50/50 flex justify-start">
            <span class="text-xs font-medium text-slate-500 tabular-nums">
                Total Members: {{ $users->total() }}
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
    <div class="modal-dialog modal-xl modal-dialog-centered" id="createUserModalDialog" style="max-width: 950px;">
        <div class="modal-content border border-slate-300 shadow-2xl bg-white rounded-none overflow-hidden">
            <div class="modal-header px-8 py-6 flex items-center justify-between border-b border-slate-200 bg-slate-50">
                <h5 class="text-xl font-bold text-slate-900 tracking-tight" id="createUserModalLabel" style="font-family: 'Open Sans', Helvetica, Arial, sans-serif !important;">Create a new user</h5>
                <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors" data-bs-dismiss="modal">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="modal-body p-8">
                <form id="createUserForm" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        
                        <!-- Column 1: Authentication Information -->
                        <div class="space-y-6">
                            <h6 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Authentication Information</h6>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Username</label>
                                    <input type="text" name="username" value="{{ old('username') }}" required
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-none text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                           placeholder="Enter username">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-none text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                           placeholder="user@example.com">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Password</label>
                                    <input type="password" name="password" required
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-none text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                           placeholder="••••••••">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Confirm Password</label>
                                    <input type="password" name="password_confirmation" required
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-none text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                           placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Personal Information -->
                        <div class="space-y-6">
                            <h6 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Personal Information</h6>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">First Name</label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                               class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-none text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                               placeholder="Hok">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Last Name</label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                               class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-none text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                               placeholder="Ratha">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-none text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                           placeholder="+855 00 000 000">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Address</label>
                                    <input type="text" name="address" value="{{ old('address') }}"
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-none text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                           placeholder="City, Country">
                                </div>
                            </div>
                        </div>

                        <!-- Column 3: Profile & Settings -->
                        <div class="space-y-6">
                            <h6 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Profile & Settings</h6>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Sex</label>
                                        <div class="relative">
                                            <select name="sex" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-none text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none appearance-none cursor-pointer">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Role</label>
                                        <div class="relative">
                                            <select name="role_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-none text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none appearance-none cursor-pointer">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ ucfirst($role->role_name) }}</option>
                                                @endforeach
                                            </select>
                                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Date of Birth</label>
                                    <input type="date" name="birthday" value="{{ old('birthday') }}"
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-none text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Upload photo</label>
                                    <div class="relative group cursor-pointer">
                                        <input type="file" name="profile_photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="photoInput">
                                        <div class="w-full px-4 py-6 border-2 border-dashed border-slate-300 rounded-none flex flex-col items-center gap-2 group-hover:border-indigo-600 transition-colors bg-slate-50/50" id="photoPreview">
                                            <i class="fas fa-cloud-upload-alt text-2xl text-slate-300 group-hover:text-indigo-600 transition-colors"></i>
                                            <span class="text-xs font-medium text-slate-400" id="uploadLabel">Click to upload photo</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mt-12">
                        <button type="submit" class="w-full h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-none font-bold text-sm transition-all shadow-xl shadow-indigo-600/10 active:scale-[0.99]">
                            Create User
                        </button>
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


<script>
(function () {
    const modal   = document.getElementById('createUserModal');
    const dialog  = document.getElementById('createUserModalDialog');
    const content = dialog ? dialog.querySelector('.modal-content') : null;
    const header  = document.getElementById('createUserModalHeader');

    if (!dialog || !content || !header) return;

    // ── Add resize handles ──────────────────────────────────────────────
    const edges = ['n','s','e','w','ne','nw','se','sw'];
    const cursors = { n:'n-resize', s:'s-resize', e:'e-resize', w:'w-resize',
                      ne:'ne-resize', nw:'nw-resize', se:'se-resize', sw:'sw-resize' };

    edges.forEach(dir => {
        const h = document.createElement('div');
        h.dataset.dir = dir;
        h.style.cssText = `position:absolute;z-index:10;`;
        const isN = dir.includes('n'), isS = dir.includes('s'),
              isE = dir.includes('e'), isW = dir.includes('w');
        const corner = (isN||isS) && (isE||isW);
        h.style.cursor = cursors[dir];
        if (corner) {
            h.style.width  = '14px';
            h.style.height = '14px';
            h.style.top    = isN ? '0' : 'auto';
            h.style.bottom = isS ? '0' : 'auto';
            h.style.left   = isW ? '0' : 'auto';
            h.style.right  = isE ? '0' : 'auto';
        } else {
            if (dir === 'n' || dir === 's') {
                h.style.left   = '14px';
                h.style.right  = '14px';
                h.style.height = '6px';
                h.style.top    = dir === 'n' ? '0' : 'auto';
                h.style.bottom = dir === 's' ? '0' : 'auto';
            } else {
                h.style.top    = '14px';
                h.style.bottom = '14px';
                h.style.width  = '6px';
                h.style.left   = dir === 'w' ? '0' : 'auto';
                h.style.right  = dir === 'e' ? '0' : 'auto';
            }
        }
        content.appendChild(h);
    });

    // ── Shared state ────────────────────────────────────────────────────
    let mode = null; // 'drag' | resize dir
    let startX, startY, origLeft, origTop, origW, origH;

    const MIN_W = 500, MIN_H = 350;

    function fixedRect() {
        const r = dialog.getBoundingClientRect();
        dialog.style.position = 'fixed';
        dialog.style.margin   = '0';
        dialog.style.left     = r.left + 'px';
        dialog.style.top      = r.top  + 'px';
        dialog.style.width    = r.width + 'px';
        content.style.height  = r.height + 'px';
        return r;
    }

    // ── Drag (header) ───────────────────────────────────────────────────
    header.addEventListener('mousedown', e => {
        if (e.target.closest('button')) return;
        mode = 'drag';
        header.style.cursor = 'grabbing';
        const r = fixedRect();
        startX = e.clientX; startY = e.clientY;
        origLeft = r.left;  origTop  = r.top;
        e.preventDefault();
    });

    // ── Resize handles ───────────────────────────────────────────────────
    content.addEventListener('mousedown', e => {
        const handle = e.target.closest('[data-dir]');
        if (!handle) return;
        mode = handle.dataset.dir;
        const r = fixedRect();
        startX = e.clientX; startY = e.clientY;
        origLeft = r.left;  origTop = r.top;
        origW = r.width;    origH  = r.height;
        e.preventDefault();
    });

    // ── Move / Resize ────────────────────────────────────────────────────
    document.addEventListener('mousemove', e => {
        if (!mode) return;
        const dx = e.clientX - startX;
        const dy = e.clientY - startY;

        const vw = window.innerWidth;
        const vh = window.innerHeight;

        if (mode === 'drag') {
            let nextL = origLeft + dx;
            let nextT = origTop + dy;
            
            // Boundary checks for drag
            nextL = Math.max(0, Math.min(vw - dialog.offsetWidth, nextL));
            nextT = Math.max(0, Math.min(vh - dialog.offsetHeight, nextT));

            dialog.style.left = nextL + 'px';
            dialog.style.top  = nextT + 'px';
            return;
        }

        let newL = origLeft, newT = origTop, newW = origW, newH = origH;

        if (mode.includes('e')) newW = Math.max(MIN_W, Math.min(vw - origLeft, origW + dx));
        if (mode.includes('s')) newH = Math.max(MIN_H, Math.min(vh - origTop, origH + dy));
        if (mode.includes('w')) { 
            newW = Math.max(MIN_W, Math.min(origLeft + origW, origW - dx)); 
            newL = origLeft + origW - newW; 
        }
        if (mode.includes('n')) { 
            newH = Math.max(MIN_H, Math.min(origTop + origH, origH - dy)); 
            newT = origTop + origH - newH; 
        }

        dialog.style.left    = newL + 'px';
        dialog.style.top     = newT + 'px';
        dialog.style.width   = newW + 'px';
        content.style.height = newH + 'px';
        content.style.display = 'flex';
        content.style.flexDirection = 'column';
    });

    // ── Release ──────────────────────────────────────────────────────────
    document.addEventListener('mouseup', () => {
        if (mode === 'drag') header.style.cursor = 'grab';
        mode = null;
    });

    // ── Reset on open ────────────────────────────────────────────────────
    modal.addEventListener('show.bs.modal', () => {
        dialog.style.position = '';
        dialog.style.left     = '';
        dialog.style.top      = '';
        dialog.style.width    = '';
        dialog.style.margin   = 'auto';
        content.style.height  = '';
    });
})();
</script>

@endsection
