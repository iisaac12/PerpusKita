@extends('layouts.app')

@section('title', 'Daftar Peminjaman - PerpusKita')
@section('header_title', 'Riwayat Peminjaman')

@section('content')
@guest
<div class="glass-card" style="text-align: center; padding: 4rem 2rem;">
    <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; border-radius: 50%; background: var(--secondary-glow, rgba(103,232,249,0.1)); display: flex; align-items: center; justify-content: center;">
        <span class="material-symbols-rounded" style="font-size: 40px; color: var(--secondary, #67e8f9);">receipt_long</span>
    </div>
    <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">Belum ada peminjaman</h3>
    <p style="color: var(--text-muted); max-width: 420px; margin: 0 auto 2rem;">Masuk ke akun Anda untuk mulai meminjam buku dan memantau status peminjaman di sini.</p>
    <button type="button" onclick="showLoginPrompt('{{ route('borrowing.create') }}')" class="btn btn-primary" style="margin: 0 auto;">
        <span class="material-symbols-rounded">add</span>
        Pinjam Buku Baru
    </button>
</div>
@else
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600;">{{ Auth::user()->isAdmin() ? 'Semua Peminjaman' : 'Peminjaman Saya' }}</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem;">{{ Auth::user()->isAdmin() ? 'Kelola dan pantau seluruh transaksi buku' : 'Pantau status dan tenggat waktu pengembalian buku' }}</p>
        </div>
        @if(!Auth::user()->isAdmin())
        <a href="{{ route('borrowing.create') }}" class="btn btn-primary">
            <span class="material-symbols-rounded">add</span>
            Pinjam Buku Baru
        </a>
        @endif
    </div>

    <!-- Filter & Search -->
    <form action="{{ route('borrowing') }}" method="GET" style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; align-items: center;">
        @if(Auth::user()->isAdmin())
        <div style="flex: 1; min-width: 250px; position: relative;">
            <span class="material-symbols-rounded" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1.2rem;">search</span>
            <input type="text" name="search" class="form-control" placeholder="Cari nama member, buku, atau ID..." value="{{ request('search') }}" style="padding-left: 3rem;">
        </div>
        @endif
        
        <div style="width: 180px;">
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="all">Semua Status</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Sedang Dipinjam</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai Kembali</option>
                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <div style="width: 160px;">
            <select name="sort" class="form-control" onchange="this.form.submit()">
                <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Tgl Pengajuan</option>
                <option value="tanggal_pinjam" {{ request('sort') == 'tanggal_pinjam' ? 'selected' : '' }}>Tgl Pinjam</option>
                <option value="tanggal_kembali" {{ request('sort') == 'tanggal_kembali' ? 'selected' : '' }}>Tgl Kembali</option>
            </select>
        </div>

        @if(request()->anyFilled(['search', 'status', 'sort']))
            <a href="{{ route('borrowing') }}" class="btn btn-glass" style="color: #f87171; border-color: rgba(248, 113, 113, 0.2);">
                <span class="material-symbols-rounded" style="font-size: 1.2rem;">restart_alt</span>
            </a>
        @endif
    </form>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Buku</th>
                    @if(Auth::user()->isAdmin())
                        <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Peminjam</th>
                    @endif
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Pinjam / Kembali</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Status</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $item)
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                    <td style="padding: 1rem;">
                        @foreach($item->buku as $buku)
                            <div style="font-weight: 600;">{{ $buku->judul }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $buku->id_buku }}</div>
                        @endforeach
                    </td>
                    @if(Auth::user()->isAdmin())
                        <td style="padding: 1rem;">
                            <div style="font-weight: 500;">{{ $item->peminjam->nama }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $item->id_peminjam }}</div>
                        </td>
                    @endif
                    <td style="padding: 1rem;">
                        <div style="font-size: 0.875rem;">
                            <span style="color: var(--text-muted);">Mulai:</span> {{ $item->tanggal_pinjam->format('d M Y') }}
                        </div>
                        <div style="font-size: 0.875rem;">
                            <span style="color: var(--text-muted);">Tenggat:</span> <span style="{{ $item->tanggal_kembali->isPast() && $item->status == 'aktif' ? 'color: #f87171; font-weight: 600;' : '' }}">{{ $item->tanggal_kembali->format('d M Y') }}</span>
                        </div>
                    </td>
                    <td style="padding: 1rem;">
                        @php
                            $statusColors = [
                                'menunggu' => ['bg' => 'rgba(167, 139, 250, 0.1)', 'text' => 'var(--primary)', 'border' => 'rgba(167, 139, 250, 0.2)'],
                                'aktif' => ['bg' => 'rgba(103, 232, 249, 0.1)', 'text' => 'var(--secondary)', 'border' => 'rgba(103, 232, 249, 0.2)'],
                                'selesai' => ['bg' => 'rgba(74, 222, 128, 0.1)', 'text' => '#4ade80', 'border' => 'rgba(74, 222, 128, 0.2)'],
                                'dibatalkan' => ['bg' => 'rgba(248, 113, 113, 0.1)', 'text' => '#f87171', 'border' => 'rgba(248, 113, 113, 0.2)'],
                            ];
                            $colors = $statusColors[$item->status];
                        @endphp
                        <span style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; border: 1px solid {{ $colors['border'] }}; text-transform: capitalize;">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        @if(Auth::user()->isAdmin())
                            <form action="{{ route('borrowing.status', $item->id_peminjaman) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="form-control" style="font-size: 0.75rem; padding: 0.4rem; background: var(--glass-bg); border-color: var(--glass-border); width: auto; display: inline-block;">
                                    <option value="menunggu" {{ $item->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="aktif" {{ $item->status == 'aktif' ? 'selected' : '' }}>Aktifkan</option>
                                    <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="dibatalkan" {{ $item->status == 'dibatalkan' ? 'selected' : '' }}>Batalkan</option>
                                </select>
                            </form>
                        @else
                            @if($item->status == 'menunggu')
                                <form action="{{ route('borrowing.status', $item->id_peminjaman) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="dibatalkan">
                                    <button type="submit" class="btn btn-sm btn-danger" style="font-size: 0.75rem; padding: 0.4rem 0.8rem; background: rgba(248, 113, 113, 0.2); border: 1px solid rgba(248, 113, 113, 0.4); color: #f87171; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem; transition: all 0.2s;" onmouseover="this.style.background='rgba(248, 113, 113, 0.3)'" onmouseout="this.style.background='rgba(248, 113, 113, 0.2)'">
                                        <span class="material-symbols-rounded" style="font-size: 1rem;">close</span>
                                        Batalkan
                                    </button>
                                </form>
                            @else
                                <span style="color: var(--text-muted); font-size: 0.875rem;">-</span>
                            @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ Auth::user()->isAdmin() ? 5 : 4 }}" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        Belum ada riwayat peminjaman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container" style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $peminjaman->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endguest
@endsection
