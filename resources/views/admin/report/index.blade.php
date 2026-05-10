@extends('layouts.app')

@section('title', 'Rekap Laporan - PerpusKita')
@section('header_title', 'Laporan Peminjaman')

@section('content')
<!-- Stats Summary (Hidden on Print) -->
<div class="dashboard-grid no-print" style="margin-bottom: 2rem;">
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

<!-- Filters (Hidden on Print) -->
<div class="glass-card no-print" style="margin-bottom: 2rem; padding: 1.5rem;">
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

<!-- Printable Report Container -->
<div class="glass-card report-container">
    <!-- Header khusus Print -->
    <div class="print-header" style="display: none; text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px;">
        <h1 style="margin: 0; font-size: 24px; text-transform: uppercase;">PerpusKita - Digital Sanctuary</h1>
        <p style="margin: 5px 0; font-size: 14px;">Laporan Aktivitas Peminjaman Buku</p>
        <p style="margin: 0; font-size: 12px; color: #666;">
            Periode: {{ request('start_date') ?? 'Semua' }} s/d {{ request('end_date') ?? 'Sekarang' }} 
            | Dicetak pada: {{ now()->format('d/m/Y H:i') }}
        </p>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;" class="no-print">
        <h3 style="font-size: 1.25rem;">Data Aktivitas Peminjaman</h3>
        <button onclick="window.print()" class="btn btn-glass" style="color: var(--secondary);">
            <span class="material-symbols-rounded">print</span>
            Cetak Laporan
        </button>
    </div>

    <div style="overflow-x: auto;">
        <table class="report-table" style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
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
                        <div style="font-weight: 600;" class="text-print-bold">{{ $item->peminjam->nama }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $item->id_peminjam }}</div>
                    </td>
                    <td style="padding: 1rem;">
                        @foreach($item->buku as $b)
                            <div style="margin-bottom: 2px;">• {{ $b->judul }}</div>
                        @endforeach
                    </td>
                    <td style="padding: 1rem;">{{ $item->lama_peminjaman }} Hari</td>
                    <td style="padding: 1rem;">
                        <span class="status-badge" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: {{ 
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

    <!-- Footer khusus Print -->
    <div class="print-footer" style="display: none; margin-top: 50px;">
        <div style="float: right; text-align: center; width: 200px;">
            <p>Mengetahui,</p>
            <p style="margin-top: 60px; font-weight: bold; border-top: 1px solid #000; padding-top: 5px;">Admin Perpustakaan</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>

<style>
    @media print {
        .sidebar, .no-print, header, .btn, form, .stat-card { display: none !important; }
        .main-content { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
        body { background: #fff !important; color: #000 !important; font-family: 'Times New Roman', serif !important; }
        .print-header, .print-footer { display: block !important; }
        .glass-card { 
            background: #fff !important; 
            border: none !important; 
            box-shadow: none !important; 
            backdrop-filter: none !important; 
            padding: 0 !important;
            transform: none !important;
        }
        .report-table { border: 1px solid #000 !important; margin-top: 20px; }
        .report-table th { 
            background-color: #f2f2f2 !important; 
            color: #000 !important; 
            border: 1px solid #000 !important;
            -webkit-print-color-adjust: exact;
        }
        .report-table td { border: 1px solid #000 !important; color: #000 !important; }
        .text-print-bold { font-weight: bold !important; }
        .status-badge { color: #000 !important; font-weight: bold !important; border: none !important; padding: 0 !important; }
        @page { size: A4; margin: 2cm; }
    }
</style>
@endsection
