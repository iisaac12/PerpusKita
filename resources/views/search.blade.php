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
<div class="search-container" style="margin-bottom: 2rem;">
    <span class="material-symbols-rounded" style="color: var(--text-muted);">search</span>
    <input type="text" placeholder="Cari judul buku, penulis, atau ISBN...">
</div>

<section class="filter-section">
    <div class="filter-chip active">Semua</div>
    <div class="filter-chip">Fiksi</div>
    <div class="filter-chip">Teknologi</div>
    <div class="filter-chip">Sains</div>
    <div class="filter-chip">Sejarah</div>
    <div class="filter-chip">Filosofi</div>
    <div class="filter-chip">Arsitektur</div>
</section>

<section class="results-grid">
    @for($i = 0; $i < 8; $i++)
    <div class="glass-card book-card">
        <div class="book-cover" style="background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center;">
            <span class="material-symbols-rounded" style="font-size: 48px; color: var(--glass-border);">book</span>
        </div>
        <p class="book-title">Buku Dummy #{{ $i + 1 }}</p>
        <p class="book-author">Penulis Terkenal</p>
        <p style="font-size: 12px; color: #4ade80; margin-top: 0.5rem; display: flex; align-items: center; gap: 4px;">
            <span class="material-symbols-rounded" style="font-size: 14px;">check_circle</span> Tersedia
        </p>
    </div>
    @endfor
</section>
@endsection
