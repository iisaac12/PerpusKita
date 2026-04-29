@extends('layouts.app')

@section('title', 'Kelola Buku - PerpusKita')
@section('header_title', 'Manajemen Koleksi Buku')

@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600;">Daftar Buku</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Total koleksi yang terdaftar di sistem</p>
        </div>
        <a href="{{ route('buku.create') }}" class="btn btn-primary">
            <span class="material-symbols-rounded">add</span>
            Tambah Buku
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Buku</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Kategori</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Info Penerbit</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Stok</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($buku as $item)
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 56px; border-radius: 4px; background: #2b2930; overflow: hidden; border: 1px solid var(--glass-border);">
                                @if($item->cover_buku)
                                    <img src="{{ asset('storage/' . $item->cover_buku) }}" alt="Cover" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                        <span class="material-symbols-rounded" style="font-size: 1.25rem;">image_not_supported</span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $item->judul }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $item->pengarang }}</div>
                                <div style="font-size: 0.7rem; font-family: monospace; color: var(--secondary);">{{ $item->id_buku }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1rem;">
                        <span style="background: rgba(167, 139, 250, 0.1); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; border: 1px solid rgba(167, 139, 250, 0.2);">
                            {{ $item->kategori->nama_kategori }}
                        </span>
                    </td>
                    <td style="padding: 1rem;">
                        <div style="font-size: 0.875rem;">{{ $item->penerbit }}</div>
                    </td>
                    <td style="padding: 1rem;">
                        <div style="font-weight: 600; color: {{ $item->stok > 5 ? '#4ade80' : '#f87171' }};">
                            {{ $item->stok }} eks
                        </div>
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <a href="{{ route('buku.edit', $item->id_buku) }}" class="btn btn-glass" style="padding: 0.5rem;">
                                <span class="material-symbols-rounded" style="font-size: 1.25rem;">edit</span>
                            </a>
                            <form action="{{ route('buku.destroy', $item->id_buku) }}" method="POST" onsubmit="return confirm('Hapus buku ini?')">
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
                    <td colspan="5" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        Belum ada data buku.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem;">
        {{ $buku->links() }}
    </div>
</div>
@endsection
