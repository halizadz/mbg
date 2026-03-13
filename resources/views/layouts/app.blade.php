<!DOCTYPE html>
<html lang="id" class="dark-mode">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="theme-color" content="#0f1c2e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>InvenTrack — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background:var(--bg-primary);color:var(--text-primary);" class="min-h-screen transition-colors duration-300">

    <!-- Network Status -->
    <div id="networkStatus" class="network-status"></div>

    <!-- Pull to Refresh -->
    <div id="ptrElement" class="ptr-element">
        <div class="animate-spin text-base">↻</div>
        <span id="ptrText">Tarik untuk refresh</span>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Theme Toggle -->
    <button id="themeToggle"
        class="fixed bottom-4 right-4 lg:bottom-6 lg:right-6 z-50 w-10 h-10 sm:w-12 sm:h-12 rounded-full shadow-lg flex items-center justify-center text-lg sm:text-xl hover:scale-110 transition-transform"
        style="background:var(--bg-secondary);border:1px solid var(--border-color);">
        🌙
    </button>

    <!-- Mobile Menu Button -->
    <button id="mobileMenuBtn"
        class="lg:hidden fixed top-4 left-4 z-50 w-10 h-10 rounded-lg flex items-center justify-center"
        style="background:var(--bg-secondary);border:1px solid var(--border-color);color:var(--text-primary);">
        ☰
    </button>

    <!-- Overlay -->
    <div id="sidebarOverlay" class="lg:hidden fixed inset-0 bg-black/50 z-40 hidden transition-opacity duration-300"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed top-0 left-0 w-[240px] h-screen flex flex-col z-50 transition-all duration-300 -translate-x-full lg:translate-x-0 overflow-y-auto"
        style="background:var(--bg-secondary);border-right:1px solid var(--border-color);">

        <!-- Logo -->
        <div class="px-5 py-6 pb-5" style="border-bottom:1px solid var(--border-color);">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-gradient-to-r from-accent to-accent2 rounded-[10px] flex items-center justify-center text-lg text-white">
                    📦
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
            <span>🏠</span> Dashboard
        </a>
        <a href="{{ route('barang.index') }}" class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
            <span>📋</span> Data Barang
        </a>
        <a href="{{ route('transaksi.masuk') }}" class="nav-item {{ request()->routeIs('transaksi.masuk') ? 'active' : '' }}">
            <span>📥</span> Barang Masuk
        </a>
        <a href="{{ route('transaksi.keluar') }}" class="nav-item {{ request()->routeIs('transaksi.keluar') ? 'active' : '' }}">
            <span>📤</span> Barang Keluar
        </a>

        <div class="px-3 pt-4 pb-2 text-[10px] font-semibold tracking-[1.2px] uppercase" style="color:var(--text-secondary);">Laporan</div>
        <a href="{{ route('laporan.index') }}" class="nav-item {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
            <span>📊</span> Laporan
        </a>
        <a href="{{ route('stok.menipis') }}" class="nav-item {{ request()->routeIs('stok.menipis') ? 'active' : '' }}">
            <span>⚠️</span> Stok Menipis
            <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full text-white" style="background:#ef4444;">4</span>
        </a>

        <div class="px-3 pt-4 pb-2 text-[10px] font-semibold tracking-[1.2px] uppercase" style="color:var(--text-secondary);">Sistem</div>
        <a href="{{ route('user.profile') }}" class="nav-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
            <span>👤</span> Profil & User
        </a>

        <!-- User Info -->
        <div class="mt-auto p-4" style="border-top:1px solid var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-accent rounded-full flex items-center justify-center text-sm font-bold text-white shrink-0">
                    A
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold truncate">Admin Gudang</div>
                    <div class="text-xs text-accent">Administrator</div>
                </div>
                <button id="logoutBtn" class="transition-colors" style="color:var(--text-secondary);" title="Logout">⎋</button>
            </div>
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

            <!-- Search (desktop) -->
            <div class="hidden sm:flex items-center gap-2 rounded-lg px-3 py-1.5 w-[180px] lg:w-[220px]"
                style="background:var(--bg-secondary);border:1px solid var(--border-color);">
                <span style="color:var(--text-secondary);">🔍</span>
                <input type="text" placeholder="Cari barang..."
                    class="bg-transparent border-none outline-none text-[13px] font-sans w-full"
                    style="color:var(--text-primary);">
            </div>

            <!-- Mobile Search -->
            <button id="mobileSearchBtn" class="sm:hidden w-8 h-8 rounded-lg flex items-center justify-center"
                style="border:1px solid var(--border-color);background:var(--bg-secondary);color:var(--text-secondary);">
                🔍
            </button>

            <!-- Notifications -->
            <button id="notificationBtn" class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg flex items-center justify-center relative"
                style="border:1px solid var(--border-color);background:var(--bg-secondary);color:var(--text-secondary);">
                🔔
                <span id="notificationDot" class="absolute top-1.5 right-1.5 w-[7px] h-[7px] bg-danger rounded-full"
                    style="border:2px solid var(--bg-primary);"></span>
            </button>

            <!-- Refresh -->
            <button id="refreshBtn" class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg flex items-center justify-center"
                style="border:1px solid var(--border-color);background:var(--bg-secondary);color:var(--text-secondary);">
                ↻
            </button>
        </header>

        <!-- Mobile Search Drawer -->
        <div id="mobileSearchDrawer"
            class="fixed inset-x-0 top-0 p-4 transform -translate-y-full transition-transform duration-300 z-50"
            style="background:var(--bg-secondary);border-bottom:1px solid var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="flex-1 flex items-center gap-2 rounded-lg px-3 py-2"
                    style="background:var(--bg-primary);border:1px solid var(--border-color);">
                    <span style="color:var(--text-secondary);">🔍</span>
                    <input type="text" id="mobileSearchInput" placeholder="Cari barang..."
                        class="bg-transparent border-none outline-none text-sm w-full"
                        style="color:var(--text-primary);">
                </div>
                <button id="closeMobileSearch" style="color:var(--text-secondary);">✕</button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content p-3 sm:p-5 lg:p-7 flex-1">
            @yield('content')
        </div>
    </main>

    @include('components.scripts')
</body>
</html>