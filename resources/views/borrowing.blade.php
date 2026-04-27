@extends('layouts.app')

@section('title', 'Pinjam Buku - PerpusKita')
@section('header_title', 'Form Peminjaman')

@section('styles')
<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
    @media (max-width: 600px) {
        .form-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <form id="borrowForm" action="{{ route('success') }}">
        <div class="glass-card">
            <div class="form-grid">
                <div class="form-group">
                    <label for="memberId">ID Anggota</label>
                    <input type="text" id="memberId" class="form-control" placeholder="PK-123456" required>
                </div>
                <div class="form-group">
                    <label for="bookId">ID Buku / ISBN</label>
                    <input type="text" id="bookId" class="form-control" placeholder="978-3-16-148410-0" required>
                </div>
                <div class="form-group">
                    <label for="borrowDate">Tanggal Pinjam</label>
                    <input type="date" id="borrowDate" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="returnDate">Estimasi Kembali</label>
                    <input type="date" id="returnDate" class="form-control" required>
                </div>
            </div>
            
            <div class="form-group" style="margin-top: 1rem;">
                <label for="notes">Catatan Tambahan</label>
                <textarea id="notes" class="form-control" rows="3" placeholder="Kondisi buku, catatan khusus, dll."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
                <button type="button" class="btn btn-glass" onclick="history.back()">Batal</button>
                <button type="submit" class="btn btn-primary">Konfirmasi Peminjaman</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Set default dates
    document.getElementById('borrowDate').valueAsDate = new Date();
    const returnDate = new Date();
    returnDate.setDate(returnDate.getDate() + 7);
    document.getElementById('returnDate').valueAsDate = returnDate;
</script>
@endsection
