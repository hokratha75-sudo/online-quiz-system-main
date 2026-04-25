<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online Quiz System - Smart Assessment</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Next-gen synchronized assessment platform for educational institutions. Built for scale, ease of use, and performance.">
    <meta name="keywords" content="quiz platform, learning management, assessments, education">
    
    <!-- Preload Assets -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'Kantumruy Pro', 'sans-serif'],
            }
          }
        }
      }
    </script>
    
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    
    <style>
        .hero-mesh {
            background-image: 
                linear-gradient(to bottom, rgba(15, 23, 42, 0.6), rgba(15, 23, 42, 0.95)),
                url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-color: #0f172a;
        }
        [x-cloak] { display: none !important; }
        .glass-header { background: rgba(255,255,255,0.02); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.05); }
    </style>
</head>
<body class="font-sans bg-slate-950 text-white selection:bg-indigo-500 selection:text-white overflow-x-hidden" 
      x-data="{ scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 50">

    <!-- Navigation Header: Institutional Variant -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-500" 
            :class="scrolled ? 'glass-header h-16' : 'h-24 bg-transparent'">
        <div class="max-w-7xl mx-auto px-6 h-full flex items-center justify-between">
            <div class="flex items-center gap-3 group cursor-pointer">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 border border-indigo-400/50 group-hover:scale-105 transition-transform">
                    <i class="fas fa-graduation-cap text-white text-[15px]"></i>
                </div>
                <span class="text-white font-bold text-xl tracking-tight">Quiz Master</span>
            </div>
            
            <nav class="hidden md:flex items-center gap-10">
                <!-- Navigation links removed -->
            </nav>

            <div class="flex items-center gap-4">
                @if (Auth::check())
                    <a href="{{ url('/dashboard') }}" class="h-10 px-6 bg-indigo-600 text-white rounded-xl text-sm font-semibold flex items-center shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition-all active:scale-95">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="h-10 px-6 bg-white text-slate-900 rounded-xl text-sm font-bold flex items-center shadow-lg shadow-white/10 hover:scale-105 active:scale-95 transition-all">Log In</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Hero Matrix: High-Density Premium -->
    <main class="relative hero-mesh min-h-screen flex items-center justify-center pt-20 px-6 overflow-hidden">
        <div class="absolute inset-0 opacity-30 pointer-events-none">
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-white/5 rounded-full blur-[120px] -mr-64 -mt-64"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-500/10 rounded-full blur-[100px] -ml-64 -mb-64"></div>
        </div>

        <div class="max-w-6xl mx-auto relative z-10 text-center">
            
            <div class="inline-flex items-center gap-3 px-5 py-2 bg-white/5 border border-white/10 rounded-full text-xs font-semibold text-indigo-100 mb-10 shadow-lg border-indigo-500/20 backdrop-blur-sm">
                <span class="flex h-2 w-2 rounded-full bg-emerald-400"></span> New Update v2.5.0 Available
            </div>
            
            <h1 class="text-5xl md:text-7xl lg:text-[80px] font-extrabold text-white tracking-tight mb-8 leading-tight">
                Smart & Fast <br/> <span class="bg-gradient-to-r from-indigo-400 via-indigo-200 to-white bg-clip-text text-transparent">Assessment.</span>
            </h1>
            
            <p class="text-indigo-100/80 text-base md:text-lg font-medium leading-relaxed max-w-2xl mx-auto mb-16">
                Empower your institution with a high-performance system for creating, testing, and tracking academic progress seamlessly.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ url('/register') }}" class="w-full sm:w-64 h-16 bg-white text-slate-900 rounded-[20px] font-bold text-sm flex items-center justify-center gap-3 shadow-xl shadow-white/10 hover:scale-105 active:scale-95 transition-all">
                    Register Now <i class="fas fa-arrow-right text-xs"></i>
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-64 h-16 bg-white/5 border border-white/10 text-white rounded-[20px] font-bold text-sm flex items-center justify-center gap-3 backdrop-blur-md hover:bg-white/10 transition-all active:scale-95">
                    Log In <i class="far fa-user text-xs"></i>
                </a>
            </div>

            <!-- Dashboard Preview Node -->
            <div class="mt-32 max-w-4xl mx-auto rounded-t-[40px] border-t border-x border-white/10 bg-white/5 backdrop-blur-xl p-4 md:p-8 transition-transform duration-1000 delay-500 translate-y-20 opacity-50 hover:translate-y-10 hover:opacity-100">
                <div class="flex justify-between items-center mb-8 px-4">
                    <div class="flex gap-2">
                        <div class="w-3 h-3 rounded-full bg-rose-500/50"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-500/50"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-500/50"></div>
                    </div>
                    <div class="h-6 px-4 bg-white/5 rounded-full text-xs font-semibold text-indigo-200 flex items-center">Dashboard Preview</div>
                </div>
                <div class="grid grid-cols-3 gap-4 mb-20">
                    <div class="h-24 bg-white/5 rounded-2xl animate-pulse"></div>
                    <div class="h-24 bg-white/5 rounded-2xl animate-pulse"></div>
                    <div class="h-24 bg-white/5 rounded-2xl animate-pulse"></div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
