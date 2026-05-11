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
                    <div id="drop-zone" style="width: 100%; aspect-ratio: 2/3; background: rgba(255,255,255,0.05); border: 2px dashed var(--glass-border); border-radius: var(--radius-md); display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text-muted); cursor: pointer; position: relative; overflow: hidden; transition: all 0.3s ease;">
                        <div id="drop-zone-content" style="text-align: center; pointer-events: none;">
                            <span class="material-symbols-rounded" style="font-size: 3rem; margin-bottom: 0.5rem;">add_photo_alternate</span>
                            <p style="font-size: 0.85rem; margin-bottom: 0.25rem;">Klik atau Tarik Gambar</p>
                            <p style="font-size: 0.7rem;">(JPG, JPEG, PNG)</p>
                        </div>
                        <img id="preview-img" src="#" alt="Preview" style="display: none; position: absolute; width: 100%; height: 100%; object-fit: cover; z-index: 10;">
                    </div>
                    <input type="file" name="cover_buku" id="cover_buku" style="display: none;" accept=".jpg,.jpeg,.png" onchange="handleFiles(this.files)">
                    <p style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.75rem;">* Maksimal ukuran: 2MB</p>
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
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('cover_buku');
    const previewImg = document.getElementById('preview-img');

    // Klik untuk pilih file
    dropZone.addEventListener('click', () => fileInput.click());

    // Highlight saat file ditarik ke atas drop zone
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.style.background = 'rgba(255,255,255,0.1)';
            dropZone.style.borderColor = 'var(--primary)';
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.style.background = 'rgba(255,255,255,0.05)';
            dropZone.style.borderColor = 'var(--glass-border)';
        }, false);
    });

    // Handle file drop
    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    });

    function handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];

            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung! Gunakan JPG, JPEG, atau PNG.');
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                return;
            }

            // Set file ke input agar bisa diproses PHP
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;

            // Preview gambar
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
