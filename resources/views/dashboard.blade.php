@extends('layouts.app')

@section('title', 'Dashboard - PerpusKita')
@section('header_title', 'Halo, Selamat Pagi!')

@section('content')
<section class="dashboard-grid">
    <!-- Stat Cards -->
    <div class="glass-card stat-card">
        <div class="stat-header">
            <span>Buku Dipinjam</span>
            <span class="material-symbols-rounded" style="color: var(--primary);">auto_stories</span>
        </div>
        <div class="stat-value">1,284</div>
        <div class="stat-trend trend-up">
            <span class="material-symbols-rounded" style="font-size: 14px;">trending_up</span>
            +12% dari bulan lalu
        </div>
    </div>

    <div class="glass-card stat-card">
        <div class="stat-header">
            <span>Overdue</span>
            <span class="material-symbols-rounded" style="color: #f87171;">schedule</span>
        </div>
        <div class="stat-value">42</div>
        <div class="stat-trend trend-down">
            <span class="material-symbols-rounded" style="font-size: 14px;">error</span>
            Perlu tindak lanjut
        </div>
    </div>

    <div class="glass-card stat-card">
        <div class="stat-header">
            <span>Anggota Aktif</span>
            <span class="material-symbols-rounded" style="color: var(--secondary);">person</span>
        </div>
        <div class="stat-value">856</div>
        <div class="stat-trend trend-up">
            <span class="material-symbols-rounded" style="font-size: 14px;">person_add</span>
            8 pendaftar baru
        </div>
    </div>
</section>

<section class="quick-actions" style="margin-bottom: 3rem;">
    <div class="glass-card" style="display: flex; flex-wrap: wrap; gap: 2rem; align-items: center; background: linear-gradient(135deg, rgba(167, 139, 250, 0.1), rgba(103, 232, 249, 0.1));">
        <div style="flex: 1; min-width: 250px;">
            <h3>Siap mengelola koleksi hari ini?</h3>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Ada 24 buku baru yang baru saja didata dan siap untuk dipinjam oleh para pembaca.</p>
        </div>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('library') }}" class="btn btn-primary">Cari Buku</a>
            <a href="{{ route('borrowing') }}" class="btn btn-glass">Pinjam Buku</a>
        </div>
    </div>
</section>

<section class="recommendations-section">
    <h3>Rekomendasi Utama</h3>
    <div class="books-scroll">
        @php
            $books = [
                ['title' => 'The Midnight Library', 'author' => 'Matt Haig', 'pos' => '0%'],
                ['title' => 'Sapiens', 'author' => 'Yuval Noah Harari', 'pos' => '25%'],
                ['title' => 'Dune: Part One', 'author' => 'Frank Herbert', 'pos' => '50%'],
                ['title' => 'Deep Work', 'author' => 'Cal Newport', 'pos' => '75%'],
                ['title' => 'Architecture of Focus', 'author' => 'P.J. Sterling', 'pos' => '100%'],
            ];
        @endphp
        @foreach($books as $book)
        <div class="book-card">
            <div class="book-cover" style="background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center;">
                <!-- Fallback if no images -->
                <span class="material-symbols-rounded" style="font-size: 48px; color: var(--glass-border);">book</span>
            </div>
            <p class="book-title">{{ $book['title'] }}</p>
            <p class="book-author">{{ $book['author'] }}</p>
        </div>
        @endforeach
    </div>
</section>
@endsection
