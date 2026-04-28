<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Quiz System</title>
    
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: '#007bff',
              'primary-dark': '#0069d9',
              label: '#5e5873',
              input: '#d8d6de',
              placeholder: '#b9b9c3'
            },
            fontFamily: {
              sans: ['Public Sans', 'sans-serif'],
            }
          }
        }
      }
    </script>
    
    <style>
        body { background-color: #f8f7fa; }
        .form-input:focus {
            border-color: #007bff !important;
            box-shadow: 0 3px 10px 0 rgba(0, 123, 255, 0.15);
        }
        /* Hide browser default password reveal button */
        input::-ms-reveal,
        input::-ms-clear {
            display: none;
        }
        input::-webkit-contacts-auto-fill-button,
        input::-webkit-credentials-auto-fill-button {
            visibility: hidden;
            display: none !important;
            pointer-events: none;
        }
    </style>
</head>
<body class="font-sans min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-[450px] bg-white rounded-xl shadow-[0_4px_24px_0_rgba(34,41,47,0.1)] p-8 md:p-10">
        
        <div class="mb-8 text-center">
            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center shadow-lg shadow-primary/20">
                    <i class="fas fa-graduation-cap text-white text-xl"></i>
                </div>
                <span class="text-slate-800 font-bold text-2xl tracking-tight">Quiz System</span>
            </div>
            
            <h2 class="text-xl font-semibold text-slate-800 mb-1 text-left">Reset Password</h2>
            <p class="text-slate-500 text-sm text-left">Your new password must be different from previously used passwords</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-lg flex items-start gap-3">
                <i class="fas fa-circle-exclamation text-red-500 mt-1"></i>
                <div class="text-sm text-red-600 font-medium">
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-xs font-semibold text-label uppercase tracking-wider mb-2">Email</label>
                <input type="email" id="email" name="email" required 
                       class="w-full bg-slate-50 border border-input text-slate-800 text-sm rounded-md px-4 py-2.5 transition-all outline-none form-input placeholder:text-placeholder" 
                       placeholder="Enter your email"
                       value="{{ $email ?? old('email') }}" readonly>
            </div>

            <div x-data="{ show: false }">
                <label for="password" class="block text-xs font-semibold text-label uppercase tracking-wider mb-2">New Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" id="password" name="password" required 
                           class="w-full bg-white border border-input text-slate-800 text-sm rounded-md px-4 py-2.5 transition-all outline-none form-input placeholder:text-placeholder" 
                           placeholder="············">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <div x-data="{ show: false }">
                <label for="password_confirmation" class="block text-xs font-semibold text-label uppercase tracking-wider mb-2">Confirm New Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required 
                           class="w-full bg-white border border-input text-slate-800 text-sm rounded-md px-4 py-2.5 transition-all outline-none form-input placeholder:text-placeholder" 
                           placeholder="············">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-semibold rounded-md text-white bg-primary hover:bg-primary-dark transition-all shadow-md shadow-primary/20">
                    Set New Password
                </button>
            </div>

        </form>
    </div>

</body>
</html>
