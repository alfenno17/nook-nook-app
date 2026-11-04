<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  {{-- TAMBAHAN: Anti-flicker + theme css --}}
  @include('partials.theme')

  <title>Pembayaran - NookNook</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/pembayaran.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('css/topbar.css') }}">

  {{-- Style tambahan untuk tombol toggle tema --}}
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
<body>

@include('partials.topbar')

<main class="page-wrap">
  <div class="container">

    <nav class="breadcrumb">
      <a href="{{ route('home') }}">Beranda</a>
      <span>/</span>
      <a href="{{ route('ruangan.daftar') }}">Ruangan</a>
      <span>/</span>
      <span class="current">Pembayaran</span>
    </nav>

    <form action="{{ route('pembayaran.bayar', $booking->id ?? $booking->ruangan_id) }}" method="POST" enctype="multipart/form-data" class="payment-grid">
      @csrf

      <section class="card form-card">

        <div class="card-head">
          <span class="section-badge">Pembayaran</span>
          <h1 class="form-title">Selesaikan Pembayaran</h1>
          <p class="form-subtitle">Lakukan transfer ke nomor Virtual Account di bawah ini.</p>
        </div>

        <div class="form-group">
          <label for="metode">Metode Pembayaran</label>
          <select id="metode" name="metode" class="input-control" required>
            <option value="Transfer Bank">Transfer Bank</option>
          </select>
        </div>

        <div class="form-group">
          <label>Nomor Virtual Account</label>
          <div class="va-box">
            <span id="vaNumber" class="va-number">8808 1234 5678 9012</span>
            <button type="button" class="btn-copy" onclick="copyVA()" aria-label="Salin VA">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
              </svg>
              Salin
            </button>
          </div>
          <p class="hint">Transfer tepat sesuai nominal agar pembayaran otomatis terverifikasi.</p>
        </div>

        <div class="form-group">
          <label for="fileInput">Upload Bukti Pembayaran</label>
          <div class="upload-area" onclick="document.getElementById('fileInput').click()">
            <div class="upload-icon">
              <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="17 8 12 3 7 8"/>
                <line x1="12" y1="3" x2="12" y2="15"/>
              </svg>
            </div>
            <p class="upload-title">Drag &amp; Drop / Klik untuk Pilih File</p>
            <p class="upload-sub">Format: JPG, PNG, PDF (Maks. 5MB)</p>
            <input type="file" id="fileInput" name="bukti_pembayaran" accept="image/*,application/pdf" style="display: none;" onchange="showFileName(this)">
            <p id="fileName" class="file-name"></p>
          </div>
        </div>

      </section>

      <aside class="sidebar">

        <div class="card summary-card">
          <span class="section-badge">Ringkasan</span>
          <h2 class="summary-title">Ringkasan Pesanan</h2>

          <div class="summary-row">
            <span class="summary-label">Ruangan</span>
            <span class="summary-value">{{ $booking->ruangan->nama ?? '-' }}</span>
          </div>
          <div class="summary-row">
            <span class="summary-label">Tanggal</span>
            <span class="summary-value">{{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}</span>
          </div>
          <div class="summary-row">
            <span class="summary-label">Durasi</span>
            <span class="summary-value">{{ $booking->jam_mulai }} – {{ $booking->jam_selesai }}</span>
          </div>

          <div class="summary-total">
            <span>Total</span>
            <strong>Rp{{ number_format($booking->harga_total ?? 0, 0, ',', '.') }}</strong>
          </div>
        </div>

        <div class="card info-card">
          <h3 class="info-title">Informasi</h3>
          <ul class="info-list">
            <li><span class="dot"></span> Pembayaran diproses maks. 1x24 jam.</li>
            <li><span class="dot"></span> Pastikan bukti transfer terlihat jelas.</li>
            <li><span class="dot"></span> Hubungi support jika ada kendala.</li>
          </ul>
        </div>

        <button type="submit" class="btn-confirm" onclick="return konfirmasi()">
          Konfirmasi Pembayaran
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </button>

      </aside>

    </form>

  </div>
</main>

@include('partials.footer')

<script>
  function copyVA() {
    const vaText = document.getElementById('vaNumber').innerText.replace(/\s+/g, '');
    navigator.clipboard.writeText(vaText).then(() => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil disalin',
        text: 'Nomor Virtual Account sudah ada di clipboard.',
        timer: 1500,
        showConfirmButton: false
      });
    });
  }

  function showFileName(input) {
    const label = document.getElementById('fileName');
    if (input.files && input.files[0]) {
      label.innerText = '📎 ' + input.files[0].name;
      label.style.display = 'block';
    } else {
      label.innerText = '';
      label.style.display = 'none';
    }
  }

  function konfirmasi() {
    return confirm('Pastikan bukti pembayaran sudah benar. Lanjutkan?');
  }
</script>

</body>
</html>