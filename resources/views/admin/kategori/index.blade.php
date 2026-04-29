@extends('layouts.app')

@section('title', 'Kelola Kategori - PerpusKita')
@section('header_title', 'Manajemen Kategori')

@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600;">Daftar Kategori</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Kelola kategori buku yang tersedia di perpustakaan</p>
        </div>
        <a href="{{ route('kategori.create') }}" class="btn btn-primary">
            <span class="material-symbols-rounded">add</span>
            Tambah Kategori
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">ID</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Nama Kategori</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Deskripsi</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategori as $item)
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1rem; font-family: monospace; color: var(--secondary);">{{ $item->id_kategori }}</td>
                    <td style="padding: 1rem; font-weight: 600;">{{ $item->nama_kategori }}</td>
                    <td style="padding: 1rem; color: var(--text-muted); max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $item->deskripsi ?? '-' }}
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <a href="{{ route('kategori.edit', $item->id_kategori) }}" class="btn btn-glass" style="padding: 0.5rem;">
                                <span class="material-symbols-rounded" style="font-size: 1.25rem;">edit</span>
                            </a>
                            <form action="{{ route('kategori.destroy', $item->id_kategori) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-glass" style="padding: 0.5rem; color: #f87171;">
                                    <span class="material-symbols-rounded" style="font-size: 1.25rem;">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        Belum ada data kategori.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem;">
        {{ $kategori->links() }}
    </div>
</div>
@endsection
