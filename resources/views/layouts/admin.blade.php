<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $dashboardTitle ?? 'Admin' }} - Quiz System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="description" content="Professional online quiz and assessment system for educational institutions.">
    <meta name="keywords" content="online quiz system, institutional assessment, student testing, exam management">
    <meta name="author" content="Online Quiz System">
    <meta property="og:title" content="Online Quiz System">
    <meta property="og:description" content="Professional assessment and performance tracking platform.">
    
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 loaded for backward compatibility with older views -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        corePlugins: {
          preflight: false,
        },
        theme: {
          extend: {
            colors: {
              primary: '#4f46e5',
            },
                                    fontFamily: {
              sans: ['"Open Sans"', 'Helvetica', 'Arial', 'sans-serif'],
              heading: ['"Open Sans"', 'Helvetica', 'Arial', 'sans-serif'],
            }
          }
        }
      }
    </script>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    @yield('styles')

    <!-- Legacy Style Overrides to keep non-refactored pages from breaking completely -->
    <style>
                /* Global Moodle-style Typography */
        h1, h2, h3, h4, h5, h6, .card-title-custom, .font-heading {
            font-family: 'Open Sans', Helvetica, Arial, sans-serif !important;
            font-weight: 600 !important;
        }
        body {
            font-family: 'Open Sans', Helvetica, Arial, sans-serif !important;
            font-weight: 400;
            color: #333333;
        }
        
        .page-wrap { padding: 30px; flex: 1; }
        .card-custom { background: #ffffff !important; border-radius: 20px !important; border: none !important; box-shadow: 0 10px 30px rgba(0,0,0,0.03) !important; padding: 20px !important; }
        .card-header-inner { border-bottom: 1px solid #f3f4f6 !important; padding: 10px 0 20px 0 !important; margin-bottom: 20px !important; }
        .card-title-custom { font-size: 18px !important; font-weight: 600 !important; color: #111827 !important; border: none !important; }
        .btn-blue, .btn-primary { background: #4f46e5 !important; color: white !important; border: none !important; border-radius: 8px !important; padding: 8px 16px !important; font-weight: 500 !important; box-shadow: 0 2px 4px rgba(91, 108, 249, 0.2) !important; transition: all 0.2s !important; }
        .btn-pink, .btn-secondary { background: white !important; color: #111827 !important; border: 1px solid #f3f4f6 !important; border-radius: 8px !important; padding: 8px 16px !important; font-weight: 500 !important; }
        .table-custom { width: 100% !important; margin-bottom: 0 !important; border-collapse: separate !important; border-spacing: 0 !important; }
        .table-custom th { border: none !important; border-bottom: 1px solid #f3f4f6 !important; padding: 14px 20px !important; color: #6b7280 !important; font-weight: 600 !important; font-size: 12px !important; text-transform: uppercase !important; letter-spacing: 0.5px !important; background: white !important; }
        .table-custom td { padding: 16px 20px !important; vertical-align: middle !important; border: none !important; border-bottom: 1px solid #f3f4f6 !important; color: #111827 !important; font-size: 14px !important; font-weight: 500 !important; }
        
        /* Fix ugly default button styling caused by missing Tailwind Preflight */
        button {
            background-color: transparent;
            background-image: none;
            cursor: pointer;
            border: none;
            padding: 0;
            line-height: inherit;
            color: inherit;
        }
        button:focus { outline: none; }
        
        /* Simplified Sidebar Styles */
        .sidebar-item {
            transition: all 0.2s ease;
            text-decoration: none !important;
            font-weight: 600 !important;
            font-family: 'Open Sans', sans-serif !important;
        }
        .sidebar-item:hover {
            background: rgba(79, 70, 229, 0.05);
            color: #6366f1 !important;
        }
        .sidebar-item-active {
            background: rgba(91, 79, 207, 0.1) !important;
            color: white !important;
            border-left: 2px solid #5b4fcf;
            border-radius: 0 !important;
        }
        .sidebar-item-active i {
            color: #5b4fcf !important;
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
<body x-data="{ sidebarOpen: false }" class="bg-slate-50 text-slate-900 font-sans antialiased flex flex-col md:flex-row min-h-screen overflow-x-hidden">
@php
    $authUser = auth()->user();
    $role = (int)($authUser->role_id ?? 0);
    $dashboardRoute = auth()->check() && (int) auth()->user()->role_id === 1 ? route('admin.dashboard') : route('dashboard');
    $departmentDataActive = request()->routeIs('admin.departments.*') || (request()->routeIs('admin.majors.index') && request('tab') === 'departments');
    $majorActive = request()->routeIs('admin.majors.show') || (request()->routeIs('admin.majors.index') && request('tab', 'majors') === 'majors');
    $classActive = request()->routeIs('admin.classes.*') || (request()->routeIs('admin.majors.index') && request('tab') === 'classes');
    $courseActive = request()->routeIs('admin.subjects.*') || (request()->routeIs('admin.majors.index') && request('tab') === 'subjects');
    $enrollmentActive = request()->routeIs('admin.enrollments.*');
    $departmentMenuOpen = $departmentDataActive || $majorActive || $classActive || $courseActive || $enrollmentActive;
@endphp

<!-- === ផ្នែក Sidebar (របារបញ្ឈរខាងឆ្វេង) === -->
<!-- 
  - w-[260px]: កំណត់ប្រវែងទទឹង Sidebar 260 ភីកសែល
  - shrink-0: ការពារកុំឱ្យ Sidebar រួញតូចពេលអេក្រង់តូច
  - bg-slate-900: ពណ៌ផ្ទៃងងឹតសម្រាប់ Sidebar
  - h-screen & sticky top-0: ធ្វើឱ្យវាអណ្តែតជាប់ជានិច្ចពេលយើង Scroll ចុះក្រោម
-->
@unless($hideSidebar ?? false)
<!-- Mobile Header Nav -->
<div class="md:hidden bg-[#1a1d2e] border-b border-slate-800 px-5 py-4 flex items-center justify-between sticky top-0 z-40 shadow-2xl">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-md shadow-indigo-500/20 shrink-0 border border-indigo-400/50">
            <i class="fas fa-graduation-cap text-white text-[13px]"></i>
        </div>
        <div>
            <h1 class="text-white font-bold tracking-tight text-base leading-none truncate max-w-[150px]">Quiz System</h1>
        </div>
    </div>
    <button @click="sidebarOpen = true" class="w-10 h-10 rounded-xl bg-slate-800 text-slate-300 flex items-center justify-center hover:bg-slate-700 hover:text-white transition-colors focus:outline-none">
        <i class="fas fa-bars-staggered"></i>
    </button>
</div>

<!-- Mobile Drawer Overlay -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-[#1a1d2e]/60 backdrop-blur-sm z-40 md:hidden" style="display: none;"></div>

<!-- Sidebar Layout -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="w-[280px] md:w-[260px] shrink-0 bg-[#1a1d2e] border-r border-slate-800 flex flex-col h-screen fixed inset-y-0 left-0 transform transition-transform duration-300 ease-in-out md:translate-x-0 md:sticky md:top-0 custom-scrollbar z-50">
    <!-- Close button for mobile inside sidebar -->
    <button @click="sidebarOpen = false" class="md:hidden absolute top-6 right-5 w-8 h-8 rounded-lg bg-slate-800 text-slate-400 flex items-center justify-center hover:text-white transition-colors">
        <i class="fas fa-times"></i>
    </button>
    
    <!-- ផ្នែក Logo និងបរិយាយឈ្មោះប្រព័ន្ធ (Brand) -->
    <div class="px-6 pt-7 pb-6 flex items-center gap-3">
        <!-- រូបតំណាង (Icon) -->
        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-md shadow-indigo-500/20 shrink-0 border border-indigo-400/50">
            <i class="fas fa-graduation-cap text-white text-[13px]"></i>
        </div>
        <div>
            <h1 class="text-white font-bold tracking-tight text-lg leading-none truncate max-w-[150px]">Quiz System</h1>
            <span class="text-xs font-semibold text-indigo-400 mt-1 block">
                @if($role === 1) Admin Panel @elseif($role === 2) Teacher Hub @else Student Home @endif
            </span>
        </div>
    </div>



    <div class="flex-1 overflow-y-auto px-3 space-y-1.5 custom-scrollbar pb-6">
        <!-- Dashboard menu -->
        <div class="space-y-1 mt-4">
            <a href="{{ $role === 3 ? route('students.dashboard') : route('dashboard') }}" 
               class="sidebar-item group flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('dashboard') || request()->routeIs('students.dashboard') ? 'sidebar-item-active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('dashboard') || request()->routeIs('students.dashboard') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                    <i class="fas fa-home text-[12px]"></i>
                </div>
                                                <span class="text-sm font-bold tracking-tight">Dashboard</span>
            </a>
            @if($role === 1 || $role === 2)
            <a href="{{ route('planner') }}" 
               class="sidebar-item group flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('planner') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('planner') ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                    <i class="fas fa-calendar-day text-[12px]"></i>
                </div>
                                                        <span class="text-sm tracking-tight">Calendar</span>
            </a>
            @endif
        </div>

        @if($role === 1)
        <!-- System Administration -->
        <div class="pt-2">
            <div class="px-4 mb-2 flex items-center justify-between">
                <h2 class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Administration</h2>
                <div class="h-px bg-slate-800 flex-grow ml-4"></div>
            </div>
            <div class="space-y-1">
                <a href="{{ route('admin.users.index') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('admin.users.*') ? 'sidebar-item-active' : 'text-slate-400' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-users text-[12px]"></i>
                    </div>
                                                            <span class="text-sm font-bold tracking-tight text-inherit">Users</span>
                </a>
                
                <div x-data="{ expanded: {{ $departmentMenuOpen ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" 
                            class="sidebar-item w-full group flex items-center gap-4 px-4 py-2 rounded-xl transition-all duration-300 {{ $departmentMenuOpen ? 'text-white' : 'text-slate-400 font-bold' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ $departmentMenuOpen ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                            <i class="fas fa-building text-[12px]"></i>
                        </div>
                                                                        <span class="text-sm tracking-tight flex-1 text-left">Academic</span>
                        <i class="fas fa-chevron-down text-[10px] opacity-40 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="expanded" x-collapse>
                        <div class="pl-4 pr-3 py-1 space-y-0.5 mt-1 border-l border-slate-700 ml-[36px]">
                                                                                    <a href="{{ route('admin.departments.index') }}" class="block px-3 py-1.5 text-xs transition-colors rounded-lg no-underline {{ request()->routeIs('admin.departments.*') ? 'text-white font-medium bg-indigo-500/20 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">Departments</a>
                                                                                    <a href="{{ route('admin.majors.index') }}" class="block px-3 py-1.5 text-xs transition-colors rounded-lg no-underline {{ request()->routeIs('admin.majors.*') ? 'text-white font-medium bg-indigo-500/20 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">Majors</a>
                                                                                    <a href="{{ route('admin.classes.index') }}" class="block px-3 py-1.5 text-xs transition-colors rounded-lg no-underline {{ request()->routeIs('admin.classes.*') ? 'text-white font-medium bg-indigo-500/20 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">Classes</a>
                                                                                    <a href="{{ route('admin.subjects.index') }}" class="block px-3 py-1.5 text-xs transition-colors rounded-lg no-underline {{ request()->routeIs('admin.subjects.*') ? 'text-white font-medium bg-indigo-500/20 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">Subjects</a>
                                                                                    <a href="{{ route('admin.enrollments.index') }}" class="block px-3 py-1.5 text-xs transition-colors rounded-lg no-underline {{ request()->routeIs('admin.enrollments.*') ? 'text-white font-medium bg-indigo-500/20 shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">Enrollments</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.settings.index') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('admin.settings.*') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
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
            <div class="px-4 mb-2 flex items-center justify-between">
                <h2 class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Assessment Hub</h2>
                <div class="h-px bg-slate-800 flex-grow ml-4"></div>
            </div>
            <div class="space-y-1">
                <a href="{{ route('quizzes.index') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('quizzes.*') && !request()->routeIs('quizzes.reports') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('quizzes.*') && !request()->routeIs('quizzes.reports') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-book-open text-[12px]"></i>
                    </div>
                                                            <span class="text-sm tracking-tight">Quizzes</span>
                </a>
                <a href="{{ route('courses.index') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('courses.index') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('courses.index') ? 'bg-teal-500 text-white shadow-lg shadow-teal-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-chalkboard text-[12px]"></i>
                    </div>
                                                            <span class="text-sm tracking-tight">Courses</span>
                </a>
                <a href="{{ route('questions.bank') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('questions.bank') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('questions.bank') ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-database text-[12px]"></i>
                    </div>
                                                            <span class="text-sm tracking-tight">Question Bank</span>
                </a>
                <a href="{{ route('quizzes.reports') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('quizzes.reports') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('quizzes.reports') ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-chart-pie text-[12px]"></i>
                    </div>
                                                            <span class="text-sm tracking-tight">Reports</span>
                </a>
                <a href="{{ route('leaderboard') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('leaderboard') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('leaderboard') ? 'bg-yellow-500 text-white shadow-lg shadow-yellow-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-crown text-[12px]"></i>
                    </div>
                                                            <span class="text-sm tracking-tight">Leaderboard</span>
                </a>
            </div>
        </div>
        @endif

        @if($role === 3)
        <!-- Student Area -->
        <div class="pt-2">
            <div class="px-4 mb-2 flex items-center justify-between opacity-40">
                                                <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Learning Space</h2>
                <div class="h-px bg-slate-800 flex-grow ml-4"></div>
            </div>
            <div class="space-y-1">
                <a href="{{ route('students.results') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('students.results') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('students.results') ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-star text-[12px]"></i>
                    </div>
                                                            <span class="text-sm tracking-tight">Results</span>
                </a>
                <a href="{{ route('courses.index') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('courses.index') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('courses.index') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-book text-[12px]"></i>
                    </div>
                                                            <span class="text-sm tracking-tight">My Courses</span>
                </a>
                <a href="{{ route('leaderboard') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('leaderboard') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('leaderboard') ? 'bg-yellow-500 text-white shadow-lg shadow-yellow-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-crown text-[12px]"></i>
                    </div>
                                                            <span class="text-sm tracking-tight">Leaderboard</span>
                </a>
                <a href="{{ route('planner') }}" 
                   class="sidebar-item group flex items-center gap-4 px-4 py-2 rounded-xl {{ request()->routeIs('planner') ? 'sidebar-item-active text-indigo-400' : 'text-slate-400 font-bold' }}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-300 {{ request()->routeIs('planner') ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/20' : 'bg-slate-800/50 group-hover:bg-indigo-500/20 group-hover:text-indigo-400' }}">
                        <i class="fas fa-calendar-day text-[12px]"></i>
                    </div>
                                                            <span class="text-sm tracking-tight">Calendar</span>
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- User Profile Footer -->
    <div class="p-4 mt-auto border-t border-slate-800/50 bg-[#1a1d2e]">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-3 cursor-pointer hover:opacity-80 transition-opacity" onclick="window.location='{{ route('profile.edit') }}'">
                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                    @if($authUser && $authUser->profile_photo)
                        <img src="{{ asset('storage/' . $authUser->profile_photo) }}" alt="Avatar" class="w-full h-full object-cover rounded-full">
                    @else
                        {{ substr($authUser?->username ?? 'R', 0, 1) }}
                    @endif
                </div>
                <div>
                    <p class="text-[14px] font-bold text-white leading-none mb-1">{{ $authUser->username ?? 'User' }}</p>
                    <div class="flex items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                        <p class="text-[11px] font-medium text-slate-500">Online</p>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="px-3 py-1.5 rounded-lg border border-slate-700/50 text-slate-500 hover:text-white hover:border-slate-500 transition-all text-xs font-medium">
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>
@endunless

<!-- === ផ្នែកផ្ទៃព័ត៌មានកណ្ដាល (Main Content Area) === -->
<!-- flex-1: ឱ្យផ្នែកនេះពង្រីកយកទំហំដែលនៅសល់ពី Sidebar -->
<main class="flex-1 min-w-0 flex flex-col bg-slate-50/50">
    
@unless($hideTopbar ?? ($hideSidebar ?? false))
    <!-- របារផ្នែកខាងលើ (Topbar) ប្រើ Glassmorphism Style -->
    <header class="h-16 px-6 md:px-10 flex items-center justify-between bg-white border-b border-slate-200 shadow-sm sticky top-0 z-40">
        <div class="flex items-center gap-4">
            <h2 class="text-lg font-semibold text-slate-800 tracking-tight flex items-center gap-2">
                @yield('topbar-title', 'Dashboard')
            </h2>
        </div>
        
        <div class="flex items-center gap-6">
            <!-- Notification Dropdown (Alpine + Tailwind) -->
            <div x-data="{ 
                open: false, 
                unreadCount: {{ $authUser ? $authUser->unreadNotifications()->count() : 0 }},
                addNotification(notif) {
                    this.unreadCount++;
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-6 right-6 bg-slate-900 border border-slate-800 text-white px-5 py-4 rounded-xl shadow-2xl flex items-center gap-4 z-[9999] transform transition-all duration-300 translate-y-10 opacity-0';
                    toast.innerHTML = `<div class='w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0'><i class='${notif.icon || 'fas fa-bell'}'></i></div> <div><p class='text-sm font-bold tracking-tight'>${notif.title}</p><p class='text-xs text-slate-400 mt-0.5 leading-snug'>${notif.message}</p></div>`;
                    document.body.appendChild(toast);
                    setTimeout(() => { toast.classList.remove('translate-y-10', 'opacity-0'); }, 10);
                    setTimeout(() => { toast.classList.add('translate-y-10', 'opacity-0'); setTimeout(() => toast.remove(), 300); }, 6000);
                }
            }" @new-notification.window="addNotification($event.detail)" class="relative">
                <button @click="open = !open" @click.away="open = false" class="relative w-10 h-10 rounded-xl bg-slate-100/50 hover:bg-slate-200/50 text-slate-500 hover:text-slate-800 transition-colors flex items-center justify-center">
                    <i class="fas fa-bell"></i>
                    <span x-show="unreadCount > 0" x-cloak style="display: none;" class="absolute top-2 right-2.5 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                </button>

                <div x-show="open" x-transition.opacity.scale.95 style="display: none;" class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden z-50">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                                                        <h3 class="text-sm font-semibold tracking-tight text-slate-800">Notifications</h3>
                                                                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-600"><span x-text="unreadCount"></span> new</span>
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
    </header>
@endunless

    <div class="flex-1 w-full max-w-full">
        <div class="h-full">
            @yield('content')
        </div>
    </div>

    <!-- Main Content Footer Copyright -->
    <footer class="w-full bg-white border-t border-slate-200 h-10 mt-auto flex items-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] sticky bottom-0 z-40">
        <div class="max-w-[1400px] mx-auto w-full px-3 md:px-10 flex items-center">
            <p class="text-[10px] font-medium text-slate-500 translate-y-[8px]">
                <strong>Copyright</strong> &copy; {{ date('Y') }} <strong><a href="#" class="text-indigo-600 hover:text-indigo-800 transition-colors">{{ \App\Models\Setting::get('site_name', 'Online Quiz System') }}</a></strong>. All rights reserved.
            </p>
        </div>
    </footer>
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
<!-- Real-time Notifications Setup via Laravel Reverb -->
<script src="https://js.pusher.com/8.3.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@2.2.0/dist/echo.iife.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.Echo === 'undefined' && typeof Echo !== 'undefined') {
            window.Pusher = Pusher;
            window.Echo = new Echo({
                broadcaster: 'reverb',
                key: '{{ env("REVERB_APP_KEY") }}',
                wsHost: '{{ env("REVERB_HOST", "localhost") }}',
                wsPort: {{ env("REVERB_PORT", 8080) }},
                wssPort: {{ env("REVERB_PORT", 8080) }},
                forceTLS: {{ env("REVERB_SCHEME", "http") === "https" ? 'true' : 'false' }},
                enabledTransports: ['ws', 'wss'],
            });

            @auth
            window.Echo.private('App.Models.User.{{ auth()->id() }}')
                .notification((notification) => {
                    console.log('Real-time notification received', notification);
                    window.dispatchEvent(new CustomEvent('new-notification', { detail: notification }));
                });
            @endauth
        }
    });
</script>

<!-- SweetAlert2 UI for Premium Alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Global Premium Confirm Function
    window.premiumConfirm = function(message, confirmCallback, title = 'Delete Account') {
        Swal.fire({
            html: `
                <div class="flex flex-col items-center pt-2">
                    <svg viewBox="0 0 24 24" fill="none" class="w-[42px] h-[42px] mb-3 text-[#f04438]" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="text-[18px] font-bold text-[#111827] tracking-tight leading-none mb-3">${title}</h3>
                    <p class="text-[14px] text-[#475467] font-medium leading-relaxed px-1">${message}</p>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete!',
            cancelButtonText: 'No, keep it.',
            width: '360px',
            customClass: {
                popup: 'rounded-[20px] shadow-[0_20px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 p-6',
                actions: 'flex flex-row w-full gap-3 mt-6 mb-0 px-1',
                confirmButton: 'flex-1 m-0 bg-[#f04438] hover:bg-[#d92d20] text-white rounded-[10px] py-[11px] text-[14px] font-bold shadow-[0_6px_16px_rgba(240,68,56,0.4)] transition-all',
                cancelButton: 'flex-1 m-0 bg-[#f2f4f7] hover:bg-[#e4e7ec] text-[#344054] rounded-[10px] py-[11px] text-[14px] font-bold transition-all'
            },
            buttonsStyling: false,
            reverseButtons: true,
            showCloseButton: false,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed && typeof confirmCallback === 'function') {
                confirmCallback();
            }
        });
    };

    // Auto-intercept basic form deletion
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('form').forEach(form => {
            let onsubmitAttr = form.getAttribute('onsubmit');
            if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                let msgMatch = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
                let msg = msgMatch ? msgMatch[1] : 'Are you sure you want to delete this?';
                
                form.removeAttribute('onsubmit');
                
                form.addEventListener('submit', function(e) {
                    if (!form.dataset.confirmed) {
                        e.preventDefault();
                        window.premiumConfirm(msg, function() {
                            form.dataset.confirmed = 'true';
                            form.submit();
                        });
                    }
                });
            }
        });
    });
</script>

@yield('scripts')
@stack('scripts')
</body>
</html>
