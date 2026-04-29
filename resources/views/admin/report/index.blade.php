@extends('layouts.app')

@section('title', 'Rekap Laporan - PerpusKita')
@section('header_title', 'Laporan Peminjaman')

@section('content')
<!-- Stats Summary -->
<div class="dashboard-grid" style="margin-bottom: 2rem;">
    <div class="glass-card stat-card" style="border-left: 4px solid var(--primary);">
        <div class="stat-header"><span>Total Transaksi</span></div>
        <div class="stat-value">{{ $stats['total'] }}</div>
    </div>
    <div class="glass-card stat-card" style="border-left: 4px solid var(--secondary);">
        <div class="stat-header"><span>Peminjaman Aktif</span></div>
        <div class="stat-value">{{ $stats['aktif'] }}</div>
    </div>
    <div class="glass-card stat-card" style="border-left: 4px solid #4ade80;">
        <div class="stat-header"><span>Selesai Tepat Waktu</span></div>
        <div class="stat-value">{{ $stats['selesai'] }}</div>
    </div>
    <div class="glass-card stat-card" style="border-left: 4px solid #f87171;">
        <div class="stat-header"><span>Dibatalkan</span></div>
        <div class="stat-value">{{ $stats['dibatalkan'] }}</div>
    </div>
</div>

<!-- Filters -->
<div class="glass-card" style="margin-bottom: 2rem; padding: 1.5rem;">
    <form action="{{ route('report.index') }}" method="GET" style="display: flex; gap: 1.5rem; align-items: flex-end; flex-wrap: wrap;">
        <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
            <label style="font-size: 0.75rem;">Mulai Tanggal</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
            <label style="font-size: 0.75rem;">Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="form-group" style="margin-bottom: 0; width: 150px;">
            <label style="font-size: 0.75rem;">Status</label>
            <select name="status" class="form-control">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="height: 45px;">
            <span class="material-symbols-rounded">filter_alt</span>
            Filter
        </button>
        <a href="{{ route('report.index') }}" class="btn btn-glass" style="height: 45px;">Reset</a>
    </form>
</div>

<!-- Report Table -->
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem;">Data Aktivitas Peminjaman</h3>
        <button onclick="window.print()" class="btn btn-glass" style="color: var(--secondary);">
            <span class="material-symbols-rounded">print</span>
            Cetak Laporan
        </button>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem; color: var(--text-muted);">Tgl Pinjam</th>
                    <th style="padding: 1rem; color: var(--text-muted);">Peminjam</th>
                    <th style="padding: 1rem; color: var(--text-muted);">Buku</th>
                    <th style="padding: 1rem; color: var(--text-muted);">Durasi</th>
                    <th style="padding: 1rem; color: var(--text-muted);">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $item)
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                    <td style="padding: 1rem;">{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td style="padding: 1rem;">
                        <div style="font-weight: 600;">{{ $item->peminjam->nama }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $item->id_peminjam }}</div>
                    </td>
                    <td style="padding: 1rem;">
                        @foreach($item->buku as $b)
                            <div>{{ $b->judul }}</div>
                        @endforeach
                    </td>
                    <td style="padding: 1rem;">{{ $item->lama_peminjaman }} Hari</td>
                    <td style="padding: 1rem;">
                        <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: {{ 
                            $item->status == 'aktif' ? 'var(--secondary)' : 
                            ($item->status == 'selesai' ? '#4ade80' : 
                            ($item->status == 'dibatalkan' ? '#f87171' : 'var(--primary)')) 
                        }}">
                            {{ $item->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 3rem; text-align: center; color: var(--text-muted);">Tidak ada data yang ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        .sidebar, .quick-actions, .filter-section, .btn, header { display: none !important; }
        .main-content { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
        .glass-card { border: 1px solid #000 !important; color: #000 !important; backdrop-filter: none !important; background: #fff !important; }
        .stat-card { background: #fff !important; border: 1px solid #ddd !important; }
        body { background: #fff !important; color: #000 !important; }
        table { border: 1px solid #000 !important; }
        th, td { color: #000 !important; border-bottom: 1px solid #000 !important; }
    }
</style>
@endsection
