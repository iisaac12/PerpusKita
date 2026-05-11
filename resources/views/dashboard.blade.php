@extends('layouts.app')

@section('title', 'Dashboard - PerpusKita')

@section('styles')
<style>
    .books-scroll {
        display: flex;
        gap: 1.5rem;
        overflow-x: auto;
        padding: 0.5rem 0 1.5rem 0;
        scrollbar-width: thin;
        scrollbar-color: var(--glass-border) transparent;
    }
    .book-card-dashboard {
        width: 160px;
        flex-shrink: 0;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .book-card-dashboard:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.35);
    }
    .book-cover-dashboard {
        width: 100%;
        aspect-ratio: 2/3;
        border-radius: var(--radius-md);
        overflow: hidden;
        background: rgba(255,255,255,0.05);
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        margin-bottom: 0.75rem;
        position: relative;
    }
    .book-cover-dashboard img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .book-title-dashboard {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .book-author-dashboard {
        color: var(--text-muted);
        font-size: 0.75rem;
    }
</style>
@endsection

@section('content')
<section class="dashboard-grid">
    @if(Auth::user()->isAdmin())
        <!-- Admin Stat Cards -->
        <div class="glass-card stat-card">
            <div class="stat-header">
                <span>Total Koleksi Buku</span>
                <span class="material-symbols-rounded" style="color: var(--primary);">auto_stories</span>
            </div>
            <div class="stat-value">{{ number_format($stats['total_buku']) }}</div>
            <div class="stat-trend trend-up">
                <span class="material-symbols-rounded" style="font-size: 14px;">category</span>
                {{ $stats['total_kategori'] }} Kategori tersedia
            </div>
        </div>

        <div class="glass-card stat-card">
            <div class="stat-header">
                <span>Peminjaman Aktif</span>
                <span class="material-symbols-rounded" style="color: #f87171;">schedule</span>
            </div>
            <div class="stat-value">{{ $stats['peminjaman_aktif'] }}</div>
            <div class="stat-trend trend-down">
                <span class="material-symbols-rounded" style="font-size: 14px;">error</span>
                Perlu dipantau
            </div>
        </div>

        <div class="glass-card stat-card">
            <div class="stat-header">
                <span>Anggota Terdaftar</span>
                <span class="material-symbols-rounded" style="color: var(--secondary);">person</span>
            </div>
            <div class="stat-value">{{ $stats['total_peminjam'] }}</div>
            <div class="stat-trend trend-up">
                <span class="material-symbols-rounded" style="font-size: 14px;">person_add</span>
                Terus bertambah
            </div>
        </div>
    @else
        <!-- User Stat Cards -->
        <div class="glass-card stat-card">
            <div class="stat-header">
                <span>Buku yang Saya Pinjam</span>
                <span class="material-symbols-rounded" style="color: var(--secondary);">auto_stories</span>
            </div>
            <div class="stat-value">{{ $stats['buku_dipinjam'] }}</div>
            <div class="stat-trend trend-up">
                <span class="material-symbols-rounded" style="font-size: 14px;">verified</span>
                Status: Aktif
            </div>
        </div>

        <div class="glass-card stat-card">
            <div class="stat-header">
                <span>Menunggu Verifikasi</span>
                <span class="material-symbols-rounded" style="color: var(--primary);">pending_actions</span>
            </div>
            <div class="stat-value">{{ $stats['menunggu_verifikasi'] }}</div>
            <div class="stat-trend" style="color: var(--text-muted);">
                <span class="material-symbols-rounded" style="font-size: 14px;">hourglass_empty</span>
                Silakan hubungi admin
            </div>
        </div>

        <div class="glass-card stat-card">
            <div class="stat-header">
                <span>Total Riwayat Baca</span>
                <span class="material-symbols-rounded" style="color: #4ade80;">history</span>
            </div>
            <div class="stat-value">{{ $stats['total_history'] }}</div>
            <div class="stat-trend trend-up">
                <span class="material-symbols-rounded" style="font-size: 14px;">military_tech</span>
                Terus tingkatkan literasi!
            </div>
        </div>
    @endif
</section>

<section class="quick-actions" style="margin-bottom: 3rem;">
    <div class="glass-card" style="display: flex; flex-wrap: wrap; gap: 2rem; align-items: center; background: linear-gradient(135deg, rgba(167, 139, 250, 0.1), rgba(103, 232, 249, 0.1));">
        <div style="flex: 1; min-width: 250px;">
            <h3>Siap mengelola koleksi hari ini?</h3>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Kelola data kategori dan buku untuk memastikan koleksi perpustakaan tetap terorganisir.</p>
        </div>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('library') }}" class="btn btn-primary">Cari Buku</a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('buku.create') }}" class="btn btn-glass">Tambah Buku</a>
            @else
                <a href="{{ route('borrowing') }}" class="btn btn-glass">Pinjam Buku</a>
            @endif
        </div>
    </div>
</section>

<section class="recommendations-section">
    <h3 style="margin-bottom: 1.5rem;">Buku Terbaru</h3>
    <div class="books-scroll">
        @forelse($recent_books as $book)
        <div class="book-card-dashboard" style="cursor: pointer;" onclick="showBookDetail('{{ $book->id_buku }}')">
            <div class="book-cover-dashboard">
                @if($book->cover_buku)
                    <img src="{{ asset('storage/' . $book->cover_buku) }}" alt="{{ $book->judul }}">
                @else
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-rounded" style="font-size: 32px; color: var(--glass-border);">book</span>
                    </div>
                @endif
            </div>
            <p class="book-title-dashboard" title="{{ $book->judul }}">{{ $book->judul }}</p>
            <p class="book-author-dashboard">{{ $book->pengarang }}</p>
        </div>
        @empty
        <p style="color: var(--text-muted);">Belum ada koleksi buku.</p>
        @endforelse
    </div>
</section>
@endsection
