<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  {{-- ANTI-FLICKER + LOAD THEME CSS --}}
  <script>
      (function() {
          const savedTheme = localStorage.getItem('theme') || 'light';
          document.documentElement.setAttribute('data-theme', savedTheme);
      })();
  </script>
  <title>Pengaturan Akun - NookNook</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  {{-- ===== LOAD THEME VARIABLES DULU ===== --}}
  <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboardpengguna.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pengaturan.css') }}">

  <style>
    /* Custom Override Styling Hover & Active Menu Pengaturan */
    .settings-menu-item {
      background: transparent !important;
      color: var(--text-secondary, #64748b) !important;
      border: none !important;
      border-radius: 10px !important;
      padding: 12px 16px !important;
      font-weight: 500 !important;
      transition: all 0.2s ease !important;
      display: flex !important;
      align-items: center !important;
      gap: 10px !important;
      width: 100% !important;
      cursor: pointer !important;
    }

    /* Hover & Active State (Latar Biru Lembut seperti Topbar) */
    .settings-menu-item:hover,
    .settings-menu-item.active {
      background: #EEF2FF !important;
      color: #2563EB !important;
      font-weight: 700 !important;
    }

    .settings-menu-item:hover .settings-menu-icon,
    .settings-menu-item.active .settings-menu-icon {
      color: #2563EB !important;
      stroke: #2563EB !important;
    }

    /* Support Dark Mode */
    [data-theme="dark"] .settings-menu-item {
      color: #94a3b8 !important;
    }

    [data-theme="dark"] .settings-menu-item:hover,
    [data-theme="dark"] .settings-menu-item.active {
      background: rgba(37, 99, 235, 0.2) !important;
      color: #60A5FA !important;
    }

    [data-theme="dark"] .settings-menu-item:hover .settings-menu-icon,
    [data-theme="dark"] .settings-menu-item.active .settings-menu-icon {
      color: #60A5FA !important;
      stroke: #60A5FA !important;
    }
  </style>
</head>
<body>

<div class="dashboard-container">

    @include('partials.sidebar')

    <main class="main-content">

      <header class="page-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
          <span class="section-badge">Pengaturan</span>
          <h1 class="page-title">Pengaturan Akun</h1>
          <p class="page-subtitle">Kelola profil, keamanan, dan preferensi akun Anda.</p>
        </div>
      </header>

      @if(session('success'))
        <div class="info-box" style="margin-bottom: 20px;">{{ session('success') }}</div>
      @endif

      @if(session('error'))
        <div class="danger-box" style="margin-bottom: 20px;">{{ session('error') }}</div>
      @endif

      <div class="settings-layout">

        {{-- ===== SIDEBAR MENU PENGATURAN ===== --}}
        <aside class="settings-menu">
          <button class="settings-menu-item active" onclick="showPanel('profil', this)">
            <svg class="settings-menu-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profil
          </button>

          <button class="settings-menu-item" onclick="showPanel('keamanan', this)">
            <svg class="settings-menu-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Keamanan
          </button>

          <button class="settings-menu-item" onclick="showPanel('notifikasi', this)">
            <svg class="settings-menu-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Notifikasi
          </button>
        </aside>

        {{-- ===== PANEL KONTEN ===== --}}
        <div class="settings-content">

          {{-- PROFIL --}}
          <div id="panel-profil" class="settings-panel active">
            <div class="card settings-card">
              <div class="card-header"><h2 class="card-title">Profil Saya</h2></div>
              <div class="card-body">
                <form method="POST" action="{{ route('account.profile.update') }}" class="form-stack">
                  @csrf
                  @method('PUT')

                  <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="form-control @error('name') is-invalid @enderror" required />
                    @error('name')
                      <div class="field-error" style="color:#e53e3e; font-size:13px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-control @error('email') is-invalid @enderror" required />
                    @error('email')
                      <div class="field-error" style="color:#e53e3e; font-size:13px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" placeholder="+62..." class="form-control" />
                    @error('phone')
                      <div class="field-error" style="color:#e53e3e; font-size:13px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" rows="3" placeholder="Ceritakan sedikit tentang Anda..." class="form-control">{{ old('bio', Auth::user()->bio ?? '') }}</textarea>
                    @error('bio')
                      <div class="field-error" style="color:#e53e3e; font-size:13px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-actions">
                    <button type="submit" class="btn-primary">Simpan Profil</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          {{-- KEAMANAN --}}
          <div id="panel-keamanan" class="settings-panel">
            {{-- Card Ubah Password --}}
            <div class="card settings-card">
              <div class="card-header"><h2 class="card-title">Ubah Password</h2></div>
              <div class="card-body">
                <form method="POST" action="{{ route('account.password.update') }}" class="form-stack">
                  @csrf
                  @method('PUT')

                  <div class="form-group">
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="old_password" placeholder="Masukkan password lama" class="form-control @error('old_password') is-invalid @enderror" />
                    @error('old_password')
                      <div class="field-error" style="color:#e53e3e; font-size:13px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="new_password" placeholder="Masukkan password baru" class="form-control @error('new_password') is-invalid @enderror" />
                    @error('new_password')
                      <div class="field-error" style="color:#e53e3e; font-size:13px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="new_password_confirmation" placeholder="Ulangi password baru" class="form-control" />
                  </div>

                  <div class="form-actions">
                    <button type="submit" class="btn-primary">Simpan Password</button>
                  </div>
                </form>
              </div>
            </div>

            {{-- Card Sesi Aktif --}}
            <div class="card settings-card" style="margin-top: 20px;">
              <div class="card-header"><h2 class="card-title">Sesi Aktif</h2></div>
              <div class="card-body">
                <div class="info-box">Anda sedang login di 1 perangkat.</div>
                <form action="{{ route('account.logout-others') }}" method="POST" onsubmit="return confirm('Logout dari semua perangkat?')">
                  @csrf
                  <button type="submit" class="btn-ghost" style="margin-top: 10px;">Logout dari Semua Perangkat</button>
                </form>
              </div>
            </div>

            {{-- Card Manajemen & Tindakan Akun (Dipindahkan dari Privasi) --}}
            <div class="card settings-card" style="margin-top: 20px;">
              <div class="card-header"><h2 class="card-title">Manajemen Akun</h2></div>
              <div class="card-body">
                <div class="form-stack">
                  <div>
                    {{-- NONAKTIFKAN AKUN --}}
                    <form action="{{ route('account.deactivate') }}" method="POST" id="formNonaktifkan">
                      @csrf
                      <button type="submit" class="btn-ghost" style="width: 100%; text-align: center;" onclick="return confirm('Nonaktifkan akun Anda sementara? Anda bisa mengaktifkannya kembali dengan login ulang.')">
                        🔒 Nonaktifkan Akun Sementara
                      </button>
                    </form>
                  </div>
                </div>

                <div class="danger-box" style="margin-top: 20px;">
                  <strong>Perhatian:</strong> Menghapus akun bersifat permanen. Semua data booking dan riwayat akan hilang.
                </div>

                {{-- HAPUS AKUN --}}
                <form action="{{ route('account.destroy') }}" method="POST" id="formHapusAkun" style="margin-top: 12px;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-danger" style="width: 100%;" onclick="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan.')">
                    Hapus Akun Saya
                  </button>
                </form>
              </div>
            </div>
          </div>

          {{-- NOTIFIKASI --}}
          <div id="panel-notifikasi" class="settings-panel">
            <div class="card settings-card">
              <div class="card-header"><h2 class="card-title">Preferensi Notifikasi</h2></div>
              <div class="card-body">
                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Email Notifikasi Status Booking</div>
                    <div class="toggle-desc">Menerima update langsung via email saat booking dibuat, menunggu konfirmasi, dan berhasil</div>
                  </div>
                  <label class="switch"><input type="checkbox" checked disabled><span class="slider"></span></label>
                </div>
              </div>
            </div>
          </div>


      {{-- LOGOUT --}}
      <div class="card settings-card" style="margin-top: 24px;">
        <div class="card-body" style="display: flex; justify-content: space-between; align-items: center;">
          <div>
            <div class="toggle-label">Logout dari Akun</div>
            <div class="toggle-desc">Anda akan keluar dari sesi ini</div>
          </div>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-danger">Logout</button>
          </form>
        </div>
      </div>

    </main>

</div>

{{-- ===== SCRIPT: PANEL SWITCH ===== --}}
<script>
  function showPanel(panelId, btn) {
    document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.settings-menu-item').forEach(m => m.classList.remove('active'));
    document.getElementById('panel-' + panelId).classList.add('active');
    btn.classList.add('active');
  }
</script>

{{-- ===== SCRIPT: DARK MODE TOGGLE ===== --}}
<script>
  (function() {
    const toggle = document.getElementById('themeToggle');
    const iconSun = document.getElementById('iconSun');
    const iconMoon = document.getElementById('iconMoon');
    const themeLabel = document.getElementById('themeLabel');

    function applyTheme(theme) {
      document.documentElement.setAttribute('data-theme', theme);
      localStorage.setItem('theme', theme);
      updateIcon(theme);
    }
    }

    // Set ikon & label awal
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    updateIcon(currentTheme);

    // Klik tombol toggle
    if (toggle) {
      toggle.addEventListener('click', function() {
        const now = document.documentElement.getAttribute('data-theme');
        const next = now === 'dark' ? 'light' : 'dark';
        applyTheme(next);
      });
    }
  })();
</script>

</body>
</html>
