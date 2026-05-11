@extends('layouts.app')

@section('title', 'Library - PerpusKita')
@section('header_title', 'Cari Buku')

@section('styles')
<style>
    .filter-section {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
    }
    .filter-chip {
        padding: 0.5rem 1.25rem;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 50px;
        color: var(--text-muted);
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .filter-chip.active, .filter-chip:hover {
        background: var(--secondary-glow);
        color: var(--secondary);
        border-color: var(--secondary);
    }
    .results-grid {
        column-count: 6;
        column-gap: 1.5rem;
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
    }
    .book-card {
        display: inline-block; /* Lebih stabil untuk column-layout */
        width: 100%;
        margin-bottom: 2rem;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        break-inside: avoid;
    }
    .book-card:hover {
        transform: translateY(-8px);
    }
    .book-cover {
        width: 100%;
        max-height: 400px; /* Pengaman agar tidak terlalu panjang */
        border-radius: var(--radius-md);
        overflow: hidden;
        background: rgba(255,255,255,0.03);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        margin-bottom: 1rem;
    }
    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Memastikan area terisi rapi */
        display: block;
    }

    /* Adaptive Grid */
    @media (max-width: 1600px) { .results-grid { column-count: 5; } }
    @media (max-width: 1200px) { .results-grid { column-count: 4; } }
    @media (max-width: 992px) { .results-grid { column-count: 3; } }
    @media (max-width: 768px) { .results-grid { column-count: 2; } }
    @media (max-width: 480px) { .results-grid { column-count: 2; column-gap: 0.75rem; } }
    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }
    .modal-content {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(25px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 2.5rem;
        position: relative;
        transform: scale(0.9);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 2.5rem;
    }
    .modal-overlay.active .modal-content {
        transform: scale(1);
    }
    .close-modal {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        cursor: pointer;
        color: var(--text-muted);
        transition: color 0.2s;
    }
    .close-modal:hover { color: #fff; }

    @media (max-width: 768px) {
        .modal-content {
            grid-template-columns: 1fr;
            padding: 1.5rem;
            gap: 1.5rem;
        }
        .modal-book-cover { width: 180px; margin: 0 auto; }
    }
</style>
@endsection

@section('content')
<form action="{{ route('library') }}" method="GET">
    <div class="search-container" style="margin-bottom: 2rem;">
        <span class="material-symbols-rounded" style="color: var(--text-muted);">search</span>
        <input type="text" name="search" placeholder="Cari judul buku, penulis, atau ID..." value="{{ request('search') }}">
        <button type="submit" style="display: none;"></button>
    </div>

    <section class="filter-section">
        <a href="{{ route('library', ['category' => 'all', 'search' => request('search')]) }}" 
           class="filter-chip {{ request('category', 'all') == 'all' ? 'active' : '' }}" style="text-decoration: none;">Semua</a>
        @foreach($categories as $cat)
            <a href="{{ route('library', ['category' => $cat->nama_kategori, 'search' => request('search')]) }}" 
               class="filter-chip {{ request('category') == $cat->nama_kategori ? 'active' : '' }}" style="text-decoration: none;">
                {{ $cat->nama_kategori }}
            </a>
        @endforeach
    </section>
</form>

<section class="results-grid">
    @forelse($buku as $item)
    <div class="glass-card book-card" style="display: flex; flex-direction: column; cursor: pointer;" onclick="showBookDetail('{{ $item->id_buku }}')">
        <div class="book-cover">
            @if($item->cover_buku)
                <img src="{{ asset('storage/' . $item->cover_buku) }}" alt="{{ $item->judul }}">
            @else
                <div style="width: 100%; height: 100%; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center;">
                    <span class="material-symbols-rounded" style="font-size: 48px; color: var(--glass-border);">book</span>
                </div>
            @endif
        </div>
        <p class="book-title" style="margin-top: 0.5rem;">{{ $item->judul }}</p>
        <p class="book-author" style="margin-bottom: auto;">{{ $item->pengarang }}</p>
        
        <div style="margin-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
            <p style="font-size: 12px; color: {{ $item->stok > 0 ? '#4ade80' : '#f87171' }}; display: flex; align-items: center; gap: 4px;">
                <span class="material-symbols-rounded" style="font-size: 14px;">{{ $item->stok > 0 ? 'check_circle' : 'error' }}</span> 
                {{ $item->stok > 0 ? 'Tersedia' : 'Habis' }}
            </p>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; color: var(--text-muted);">
        <span class="material-symbols-rounded" style="font-size: 4rem; display: block; margin-bottom: 1rem;">search_off</span>
        Buku tidak ditemukan.
    </div>
    @endforelse
</section>

<div style="margin-top: 3rem;">
    {{ $buku->appends(request()->query())->links() }}
</div>
@endsection

@section('scripts')
<!-- Modal Detail Buku -->
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
        const modalContent = modal.querySelector('.modal-content');
        
        // Reset modal content/show loading
        document.getElementById('modalTitle').innerText = 'Memuat...';
        document.getElementById('modalDescription').innerText = '';
        document.getElementById('modalCategories').innerHTML = '';
        document.getElementById('modalCover').innerHTML = '';
        document.getElementById('modalAuthor').innerText = '';
        document.getElementById('modalPublisher').innerText = '';
        document.getElementById('modalStatus').innerHTML = '';
        document.getElementById('modalAction').innerHTML = '';
        
        // Tampilkan modal overlay
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Fetch data dari API
        fetch(`/api/buku/${id}`)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const buku = data.data;
                    
                    // Judul & Info Dasar
                    document.getElementById('modalTitle').innerText = buku.judul;
                    document.getElementById('modalAuthor').innerText = buku.pengarang;
                    document.getElementById('modalPublisher').innerText = buku.penerbit;
                    document.getElementById('modalDescription').innerText = buku.deskripsi || 'Tidak ada sinopsis untuk buku ini.';
                    
                    // Cover
                    const coverContainer = document.getElementById('modalCover');
                    if(buku.cover_buku) {
                        coverContainer.innerHTML = `<img src="/storage/${buku.cover_buku}" alt="${buku.judul}" style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-md);">`;
                    } else {
                        coverContainer.innerHTML = `<div style="width: 100%; height: 100%; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center;"><span class="material-symbols-rounded" style="font-size: 64px; color: var(--glass-border);">book</span></div>`;
                    }

                    // Kategori (Badge)
                    const catContainer = document.getElementById('modalCategories');
                    if(buku.categories) {
                        buku.categories.forEach(cat => {
                            const badge = document.createElement('span');
                            badge.className = 'filter-chip active';
                            badge.style.fontSize = '0.7rem';
                            badge.style.padding = '0.25rem 0.75rem';
                            badge.innerText = cat.nama_kategori;
                            catContainer.appendChild(badge);
                        });
                    }

                    // Status Stok
                    const statusEl = document.getElementById('modalStatus');
                    if(buku.stok > 0) {
                        statusEl.innerHTML = `<span style="color: #4ade80;">Tersedia (${buku.stok})</span>`;
                        // Action Button (Hanya jika bukan Admin)
                        @if(!Auth::user()->isAdmin())
                            document.getElementById('modalAction').innerHTML = `
                                <a href="/peminjaman/create?buku_id=${buku.id_buku}" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem;">
                                    Pinjam Buku Sekarang
                                </a>
                            `;
                        @endif
                    } else {
                        statusEl.innerHTML = `<span style="color: #f87171;">Stok Habis</span>`;
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching book detail:', error);
                document.getElementById('modalTitle').innerText = 'Gagal memuat data.';
            });
    }

    function closeModal(e) {
        const modal = document.getElementById('bookModal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Tutup modal dengan tombol ESC
    document.addEventListener('keydown', (e) => {
        if(e.key === 'Escape') closeModal();
    });
</script>
@endsection
