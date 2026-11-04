<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- TAMBAHAN: Anti-flicker + theme css --}}
    @include('partials.theme')

    <title>Edit Pengguna - NookNook</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 32px;
        }
        .form-card-header {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 16px;
            color: #111827;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 500;
            display: block;
            margin-bottom: 6px;
            color: #374151;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }
        .form-control.is-invalid {
            border-color: #dc2626;
        }
        .invalid-feedback {
            color: #dc2626;
            font-size: 14px;
            margin-top: 4px;
        }
        .btn {
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            transition: background-color 0.2s;
        }
        .btn-primary {
            background-color: #2563eb;
            color: white;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
        }
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        .d-flex {
            display: flex;
        }
        .justify-content-between {
            justify-content: space-between;
        }
        .mt-3 {
            margin-top: 16px;
        }
        .flash-success {
            background: #def7ec;
            color: #03543f;
            padding: 16px 20px;
            border-radius: 12px;
            font-weight: 500;
            margin-bottom: 20px;
        }
        .flash-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 16px 20px;
            border-radius: 12px;
            font-weight: 500;
            margin-bottom: 20px;
        }
        .role-hint {
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }
        .text-muted {
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }

        /* TAMBAHAN: Style tombol toggle tema */
        .theme-toggle {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 999;
            background: var(--bg-card, #ffffff);
            border: 1px solid var(--border-color, #e5e7eb);
            border-radius: 10px;
            padding: 8px 12px;
            cursor: pointer;
            color: var(--text-primary, #111827);
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: background 0.2s, transform 0.2s;
        }
        .theme-toggle:hover {
            transform: translateY(-2px);
            background: var(--bg-sidebar-hover, #f3f4f6);
        }
        [data-theme="dark"] .theme-toggle {
            background: #1e293b;
            border-color: #334155;
            color: #f1f5f9;
        }
    </style>
</head>
<body>


<div class="dashboard-container">

    <main class="main-content">
        <header class="top-header">
            <h1 class="header-title">Edit Pengguna</h1>
        </header>

        <div class="content-body">
            <div class="form-container">
                <div class="form-card">
                    <div class="form-card-header">Edit Akun: {{ $user->name }}</div>

                    @if(session('success'))
                        <div class="flash-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="flash-error">
                            <ul style="margin:0; padding-left:16px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- NAMA --}}
                        <div class="form-group">
                            <label for="name" class="form-label">Nama Lengkap <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- EMAIL --}}
                        <div class="form-group">
                            <label for="email" class="form-label">Email <span style="color:#dc2626;">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- PASSWORD --}}
                        <div class="form-group">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak ingin mengubah">
                            <div class="text-muted">Minimal 8 karakter. Kosongkan jika tidak ingin mengganti password.</div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- KONFIRMASI PASSWORD --}}
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                        </div>

                        {{-- ROLE --}}
                        <div class="form-group">
                            <label for="role" class="form-label">Role <span style="color:#dc2626;">*</span></label>
                            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $user->roles->first()->name ?? '') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="role-hint">🔹 <strong>Admin</strong> memiliki akses penuh ke semua fitur.<br>🔹 <strong>User</strong> hanya bisa melakukan booking dan melihat ruangan.</div>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- AKSI --}}
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('admin.user') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Update Pengguna</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>