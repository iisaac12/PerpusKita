<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PerpusKita</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        .auth-card {
            width: 100%;
            max-width: 540px;
            padding: 3rem;
            animation: slideUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .auth-logo {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 0 30px var(--primary-glow);
        }

        .auth-logo span {
            font-size: 2rem;
            color: var(--bg-color);
        }

        .auth-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #fff, var(--text-muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .error-text {
            color: #f87171;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .auth-footer {
            margin-top: 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="glass-card auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <span class="material-symbols-rounded">auto_stories</span>
                </div>
                <h1>Daftar Akun</h1>
                <p style="color: var(--text-muted);">Mulai perjalanan literasi kamu hari ini</p>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama lengkap kamu" required value="{{ old('nama') }}">
                    @error('nama') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" required value="{{ old('email') }}">
                    @error('email') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Kata Sandi</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Min 8 karakter" required>
                        @error('password') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi kata sandi" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="no_telepon">Nomor Telepon</label>
                    <input type="text" name="no_telepon" id="no_telepon" class="form-control" placeholder="08xxxxxx" value="{{ old('no_telepon') }}">
                    @error('no_telepon') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="2" placeholder="Alamat lengkap kamu">{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; height: 50px; margin-top: 1rem;">
                    Buat Akun Sekarang
                </button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk Kembali</a>
            </div>
        </div>
    </div>
</body>
</html>
