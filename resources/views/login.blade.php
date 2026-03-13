<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - MBG</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-md">
    <div class="text-center mb-8 text-white">
      <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 rounded-full mb-4 backdrop-blur-sm">
        <i class="fas fa-store text-4xl"></i>
      </div>
      <h1 class="text-3xl font-bold">MBG</h1>
      <p class="text-blue-200 mt-2">Makan Bergizi Gratis</p>
    </div>

    <div class="bg-white rounded-2xl shadow-2xl p-8 bg-white/95">
      <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Selamat Datang</h2>
      
      {{-- Menampilkan Error Login --}}
      @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-5 text-sm rounded">
          @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
        @csrf
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
              <i class="fas fa-envelope"></i>
            </span>
            <input type="email" name="email" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="nama@email.com" value="{{ old('email') }}" required>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
              <i class="fas fa-lock"></i>
            </span>
            <input type="password" name="password" id="password" class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="••••••••" required>
            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
              <i class="fas fa-eye" id="eye-icon"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition transform hover:scale-[1.02] shadow-lg">
          Masuk
        </button>
      </form>

      <p class="text-center text-gray-600 mt-8 text-sm">
        Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-semibold">Daftar sekarang</a>
      </p>
    </div>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const icon = document.getElementById('eye-icon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }
  </script>
</body>
</html>