<style>
/* ============================================================
   TOPBAR — Force horizontal layout
   ============================================================ */
header.navbar {
  background-color: var(--bg-card, #fff) !important;
  border-bottom: 1px solid var(--border-color, #e5e7eb) !important;
  position: sticky !important;
  top: 0 !important;
  z-index: 9999 !important;
  width: 100% !important;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
}
header.navbar .topbar-container {
  display: flex !important;
  flex-direction: row !important;
  flex-wrap: nowrap !important;
  justify-content: space-between !important;
  align-items: center !important;
  height: 72px !important;
  max-width: 1200px !important;
  margin: 0 auto !important;
  padding: 0 24px !important;
  gap: 16px !important;
}
header.navbar .logo {
  display: flex !important;
  flex-direction: row !important;
  align-items: center !important;
  gap: 10px !important;
  font-size: 20px !important;
  font-weight: 800 !important;
  color: var(--text-primary, #111) !important;
  text-decoration: none !important;
  letter-spacing: -0.02em !important;
  flex-shrink: 0 !important;
  line-height: 1 !important;
}
header.navbar .logo-mark {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 34px !important;
  height: 34px !important;
  border-radius: 10px !important;
  background: linear-gradient(135deg, #6366F1, #8B5CF6) !important;
  color: #fff !important;
  font-size: 18px !important;
  font-weight: 800 !important;
  flex-shrink: 0 !important;
}
header.navbar .logo-mark::before { content: "N"; }
header.navbar .topbar-nav {
  display: flex !important;
  flex-direction: row !important;
  align-items: center !important;
}
header.navbar .topbar-nav ul {
  display: flex !important;
  flex-direction: row !important;
  gap: 6px !important;
  list-style: none !important;
  align-items: center !important;
  margin: 0 !important;
  padding: 0 !important;
}
header.navbar .topbar-nav a {
  text-decoration: none !important;
  color: var(--text-secondary, #4b5563) !important;
  font-size: 15px !important;
  font-weight: 500 !important;
  padding: 8px 16px !important;
  border-radius: 10px !important;
  display: inline-block !important;
  line-height: 1.4 !important;
  transition: all 0.2s ease !important;
}

/* Hover & Active State (Latar Biru-Ungu Lembut Seperti Gambar 2) */
header.navbar .topbar-nav a:hover,
header.navbar .topbar-nav a.active {
  color: #0f172a !important;
  background: #EEF2FF !important; /* Biru/Ungu Lembut */
}

/* Tombol Login disamakan dengan link navigasi biasa */
header.navbar .btn-login {
  background: transparent !important;
  color: var(--text-secondary, #4b5563) !important;
  padding: 8px 16px !important;
  border-radius: 10px !important;
  font-weight: 500 !important;
}

header.navbar .btn-login:hover,
header.navbar .btn-login.active {
  background: #EEF2FF !important; /* Efek Biru Lembut saat Diarahkan */
  color: #0f172a !important;
}

header.navbar .btn-logout {
  background: transparent !important;
  border: 1px solid var(--border-color, #e5e7eb) !important;
  color: var(--text-secondary, #6b7280) !important;
  font-family: inherit !important;
  font-size: 15px !important;
  font-weight: 500 !important;
  padding: 8px 14px !important;
  border-radius: 10px !important;
  cursor: pointer !important;
}
header.navbar .btn-logout:hover {
  border-color: #FCA5A5 !important;
  color: #DC2626 !important;
  background: #FEF2F2 !important;
}

/* Dark Mode Adjustments */
[data-theme="dark"] header.navbar .topbar-nav a { color: #ffffff !important; }
[data-theme="dark"] header.navbar .topbar-nav a:hover,
[data-theme="dark"] header.navbar .topbar-nav a.active,
[data-theme="dark"] header.navbar .btn-login:hover { 
  background: rgba(99, 102, 241, 0.25) !important; 
  color: #ffffff !important;
}
[data-theme="dark"] header.navbar .btn-logout { color: #ffffff !important; }

@media (max-width: 768px) {
  header.navbar .topbar-container {
    height: auto !important;
    min-height: 64px !important;
    padding: 10px 16px !important;
    flex-wrap: wrap !important;
  }
  header.navbar .topbar-nav ul {
    gap: 2px !important;
    flex-wrap: wrap !important;
  }
  header.navbar .topbar-nav a {
    padding: 6px 10px !important;
    font-size: 14px !important;
  }
}
</style>

@guest
<script>
function cekLoginDulu(pesan = 'Silakan login terlebih dahulu.', redirectUrl = null) {
  Swal.fire({
    icon: 'warning',
    title: 'Anda belum login',
    text: pesan,
    showCancelButton: true,
    confirmButtonText: 'Login',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      if (redirectUrl) {
        window.location.href = "{{ route('login') }}?redirect=" + encodeURIComponent(redirectUrl);
      } else {
        window.location.href = "{{ route('login') }}";
      }
    }
  });
}
</script>
@endguest

<header class="navbar">
    <div class="topbar-container">
        <a href="{{ route('home') }}" class="logo">
            <span class="logo-mark"></span>
            <span>NookNook</span>
        </a>

        @auth
            <nav class="topbar-nav">
                <ul>
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                    <li><a href="{{ route('ruangan.daftar') }}" class="{{ request()->routeIs('ruangan.daftar') ? 'active' : '' }}">Ruangan</a></li>
                    <li><a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-logout">Logout</button>
                        </form>
                    </li>
                </ul>
            </nav>
        @endauth

        @guest
            <nav class="topbar-nav">
                <ul>
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                    <li><a href="javascript:void(0)" onclick="cekLoginDulu('Silakan login untuk melihat daftar ruangan.', '{{ route('ruangan.daftar') }}')">Ruangan</a></li>
                    <li><a href="javascript:void(0)" onclick="cekLoginDulu('Silakan login untuk mengakses dashboard.', '{{ route('user.dashboard') }}')">Dashboard</a></li>
                    <li><a href="{{ route('login') }}" class="btn-login {{ request()->routeIs('login') ? 'active' : '' }}">Login</a></li>
                </ul>
            </nav>
        @endguest
    </div>
</header>
