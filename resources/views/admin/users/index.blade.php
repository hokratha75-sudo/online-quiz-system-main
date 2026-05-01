@extends('layouts.admin')

@section('topbar-title', 'User Management')

@section('content')
<div class="max-w-[1400px] mx-auto p-6 md:p-10 font-inter text-slate-900 bg-slate-50/30 min-h-screen">

    @if(session('success'))
    <!-- Toast Notification -->
    <div id="toast-success" class="fixed top-6 right-6 z-[100] flex items-center w-full max-w-sm p-4 text-slate-600 bg-white rounded-[16px] shadow-2xl shadow-emerald-500/10 border border-emerald-100 transform transition-all duration-300 translate-y-0 opacity-100" role="alert">
        <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 text-emerald-500 bg-emerald-50 rounded-xl border border-emerald-100">
            <i class="fas fa-check text-lg"></i>
        </div>
        <div class="ms-4 text-sm font-bold tracking-wide">{{ session('success') }}</div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-slate-400 hover:text-rose-500 rounded-lg p-1.5 hover:bg-rose-50 inline-flex items-center justify-center h-8 w-8 transition-colors" onclick="this.closest('#toast-success').remove()" aria-label="Close">
            <i class="fas fa-xmark text-lg"></i>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-success');
            if (toast) {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('-translate-y-4', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    </script>
    @endif

    <!-- Top Actions & Filters -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <!-- Role Filter Tabs -->
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.users.index', ['search' => $search]) }}" 
               class="no-underline px-5 py-2.5 rounded-[16px] text-[11px] font-bold uppercase tracking-widest transition-all {{ empty($roleName) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 border border-indigo-600' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200' }}">
                All Users <span class="ml-2 px-2 py-1 rounded-lg text-[10px] {{ empty($roleName) ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $counts['total'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin', 'search' => $search]) }}" 
               class="no-underline px-5 py-2.5 rounded-[16px] text-[11px] font-bold uppercase tracking-widest transition-all {{ $roleName === 'admin' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/20 border border-rose-500' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200' }}">
                Admins <span class="ml-2 px-2 py-1 rounded-lg text-[10px] {{ $roleName === 'admin' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $counts['admin'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'teacher', 'search' => $search]) }}" 
               class="no-underline px-5 py-2.5 rounded-[16px] text-[11px] font-bold uppercase tracking-widest transition-all {{ $roleName === 'teacher' ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/20 border border-blue-500' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200' }}">
                Teachers <span class="ml-2 px-2 py-1 rounded-lg text-[10px] {{ $roleName === 'teacher' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $counts['teacher'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'student', 'search' => $search]) }}" 
               class="no-underline px-5 py-2.5 rounded-[16px] text-[11px] font-bold uppercase tracking-widest transition-all {{ $roleName === 'student' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 border border-emerald-500' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200' }}">
                Students <span class="ml-2 px-2 py-1 rounded-lg text-[10px] {{ $roleName === 'student' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $counts['student'] ?? 0 }}</span>
            </a>
        </div>
        
        <!-- Create Button -->
        <button type="button" data-bs-toggle="modal" data-bs-target="#createUserModal" class="shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white px-7 py-3 rounded-[16px] text-[11px] font-bold transition-all flex items-center gap-3 shadow-xl shadow-indigo-600/20 active:scale-[0.98] uppercase tracking-widest">
            <i class="fas fa-plus"></i> Create User
        </button>
    </div>

    <!-- Data Table Card (Standard Clean Style) -->
    <div class="card shadow-sm border-0 rounded-[24px]">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h3 class="text-sm font-bold text-slate-900">Member Directory</h3>
                <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-[10px] font-black uppercase tabular-nums tracking-widest">{{ $users->total() }} total</span>
            </div>
            <!-- Bootstrap Search Input Group -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="w-full sm:w-80">
                @if($roleName)
                    <input type="hidden" name="role" value="{{ $roleName }}">
                @endif
                <div class="input-group shadow-sm rounded-[20px] overflow-hidden">
                    <span class="input-group-text bg-white border-end-0 text-slate-400 ps-4">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="SEARCH COMMUNITY..." 
                           class="form-control border-start-0 text-[10px] font-bold text-slate-700 placeholder-slate-300 uppercase tracking-widest py-3 px-0 shadow-none focus:ring-0">
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-hover table-borderless align-middle mb-0">
                <thead class="table-light text-slate-500 text-xs uppercase tracking-widest">
                    <tr>
                        <th class="ps-4 py-3" style="width: 60px;">#</th>
                        <th class="py-3">User Identity</th>
                        <th class="py-3">Contact Email</th>
                        <th class="py-3" style="width: 150px;">Role Type</th>
                        <th class="py-3" style="width: 180px;">Registration Date</th>
                        <th class="pe-4 py-3 text-center" style="width: 120px;">Management</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td>
                            <span class="font-bold text-slate-400">{{ $users->firstItem() + $index }}.</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-[10px] font-black border border-indigo-500/30 uppercase">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        {{ substr($user->username ?? 'U', 0, 1) }}
                                    @endif
                                </div>
                                <span class="text-[13px] font-bold text-slate-900">{{ $user->username }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-[12px] font-medium text-slate-500">{{ $user->email ?? '--' }}</span>
                        </td>
                        <td>
                            @php
                                $roleName = strtolower($user->role->role_name ?? 'student');
                            @endphp
                            @if($roleName == 'admin')
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 text-[10px] tracking-widest">{{ strtoupper($roleName) }}</span>
                            @elseif(in_array($roleName, ['teacher', 'instructor']))
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 text-[10px] tracking-widest">{{ strtoupper($roleName) }}</span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 text-[10px] tracking-widest">{{ strtoupper($roleName) }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-[12px] font-bold text-slate-600">{{ optional($user->created_at)->format('M d, Y') }}</span>
                        </td>
                        <td class="pe-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary rounded-xl" data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-pen-to-square"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently remove this member?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-xl ms-1" data-bs-toggle="tooltip" title="Delete">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-slate-400 font-medium uppercase tracking-widest text-xs">No community members found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                MEMBERS {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} OF {{ $users->total() }}
            </p>
            <div class="pagination-clean">
                {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>


<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" id="createUserModalDialog" style="max-width: 750px;">
        <div class="modal-content border-0 shadow-2xl bg-white rounded-2xl overflow-hidden">
            <div class="modal-header px-8 py-6 flex items-center justify-between border-b border-slate-100 bg-slate-50/50" id="createUserModalHeader">
                <h5 class="text-xl font-bold text-slate-900 tracking-tight" id="createUserModalLabel"><i class="fas fa-user-plus text-indigo-200 mr-2"></i> Create a new user</h5>
                <button type="button" class="text-slate-400 hover:text-slate-600 transition-colors" data-bs-dismiss="modal">
                    <i class="fas fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="modal-body p-8">
                <form id="createUserForm" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Column 1: Authentication & Access -->
                        <div class="space-y-6">
                            <h6 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Authentication & Access</h6>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Username</label>
                                    <input type="text" name="username" value="{{ old('username') }}" required
                                           class="form-control px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:border-indigo-600 transition-all shadow-none"
                                           placeholder="Enter username">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                           class="form-control px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:border-indigo-600 transition-all shadow-none"
                                           placeholder="user@example.com">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">System Role</label>
                                    <select name="role_id" class="form-select px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:border-indigo-600 transition-all shadow-none cursor-pointer">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst($role->role_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Password</label>
                                        <input type="password" name="password" required
                                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                               placeholder="••••••••">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Confirm</label>
                                        <input type="password" name="password_confirmation" required
                                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                               placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Personal Information -->
                        <div class="space-y-6">
                            <h6 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Personal Profile</h6>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">First Name</label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                               placeholder="Hok">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Last Name</label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                               placeholder="Ratha">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Phone</label>
                                        <input type="text" name="phone" value="{{ old('phone') }}"
                                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                               placeholder="+855 00 000">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Date of Birth</label>
                                        <input type="date" name="birthday" value="{{ old('birthday') }}"
                                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Address</label>
                                        <input type="text" name="address" value="{{ old('address') }}"
                                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:border-indigo-600 transition-all outline-none"
                                               placeholder="City, Country">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-2">Sex</label>
                                        <select name="sex" class="form-select px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:border-indigo-600 transition-all shadow-none cursor-pointer">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Upload photo</label>
                                    <div class="relative group cursor-pointer">
                                        <input type="file" name="profile_photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="photoInput">
                                        <div class="w-full px-4 py-4 border-2 border-dashed border-slate-300 rounded-xl flex items-center justify-center gap-3 group-hover:border-indigo-600 transition-colors bg-slate-50/50" id="photoPreview">
                                            <i class="fas fa-cloud-arrow-up text-xl text-slate-400 group-hover:text-indigo-600 transition-colors"></i>
                                            <span class="text-xs font-medium text-slate-500" id="uploadLabel">Click to upload photo</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mt-12">
                        <button type="submit" class="w-full h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-sm transition-all shadow-xl shadow-indigo-600/20 active:scale-[0.99] uppercase tracking-widest">
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
