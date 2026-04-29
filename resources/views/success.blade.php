@extends('layouts.app')

@section('title', 'Berhasil - PerpusKita')

@section('styles')
<style>
    .success-content {
        max-width: 500px;
        margin: 4rem auto;
        text-align: center;
    }
    .qr-code {
        width: 200px;
        height: 200px;
        background: #fff;
        margin: 2rem auto;
        padding: 1rem;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qr-placeholder {
        width: 100%;
        height: 100%;
        background: repeating-linear-gradient(45deg, #eee, #eee 10px, #ddd 10px, #ddd 20px);
        border: 4px solid #333;
    }
    .success-icon {
        width: 80px;
        height: 80px;
        background: #4ade80;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        color: var(--bg-color);
        box-shadow: 0 0 30px rgba(74, 222, 128, 0.4);
    }
</style>
@endsection

@section('content')
@php
    $peminjaman_id = session('peminjaman_id');
    $peminjaman = $peminjaman_id ? \App\Models\Peminjaman::with('buku')->find($peminjaman_id) : null;
@endphp

<div class="success-content">
    <div class="success-icon">
        <span class="material-symbols-rounded" style="font-size: 48px;">check_circle</span>
    </div>
    
    <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">Pengajuan Berhasil!</h1>
    <p style="color: var(--text-muted); line-height: 1.6;">Permintaan peminjaman kamu telah tercatat. Silakan tunggu verifikasi admin untuk mengambil buku fisik di perpustakaan.</p>

    @if($peminjaman)
    <div class="glass-card" style="margin-top: 3rem;">
        <div class="qr-code">
            <!-- Simple QR Placeholder with dynamic text -->
            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f0f0f0; border-radius: 8px;">
                <span class="material-symbols-rounded" style="font-size: 80px; color: #333;">qr_code_2</span>
            </div>
        </div>
        <p style="font-family: monospace; font-size: 1.25rem; color: #fff;">{{ $peminjaman->id_peminjaman }}</p>
        
        <div style="margin-top: 2rem; text-align: left; border-top: 1px solid var(--glass-border); padding-top: 1.5rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Peminjam</span>
                <span>{{ Auth::user()->nama }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--text-muted);">Buku</span>
                <span>
                    @foreach($peminjaman->buku as $b)
                        {{ $b->judul }}{{ !$loop->last ? ',' : '' }}
                    @endforeach
                </span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 0.5rem;">
                <span style="color: var(--text-muted);">Tenggat Kembali</span>
                <span style="color: var(--secondary);">{{ $peminjaman->tanggal_kembali->format('d M Y') }}</span>
            </div>
        </div>
    </div>
    @endif

    <div style="margin-top: 3rem;">
        <a href="{{ route('borrowing') }}" class="btn btn-primary" style="width: 100%; justify-content: center;">Lihat Riwayat Pinjam</a>
    </div>
</div>
@endsection
