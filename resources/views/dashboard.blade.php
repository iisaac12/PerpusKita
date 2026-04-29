@extends('layouts.app')

@section('title', 'Dashboard - PerpusKita')
@section('header_title', 'Halo, Selamat Pagi!')

@section('content')
<section class="dashboard-grid">
    <!-- Stat Cards -->
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
    <h3>Buku Terbaru</h3>
    <div class="books-scroll">
        @forelse($recent_books as $book)
        <div class="book-card">
            <div class="book-cover">
                @if($book->cover_buku)
                    <img src="{{ asset('storage/' . $book->cover_buku) }}" alt="{{ $book->judul }}">
                @else
                    <div style="width: 100%; height: 100%; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-rounded" style="font-size: 48px; color: var(--glass-border);">book</span>
                    </div>
                @endif
            </div>
            <p class="book-title">{{ $book->judul }}</p>
            <p class="book-author">{{ $book->pengarang }}</p>
        </div>
        @empty
        <p style="color: var(--text-muted);">Belum ada koleksi buku.</p>
        @endforelse
    </div>
</section>
@endsection
