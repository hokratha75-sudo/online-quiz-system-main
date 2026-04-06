<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back - Online Quiz System</title>
    
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
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.85), rgba(15, 23, 42, 0.9)), url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071&auto=format&fit=crop') center/cover;
            position: relative;
        }
        [x-cloak] { display: none !important; }
        .input-active { border-color: #4f46e5 !important; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important; background: white !important; }
    </style>
</head>
<body class="font-sans bg-slate-50 min-h-screen flex items-center justify-center p-4 md:p-8 selection:bg-indigo-100 selection:text-indigo-900">

    <div class="w-full max-w-[1100px] bg-white rounded-[40px] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-slate-100 min-h-[600px] transition-all duration-700">
        
        <!-- Branding Identity Panel: Premium SaaS Matrix -->
        <div class="w-full md:w-1/2 login-mesh p-12 md:p-16 flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute inset-0 backdrop-blur-[1px] z-[1]"></div>
            <div class="absolute -top-32 -right-32 w-80 h-80 bg-white/5 rounded-full blur-3xl opacity-50 group-hover:opacity-70 transition-opacity"></div>
            <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl opacity-50 group-hover:opacity-70 transition-opacity"></div>
            
            <div class="relative z-10">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-[24px] border border-white/20 flex items-center justify-center mb-10 shadow-2xl">
                    <i class="fas fa-graduation-cap text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white tracking-tight leading-tight mb-6">
                    Online Quiz <br/> <span class="text-indigo-300">System.</span>
                </h1>
                <p class="text-indigo-100/80 text-sm font-medium leading-relaxed max-w-sm">
                    Secure access portal for administrators, teachers, and students. Manage your assessments efficiently.
                </p>
            </div>

            <div class="relative z-10 pt-12">
                <div class="flex items-center gap-5 py-6 border-t border-white/10">
                    <div class="flex -space-x-3">
                        <div class="w-10 h-10 rounded-xl border-2 border-indigo-600 bg-slate-900 flex items-center justify-center text-[10px] font-bold text-white shadow-xl">AD</div>
                        <div class="w-10 h-10 rounded-xl border-2 border-indigo-600 bg-indigo-500 flex items-center justify-center text-[10px] font-bold text-white shadow-xl">TC</div>
                        <div class="w-10 h-10 rounded-xl border-2 border-indigo-600 bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-400 shadow-xl">ST</div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-indigo-50 tracking-wide">Secure Connection</span>
                        <span class="text-[10px] font-medium text-indigo-300 mt-0.5">TLS 1.3 Encrypted</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Authentication Matrix -->
        <div class="w-full md:w-1/2 p-10 md:p-20 flex flex-col justify-center bg-white relative">
            <div class="max-w-md mx-auto w-full">
                <div class="mb-12 text-center md:text-left">
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight leading-none">Welcome Back</h2>
                    <p class="text-sm font-medium text-slate-500 mt-3">Sign in to your account</p>
                </div>

                @if($errors->any())
                    <div class="mb-10 p-5 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <i class="fas fa-exclamation-circle text-sm"></i>
                        </div>
                        <div class="flex flex-col pt-1">
                            <span class="text-sm font-bold text-rose-800 mb-1">Login Failed</span>
                            <span class="text-sm font-medium text-rose-600">
                                {{ $errors->first() }}
                            </span>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-semibold text-slate-700 ml-1">Email or Username</label>
                        <div class="relative group">
                            <i class="far fa-user absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors"></i>
                            <input type="text" id="username" name="username" required 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-6 py-4 text-sm font-medium text-slate-900 outline-none transition-all placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" 
                                   placeholder="Enter your email or username">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                            <a href="#" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Forgot Password?</a>
                        </div>
                        <div class="relative group">
                            <i class="far fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors"></i>
                            <input type="password" id="password" name="password" required 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-6 py-4 text-sm font-medium text-slate-900 outline-none transition-all placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" 
                                   placeholder="Enter your password">
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-all shadow-md active:scale-[0.98] flex items-center justify-center gap-2">
                            Sign In <i class="fas fa-arrow-right text-xs opacity-70"></i>
                        </button>
                    </div>

                    <div class="pt-8 text-center">
                        <p class="text-xs text-slate-400 font-medium">
                            Version {{ config('app.version', '2.4.0') }}
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
