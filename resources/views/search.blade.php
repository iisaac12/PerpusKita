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
        display: inline-block;
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
        max-height: 400px;
        border-radius: var(--radius-md);
        overflow: hidden;
        background: rgba(255,255,255,0.03);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        margin-bottom: 1rem;
    }
    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    @media (max-width: 1600px) { .results-grid { column-count: 5; } }
    @media (max-width: 1200px) { .results-grid { column-count: 4; } }
    @media (max-width: 992px) { .results-grid { column-count: 3; } }
    @media (max-width: 768px) { .results-grid { column-count: 2; } }
    @media (max-width: 480px) { .results-grid { column-count: 2; column-gap: 0.75rem; } }
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

<div class="pagination-container" style="margin-top: 3rem; display: flex; justify-content: center;">
    {{ $buku->appends(request()->query())->links('pagination::bootstrap-4') }}
</div>
@endsection
