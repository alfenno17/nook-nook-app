<?php
session_start();

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['password_confirmation']); 

    if (!empty($email) && !empty($password) && !empty($confirm)) {
        if ($password !== $confirm) {
            $error = "Konfirmasi password tidak cocok!";
        } else {
            $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $error = "Email sudah terdaftar!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                $insert_stmt = mysqli_prepare($conn, "INSERT INTO users (email, password) VALUES (?, ?)");
                mysqli_stmt_bind_param($insert_stmt, "ss", $email, $hashed_password);

                if (mysqli_stmt_execute($insert_stmt)) {
                    $success = "Registrasi berhasil! Silakan login.";
                } else {
                    $error = "Terjadi kesalahan, coba lagi.";
                }
            }
        }
    } else {
        $error = "Harap isi semua kolom!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar - NookNook</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

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
        <span class="section-badge">Daftar Akun</span>
        <h2>Buat Akun Baru</h2> 
      </div>

      <!-- Pesan Sukses / Error PHP Native -->
      <?php if(!empty($error)): ?>
          <div class="alert alert-danger">
              <p><?= $error ?></p>
          </div>
      <?php endif; ?>
      
      <?php if(!empty($success)): ?>
          <div class="alert alert-success">
              <p><?= $success ?></p>
          </div>
      <?php endif; ?>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="login-form" action="{{ route('register.submit') }}" method="POST">
        @csrf

        <div class="input-group">
          <label for="name">Nama</label>
          <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama Anda" required>
        </div>

        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" required>
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Buat password" required>
        </div>

        <div class="input-group">
          <label for="password_confirmation">Konfirmasi Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password Anda" required>
        </div>

        <button type="submit" class="btn-submit">
          Daftar
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </button>

        <div class="form-header" style="margin-top: 16px; margin-bottom: 0;">
          <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a></p>
        </div>

      </form>

      <p class="form-footer-text" style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-secondary);">
        Dengan mendaftar, Anda menyetujui <a href="{{ route('syarat.ketentuan') }}" style="color: #2563EB; text-decoration: none;">Syarat & Ketentuan</a> kami.
      </p>

    </div>
  </section>
</main>

</body>
</html>
