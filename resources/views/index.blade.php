<?php
$kategori = [
    ['nama' => 'Meeting',   'icon' => '💼', 'gambar' => 'image/images (1).jpeg'],
    ['nama' => 'Coworking', 'icon' => '💻', 'gambar' => 'image/images (2).jpeg'],
    ['nama' => 'Event',     'icon' => '🎉', 'gambar' => 'image/images.jpeg'],
    ['nama' => 'Private',   'icon' => '🔒', 'gambar' => 'image/images3.jpeg'],
];
$ruangan_populer = [
    ['nama' => 'Meeting Room 1', 'gambar' => 'image/images (2).jpeg'],
    ['nama' => 'Meeting Room 2', 'gambar' => 'image/images (3).jpeg'],
    ['nama' => 'Meeting Room 1', 'gambar' => 'image/images2.jpeg'],
    ['nama' => 'Meeting Room 2', 'gambar' => 'image/images3.jpeg'],
    ['nama' => 'Meeting Room 3', 'gambar' => 'image/images.jpeg'],
];

$keunggulan = ['Booking Cepat', 'Harga Transparan', 'Support 24/7'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @include('partials.theme')

  <title>NookNook - Cari Ruangan Terbaik</title>
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
  {{-- Style tambahan untuk tombol toggle tema (biar tidak ubah CSS utama) --}}
  <style>
    .theme-toggle {
      position: fixed;
      top: 90px;
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
@guest
<script>
function cekLoginDulu(pesan = 'Silakan login terlebih dahulu.') {
    Swal.fire({
        icon: 'warning',
        title: 'Anda belum login',
        text: pesan,
        showCancelButton: true,
        confirmButtonText: 'Login',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('login') }}";
        }
    });
}
</script>
@endguest
<body>

  @include ('partials.topbar')


  <section class="hero">
    <div class="hero-bg-blob"></div>

    <div class="hero-visual">
      <img src="{{ asset('image/images (1).jpeg') }}" alt="Hero Image">
      <div class="hero-visual-overlay"></div>
    </div>

    <div class="container hero-grid">
      <div class="hero-content">
        @auth
          <span class="welcome-badge">Selamat datang, {{ Auth::user()->name }}</span>
        @endauth
        <h1>Cari Ruangan Terbaik <br><span class="gradient-text">Untuk Aktivitas Anda</span></h1>
        <p>Booking ruang meeting, coworking, dan event dengan mudah, cepat, dan transparan. Ribuan ruangan berkualitas siap menanti Anda.</p>

        <div class="hero-cta">
          @auth
            <a href="{{ route('ruangan.daftar') }}" class="btn-primary">
              Mulai Booking
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          @else
            <a href="javascript:void(0)" onclick="cekLoginBooking()" class="btn-primary">
              Mulai Booking
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          @endauth
          <a href="{{ route('ruangan.daftar') }}" class="btn-ghost">Lihat Ruangan</a>
        </div>

        <div class="hero-stats">
          <div class="stat"><strong>5+</strong><span>Ruangan</span></div>
          <div class="stat-divider"></div>
          <div class="stat"><strong>100+</strong><span>Booking</span></div>
          <div class="stat-divider"></div>
          <div class="stat"><strong>4.8★</strong><span>Rating</span></div>
        </div>
      </div>
    </div>
    
  </section>

  @guest
  <script>
  function cekLoginBooking() {
      Swal.fire({
          icon: 'warning',
          title: 'Anda belum login',
          text: 'Silakan login terlebih dahulu untuk melakukan booking.',
          showCancelButton: true,
          confirmButtonText: 'Login',
          cancelButtonText: 'Batal'
      }).then((result) => {
          if (result.isConfirmed) {
              window.location.href = "{{ route('login') }}?redirect={{ urlencode(route('ruangan.daftar')) }}";
          }
      });
  }
  </script>
  @endguest


<section class="container section">
  <div class="section-header">
    <span class="section-badge">Kategori</span>
    <h2 class="section-title">Pilih Sesuai Kebutuhan</h2>
    <p class="section-subtitle">Temukan ruangan yang paling cocok untuk aktivitas Anda.</p>
  </div>
  <div class="category-grid">
    @foreach($kategori as $k)
      @php
        $adaFoto = !empty($k['gambar']) && file_exists(public_path($k['gambar']));
      @endphp
      <div class="category-card"
        @if($adaFoto)
          style="background-image: url('{{ asset($k['gambar']) }}') !important; background-size: cover !important; background-position: center !important; background-repeat: no-repeat !important;"
        @endif
      >
        <div class="category-icon" @if($adaFoto) style="background: rgba(0,0,0,0.45) !important; backdrop-filter: blur(2px);" @endif>{{ $k['icon'] }}</div>
        <span>{{ $k['nama'] }}</span>
      </div>
    @endforeach
  </div>
</section>

  <section class="container section">
    <div class="section-header">
      <span class="section-badge">Keunggulan</span>
      <h2 class="section-title">Kenapa Memilih Kami</h2>
      <p class="section-subtitle">Alasan ribuan pengguna mempercayakan kebutuhan ruangan mereka.</p>
    </div>
    <div class="feature-grid">
      <div class="feature-card">
        <div class="feature-icon">⚡</div>
        <h3>Booking Cepat</h3>
        <p>Pesan ruangan hanya dalam hitungan detik tanpa ribet.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">💰</div>
        <h3>Harga Transparan</h3>
        <p>Tanpa biaya tersembunyi. Semua jelas di awal.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🎧</div>
        <h3>Support 24/7</h3>
        <p>Tim kami siap membantu kapan pun Anda butuh.</p>
      </div>
    </div>
  </section>

  <section class="container section">
    <div class="section-header">
      <span class="section-badge">Cara Kerja</span>
      <h2 class="section-title">Booking dalam 3 Langkah</h2>
      <p class="section-subtitle">Sederhana, cepat, tanpa ribet.</p>
    </div>
    <div class="steps-grid">
      <div class="step-card">
        <div class="step-number">01</div>
        <h3>Cari Ruangan</h3>
        <p>Telusuri ratusan ruangan berdasarkan kategori, kapasitas, dan lokasi.</p>
      </div>
      <div class="step-card">
        <div class="step-number">02</div>
        <h3>Pilih Jadwal</h3>
        <p>Tentukan tanggal dan jam yang Anda butuhkan. Cek ketersediaan real-time.</p>
      </div>
      <div class="step-card">
        <div class="step-number">03</div>
        <h3>Konfirmasi & Bayar</h3>
        <p>Lakukan pembayaran dengan aman. Booking langsung terkonfirmasi.</p>
      </div>
    </div>
  </section>

  <section class="container section">
    <div class="cta-banner">
      <div class="cta-banner-content">
        <h2>Siap Mulai Booking?</h2>
        <p>Bergabung dengan ribuan pengguna yang sudah menemukan ruangan favorit mereka.</p>
      </div>
      <a href="{{ route('ruangan.daftar') }}" class="btn-primary btn-cta-light">
        Mulai Sekarang
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>
  </section>

  <footer>@include('partials.footer')</footer>
</body>
</html>