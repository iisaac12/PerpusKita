@extends('layouts.app')

@section('title', 'Pinjam Buku - PerpusKita')
@section('header_title', 'Ajukan Peminjaman')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <a href="{{ route('borrowing') }}" style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); text-decoration: none; margin-bottom: 1.5rem; font-size: 0.875rem;">
        <span class="material-symbols-rounded">arrow_back</span>
        Kembali ke Riwayat
    </a>

    <div class="glass-card">
        <form action="{{ route('borrowing.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 2rem; padding: 1.5rem; background: rgba(167, 139, 250, 0.05); border-radius: var(--radius-md); border: 1px solid rgba(167, 139, 250, 0.1);">
                <h4 style="font-size: 1rem; margin-bottom: 1rem; color: var(--primary); display: flex; align-items: center; gap: 0.5rem;">
                    <span class="material-symbols-rounded">info</span>
                    Informasi Peminjam
                </h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <p style="font-size: 0.75rem; color: var(--text-muted);">Nama Peminjam</p>
                        <p style="font-weight: 600;">{{ Auth::user()->nama }}</p>
                    </div>
                    <div>
                        <p style="font-size: 0.75rem; color: var(--text-muted);">ID Anggota</p>
                        <p style="font-family: monospace; font-weight: 600;">{{ Auth::user()->id_peminjam }}</p>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="id_buku">Pilih Buku yang Ingin Dipinjam</label>
                <select name="id_buku" id="id_buku" class="form-control" required style="appearance: none;">
                    <option value="">-- Pilih Buku Tersedia --</option>
                    @foreach($buku_list as $buku)
                        <option value="{{ $buku->id_buku }}" {{ (isset($selected_buku) && $selected_buku->id_buku == $buku->id_buku) ? 'selected' : '' }}>
                            {{ $buku->judul }} ({{ $buku->pengarang }}) - Stok: {{ $buku->stok }}
                        </option>
                    @endforeach
                </select>
                @error('id_buku') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label for="tanggal_pinjam">Tanggal Mulai Pinjam</label>
                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" required value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                    @error('tanggal_pinjam') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label for="lama_peminjaman">Lama Pinjam (Hari)</label>
                    <select name="lama_peminjaman" id="lama_peminjaman" class="form-control" required>
                        <option value="1">1 Hari</option>
                        <option value="2">2 Hari</option>
                        <option value="3">3 Hari (Maksimum)</option>
                    </select>
                    @error('lama_peminjaman') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.02); padding: 1rem; border-radius: var(--radius-sm); margin-top: 1rem;">
                <p style="font-size: 0.875rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem;">
                    <span class="material-symbols-rounded" style="font-size: 1.1rem; color: var(--secondary);">event_available</span>
                    Estimasi Pengembalian: <strong id="return-estimate" style="color: #fff; margin-left: 0.25rem;">-</strong>
                </p>
            </div>

            <div style="margin-top: 2.5rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center; height: 50px;">
                    <span class="material-symbols-rounded">bookmark_add</span>
                    Konfirmasi Pinjam
                </button>
                <button type="reset" class="btn btn-glass" style="padding: 0 2rem;">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const tanggalPinjamInput = document.getElementById('tanggal_pinjam');
    const lamaPinjamInput = document.getElementById('lama_peminjaman');
    const returnEstimate = document.getElementById('return-estimate');

    function updateEstimate() {
        if (!tanggalPinjamInput.value) return;
        
        const date = new Date(tanggalPinjamInput.value);
        const days = parseInt(lamaPinjamInput.value);
        
        date.setDate(date.getDate() + days);
        
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        returnEstimate.textContent = date.toLocaleDateString('id-ID', options);
    }

    tanggalPinjamInput.addEventListener('change', updateEstimate);
    lamaPinjamInput.addEventListener('change', updateEstimate);
    
    // Initial call
    updateEstimate();
</script>
@endsection
