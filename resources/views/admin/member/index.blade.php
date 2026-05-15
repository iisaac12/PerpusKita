@extends('layouts.app')

@section('title', 'Daftar Member - PerpusKita')
@section('header_title', 'Manajemen Anggota')

@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600;">Daftar Member</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Total anggota yang terdaftar di sistem</p>
        </div>
    </div>

    <!-- Filter & Search -->
    <form action="{{ route('member.index') }}" method="GET" style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; align-items: center;">
        <div style="flex: 1; min-width: 250px; position: relative;">
            <span class="material-symbols-rounded" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1.2rem;">search</span>
            <input type="text" name="search" class="form-control" placeholder="Cari nama, email, atau ID..." value="{{ request('search') }}" style="padding-left: 3rem;">
        </div>
        <div style="width: 180px;">
            <select name="sort" class="form-control" onchange="this.form.submit()">
                <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Urut Nama (A-Z)</option>
                <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Member Terbaru</option>
            </select>
        </div>
        <div style="width: 120px;">
            <select name="order" class="form-control" onchange="this.form.submit()">
                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
            </select>
        </div>
        @if(request()->anyFilled(['search', 'sort', 'order']))
            <a href="{{ route('member.index') }}" class="btn btn-glass" style="color: #f87171; border-color: rgba(248, 113, 113, 0.2);">
                <span class="material-symbols-rounded" style="font-size: 1.2rem;">restart_alt</span>
            </a>
        @endif
    </form>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Member</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Kontak</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Alamat</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Tgl Bergabung</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $item)
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; color: var(--bg-color); font-weight: 700;">
                                {{ substr($item->nama, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $item->nama }}</div>
                                <div style="font-size: 0.75rem; font-family: monospace; color: var(--secondary);">{{ $item->id_peminjam }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1rem;">
                        <div style="font-size: 0.875rem;">{{ $item->email }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $item->no_telepon ?? '-' }}</div>
                    </td>
                    <td style="padding: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-muted);">{{ $item->alamat ?? 'Belum diatur' }}</div>
                    </td>
                    <td style="padding: 1rem;">
                        <div style="font-size: 0.875rem;">{{ $item->created_at->format('d M Y') }}</div>
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <form action="{{ route('member.destroy', $item->id_peminjam) }}" method="POST" onsubmit="return confirm('Hapus member ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-glass" style="padding: 0.5rem; color: #f87171;">
                                    <span class="material-symbols-rounded" style="font-size: 12px; margin-right: 4px;">delete</span>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        Belum ada data member.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container" style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $members->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
