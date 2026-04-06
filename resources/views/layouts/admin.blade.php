<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $dashboardTitle ?? 'Admin' }} - QuizMaster</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Figtree:wght@300;400;500;600;700;800;900&family=Kantumruy+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 loaded for backward compatibility with older views -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js for modern transitions and interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <!-- Tailwind CSS (via CDN for immediate rendering without Vite build) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        corePlugins: {
          preflight: false, // Prevents Tailwind from breaking Bootstrap styles
        },
        theme: {
          extend: {
            colors: {
              primary: '#4f46e5', // Brand Indigo
            },
            fontFamily: {
              sans: ['Inter', 'Kantumruy Pro', 'sans-serif'],
              heading: ['Figtree', 'sans-serif'],
            }
          }
        }
      }
    </script>

    <!-- Tailwind CSS & Vite (Fallback) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')

    <!-- Legacy Style Overrides to keep non-refactored pages from breaking completely -->
    <style>
        .page-wrap { padding: 30px; flex: 1; }
        .card-custom { background: #ffffff !important; border-radius: 20px !important; border: none !important; box-shadow: 0 10px 30px rgba(0,0,0,0.03) !important; padding: 20px !important; }
        .card-header-inner { border-bottom: 1px solid #f3f4f6 !important; padding: 10px 0 20px 0 !important; margin-bottom: 20px !important; }
        .card-title-custom { font-size: 18px !important; font-weight: 600 !important; color: #111827 !important; border: none !important; }
        .btn-blue, .btn-primary { background: #4f46e5 !important; color: white !important; border: none !important; border-radius: 8px !important; padding: 8px 16px !important; font-weight: 500 !important; box-shadow: 0 2px 4px rgba(91, 108, 249, 0.2) !important; transition: all 0.2s !important; }
        .btn-pink, .btn-secondary { background: white !important; color: #111827 !important; border: 1px solid #f3f4f6 !important; border-radius: 8px !important; padding: 8px 16px !important; font-weight: 500 !important; }
        .table-custom { width: 100% !important; margin-bottom: 0 !important; border-collapse: separate !important; border-spacing: 0 !important; }
        .table-custom th { border: none !important; border-bottom: 1px solid #f3f4f6 !important; padding: 14px 20px !important; color: #6b7280 !important; font-weight: 600 !important; font-size: 12px !important; text-transform: uppercase !important; letter-spacing: 0.5px !important; background: white !important; }
        .table-custom td { padding: 16px 20px !important; vertical-align: middle !important; border: none !important; border-bottom: 1px solid #f3f4f6 !important; color: #111827 !important; font-size: 14px !important; font-weight: 500 !important; }
        
        /* Simplified Sidebar Styles */
        .sidebar-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            text-decoration: none !important;
        }
        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white !important;
        }
        .sidebar-item-active {
            background: rgba(79, 70, 229, 0.1) !important;
            border: 1px solid rgba(79, 70, 229, 0.25) !important;
            color: #818cf8 !important; /* Indigo-400 */
            backdrop-filter: blur(10px);
        }
        .sidebar-item * { text-decoration: none !important; }

        /* Glassmorphism for Dashboard Panels */
        .glass-panel { 
            background: rgba(255, 255, 255, 0.65) !important; 
            backdrop-filter: blur(16px) !important; 
            -webkit-backdrop-filter: blur(16px) !important; 
            border: 1px solid rgba(255, 255, 255, 0.5) !important; 
            box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.03) !important;
        }
    </style>
</head>
<!-- === ចាប់ផ្ដើមរចនាសម្ព័ន្ធ Layout === -->
<!-- 
  - bg-slate-50: កំណត់ពណ៌ផ្ទៃខាងក្រោយទាំងមូលពណ៌ប្រផេះស្រាល
  - text-slate-900: កំណត់ពណ៌អក្សរទូទៅទៅជាប្រផេះដិតងងឹត
  - antialiased: ធ្វើឱ្យអក្សរមើលទៅម៉ត់ច្បាស់ល្អ
  - flex & min-h-screen: រៀបចំទំព័រឱ្យពេញកម្ពស់អេក្រង់និងបែងចែក (Sidebar + Main Content)
-->
<body class="bg-slate-50 text-slate-900 font-sans antialiased flex min-h-screen overflow-x-hidden">
@php
    $authUser = auth()->user();
    $dashboardRoute = auth()->check() && (int) auth()->user()->role_id === 1 ? route('admin.dashboard') : route('dashboard');
    $departmentDataActive = request()->routeIs('admin.departments.*') || (request()->routeIs('admin.majors.index') && request('tab') === 'departments');
    $majorActive = request()->routeIs('admin.majors.show') || (request()->routeIs('admin.majors.index') && request('tab', 'majors') === 'majors');
    $classActive = request()->routeIs('admin.classes.*') || (request()->routeIs('admin.majors.index') && request('tab') === 'classes');
    $courseActive = request()->routeIs('admin.subjects.show') || (request()->routeIs('admin.majors.index') && request('tab') === 'subjects');
    $departmentMenuOpen = $departmentDataActive || $majorActive || $classActive || $courseActive;
@endphp

<!-- === ផ្នែក Sidebar (របារបញ្ឈរខាងឆ្វេង) === -->
<!-- 
  - w-[260px]: កំណត់ប្រវែងទទឹង Sidebar 260 ភីកសែល
  - shrink-0: ការពារកុំឱ្យ Sidebar រួញតូចពេលអេក្រង់តូច
  - bg-slate-900: ពណ៌ផ្ទៃងងឹតសម្រាប់ Sidebar
  - h-screen & sticky top-0: ធ្វើឱ្យវាអណ្តែតជាប់ជានិច្ចពេលយើង Scroll ចុះក្រោម
-->
@unless($hideSidebar ?? false)
<aside class="w-[260px] shrink-0 bg-slate-900 border-r border-slate-800 flex flex-col h-screen sticky top-0 custom-scrollbar z-50">
@endunless
    
    <!-- ផ្នែក Logo និងបរិយាយឈ្មោះប្រព័ន្ធ (Brand) -->
    <div class="px-6 pt-7 pb-6 flex items-center gap-3">
        <!-- រូបតំណាង (Icon) ដែលមានផ្ទៃពណ៌ពព្រុស (bg-indigo-500) -->
        <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center text-white shadow-sm shrink-0">
            <i class="fas fa-layer-group text-sm"></i>
        </div>
        <div>
            <h1 class="text-white font-bold tracking-tight text-lg leading-none uppercase">QuizMaster</h1>
            <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mt-1 block tracking-tight">Core Enterprise</span>
        </div>
    </div>

    @php $role = (int)($authUser->role_id ?? 0); @endphp

    <div class="flex-1 overflow-y-auto px-4 space-y-8 custom-scrollbar pb-6">
        <!-- Dashboard menu -->
        <div class="space-y-1 mt-4">
            <a href="{{ $role === 3 ? route('students.dashboard') : route('dashboard') }}" 
               class="sidebar-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') || request()->routeIs('students.dashboard') ? 'sidebar-item-active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('dashboard') || request()->routeIs('students.dashboard') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                    <i class="fas fa-home text-[12px]"></i>
                </div>
                <span class="text-sm font-bold tracking-tight">Dashboard</span>
            </a>
        </div>

        @if($role === 1)
        <!-- System Administration -->
        <div class="pt-2">
            <div class="px-4 mb-4 flex items-center justify-between">
                <h2 class="text-[11px] font-bold text-indigo-500 uppercase tracking-widest">Administration</h2>
                <div class="h-px bg-slate-800 flex-grow ml-4"></div>
            </div>
            <div class="space-y-1">
                <a href="{{ route('admin.users.index') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.users.*') ? 'sidebar-item-active' : 'text-slate-400' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-users text-[12px]"></i>
                    </div>
                    <span class="text-sm font-bold tracking-tight text-inherit">Users</span>
                </a>
                
                <div x-data="{ expanded: {{ $departmentMenuOpen ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" 
                            class="sidebar-item w-full group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 {{ $departmentMenuOpen ? 'text-white' : 'text-slate-400 font-bold' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ $departmentMenuOpen ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                            <i class="fas fa-building text-[12px]"></i>
                        </div>
                        <span class="text-sm tracking-tight flex-1 text-left">Academic</span>
                        <i class="fas fa-chevron-down text-[10px] opacity-40 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="expanded" x-collapse>
                        <div class="pl-12 pr-3 py-2 space-y-1 mt-1 border-l border-slate-800 ml-8">
                            <a href="{{ route('admin.departments.index') }}" class="block px-3 py-2 text-[11px] transition-colors rounded-xl uppercase tracking-widest no-underline {{ request()->routeIs('admin.departments.*') ? 'text-indigo-400 font-bold bg-indigo-500/10 shadow-sm' : 'text-slate-500 font-bold hover:text-white hover:bg-white/5' }}">Departments</a>
                            <a href="{{ route('admin.majors.index') }}" class="block px-3 py-2 text-[11px] transition-colors rounded-xl uppercase tracking-widest no-underline {{ request()->routeIs('admin.majors.*') ? 'text-indigo-400 font-bold bg-indigo-500/10 shadow-sm' : 'text-slate-500 font-bold hover:text-white hover:bg-white/5' }}">Majors</a>
                            <a href="{{ route('admin.classes.index') }}" class="block px-3 py-2 text-[11px] transition-colors rounded-xl uppercase tracking-widest no-underline {{ request()->routeIs('admin.classes.*') ? 'text-indigo-400 font-bold bg-indigo-500/10 shadow-sm' : 'text-slate-500 font-bold hover:text-white hover:bg-white/5' }}">Classes</a>
                            <a href="{{ route('admin.subjects.index') }}" class="block px-3 py-2 text-[11px] transition-colors rounded-xl uppercase tracking-widest no-underline {{ request()->routeIs('admin.subjects.*') ? 'text-indigo-400 font-bold bg-indigo-500/10 shadow-sm' : 'text-slate-500 font-bold hover:text-white hover:bg-white/5' }}">Subjects</a>
                            <a href="{{ route('admin.enrollments.index') }}" class="block px-3 py-2 text-[11px] transition-colors rounded-xl uppercase tracking-widest no-underline {{ request()->routeIs('admin.enrollments.*') ? 'text-indigo-400 font-bold bg-indigo-500/10 shadow-sm' : 'text-slate-500 font-bold hover:text-white hover:bg-white/5' }}">Enrollments</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.settings.index') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('admin.settings.*') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-sliders-h text-[12px]"></i>
                    </div>
                    <span class="text-sm tracking-tight">Settings</span>
                </a>
            </div>
        </div>
        @endif

        @if($role === 1 || $role === 2)
        <!-- Assessment Management -->
        <div class="pt-2">
            <div class="px-4 mb-4 flex items-center justify-between">
                <h2 class="text-[11px] font-bold text-emerald-500 uppercase tracking-widest">Assessment Hub</h2>
                <div class="h-px bg-slate-800 flex-grow ml-4"></div>
            </div>
            <div class="space-y-1">
                <a href="{{ route('quizzes.index') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('quizzes.*') && !request()->routeIs('quizzes.reports') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('quizzes.*') && !request()->routeIs('quizzes.reports') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-book-open text-[12px]"></i>
                    </div>
                    <span class="text-sm tracking-tight text-inherit">Quizzes</span>
                </a>
                <a href="{{ route('courses.index') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('courses.index') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('courses.index') ? 'bg-teal-500 text-white shadow-lg shadow-teal-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-chalkboard text-[12px]"></i>
                    </div>
                    <span class="text-sm tracking-tight text-inherit">Courses</span>
                </a>
                <a href="{{ route('questions.bank') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('questions.bank') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('questions.bank') ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-database text-[12px]"></i>
                    </div>
                    <span class="text-sm tracking-tight text-inherit">Question Bank</span>
                </a>
                <a href="{{ route('quizzes.reports') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('quizzes.reports') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('quizzes.reports') ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-chart-pie text-[12px]"></i>
                    </div>
                    <span class="text-sm tracking-tight text-inherit">Reports</span>
                </a>
            </div>
        </div>
        @endif

        @if($role === 3)
        <!-- Student Area -->
        <div class="pt-2">
            <div class="px-4 mb-4 flex items-center justify-between opacity-40">
                <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Learning Node</h2>
                <div class="h-px bg-slate-800 flex-grow ml-4"></div>
            </div>
            <div class="space-y-1">
                <a href="{{ route('students.results') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('students.results') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('students.results') ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-star text-[12px]"></i>
                    </div>
                    <span class="text-sm tracking-tight">Performance</span>
                </a>
                <a href="{{ route('courses.index') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-3 rounded-xl {{ request()->routeIs('courses.index') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('courses.index') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-book text-[12px]"></i>
                    </div>
                    <span class="text-sm tracking-tight">My Courses</span>
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- User Profile Footer -->
    <div class="p-4 mt-auto border-t border-slate-800">
        <div class="group p-3 hover:bg-white/5 rounded-xl flex items-center gap-3 mb-2 transition-all cursor-pointer" onclick="window.location='{{ route('profile.edit') }}'">
            <div class="w-9 h-9 rounded-lg overflow-hidden bg-slate-800 shrink-0 text-white flex items-center justify-center font-bold text-xs uppercase">
                @if($authUser && $authUser->profile_photo)
                    <img src="{{ asset('storage/' . $authUser->profile_photo) }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    {{ substr($authUser?->username ?? 'A', 0, 1) }}
                @endif
            </div>
            <div class="overflow-hidden">
                <p class="text-xs font-bold text-white truncate tracking-tight">{{ $authUser->username ?? 'User' }}</p>
                <div class="flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <p class="text-[10px] font-medium text-slate-500 truncate mt-0.5">Online</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-item w-full py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 text-xs font-bold text-slate-500 hover:text-rose-400 hover:bg-rose-500/5 transition-all duration-300">
                <i class="fas fa-arrow-right-from-bracket text-[11px]"></i> 
                <span>Sign Out</span>
            </button>
        </form>
    </div>
@unless($hideSidebar ?? false)
</aside>
@endunless

<!-- === ផ្នែកផ្ទៃព័ត៌មានកណ្ដាល (Main Content Area) === -->
<!-- flex-1: ឱ្យផ្នែកនេះពង្រីកយកទំហំដែលនៅសល់ពី Sidebar -->
<main class="flex-1 min-w-0 flex flex-col bg-slate-50/50">
    
@unless($hideSidebar ?? false)
    <!-- របារផ្នែកខាងលើ (Topbar) ប្រើ Glassmorphism Style -->
    <!-- backdrop-blur-md: ធ្វើឱ្យផ្ទៃរបារមើលទៅព្រិលៗ (Glass effect), sticky top-0: ធ្វើឱ្យវាអណ្តែតខាងលើជានិច្ច -->
    <header class="h-16 px-6 md:px-10 flex items-center justify-between bg-white/70 backdrop-blur-md sticky top-0 z-40">
@endunless
        <div class="flex items-center gap-4">
            <h2 class="text-lg font-semibold text-slate-800 tracking-tight flex items-center gap-2">
                @yield('topbar-title', 'Dashboard')
            </h2>
        </div>
        
        <div class="flex items-center gap-6">
            <!-- Notification Dropdown (Alpine + Tailwind) -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false" class="relative w-10 h-10 rounded-xl bg-slate-100/50 hover:bg-slate-200/50 text-slate-500 hover:text-slate-800 transition-colors flex items-center justify-center">
                    <i class="fas fa-bell"></i>
                    @if($authUser && $authUser->unreadNotifications->count() > 0)
                    <span class="absolute top-2 right-2.5 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    @endif
                </button>

                <div x-show="open" x-transition.opacity.scale.95 style="display: none;" class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden z-50">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <h3 class="text-sm font-semibold tracking-tight text-slate-800">Notifications</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-600">{{ $authUser ? $authUser->unreadNotifications->count() : 0 }} new</span>
                    </div>
                    
                    <div class="max-h-[300px] overflow-y-auto custom-scrollbar">
                        @if($authUser && $authUser->unreadNotifications->count() > 0)
                            @foreach($authUser->unreadNotifications->take(5) as $notification)
                                <a href="#" onclick="event.preventDefault(); document.getElementById('notif-{{ $notification->id }}').submit();" class="block px-5 py-4 border-b border-slate-50 hover:bg-slate-50 transition-colors">
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ ($notification->data['type'] ?? '') == 'success' ? 'bg-indigo-100 text-indigo-600' : 'bg-indigo-100 text-indigo-600' }}">
                                            <i class="fas fa-bell text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900 uppercase tracking-tight">{{ $notification->data['title'] ?? 'Alert' }}</p>
                                            <p class="text-xs text-slate-900 font-medium uppercase mt-1 leading-snug break-words opacity-60">{{ $notification->data['message'] ?? '' }}</p>
                                            <p class="text-[10px] font-bold text-indigo-600 mt-2 uppercase tracking-widest tabular-nums">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </a>
                                <form id="notif-{{ $notification->id }}" action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="hidden">@csrf</form>
                            @endforeach
                            <form action="{{ route('notifications.markAllRead') }}" method="POST" class="p-2 border-t border-slate-100 text-center">
                                @csrf
                                <button type="submit" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 p-2">Mark all as read</button>
                            </form>
                        @else
                            <div class="p-8 text-center">
                                <i class="fas fa-bell-slash text-slate-300 text-2xl mb-3"></i>
                                <p class="text-sm text-slate-500 font-medium tracking-tight">You're caught up!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Profile Info -->
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 pl-2.5 pr-1.5 py-1.5 rounded-full border border-slate-200/60 bg-white hover:bg-slate-50 transition-colors shadow-sm shadow-slate-100 cursor-pointer group">
                <span class="text-sm font-semibold text-slate-700 tracking-tight ml-1">{{ $authUser->username ?? 'Profile' }}</span>
                <div class="w-7 h-7 rounded-full bg-slate-900 text-white flex items-center justify-center text-[10px] font-bold overflow-hidden shadow-inner uppercase">
                    @if($authUser && $authUser->profile_photo)
                        <img src="{{ asset('storage/' . $authUser->profile_photo) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        {{ substr($authUser?->username ?? 'A', 0, 1) }}
                    @endif
                </div>
            </a>
        </div>
@unless($hideSidebar ?? false)
    </header>
@endunless

    <div class="flex-1 w-full max-w-full">
        <!-- Legacy container class compatible wrapper, but refactored children will not use .page-wrap -->
        <div class="h-full">
            @yield('content')
        </div>
    </div>
</main>

<style>
    /* Custom Scrollbar for sleek aesthetic */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(148, 163, 184, 0.5); }
    [x-cloak] { display: none !important; }
</style>

<!-- Legacy scripts for Bootstrap widgets -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
@stack('scripts')
</body>
</html>
