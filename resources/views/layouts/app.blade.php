<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PerpusKita - Digital Sanctuary')</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <!-- CSS & JS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-icon">
                    <span class="material-symbols-rounded">auto_stories</span>
                </div>
                <div class="brand-name">
                    <h1>PerpusKita</h1>
                    <p style="font-size: 10px; color: var(--text-muted);">Digital Sanctuary</p>
                </div>
            </div>
            
            <nav class="nav-menu">
                <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <span class="material-symbols-rounded">dashboard</span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('library*') ? 'active' : '' }}">
                    <a href="{{ route('library') }}">
                        <span class="material-symbols-rounded">manage_search</span>
                        <span class="nav-text">Library</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('borrowing*') ? 'active' : '' }}">
                    <a href="{{ route('borrowing') }}">
                        <span class="material-symbols-rounded">receipt_long</span>
                        <span class="nav-text">Borrowing</span>
                    </a>
                </li>

                @if(Auth::user()->isAdmin())
                <div style="padding: 1rem 1rem 0.5rem; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Admin Tools</div>
                <li class="nav-item {{ Request::is('admin/kategori*') ? 'active' : '' }}">
                    <a href="{{ route('kategori.index') }}">
                        <span class="material-symbols-rounded">category</span>
                        <span class="nav-text">Kelola Kategori</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/buku*') ? 'active' : '' }}">
                    <a href="{{ route('buku.index') }}">
                        <span class="material-symbols-rounded">book</span>
                        <span class="nav-text">Kelola Buku</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/report*') ? 'active' : '' }}">
                    <a href="{{ route('report.index') }}">
                        <span class="material-symbols-rounded">analytics</span>
                        <span class="nav-text">Rekap Laporan</span>
                    </a>
                </li>
                @endif

                <li class="nav-item {{ Request::is('history*') ? 'active' : '' }}">
                    <a href="{{ route('history') }}">
                        <span class="material-symbols-rounded">history</span>
                        <span class="nav-text">History</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('profile*') ? 'active' : '' }}">
                    <a href="{{ route('profile') }}">
                        <span class="material-symbols-rounded">settings</span>
                        <span class="nav-text">Settings</span>
                    </a>
                </li>
                <li class="nav-item" style="margin-top: auto;">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="material-symbols-rounded">logout</span>
                        <span class="nav-text">Logout</span>
                    </a>
                </li>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <div class="welcome-text">
                    <p style="color: var(--text-muted);">Selamat Datang Kembali</p>
                    <h2 style="font-size: 1.75rem;">@yield('header_title', 'Halo, Selamat Pagi!')</h2>
                </div>
                
                <div class="user-profile">
                    <div class="avatar" style="display: flex; align-items: center; justify-content: center; color: var(--bg-color); font-weight: 700;">
                        {{ substr(Auth::user()->nama, 0, 1) }}
                    </div>
                    <div class="user-info">
                        <p style="font-size: 0.875rem; font-weight: 600;">{{ Auth::user()->nama }}</p>
                        <p style="font-size: 0.75rem; color: var(--text-muted);">{{ ucfirst(Auth::user()->role) }} Profile</p>
                    </div>
                </div>
            </header>

            @if(session('success'))
                <div class="glass-card" style="margin-bottom: 2rem; border-color: rgba(74, 222, 128, 0.2); background: rgba(74, 222, 128, 0.05); color: #4ade80; display: flex; align-items: center; gap: 0.75rem; padding: 1rem;">
                    <span class="material-symbols-rounded">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    @yield('scripts')
</body>
</html>
