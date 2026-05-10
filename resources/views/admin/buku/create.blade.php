@extends('layouts.app')

@section('title', 'Tambah Buku - PerpusKita')
@section('header_title', 'Tambah Koleksi Buku')

@section('content')
<div style="max-width: 800px;">
    <a href="{{ route('buku.index') }}" style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); text-decoration: none; margin-bottom: 1.5rem; font-size: 0.875rem;">
        <span class="material-symbols-rounded">arrow_back</span>
        Kembali ke Daftar
    </a>

    <div class="glass-card">
        <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
                <!-- Cover Upload Part -->
                <div>
                    <label style="display: block; margin-bottom: 1rem; color: var(--text-muted);">Cover Buku</label>
                    <div id="cover-preview" style="width: 100%; aspect-ratio: 2/3; background: rgba(255,255,255,0.05); border: 2px dashed var(--glass-border); border-radius: var(--radius-md); display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text-muted); cursor: pointer; position: relative; overflow: hidden;" onclick="document.getElementById('cover_buku').click()">
                        <span class="material-symbols-rounded" style="font-size: 3rem; margin-bottom: 0.5rem;">add_photo_alternate</span>
                        <span style="font-size: 0.75rem;">Pilih File Cover</span>
                        <img id="preview-img" src="#" alt="Preview" style="display: none; position: absolute; width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <input type="file" name="cover_buku" id="cover_buku" style="display: none;" accept="image/*" onchange="previewImage(this)">
                    <p style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.75rem;">* Format: JPG, PNG, Max: 2MB</p>
                    @error('cover_buku') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                </div>

                <!-- Form Inputs -->
                <div>
                    <div class="form-group">
                        <label for="judul">Judul Buku</label>
                        <input type="text" name="judul" id="judul" class="form-control" placeholder="Masukkan judul lengkap" required value="{{ old('judul') }}">
                        @error('judul') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label>Pilih Kategori (Bisa pilih lebih dari satu)</label>
                        <div style="background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: var(--radius-md); padding: 1rem; max-height: 200px; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.75rem;">
                            @foreach($kategori as $kat)
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border-radius: 8px; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                    <input type="checkbox" name="categories[]" value="{{ $kat->id_kategori }}" 
                                           {{ (old('categories') && in_array($kat->id_kategori, old('categories'))) ? 'checked' : '' }}
                                           style="width: 1.2rem; height: 1.2rem; accent-color: var(--primary); cursor: pointer;">
                                    <span style="font-size: 0.875rem;">{{ $kat->nama_kategori }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('categories') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="pengarang">Pengarang</label>
                            <input type="text" name="pengarang" id="pengarang" class="form-control" placeholder="Nama penulis" required value="{{ old('pengarang') }}">
                            @error('pengarang') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="penerbit">Penerbit</label>
                            <input type="text" name="penerbit" id="penerbit" class="form-control" placeholder="Nama penerbit" required value="{{ old('penerbit') }}">
                            @error('penerbit') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok Buku</label>
                        <input type="number" name="stok" id="stok" class="form-control" placeholder="0" min="0" required value="{{ old('stok', 0) }}">
                        @error('stok') <p style="color: #f87171; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                    </div>

                    <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                        <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">
                            <span class="material-symbols-rounded">save</span>
                            Simpan Koleksi
                        </button>
                        <button type="reset" class="btn btn-glass" style="flex: 1; justify-content: center;">Reset</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var previewImg = document.getElementById('preview-img');
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
