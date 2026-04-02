<!DOCTYPE html>
<html lang="id" class="light-mode">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="theme-color" content="#f1f5f9">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>InvenTrack — @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <script>
        // Persist theme immediately to prevent flashing
        let theme = localStorage.getItem('theme');
        if (!theme) {
            theme = 'light';
            localStorage.setItem('theme', theme);
        }
        
        if (theme === 'dark') {
            document.documentElement.classList.remove('light-mode');
            document.documentElement.classList.add('dark-mode');
        } else {
            document.documentElement.classList.remove('dark-mode');
            document.documentElement.classList.add('light-mode');
        }
    </script>
</head>
<body style="background:var(--bg-primary);color:var(--text-primary);" class="min-h-screen transition-colors duration-300">

    <!-- Network Status -->
    <div id="networkStatus" class="network-status"></div>

    <!-- Pull to Refresh -->
    <div id="ptrElement" class="ptr-element">
        <div class="animate-spin text-base"><i class="fas fa-spinner"></i></div>
        <span id="ptrText">Tarik untuk refresh</span>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Theme Toggle -->
    <button id="themeToggle"
        class="fixed bottom-4 right-4 lg:bottom-6 lg:right-6 z-50 w-10 h-10 sm:w-12 sm:h-12 rounded-full shadow-lg flex items-center justify-center text-lg sm:text-xl hover:scale-110 transition-transform"
        style="background:var(--bg-secondary);border:1px solid var(--border-color);">
        <i class="fas fa-moon"></i>
    </button>

    <!-- Mobile Menu Button -->
    <button id="mobileMenuBtn"
        class="lg:hidden fixed top-4 left-4 z-50 w-10 h-10 rounded-lg flex items-center justify-center"
        style="background:var(--bg-secondary);border:1px solid var(--border-color);color:var(--text-primary);">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay -->
    <div id="sidebarOverlay" class="lg:hidden fixed inset-0 bg-black/50 z-40 hidden transition-opacity duration-300"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
    class="fixed top-0 left-0 w-[240px] h-screen flex flex-col z-50 transition-all duration-300 -translate-x-full lg:translate-x-0 overflow-y-auto pb-[env(safe-area-inset-bottom)]"
        style="background:var(--bg-secondary);border-right:1px solid var(--border-color);">

        <!-- Logo -->
        <div class="px-5 py-6 pb-5" style="border-bottom:1px solid var(--border-color);">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-gradient-to-r from-accent to-accent2 rounded-[10px] flex items-center justify-center text-lg text-white">
                    <i class="fas fa-box-open"></i>
                </div>
                <div>
                    <div class="text-[17px] font-bold tracking-[-0.3px]">InvenTrack</div>
                    <div class="text-[11px] mt-0.5" style="color:var(--text-secondary);">Manajemen Inventaris</div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="px-3 pt-4 pb-2 text-[10px] font-semibold tracking-[1.2px] uppercase" style="color:var(--text-secondary);">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home w-5 text-center"></i> Dashboard
        </a>
        <a href="{{ route('barang.index') }}" class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
            <i class="fas fa-list-alt w-5 text-center text-accent"></i> Data Barang
        </a>
        <a href="{{ route('transaksi.masuk') }}" class="nav-item {{ request()->routeIs('transaksi.masuk') ? 'active' : '' }}">
            <i class="fas fa-arrow-down w-5 text-center text-success"></i> Barang Masuk
        </a>
        <a href="{{ route('transaksi.keluar') }}" class="nav-item {{ request()->routeIs('transaksi.keluar') ? 'active' : '' }}">
            <i class="fas fa-arrow-up w-5 text-center text-danger"></i> Barang Keluar
        </a>

        <div class="px-3 pt-4 pb-2 text-[10px] font-semibold tracking-[1.2px] uppercase" style="color:var(--text-secondary);">Laporan</div>
        <a href="{{ route('laporan.index') }}" class="nav-item {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
            <i class="fas fa-chart-bar w-5 text-center text-indigo-400"></i> Laporan
        </a>
        <a href="{{ route('stok.menipis') }}" class="nav-item {{ request()->routeIs('stok.menipis') ? 'active' : '' }}">
            <i class="fas fa-exclamation-triangle w-5 text-center text-warning"></i> Stok Menipis
            @php $stokMenipisCount = \App\Models\Barang::whereColumn('stok', '<=', 'stok_minimum')->count(); @endphp
            @if($stokMenipisCount > 0)
            <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full text-white" style="background:#ef4444;">{{ $stokMenipisCount }}</span>
            @endif
        </a>

        @if(auth()->user()->isAdmin())
        <div class="px-3 pt-4 pb-2 text-[10px] font-semibold tracking-[1.2px] uppercase" style="color:var(--text-secondary);">Sistem</div>
        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-users w-5 text-center text-accent2"></i> Manajemen User
        </a>
        <a href="{{ route('audit.index') }}" class="nav-item {{ request()->routeIs('audit.index') ? 'active' : '' }}">
            <i class="fas fa-history w-5 text-center text-indigo-400"></i> Log Aktivitas (Audit)
        </a>
        <a href="{{ route('trash.index') }}" class="nav-item {{ request()->routeIs('trash.*') ? 'active' : '' }}">
            <i class="fas fa-trash-restore w-5 text-center text-red-400"></i> Tempat Sampah
        </a>
        @endif

        <!-- User Info & Logout (Bottom Sidebar) -->
        <div class="lg:mt-auto mt-6 mb-8 lg:mb-0 p-4 flex items-center gap-3" style="border-top:1px solid var(--border-color);">
            <a href="{{ route('user.profil') }}" class="flex-1 min-w-0 flex items-center gap-3 group" title="Profil Saya">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-accent rounded-full flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform shadow-sm border border-blue-400/20 text-white">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold truncate group-hover:text-accent transition-colors">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="text-[10px] text-accent font-medium mt-0.5">PROFIL SAYA</div>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all hover:bg-red-500/10 text-red-500 border border-red-500/20 active:scale-95" title="Logout">
                    <span>Keluar</span>
                    <span class="text-sm"><i class="fas fa-undo"></i></span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="min-h-screen flex flex-col transition-all duration-300 lg:ml-[240px] ml-0">
        <!-- Topbar -->
        <header class="sticky top-0 backdrop-blur-md px-4 sm:px-7 h-[60px] flex items-center gap-2 sm:gap-4 z-40"
            style="background:rgba(var(--bg-primary-rgb, 15,28,46),0.90);border-bottom:1px solid var(--border-color);background-color:color-mix(in srgb, var(--bg-primary) 90%, transparent);">
            <div class="lg:hidden w-10"></div><!-- spacer for mobile menu btn -->
            <div class="flex-1 min-w-0">
                <div class="text-sm sm:text-base font-semibold truncate">@yield('title', 'Dashboard')</div>
                <div class="text-[10px] sm:text-xs truncate" style="color:var(--text-secondary);">@yield('breadcrumb')</div>
            </div>

            <!-- Notifications -->
            <button id="notificationBtn" class="sm:ml-auto w-8 h-8 sm:w-9 sm:h-9 rounded-lg flex items-center justify-center relative"
                style="border:1px solid var(--border-color);background:var(--bg-secondary);color:var(--text-secondary);">
                <i class="fas fa-bell"></i>
                <span id="notificationDot" class="absolute top-1.5 right-1.5 w-[7px] h-[7px] bg-danger rounded-full"
                    style="border:2px solid var(--bg-primary);"></span>
            </button>

            <!-- Refresh -->
            <button id="refreshBtn" class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg flex items-center justify-center"
                style="border:1px solid var(--border-color);background:var(--bg-secondary);color:var(--text-secondary);">
                <i class="fas fa-sync-alt"></i>
            </button>
        </header>

        <!-- Content Area -->
        <div class="content p-3 sm:p-5 lg:p-7 flex-1">
            @yield('content')
        </div>
    </main>

    @include('components.scripts')
</body>
</html>