@extends('layouts.app')

@section('title', 'History - PerpusKita')
@section('header_title', 'Arsip Aktivitas')

@section('content')
<div class="glass-card">
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600;">{{ Auth::user()->isAdmin() ? 'Arsip Peminjaman Global' : 'Riwayat Baca Saya' }}</h3>
        <p style="color: var(--text-muted); font-size: 0.875rem;">Daftar transaksi yang sudah selesai atau dibatalkan</p>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Buku</th>
                    @if(Auth::user()->isAdmin())
                        <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Peminjam</th>
                    @endif
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Periode Pinjam</th>
                    <th style="padding: 1rem; color: var(--text-muted); font-weight: 500;">Status Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($history as $item)
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
                        <div style="font-size: 0.875rem; color: var(--text-muted);">
                            {{ $item->tanggal_pinjam->format('d M Y') }} — {{ $item->tanggal_kembali->format('d M Y') }}
                        </div>
                    </td>
                    <td style="padding: 1rem;">
                        @if($item->status == 'selesai')
                            <span style="background: rgba(74, 222, 128, 0.1); color: #4ade80; padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; border: 1px solid rgba(74, 222, 128, 0.2);">
                                <span class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle; margin-right: 4px;">task_alt</span>
                                Selesai
                            </span>
                        @else
                            <span style="background: rgba(248, 113, 113, 0.1); color: #f87171; padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; border: 1px solid rgba(248, 113, 113, 0.2);">
                                <span class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle; margin-right: 4px;">cancel</span>
                                Dibatalkan
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ Auth::user()->isAdmin() ? 4 : 3 }}" style="padding: 4rem; text-align: center; color: var(--text-muted);">
                        <span class="material-symbols-rounded" style="font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.3;">history_toggle_off</span>
                        Belum ada riwayat aktivitas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem;">
        {{ $history->links() }}
    </div>
</div>
@endsection
