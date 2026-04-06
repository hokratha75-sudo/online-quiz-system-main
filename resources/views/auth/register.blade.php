<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - QuizMaster</title>
    
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
    </style>
</head>
<body class="font-sans bg-slate-50 min-h-screen flex items-center justify-center p-4 md:p-8">

    <div class="w-full max-w-[1100px] bg-white rounded-[40px] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-slate-100 min-h-[650px]">
        
        <!-- Branding Identity Panel -->
        <div class="w-full md:w-5/12 login-mesh p-12 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute inset-0 backdrop-blur-[2px] z-[1]"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
            
            <div class="relative z-10">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex items-center justify-center mb-8 shadow-xl">
                    <i class="fas fa-graduation-cap text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tighter leading-none mb-6 uppercase">
                    Join <br/> <span class="text-indigo-300">Today.</span>
                </h1>
                <p class="text-indigo-100/70 text-xs md:text-sm font-bold uppercase tracking-widest leading-relaxed max-w-sm italic">
                    Create your account to start your learning journey and access world-class assessment materials.
                </p>
            </div>

            <div class="relative z-10 pt-12">
                <div class="flex items-center gap-4 py-4 border-t border-white/10">
                    <div class="flex -space-x-3">
                        <div class="w-8 h-8 rounded-full border-2 border-indigo-600 bg-slate-800 flex items-center justify-center text-[10px] font-bold text-white shadow-xl">IQ</div>
                        <div class="w-8 h-8 rounded-full border-2 border-indigo-600 bg-indigo-500/20 backdrop-blur-md flex items-center justify-center text-[10px] font-bold text-white shadow-xl">+</div>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest">Self-registration Active</span>
                </div>
            </div>
        </div>

        <!-- Registration Matrix -->
        <div class="w-full md:w-7/12 p-8 md:p-12 lg:p-16 flex flex-col justify-center bg-white overflow-y-auto custom-scrollbar">
            <div class="max-w-xl mx-auto w-full">
                <div class="mb-8 text-center md:text-left">
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Create Account</h2>
                    <p class="text-xs font-bold text-indigo-600 mt-2 uppercase tracking-widest leading-relaxed">Fill in the identity vectors below to proceed</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-rose-500/20">
                            <i class="fas fa-exclamation-triangle text-xs"></i>
                        </div>
                        <div class="text-[11px] font-bold text-rose-700 uppercase tracking-tight leading-relaxed pt-1">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="first_name" class="block text-[10px] font-extrabold text-indigo-700 uppercase tracking-widest mb-2 ml-1">First Name</label>
                            <input type="text" id="first_name" name="first_name" required 
                                   class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-300" 
                                   placeholder="First Name" value="{{ old('first_name') }}">
                        </div>
                        <div>
                            <label for="last_name" class="block text-[10px] font-extrabold text-indigo-700 uppercase tracking-widest mb-2 ml-1">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required 
                                   class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-300" 
                                   placeholder="Last Name" value="{{ old('last_name') }}">
                        </div>
                    </div>

                    <div>
                        <label for="username" class="block text-[10px] font-extrabold text-indigo-700 uppercase tracking-widest mb-2 ml-1">Choose Username</label>
                        <input type="text" id="username" name="username" required 
                               class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all uppercase placeholder:text-slate-300" 
                               placeholder="e.g. STUDENT_PRO" value="{{ old('username') }}">
                    </div>

                    <div>
                        <label for="email" class="block text-[10px] font-extrabold text-indigo-700 uppercase tracking-widest mb-2 ml-1">Institutional Email</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-300" 
                               placeholder="example@student.edu" value="{{ old('email') }}">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="password" class="block text-[10px] font-extrabold text-indigo-700 uppercase tracking-widest mb-2 ml-1">Create Password</label>
                            <input type="password" id="password" name="password" required 
                                   class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-300" 
                                   placeholder="••••••••••••">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-[10px] font-extrabold text-indigo-700 uppercase tracking-widest mb-2 ml-1">Confirm Identity</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required 
                                   class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold text-slate-900 outline-none focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-slate-300" 
                                   placeholder="••••••••••••">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-slate-950 hover:bg-slate-900 text-white py-4 rounded-[20px] font-bold uppercase tracking-[0.2em] text-xs transition-all shadow-2xl shadow-slate-950/20 active:scale-95 flex items-center justify-center gap-3">
                            Confirm Registration <i class="fas fa-check-circle text-[10px]"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-loose italic">
                        Already have an account? <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors">Sign in here</a> <br/>
                        <span class="opacity-50 inline-block mt-2 font-black italic">QuizMaster v2.5.0 Secure Deployment</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
