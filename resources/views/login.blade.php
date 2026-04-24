<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - MBG</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { font-family: 'Inter', sans-serif; }
    .glass { background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); }
    .input-focus:focus { box-shadow: 0 0 0 3px rgba(59,130,246,0.15); }
    @keyframes fadeInUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    .animate-in { animation: fadeInUp 0.5s ease-out; }
    .animate-in-delay { animation: fadeInUp 0.5s ease-out 0.15s both; }
  </style>
</head>
<body>
  <div class="min-h-screen bg-linear-to-br from-blue-600 via-blue-700 to-indigo-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md animate-in">
      <!-- Logo & Title -->
      <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-35 h-35 mb-4 p-2">
        <img src="{{ asset('img/logo-bgn.png') }}" alt="Logo BGN" class="max-w-full max-h-full object-contain">
      </div>
        <h1 class="text-3xl font-bold text-white tracking-tight">MBG</h1>
        <p class="text-blue-200 mt-1 text-sm">Sistem Inventaris Makan Bergizi Gratis</p>
      </div>

      <!-- Login Card -->
      <div class="glass rounded-2xl shadow-2xl p-6 sm:p-8 animate-in-delay">
        <h2 class="text-xl font-bold text-gray-800 text-center mb-1">Selamat Datang</h2>
        <p class="text-gray-500 text-sm text-center mb-6">Masuk ke akun Anda untuk melanjutkan</p>

        {{-- Status Message (setelah logout) --}}
        @if(session('status'))
        <div class="mb-4 px-4 py-3 rounded-xl text-sm font-medium bg-green-50 border border-green-200 text-green-700 flex items-center gap-2">
          <i class="fas fa-check-circle"></i>
          {{ session('status') }}
        </div>
        @endif

        {{-- Error Message --}}
        @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-xl text-sm font-medium bg-red-50 border border-red-200 text-red-600 flex items-center gap-2">
          <i class="fas fa-exclamation-circle"></i>
          {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
          @csrf
          
          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400 text-sm"></i>
              </div>
              <input 
                type="email" 
                name="email" 
                id="email" 
                value="{{ old('email') }}"
                class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm input-focus" 
                placeholder="nama@email.com"
                autocomplete="email"
                required
                autofocus
              >
            </div>
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400 text-sm"></i>
              </div>
              <input 
                type="password" 
                name="password" 
                id="password" 
                class="w-full pl-10 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm input-focus" 
                placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                autocomplete="current-password"
                required
              >
              <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center">
                <i id="eyeIcon" class="fas fa-eye text-gray-400 hover:text-gray-600 cursor-pointer text-sm transition-colors"></i>
              </button>
            </div>
          </div>

          <!-- Remember Me -->
          <div class="flex items-center">
            <label class="flex items-center cursor-pointer group">
              <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
              <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-800 transition-colors">Ingat saya</span>
            </label>
          </div>

          <!-- Login Button -->
          <button 
            type="submit" 
            class="w-full bg-linear-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl active:scale-[0.98] text-sm"
          >
            <i class="fas fa-sign-in-alt mr-2"></i>
            Masuk
          </button>
        </form>
      </div>

      <!-- Footer -->
      <p class="text-center text-blue-200/70 text-xs mt-8">
        &copy; 2026 MBG &mdash; Sistem Inventaris Makan Bergizi Gratis
      </p>
    </div>
  </div>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const eyeIcon = document.getElementById('eyeIcon');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
      }
    }
  </script>
</body>
</html>
