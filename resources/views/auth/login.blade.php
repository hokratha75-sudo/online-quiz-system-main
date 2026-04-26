<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back - Quiz Master</title>
    
    <!-- SEO & Performance Nodes -->
    <meta name="description" content="Secure portal for the Online Quiz System.">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Kantumruy+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
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
        .login-mesh {
            background: linear-gradient(135deg, rgba(30, 27, 75, 0.9), rgba(79, 70, 229, 0.8)), url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071&auto=format&fit=crop') center/cover;
            position: relative;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        [x-cloak] { display: none !important; }
        
        .floating-shape-1 {
            animation: float 8s ease-in-out infinite;
        }
        .floating-shape-2 {
            animation: float 10s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
    </style>
</head>
<body class="font-sans bg-slate-100 min-h-screen flex items-center justify-center p-4 md:p-8 selection:bg-indigo-500 selection:text-white">

    <div class="w-full max-w-[1100px] bg-white rounded-[32px] shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] overflow-hidden flex flex-col md:flex-row min-h-[640px]">
        
        <!-- Branding Identity Panel -->
        <div class="w-full md:w-[45%] login-mesh p-10 md:p-14 flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute -top-32 -right-32 w-[400px] h-[400px] bg-indigo-400/20 rounded-full blur-[80px] floating-shape-1"></div>
            <div class="absolute -bottom-32 -left-32 w-[300px] h-[300px] bg-violet-500/20 rounded-full blur-[60px] floating-shape-2"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 border border-indigo-400/50">
                        <i class="fas fa-graduation-cap text-white text-[18px]"></i>
                    </div>
                    <span class="text-white font-bold text-2xl tracking-tight">Quiz Master</span>
                </div>
                
                                                                                                <h1 class="text-[40px] md:text-[46px] font-extrabold text-white tracking-tight leading-[1.1] mb-6" style="font-family: 'Open Sans', Helvetica, Arial, sans-serif !important;">
                    Elevate your <br/> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-purple-300">learning</span> experience.
                </h1>
                                                <p class="text-indigo-100/90 text-sm font-medium leading-relaxed max-w-sm">
                    The premium platform for synchronized assessments, analytics, and institutional management.
                </p>
            </div>

            <div class="relative z-10 pt-12">
                <div class="glass-panel rounded-2xl p-4 flex items-center gap-4">
                    <div class="flex -space-x-3">
                        <div class="w-9 h-9 rounded-full border-2 border-[#312e81] bg-emerald-500 flex items-center justify-center text-[10px] font-bold text-white shadow-xl"><i class="fas fa-shield-check"></i></div>
                    </div>
                    <div class="flex flex-col">
                                                                        <span class="text-xs font-bold text-white tracking-wide">Enterprise Security</span>
                                                                        <span class="text-[11px] font-medium text-indigo-200 mt-0.5">End-to-end Encrypted</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Authentication Form -->
        <div class="w-full md:w-[55%] p-8 md:p-16 flex flex-col justify-center bg-white relative">
            
            <div class="max-w-[400px] mx-auto w-full">
                <div class="mb-10 text-center md:text-left">
                                                                                                                        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Open Sans', Helvetica, Arial, sans-serif !important;">Welcome Back</h2>
                    <p class="text-sm font-medium text-slate-500 mt-2">Please enter your details to sign in.</p>
                </div>

                @if($errors->any())
                    <div class="mb-8 p-4 bg-rose-50/50 border border-rose-100 rounded-2xl flex items-start gap-4 animate-[pulse_2s_ease-in-out_1]">
                        <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas fa-exclamation-triangle text-xs"></i>
                        </div>
                        <div class="flex flex-col">
                                                                                    <span class="text-sm font-bold text-rose-800 mb-0.5">Authentication Failed</span>
                            <span class="text-[13px] font-medium text-rose-600 leading-relaxed">
                                {{ $errors->first() }}
                            </span>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    
                    <div class="space-y-2 group">
                                                                        <label for="username" class="block text-[13px] font-bold text-slate-700 ml-1 transition-colors group-focus-within:text-indigo-600">Email or Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="far fa-user text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                            </div>
                            <input type="text" id="username" name="username" required 
                                   class="w-full bg-slate-50/50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white block px-11 py-3.5 transition-all outline-none font-medium placeholder:text-slate-400 placeholder:font-normal" 
                                                                                                         placeholder="Enter your email or username">
                        </div>
                    </div>

                    <div class="space-y-2 group">
                        <div class="flex justify-between items-center px-1">
                                                                                    <label for="password" class="block text-[13px] font-bold text-slate-700 transition-colors group-focus-within:text-indigo-600">Password</label>
                                                                                    <a href="#" class="text-[13px] font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Forgot Password?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="far fa-lock text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                            </div>
                            <input type="password" id="password" name="password" required 
                                   class="w-full bg-slate-50/50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white block px-11 py-3.5 transition-all outline-none font-medium placeholder:text-slate-400 placeholder:font-normal" 
                                   placeholder="••••••••">
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-2 px-1">
                        <label class="flex items-center gap-2 cursor-pointer group/check">
                            <div class="relative flex items-center justify-center w-5 h-5">
                                <input type="checkbox" name="remember" class="peer sr-only">
                                <div class="w-5 h-5 border-2 border-slate-300 rounded-[6px] peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all"></div>
                                <i class="fas fa-check text-white text-[10px] absolute opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                            </div>
                                                                                    <span class="text-[13px] font-semibold text-slate-600 group-hover/check:text-slate-900 transition-colors">Remember me</span>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98] transition-all shadow-lg shadow-indigo-500/25 overflow-hidden">
                            <span class="relative z-10 flex items-center gap-2">
                                                                                                Sign In <i class="fas fa-arrow-right text-[11px] group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </button>
                    </div>
                    
                    <div class="pt-6 text-center">
                        <p class="text-[13px] font-medium text-slate-500">
                                                                                    Don't have an account? <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-bold transition-colors">Create one now</a>
                        </p>
                    </div>

                </form>
            </div>
            
            <div class="absolute bottom-6 left-0 right-0 text-center">
                 <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-widest">
                    Version {{ config('app.version', '2.5.0') }}
                </p>
            </div>
        </div>
    </div>

</body>
</html>
