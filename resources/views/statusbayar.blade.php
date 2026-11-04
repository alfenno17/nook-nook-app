<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  {{-- TAMBAHAN: Anti-flicker + theme css --}}
  @include('partials.theme')

  <title>Status Pembayaran</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    [data-theme="dark"] .status-card {
      background: #1e293b;
      color: #f1f5f9;
    }
  </style>
</head>
<body>

  <div class="flex h-screen w-full">
    <main class="flex-1 overflow-y-auto p-8">
      <div class="status-card">

        @if($booking->status == 'menunggu_konfirmasi_admin')
            <div class="icon-container" style="background-color: #E0E7FF;">
              <svg viewBox="0 0 48 38" style="stroke: #4F46E5;">
                <circle cx="24" cy="19" r="15" stroke-width="4" fill="none" />
                <path d="M24 12v8M24 26h.01" stroke-width="4" stroke-linecap="round" />
              </svg>
            </div>
            <h2 class="status-headline" style="color: #4F46E5;">Menunggu Konfirmasi Admin</h2>
            <p class="status-subtext">Booking Anda sedang ditinjau oleh admin. Setelah disetujui, Anda dapat melanjutkan pembayaran.</p>

        @elseif($booking->status == 'disetujui')
            <div class="icon-container" style="background-color: #CFFAFE;">
              <svg viewBox="0 0 48 38" style="stroke: #0891B2;">
                <circle cx="24" cy="19" r="15" stroke-width="4" fill="none" />
                <path d="M16 19l6 6 12-12" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none" />
              </svg>
            </div>
            <h2 class="status-headline" style="color: #0891B2;">Booking Disetujui</h2>
            <p class="status-subtext">Booking Anda telah dikonfirmasi admin. Silakan lanjutkan pembayaran.</p>

        @elseif($booking->status == 'menunggu_verifikasi')
            <div class="icon-container" style="background-color: #FEF3C7;">
              <svg viewBox="0 0 48 38" style="stroke: #D97706;">
                <circle cx="24" cy="19" r="15" stroke-width="4" fill="none" />
                <path d="M24 12v8M24 26h.01" stroke-width="4" stroke-linecap="round" />
              </svg>
            </div>
            <h2 class="status-headline" style="color: #D97706;">Menunggu Verifikasi Admin</h2>
            <p class="status-subtext">Pembayaran Anda sedang diverifikasi oleh admin. Silakan cek kembali beberapa saat lagi.</p>

        @elseif($booking->status == 'dibayar' || $booking->status == 'selesai')
            <div class="icon-container">
              <svg viewBox="0 0 48 38">
                <path d="M3 18L18 33L45 3" />
              </svg>
            </div>
            <h2 class="status-headline">Pembayaran Berhasil</h2>
            <p class="status-subtext">Terima kasih, pembayaran Anda telah dikonfirmasi.</p>

        @else
            <div class="icon-container" style="background-color: #FEE2E2;">
              <svg viewBox="0 0 48 38" style="stroke: #DC2626;">
                <path d="M14 14L34 34M34 14L14 34" stroke-width="4" stroke-linecap="round" />
              </svg>
            </div>
            <h2 class="status-headline" style="color: #DC2626;">Status Tidak Diketahui</h2>
        @endif

        <div class="divider"></div>

        <div class="details-list">
          <div class="detail-item">
            <span class="detail-label">ID Booking</span>
            <span class="detail-value">{{ $booking->kode ?? $booking->id }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Ruangan</span>
            <span class="detail-value">{{ $booking->ruangan->nama }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Tanggal</span>
            <span class="detail-value">{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d F Y') }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Total</span>
            <span class="detail-value">Rp{{ number_format($booking->harga_total ?? $booking->ruangan->harga_per_jam, 0, ',', '.') }}</span>
          </div>
          <div class="detail-item">
            <span class="detail-label">Metode</span>
            <span class="detail-value">Transfer Bank</span>
          </div>
        </div>

        <div class="action-buttons">
          <button class="btn btn-dashboard" onclick="goToDashboard()">Kembali ke Dashboard</button>

          @if($booking->status == 'disetujui')
            <button class="btn btn-invoice" onclick="goToBayar()">Bayar Sekarang</button>
          @elseif($booking->status == 'menunggu_verifikasi' || $booking->status == 'dibayar' || $booking->status == 'selesai')
            <button class="btn btn-invoice" onclick="viewInvoice()">Lihat Invoice</button>
          @endif
        </div>

      </div>
    </main>
  </div>

  <script>
    function goToDashboard() {
      window.location.href = "{{ route('index') }}";
    }
    function viewInvoice() {
      window.location.href = "{{ route('booking.invoice', $booking->id) }}";
    }
    function goToBayar() {
      window.location.href = "{{ route('pembayaran', $booking->id) }}";
    }
  </script>

</body>
</html> 