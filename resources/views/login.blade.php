<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  @include('partials.theme')

  <title>Login - NookNook</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">

  <style>
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

@include('partials.theme-toggle')

<main class="login-container">

  <section class="brand-section">
    <div class="brand-content">
      <a href="{{ route('home') }}" class="brand-logo">
        <span class="brand-logo-mark">N</span>
        <span>NookNook</span>
      </a>

      <h1 class="brand-headline">
        Booking Ruangan <br>
        <span class="gradient-text">Jadi Mudah.</span>
      </h1>

      <p class="brand-subtitle">
        Temukan dan pesan ruang meeting, coworking, atau event dalam hitungan detik.
      </p>

      <ul class="brand-features">
        <li><span class="check">✓</span> 50+ ruangan berkualitas</li>
        <li><span class="check">✓</span> Harga transparan</li>
        <li><span class="check">✓</span> Support 24/7</li>
      </ul>
    </div>

    <div class="brand-blob-1"></div>
    <div class="brand-blob-2"></div>
  </section>

  <section class="form-section">
    <div class="form-wrapper">

      <div class="form-header">
        <span class="section-badge">Selamat datang</span>
        <h2>Masuk ke Akun Anda</h2>
        <p>Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
      </div>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="login-form" action="{{ route('login.submit') }}" method="POST">
        @csrf

        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username Anda" autocomplete="username" required>
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Masukkan password Anda" autocomplete="current-password" required>
        </div>

        <div class="form-row-between">
          <label class="remember-label">
            <input type="checkbox" name="remember" id="remember">
            <span>Ingat saya</span>
          </label>
          <a href="#" class="forgot-link">Lupa password?</a>
        </div>

        <button type="submit" class="btn-submit">
          Masuk
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </button>

      </form>

      <p class="form-footer-text">
        Dengan masuk, Anda menyetujui <a href="{{ route('syarat.ketentuan') }}">Syarat & Ketentuan</a> kami.
      </p>
    </div>
  </section>
</main>
</body>
</html>