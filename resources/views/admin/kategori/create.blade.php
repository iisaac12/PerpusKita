@extends('layouts.app')

@section('title', 'Tambah Kategori - PerpusKita')
@section('header_title', 'Tambah Kategori Baru')

@section('content')
<div style="max-width: 600px;">
    <a href="{{ route('kategori.index') }}" style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); text-decoration: none; margin-bottom: 1.5rem; font-size: 0.875rem;">
        <span class="material-symbols-rounded">arrow_back</span>
        Kembali ke Daftar
    </a>

    <div class="glass-card">
        <form action="{{ route('kategori.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" placeholder="Contoh: Teknologi, Fiksi, Sains" required value="{{ old('nama_kategori') }}">
                @error('nama_kategori') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" placeholder="Penjelasan singkat tentang kategori ini">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
            </div>

            <div style="margin-top: 2.5rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">
                    <span class="material-symbols-rounded">save</span>
                    Simpan Kategori
                </button>
                <button type="reset" class="btn btn-glass">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection
