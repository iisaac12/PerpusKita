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
                <li class="nav-item {{ Request::is('admin/member*') ? 'active' : '' }}">
                    <a href="{{ route('member.index') }}">
                        <span class="material-symbols-rounded">group</span>
                        <span class="nav-text">Daftar Member</span>
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
                @php
                    $hour = now()->hour;
                    if ($hour >= 5 && $hour < 11) {
                        $greeting = 'Selamat Pagi';
                    } elseif ($hour >= 11 && $hour < 15) {
                        $greeting = 'Selamat Siang';
                    } elseif ($hour >= 15 && $hour < 18) {
                        $greeting = 'Selamat Sore';
                    } else {
                        $greeting = 'Selamat Malam';
                    }
                @endphp
                <div class="welcome-text">
                    <p style="color: var(--text-muted);">Selamat Datang Kembali</p>
                    <h2 style="font-size: 1.75rem;">@yield('header_title', 'Halo, ' . $greeting . '!')</h2>
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

    <!-- Global Book Detail Modal -->
    <div id="bookModal" class="modal-overlay" onclick="closeModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <span class="material-symbols-rounded close-modal" onclick="closeModal()">close</span>
            <div class="modal-left">
                <div class="book-cover modal-book-cover" id="modalCover" style="height: 400px; box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
                    <!-- Cover Image via JS -->
                </div>
            </div>
            <div class="modal-right" style="display: flex; flex-direction: column; gap: 1rem;">
                <h2 id="modalTitle" style="font-size: 2rem; font-weight: 700; color: #fff;"></h2>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;" id="modalCategories">
                    <!-- Categories via JS -->
                </div>
                
                <div style="display: grid; grid-template-columns: 100px 1fr; gap: 0.5rem; font-size: 0.9rem; margin-top: 1rem;">
                    <span style="color: var(--text-muted);">Penulis</span>
                    <span id="modalAuthor" style="color: #fff; font-weight: 500;"></span>
                    
                    <span style="color: var(--text-muted);">Penerbit</span>
                    <span id="modalPublisher" style="color: #fff; font-weight: 500;"></span>
                    
                    <span style="color: var(--text-muted);">Status</span>
                    <span id="modalStatus"></span>
                </div>

                <hr style="border: none; border-top: 1px solid var(--glass-border); margin: 1rem 0;">
                
                <div style="flex: 1;">
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem;">Sinopsis</p>
                    <p id="modalDescription" style="line-height: 1.6; color: var(--text-main); font-size: 0.95rem;"></p>
                </div>

                <div id="modalAction" style="margin-top: 2rem;">
                    <!-- Action Button via JS -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showBookDetail(id) {
            const modal = document.getElementById('bookModal');
            
            // Reset modal content
            document.getElementById('modalTitle').innerText = 'Memuat...';
            document.getElementById('modalDescription').innerText = '';
            document.getElementById('modalCategories').innerHTML = '';
            document.getElementById('modalCover').innerHTML = '';
            document.getElementById('modalAuthor').innerText = '';
            document.getElementById('modalPublisher').innerText = '';
            document.getElementById('modalStatus').innerHTML = '';
            document.getElementById('modalAction').innerHTML = '';
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            fetch(`/api/buku/${id}`)
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        const buku = data.data;
                        document.getElementById('modalTitle').innerText = buku.judul;
                        document.getElementById('modalAuthor').innerText = buku.pengarang;
                        document.getElementById('modalPublisher').innerText = buku.penerbit;
                        document.getElementById('modalDescription').innerText = buku.deskripsi || 'Tidak ada sinopsis untuk buku ini.';
                        
                        const coverContainer = document.getElementById('modalCover');
                        if(buku.cover_buku) {
                            coverContainer.innerHTML = `<img src="/storage/${buku.cover_buku}" alt="${buku.judul}" style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-md);">`;
                        } else {
                            coverContainer.innerHTML = `<div style="width: 100%; height: 100%; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center;"><span class="material-symbols-rounded" style="font-size: 64px; color: var(--glass-border);">book</span></div>`;
                        }

                        const catContainer = document.getElementById('modalCategories');
                        if(buku.categories) {
                            buku.categories.forEach(cat => {
                                const badge = document.createElement('span');
                                badge.className = 'filter-chip active';
                                badge.style.fontSize = '0.7rem';
                                badge.style.padding = '0.25rem 0.75rem';
                                badge.style.background = 'var(--secondary-glow)';
                                badge.style.color = 'var(--secondary)';
                                badge.style.border = '1px solid var(--secondary)';
                                badge.style.borderRadius = '50px';
                                badge.innerText = cat.nama_kategori;
                                catContainer.appendChild(badge);
                            });
                        }

                        const statusEl = document.getElementById('modalStatus');
                        if(buku.stok > 0) {
                            statusEl.innerHTML = `<span style="color: #4ade80;">Tersedia (${buku.stok})</span>`;
                            @if(Auth::check() && !Auth::user()->isAdmin())
                                document.getElementById('modalAction').innerHTML = `
                                    <a href="/borrowing/create?buku_id=${buku.id_buku}" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem;">
                                        Pinjam Buku Sekarang
                                    </a>
                                `;
                            @endif
                        } else {
                            statusEl.innerHTML = `<span style="color: #f87171;">Stok Habis</span>`;
                        }
                    }
                });
        }

        function closeModal(e) {
            document.getElementById('bookModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', (e) => {
            if(e.key === 'Escape') closeModal();
        });
    </script>

    @yield('scripts')
</body>
</html>
