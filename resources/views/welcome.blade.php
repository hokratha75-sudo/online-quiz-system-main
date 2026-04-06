<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QuizMaster - Evolutionary Learning Hub</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Kantumruy+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Plus Jakarta Sans', 'Kantumruy Pro', 'sans-serif'],
            }
          }
        }
      }
    </script>
    
    <style>
        .mesh-gradient {
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,80%,15%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,80%,20%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,80%,20%,1) 0, transparent 50%);
        }
        .hero-mesh {
            background-color: #4f46e5;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,60%,20%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,60%,25%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,60%,25%,1) 0, transparent 50%);
        }
    </style>
</head>
<body class="font-sans bg-slate-50 selection:bg-indigo-500 selection:text-white">

    <!-- Navigation Header: Minimal & High-Contrast -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/5 backdrop-blur-md border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center backdrop-blur-sm shadow-xl">
                    <i class="fas fa-microchip text-white text-sm"></i>
                </div>
                <span class="text-white font-extrabold text-lg tracking-tighter uppercase">QuizMaster</span>
            </div>
            
            <nav class="hidden md:flex items-center gap-10">
                <a href="#" class="text-[10px] font-bold text-white/60 hover:text-white uppercase tracking-[0.2em] transition-colors">Courses</a>
                <a href="#" class="text-[10px] font-bold text-white/60 hover:text-white uppercase tracking-[0.2em] transition-colors">Exams</a>
                <a href="#" class="text-[10px] font-bold text-white/60 hover:text-white uppercase tracking-[0.2em] transition-colors">Analytics</a>
            </nav>

            <div class="flex items-center gap-4">
                @if (Auth::check())
                    <a href="{{ url('/dashboard') }}" class="h-10 px-8 bg-white text-slate-900 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center shadow-2xl shadow-white/5 hover:scale-105 transition-all">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="h-10 px-8 bg-white/10 hover:bg-white text-white hover:text-slate-900 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center border border-white/10 transition-all">Sign In</a>
                    <a href="{{ url('/register') }}" class="h-10 px-8 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center shadow-2xl shadow-indigo-600/20 transition-all">Join Now</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Hero: Immersive High-Density Node -->
    <main class="relative hero-mesh min-h-screen flex items-center justify-center pt-20 px-6 overflow-hidden">
        <div class="absolute inset-0 bg-indigo-600/10 pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-white/5 rounded-full blur-[120px] -mr-64 -mt-64"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[120px] -ml-64 -mb-64"></div>

        <div class="max-w-5xl mx-auto relative z-10 text-center">
            <div class="inline-flex items-center gap-3 px-4 py-1.5 bg-white/10 border border-white/10 rounded-full text-[10px] font-bold text-indigo-200 uppercase tracking-[0.2em] mb-10 shadow-lg">
                <i class="fas fa-bolt text-emerald-400"></i> v2.5.0 Production Ready
            </div>
            
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white tracking-tighter uppercase mb-8 leading-none">
                Next-Gen Learning <br/> <span class="bg-gradient-to-r from-indigo-300 to-indigo-100 bg-clip-text text-transparent">Experiences</span>
            </h1>
            
            <p class="text-indigo-100/70 text-xs md:text-sm font-bold uppercase tracking-[0.2em] leading-relaxed max-w-2xl mx-auto mb-16 italic">
                Empowering educators with real-time insights and secure automated testing. Built for the next generation of digital excellence.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ url('/register') }}" class="w-full sm:w-64 h-16 bg-white text-slate-900 rounded-[24px] font-bold uppercase tracking-[0.2em] text-xs flex items-center justify-center gap-3 shadow-2xl shadow-white/5 hover:scale-105 active:scale-95 transition-all">
                    Register Now <i class="fas fa-chevron-right text-[10px]"></i>
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-64 h-16 bg-white/5 border border-white/10 text-white rounded-[24px] font-bold uppercase tracking-[0.2em] text-xs flex items-center justify-center gap-3 backdrop-blur-sm hover:bg-white/10 transition-all">
                    Member Login <i class="fas fa-sign-in-alt text-[10px]"></i>
                </a>
            </div>

            <!-- Dashboard Preview Node -->
            <div class="mt-24 p-4 rounded-[40px] bg-white/5 border border-white/10 backdrop-blur-md shadow-3xl max-w-4xl mx-auto group hover:scale-[1.02] transition-transform duration-700">
                <div class="bg-slate-950 rounded-[30px] overflow-hidden border border-white/5 aspect-video relative">
                    <div class="absolute inset-0 flex items-center justify-center bg-indigo-600/10">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl animate-pulse cursor-pointer">
                                <i class="fas fa-play ml-1"></i>
                            </div>
                            <span class="text-[10px] font-bold text-white uppercase tracking-widest opacity-50">Operational Walkthrough</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Institutional Partners / Credits: Minimalist Footer -->
    <footer class="bg-slate-950 py-20 px-6 border-t border-white/5">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-10">
            <div class="flex items-center gap-3 opacity-40">
                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                    <i class="fas fa-microchip text-white text-xs"></i>
                </div>
                <span class="text-white font-extrabold text-sm tracking-tighter uppercase">QuizMaster</span>
            </div>
            
            <div class="text-[10px] font-bold text-slate-600 uppercase tracking-widest text-center md:text-right leading-loose italic">
                &copy; {{ date('Y') }} QuizMaster Global. Engineered for academic excellence. <br/>
                <span class="text-indigo-400 opacity-40">System Status: Optimal Performance</span>
            </div>
        </div>
    </footer>

</body>
</html>
