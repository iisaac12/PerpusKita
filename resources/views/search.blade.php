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
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 2rem;
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
    <div class="glass-card book-card" style="display: flex; flex-direction: column;">
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
            @if($item->stok > 0 && !Auth::user()->isAdmin())
                <a href="{{ route('borrowing.create', ['buku_id' => $item->id_buku]) }}" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">Pinjam</a>
            @endif
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
