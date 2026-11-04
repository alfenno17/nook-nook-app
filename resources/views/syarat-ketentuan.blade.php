<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  {{-- TAMBAHAN: Anti-flicker + theme css --}}
  @include('partials.theme')

  <title>Syarat & Ketentuan - NookNook</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
  <style>
    /* TAMBAHAN: Style tombol toggle tema */
    .theme-toggle {
      position: fixed;
      top: 24px;
      right: 24px;
      z-index: 9999;
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

    /* ============================================
       RESET & BASE
       ============================================ */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --bg: #F8FAFC;
      --surface: #FFFFFF;
      --text: #0F172A;
      --text-muted: #64748B;
      --border: #E5E7EB;
      --primary: #0F172A;
      --primary-hover: #1E293B;
      --accent: #6366F1;
      --accent-soft: #EEF2FF;
      --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.04);
      --shadow-md: 0 8px 24px rgba(15, 23, 42, 0.06);
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, sans-serif;
      background-color: var(--bg);
      color: var(--text);
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
    }

    /* NAVBAR */
    .navbar {
      background-color: rgba(255, 255, 255, 0.85);
      backdrop-filter: saturate(180%) blur(14px);
      -webkit-backdrop-filter: saturate(180%) blur(14px);
      border-bottom: 1px solid var(--border);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .nav-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      height: 72px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 20px;
      font-weight: 800;
      color: var(--text);
      text-decoration: none;
      letter-spacing: -0.02em;
    }

    .logo-mark {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 34px;
      height: 34px;
      border-radius: 10px;
      background: linear-gradient(135deg, #0F172A 0%, #334155 100%);
      color: #fff;
      font-size: 18px;
      font-weight: 800;
    }

    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      text-decoration: none;
      color: var(--text-muted);
      font-size: 14px;
      font-weight: 500;
      padding: 8px 14px;
      border-radius: 10px;
      transition: all 0.2s ease;
    }

    .back-link:hover {
      color: var(--text);
      background: var(--accent-soft);
    }

    /* HERO */
    .page-hero {
      padding: 56px 0 32px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .page-hero::before {
      content: '';
      position: absolute;
      top: -150px;
      right: -150px;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, transparent 70%);
      border-radius: 50%;
      pointer-events: none;
    }

    .container {
      max-width: 860px;
      margin: 0 auto;
      padding: 0 24px;
      width: 100%;
      position: relative;
      z-index: 1;
    }

    .section-badge {
      display: inline-block;
      background: var(--accent-soft);
      color: var(--accent);
      padding: 5px 14px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      margin-bottom: 14px;
    }

    .page-title {
      font-size: clamp(30px, 4.5vw, 44px);
      font-weight: 800;
      letter-spacing: -0.03em;
      line-height: 1.15;
      margin-bottom: 12px;
      color: var(--text);
    }

    .gradient-text {
      background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 50%, #EC4899 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .page-subtitle {
      font-size: 16px;
      color: var(--text-muted);
      max-width: 560px;
      margin: 0 auto;
    }

    .last-updated {
      display: inline-block;
      margin-top: 16px;
      font-size: 13px;
      color: #94A3B8;
      background: #F1F5F9;
      padding: 6px 14px;
      border-radius: 999px;
    }

    /* CONTENT */
    .content-wrap {
      flex: 1;
      padding: 32px 0 80px;
    }

    .syarat-card {
      background: #FFFFFF;
      border: 1px solid var(--border);
      border-radius: 24px;
      box-shadow: var(--shadow-sm);
      padding: 48px 44px;
    }

    .intro {
      font-size: 15px;
      color: #334155;
      line-height: 1.8;
      padding-bottom: 24px;
      margin-bottom: 32px;
      border-bottom: 1px solid var(--border);
    }

    .syarat-section {
      margin-bottom: 32px;
    }

    .syarat-section:last-child {
      margin-bottom: 0;
    }

    .section-heading {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 19px;
      font-weight: 800;
      color: var(--text);
      letter-spacing: -0.01em;
      margin-bottom: 14px;
    }

    .section-number {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 34px;
      height: 34px;
      border-radius: 10px;
      background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
      color: #fff;
      font-size: 14px;
      font-weight: 800;
      flex-shrink: 0;
    }

    .syarat-section p {
      font-size: 15px;
      color: #334155;
      line-height: 1.8;
      margin-bottom: 10px;
    }

    .syarat-section ul {
      list-style: none;
      padding: 0;
      margin: 12px 0 0 0;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .syarat-section ul li {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 15px;
      color: #334155;
      line-height: 1.7;
    }

    .syarat-section ul li::before {
      content: '';
      display: inline-block;
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #6366F1;
      margin-top: 10px;
      flex-shrink: 0;
    }

    .syarat-section strong {
      color: var(--text);
      font-weight: 700;
    }

    /* Callout */
    .callout {
      background: var(--accent-soft);
      border-left: 4px solid var(--accent);
      border-radius: 12px;
      padding: 18px 22px;
      margin: 24px 0;
      font-size: 14px;
      color: #3730A3;
      line-height: 1.7;
    }

    .callout strong {
      color: #312E81;
    }

    /* Contact block */
    .contact-block {
      background: #F8FAFC;
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 24px 28px;
      margin-top: 32px;
    }

    .contact-block h4 {
      font-size: 15px;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 12px;
    }

    .contact-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 8px;
      font-size: 14px;
      color: #475569;
    }

    .contact-list li {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .contact-list li::before {
      content: '•';
      color: #6366F1;
      font-weight: 800;
    }

    /* Accept bar */
    .accept-bar {
      margin-top: 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 22px 28px;
      background: #0F172A;
      border-radius: 16px;
      flex-wrap: wrap;
    }

    .accept-bar p {
      color: #CBD5E1;
      font-size: 14px;
      margin: 0;
    }

    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 26px;
      background: #FFFFFF;
      color: var(--text);
      border: none;
      border-radius: 12px;
      font-family: inherit;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .btn-back:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    /* Responsive */
    @media (max-width: 640px) {
      .nav-container { height: 64px; padding: 0 16px; }
      .logo { font-size: 18px; }
      .back-link { font-size: 13px; padding: 6px 10px; }

      .page-hero { padding: 32px 0 20px; }

      .container { padding: 0 16px; }

      .syarat-card {
        padding: 28px 22px;
        border-radius: 18px;
      }

      .section-heading { font-size: 17px; }

      .section-number { width: 30px; height: 30px; font-size: 13px; }

      .accept-bar {
        padding: 18px 22px;
        flex-direction: column;
        align-items: stretch;
      }

      .btn-back {
        justify-content: center;
      }
    }
  </style>
</head>
<body>


{{-- NAVBAR --}}
<header class="navbar">
  <div class="nav-container">
    <a href="{{ route('home') }}" class="logo">
      <span class="logo-mark">N</span>
      <span>NookNook</span>
    </a>
    <a href="javascript:history.back()" class="back-link">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 12H5M12 19l-7-7 7-7"/>
      </svg>
      Kembali
    </a>
  </div>
</header>

{{-- HERO --}}
<section class="page-hero">
  <div class="container">
    <span class="section-badge">Legal</span>
    <h1 class="page-title">Syarat & <span class="gradient-text">Ketentuan</span></h1>
    <p class="page-subtitle">
      Harap baca dengan saksama sebelum menggunakan layanan booking ruangan NookNook.
    </p>
    <span class="last-updated">Terakhir diperbarui: 15 September 2026</span>
  </div>
</section>

{{-- CONTENT --}}
<main class="content-wrap">
  <div class="container">
    <div class="syarat-card">

      <p class="intro">
        Selamat datang di NookNook. Syarat dan Ketentuan ini mengatur penggunaan layanan
        penyewaan ruangan yang disediakan oleh NookNook ("Kami", "Platform"). Dengan
        mengakses atau menggunakan layanan kami, Anda ("Pengguna") dianggap telah membaca,
        memahami, dan menyetujui seluruh ketentuan yang tercantum di bawah ini. Jika Anda
        tidak setuju, mohon untuk tidak menggunakan layanan kami.
      </p>

      {{-- 1 --}}
      <div class="syarat-section">
        <h3 class="section-heading">
          <span class="section-number">01</span>
          Definisi
        </h3>
        <p>
          Dalam Syarat dan Ketentuan ini, yang dimaksud dengan:
        </p>
        <ul>
          <li><strong>NookNook</strong> adalah platform penyedia layanan booking ruangan secara online.</li>
          <li><strong>Pengguna</strong> adalah setiap individu yang mendaftar dan/atau menggunakan layanan NookNook.</li>
          <li><strong>Ruangan</strong> adalah unit properti yang disewakan melalui platform NookNook, termasuk namun tidak terbatas pada ruang meeting, coworking, dan event.</li>
          <li><strong>Booking</strong> adalah pemesanan ruangan yang dilakukan oleh Pengguna melalui platform.</li>
          <li><strong>Pembayaran</strong> adalah transaksi keuangan yang dilakukan oleh Pengguna untuk menyelesaikan booking.</li>
        </ul>
      </div>

      {{-- 2 --}}
      <div class="syarat-section">
        <h3 class="section-heading">
          <span class="section-number">02</span>
          Akun Pengguna
        </h3>
        <ul>
          <li>Pengguna wajib mendaftar dengan informasi yang benar, akurat, dan terkini.</li>
          <li>Pengguna bertanggung jawab penuh atas kerahasiaan username dan password akun.</li>
          <li>Segala aktivitas yang terjadi melalui akun Pengguna menjadi tanggung jawab Pengguna.</li>
          <li>NookNook berhak menangguhkan atau menonaktifkan akun yang terbukti melakukan pelanggaran.</li>
          <li>Satu Pengguna hanya diperbolehkan memiliki satu akun aktif, kecuali disetujui secara tertulis.</li>
        </ul>
      </div>

      {{-- 3 --}}
      <div class="syarat-section">
        <h3 class="section-heading">
          <span class="section-number">03</span>
          Proses Booking
        </h3>
        <ul>
          <li>Pengguna dapat melakukan booking setelah login ke akun yang terverifikasi.</li>
          <li>Ketersediaan ruangan bersifat real-time dan dapat berubah sewaktu-waktu.</li>
          <li>Booking dianggap sah setelah Pengguna menyelesaikan pembayaran penuh sesuai nominal yang tertera.</li>
          <li>Pengguna wajib memastikan tanggal, jam, dan detail booking sudah benar sebelum konfirmasi.</li>
          <li>NookNook tidak bertanggung jawab atas kesalahan input data yang dilakukan oleh Pengguna.</li>
        </ul>
      </div>

      {{-- 4 --}}
      <div class="syarat-section">
        <h3 class="section-heading">
          <span class="section-number">04</span>
          Pembayaran
        </h3>
        <ul>
          <li>Pembayaran dilakukan melalui metode yang tersedia di platform (Transfer Bank atau QRIS).</li>
          <li>Pengguna wajib mengunggah bukti pembayaran dalam waktu 1x24 jam setelah booking.</li>
          <li>Booking yang belum dibayar dalam batas waktu tersebut dapat dibatalkan secara otomatis.</li>
          <li>Harga yang tertera sudah termasuk pajak dan biaya layanan, kecuali dinyatakan lain.</li>
          <li>NookNook berhak mengubah harga kapan saja, namun perubahan tidak berlaku untuk booking yang sudah dikonfirmasi.</li>
        </ul>
      </div>

      {{-- 5 --}}
      <div class="syarat-section">
        <h3 class="section-heading">
          <span class="section-number">05</span>
          Pembatalan & Pengembalian Dana
        </h3>
        <ul>
          <li>Pembatalan booking dapat dilakukan minimal <strong>1x24 jam</strong> sebelum jadwal dimulai.</li>
          <li>Pembatalan kurang dari 1x24 jam tidak berhak atas pengembalian dana.</li>
          <li>Pengembalian dana (jika disetujui) akan diproses dalam 3–7 hari kerja.</li>
          <li>Biaya administrasi pembatalan sebesar <strong>10%</strong> dari total pembayaran akan dikenakan untuk setiap pembatalan yang disetujui.</li>
          <li>Pembatalan sepihak oleh pihak NookNook akan mengembalikan dana 100% kepada Pengguna.</li>
        </ul>
      </div>

      {{-- 6 --}}
      <div class="syarat-section">
        <h3 class="section-heading">
          <span class="section-number">06</span>
          Kewajiban Pengguna
        </h3>
        <ul>
          <li>Menjaga kebersihan, ketertiban, dan keamanan ruangan selama masa sewa.</li>
          <li>Tidak menggunakan ruangan untuk aktivitas ilegal atau melanggar hukum.</li>
          <li>Tidak membawa barang terlarang ke dalam area ruangan.</li>
          <li>Mengganti kerugian apabila terjadi kerusakan fasilitas akibat kelalaian Pengguna.</li>
          <li>Menaati seluruh peraturan internal gedung tempat ruangan berada.</li>
        </ul>
      </div>

      {{-- 7 --}}
      <div class="syarat-section">
        <h3 class="section-heading">
          <span class="section-number">07</span>
          Larangan
        </h3>
        <p>Pengguna dilarang:</p>
        <ul>
          <li>Menyalahgunakan platform untuk aktivitas penipuan atau tindak pidana.</li>
          <li>Mengakses sistem NookNook tanpa izin (hacking, scraping, brute force, dll).</li>
          <li>Menyebarkan konten yang bersifat SARA, pornografi, atau ujaran kebencian.</li>
          <li>Memindahtangankan akun kepada pihak lain tanpa persetujuan tertulis.</li>
          <li>Melakukan booking fiktif yang dapat merugikan pihak lain.</li>
        </ul>
      </div>

      {{-- 8 --}}
      <div class="syarat-section">
        <h3 class="section-heading">
          <span class="section-number">08</span>
          Batasan Tanggung Jawab
        </h3>
        <ul>
          <li>NookNook bertindak sebagai platform perantara antara Pengguna dan pemilik ruangan.</li>
          <li>NookNook tidak bertanggung jawab atas kehilangan barang berharga milik Pengguna selama penggunaan ruangan.</li>
          <li>NookNook tidak bertanggung jawab atas gangguan layanan yang disebabkan oleh force majeure (bencana alam, gangguan jaringan, dsb).</li>
          <li>Kerusakan fasilitas akibat kelalaian Pengguna menjadi tanggung jawab Pengguna sepenuhnya.</li>
        </ul>
      </div>

      {{-- 9 --}}
      <div class="syarat-section">
        <h3 class="section-heading">
          <span class="section-number">09</span>
          Perubahan Ketentuan
        </h3>
        <p>
          NookNook berhak mengubah, menambah, atau menghapus sebagian maupun seluruh isi
          Syarat dan Ketentuan ini sewaktu-waktu tanpa pemberitahuan terlebih dahulu.
          Perubahan akan berlaku efektif sejak dipublikasikan di halaman ini. Pengguna
          disarankan untuk memeriksa halaman ini secara berkala.
        </p>
      </div>

      {{-- 10 --}}
      <div class="syarat-section">
        <h3 class="section-heading">
          <span class="section-number">10</span>
          Hukum yang Berlaku
        </h3>
        <p>
          Syarat dan Ketentuan ini tunduk pada hukum yang berlaku di Republik Indonesia.
          Setiap perselisihan yang timbul akan diselesaikan secara musyawarah terlebih
          dahulu. Apabila tidak tercapai kesepakatan, maka akan diselesaikan melalui
          pengadilan yang berwenang di Indonesia.
        </p>
      </div>

      {{-- Callout --}}
      <div class="callout">
        <strong>Catatan penting:</strong> Dengan menekan tombol "Daftar" atau "Masuk",
        Anda secara sadar menyatakan telah membaca, memahami, dan menyetujui seluruh
        Syarat dan Ketentuan yang berlaku di NookNook.
      </div>

      {{-- Contact --}}
      <div class="contact-block">
        <h4>Pertanyaan tentang S&K?</h4>
        <ul class="contact-list">
          <li>Email: support@nooknook.id</li>
          <li>WhatsApp: +62 812-3456-7890</li>
          <li>Jam operasional: Senin – Jumat, 09.00 – 18.00 WIB</li>
        </ul>
      </div>

      {{-- Accept Bar --}}
      <div class="accept-bar">
        <p>Dengan menggunakan layanan kami, Anda menyetujui seluruh ketentuan di atas.</p>
        <a href="javascript:history.back()" class="btn-back">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
          </svg>
          Saya Mengerti
        </a>
      </div>

    </div>
  </div>
</main>

@include('partials.footer')

</body>
</html>