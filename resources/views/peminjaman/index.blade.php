@extends('layouts.app')

@section('title', 'Daftar Peminjaman - PerpusKita')
@section('header_title', 'Riwayat Peminjaman')

@section('content')
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600;">{{ Auth::user()->isAdmin() ? 'Semua Peminjaman' : 'Peminjaman Saya' }}</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Pantau status dan tenggat waktu pengembalian buku</p>
        </div>
        @if(!Auth::user()->isAdmin())
        <a href="{{ route('borrowing.create') }}" class="btn btn-primary">
            <span class="material-symbols-rounded">add</span>
            Pinjam Buku Baru
        </a>
        @endif
    </div>

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
                    @if(Auth::user()->isAdmin())
                        <th style="padding: 1rem; color: var(--text-muted); font-weight: 500; text-align: right;">Aksi</th>
                    @endif
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
                    @if(Auth::user()->isAdmin())
                        <td style="padding: 1rem; text-align: right;">
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
                        </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ Auth::user()->isAdmin() ? 5 : 3 }}" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        Belum ada riwayat peminjaman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem;">
        {{ $peminjaman->links() }}
    </div>
</div>
@endsection
