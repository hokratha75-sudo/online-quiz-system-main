<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login - QuizMaster</title>
    
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
        .login-mesh {
            background-image: url('/image/login_bg.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .login-mesh::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.95), rgba(15, 23, 42, 0.9));
            z-index: 1;
        }
        [vx-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans bg-slate-50 min-h-screen flex items-center justify-center p-4 md:p-8">

    <div class="w-full max-w-[1100px] bg-white rounded-[40px] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-slate-100 min-h-[600px]">
        
        <!-- Branding Identity Panel -->
        <div class="w-full md:w-1/2 login-mesh p-12 md:p-16 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute inset-0 backdrop-blur-[2px] z-[1]"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
            
            <div class="relative z-10">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex items-center justify-center mb-8 shadow-xl">
                    <i class="fas fa-microchip text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tighter leading-none mb-6 uppercase">
                    Welcome <br/> <span class="text-indigo-300">Back.</span>
                </h1>
                <p class="text-indigo-100/70 text-xs md:text-sm font-bold uppercase tracking-widest leading-relaxed max-w-sm italic">
                    Sign in to access your courses, quizzes, and academic performance analytics.
                </p>
            </div>

            <div class="relative z-10 pt-12">
                <div class="flex items-center gap-4 py-4 border-t border-white/10">
                    <div class="flex -space-x-3">
                        <div class="w-8 h-8 rounded-full border-2 border-indigo-600 bg-slate-800 flex items-center justify-center text-[10px] font-bold text-white">AD</div>
                        <div class="w-8 h-8 rounded-full border-2 border-indigo-600 bg-emerald-600 flex items-center justify-center text-[10px] font-bold text-white">TC</div>
                        <div class="w-8 h-8 rounded-full border-2 border-indigo-600 bg-amber-600 flex items-center justify-center text-[10px] font-bold text-white">ST</div>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest">Secure Access Enabled</span>
                </div>
            </div>
        </div>

        <!-- Authentication Matrix -->
        <div class="w-full md:w-1/2 p-10 md:p-20 flex flex-col justify-center bg-white">
            <div class="max-w-md mx-auto w-full">
                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Account Login</h2>
                    <p class="text-xs font-bold text-indigo-600 mt-2 uppercase tracking-widest">Premium Learning Experience</p>
                </div>

                @if($errors->any())
                    <div class="mb-8 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-rose-500/20">
                            <i class="fas fa-exclamation-triangle text-xs"></i>
                        </div>
                        <div class="text-[11px] font-bold text-rose-700 uppercase tracking-tight leading-relaxed pt-1">
                            {{ $errors->first() }}
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="username" class="block text-[10px] font-bold text-indigo-700 uppercase tracking-widest mb-3 ml-1">Username or Email</label>
                        <div class="relative group">
                            <i class="fas fa-user absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                            <input type="text" id="username" name="username" required 
                                   class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-900 outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all uppercase placeholder:text-slate-300" 
                                   placeholder="Your Account ID" value="{{ old('username') }}">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-[10px] font-bold text-indigo-700 uppercase tracking-widest mb-3 ml-1">Password</label>
                        <div class="relative group">
                            <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors"></i>
                            <input type="password" id="password" name="password" required 
                                   class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-900 outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-300" 
                                   placeholder="••••••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-5 h-5 rounded-lg border-slate-200 text-indigo-600 focus:ring-indigo-500 bg-slate-50 accent-indigo-600 cursor-pointer">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-slate-950 hover:bg-slate-900 text-white py-5 rounded-[24px] font-bold uppercase tracking-[0.2em] text-xs transition-all shadow-2xl shadow-slate-950/20 active:scale-95 flex items-center justify-center gap-3">
                        Sign In <i class="fas fa-arrow-right text-[10px]"></i>
                    </button>
                    
                    <div class="mt-8 text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">
                            Don't have an account? <a href="{{ url('/register') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors underline underline-offset-4">Sign up for free</a>
                        </p>
                    </div>
                </form>

                <div class="mt-12 text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">
                        QuizMaster v2.5.0 <br/>
                        <span class="text-indigo-600 opacity-50 mt-1 inline-block">Secure Protocol Active</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
