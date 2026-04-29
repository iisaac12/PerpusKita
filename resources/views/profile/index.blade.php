@extends('layouts.app')

@section('title', 'Profil Saya - PerpusKita')
@section('header_title', 'Pengaturan Akun')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Profile Info -->
    <div class="glass-card">
        <h3 style="font-size: 1.25rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
            <span class="material-symbols-rounded" style="color: var(--primary);">person</span>
            Informasi Pribadi
        </h3>
        
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                @error('nama') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="no_telepon">Nomor Telepon</label>
                <input type="text" name="no_telepon" id="no_telepon" class="form-control" value="{{ old('no_telepon', $user->no_telepon) }}">
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" id="alamat" class="form-control" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 1rem; width: 100%; justify-content: center;">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <!-- Password Update -->
    <div class="glass-card">
        <h3 style="font-size: 1.25rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
            <span class="material-symbols-rounded" style="color: #f87171;">security</span>
            Ubah Kata Sandi
        </h3>

        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="current_password">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" id="current_password" class="form-control" placeholder="••••••••" required>
                @error('current_password') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi Baru</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Min 8 karakter" required>
                @error('password') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi kata sandi baru" required>
            </div>

            <button type="submit" class="btn btn-glass" style="margin-top: 1rem; width: 100%; justify-content: center; color: #fff; border-color: rgba(248, 113, 113, 0.2);">
                Perbarui Kata Sandi
            </button>
        </form>
    </div>
</div>
@endsection
