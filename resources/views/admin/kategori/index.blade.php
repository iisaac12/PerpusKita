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

    <!-- Filter & Search -->
    <form action="{{ route('kategori.index') }}" method="GET" style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; align-items: center;">
        <div style="flex: 1; min-width: 250px; position: relative;">
            <span class="material-symbols-rounded" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1.2rem;">search</span>
            <input type="text" name="search" class="form-control" placeholder="Cari nama kategori..." value="{{ request('search') }}" style="padding-left: 3rem;">
        </div>
        <div style="width: 160px;">
            <select name="sort" class="form-control" onchange="this.form.submit()">
                <option value="nama_kategori" {{ request('sort') == 'nama_kategori' ? 'selected' : '' }}>Nama Kategori</option>
                <option value="id_kategori" {{ request('sort') == 'id_kategori' ? 'selected' : '' }}>ID Kategori</option>
                <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Tanggal Input</option>
            </select>
        </div>
        <div style="width: 120px;">
            <select name="order" class="form-control" onchange="this.form.submit()">
                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Terurut A-Z</option>
                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Terurut Z-A</option>
            </select>
        </div>
        @if(request()->anyFilled(['search', 'sort', 'order']))
            <a href="{{ route('kategori.index') }}" class="btn btn-glass" style="color: #f87171; border-color: rgba(248, 113, 113, 0.2);">
                <span class="material-symbols-rounded" style="font-size: 1.2rem;">restart_alt</span>
            </a>
        @endif
        <button type="submit" style="display: none;">Submit</button>
    </form>

    <div style="max-height: 500px; overflow-y: auto; overflow-x: auto; padding-right: 0.5rem; scrollbar-width: thin; scrollbar-color: var(--glass-border) transparent;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead style="position: sticky; top: 0; background: #1a1625; z-index: 10; box-shadow: 0 1px 0 var(--glass-border);">
                <tr style="text-align: left;">
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
