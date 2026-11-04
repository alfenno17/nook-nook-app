<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  @include('partials.theme')

  <title>Riwayat Booking - NookNook</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dashboardpengguna.css') }}">
  <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
  <style>
    .btn-bayar {
      background-color: #2563EB;
      color: #ffffff;
      padding: 6px 14px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      border: none;
      cursor: pointer;
      transition: background-color 0.2s;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .btn-bayar:hover {
      background-color: #1D4ED8;
    }
    .btn-invoice {
      background-color: var(--bg-body, #f1f5f9);
      color: var(--text-primary, #475569);
      border: 1px solid var(--border-color, #cbd5e1);
      padding: 6px 14px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      text-decoration: none !important;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
    }
    .btn-invoice:hover {
      background-color: #e2e8f0;
      color: #0f172a;
    }
    .action-group {
      display: flex;
      gap: 8px;
      justify-content: center;
      align-items: center;
    }

    /* Modal Upload Bukti Transfer */
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(15, 23, 42, 0.6);
      z-index: 999;
      justify-content: center;
      align-items: center;
    }
    .modal-overlay.active {
      display: flex;
    }
    .modal-card {
      background: var(--bg-card, #ffffff);
      color: var(--text-primary, #0F172A);
      border: 1px solid var(--border-color, #CBD5E1);
      border-radius: 16px;
      padding: 24px;
      width: 100%;
      max-width: 480px;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
    }
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }
    .modal-title { 
      font-size: 18px; 
      font-weight: 700; 
      color: var(--text-primary, #0F172A); 
    }
    .close-btn { 
      cursor: pointer; 
      font-size: 20px; 
      font-weight: bold; 
      color: var(--text-secondary, #64748B); 
      border: none; 
      background: none; 
    }
    .form-group { margin-bottom: 16px; text-align: left; }
    .form-group label { 
      display: block; 
      font-size: 13px; 
      font-weight: 600; 
      margin-bottom: 6px; 
      color: var(--text-primary, #334155); 
    }
    .form-group select, .form-group input {
      width: 100%; 
      padding: 10px; 
      background-color: var(--bg-input, #ffffff);
      color: var(--text-primary, #0F172A);
      border: 1px solid var(--border-color, #CBD5E1); 
      border-radius: 8px; 
      font-size: 14px; 
      box-sizing: border-box;
    }
    
    /* Kotak Informasi Rekening di dalam Modal */
    .modal-card div[style*="background"] {
      background: var(--bg-body, #F1F5F9) !important;
      color: var(--text-primary, #334155);
    }
    .modal-card p, .modal-card strong {
      color: var(--text-primary, #0F172A) !important;
    }
    .modal-card hr {
      border-top: 1px solid var(--border-color, #CBD5E1) !important;
    }
    .btn-submit {
      width: 100%; background-color: #10B981; color: white; padding: 10px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 14px;
    }
    .btn-submit:hover { background-color: #059669; }
  </style>
</head>
<body>

<div class="dashboard-container">

    @include('partials.sidebar')

    <main class="main-content">

      {{-- HEADER --}}
      <header class="page-header">
        <div>
          <span class="section-badge">Riwayat</span>
          <h1 class="page-title">Riwayat Booking</h1>
          <p class="page-subtitle">Semua transaksi dan booking yang pernah Anda lakukan.</p>
        </div>

        {{-- SEARCH --}}
        <div class="search-wrap">
          <svg class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input type="text" placeholder="Cari riwayat..." class="search-input" />
        </div>
      </header>

      {{-- NOTIFIKASI SUKSES --}}
      @if(session('success'))
        <div style="background-color: #D1FAE5; color: #065F46; padding: 12px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 600;">
          {{ session('success') }}
        </div>
      @endif

      {{-- TABLE CARD --}}
      <div class="card">
        <div class="card-body no-pad">
          <table class="booking-table">
            <thead>
              <tr>
                <th>Ruangan</th>
                <th>Tanggal</th>
                <th class="text-center">Status</th>
                <th class="text-center">Pembayaran</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($riwayat as $booking)
              <tr>
                <td><strong>{{ $booking->ruangan->nama ?? 'Tidak diketahui' }}</strong></td>
                <td>{{ \Carbon\Carbon::parse($booking->tanggal)->format('d F Y') }}</td>
                <td class="text-center">
                  <span class="badge
                    @if(in_array($booking->status, ['selesai', 'dibayar'])) badge-success
                    @elseif($booking->status == 'dibatalkan') badge-danger
                    @elseif(in_array($booking->status, ['menunggu_pembayaran', 'menunggu_konfirmasi_admin'])) badge-warning
                    @else badge-info @endif">
                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                  </span>
                </td>
                <td class="text-center">
                  <span class="badge
                    @if(in_array($booking->status, ['dibayar', 'selesai'])) badge-success
                    @elseif($booking->status == 'menunggu_verifikasi') badge-warning
                    @else badge-neutral @endif">
                    @if(in_array($booking->status, ['dibayar', 'selesai']))
                      Lunas
                    @elseif($booking->status == 'menunggu_verifikasi')
                      Menunggu Verifikasi
                    @else
                      Belum Dibayar
                    @endif
                  </span>
                </td>
                <td class="text-center">
                  <div class="action-group">
                    <a href="{{ route('invoice', $booking->id) }}" class="btn-invoice">
                        Lihat Invoice
                    </a>

                    @if(in_array($booking->status, ['menunggu_pembayaran', 'disetujui']))
                      <button type="button" class="btn-bayar" onclick="openPaymentModal({{ $booking->id }}, '{{ $booking->ruangan->nama ?? '' }}', '{{ number_format($booking->harga_total ?? 0, 0, ',', '.') }}')">
                        Bayar Sekarang
                      </button>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center empty-row">Belum ada riwayat booking.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </main>

</div>

{{-- MODAL UPLOAD BUKTI --}}
<div class="modal-overlay" id="paymentModal">
  <div class="modal-card">
    <div class="modal-header">
      <h3 class="modal-title">Upload Bukti Pembayaran</h3>
      <button class="close-btn" onclick="closePaymentModal()">&times;</button>
    </div>
    
    <div style="background: #F1F5F9; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 13px;">
      <p style="margin: 0 0 4px 0;"><strong>Ruangan:</strong> <span id="modalRuanganNama">-</span></p>
      <p style="margin: 0;"><strong>Total Bayar:</strong> Rp<span id="modalTotalHarga">0</span></p>
      <hr style="margin: 8px 0; border: none; border-top: 1px solid #CBD5E1;">
      <p style="margin: 0; color: #475569; font-size: 12px;" id="infoRekening">
        Transfer BCA: <strong>1234567890</strong> a.n NookNook
      </p>
    </div>

    <form id="paymentForm" action="" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label for="metode">Metode Pembayaran</label>
        <select name="metode" id="metode" onchange="updateInfoRekening(this.value)" required>
          <option value="Transfer BCA">Transfer BCA</option>
          <option value="Transfer Mandiri">Transfer Mandiri</option>
          <option value="E-Wallet (Dana/OVO/Gopay)">E-Wallet (Dana/OVO/Gopay)</option>
        </select>
      </div>

      <div class="form-group">
        <label for="bukti">Foto / Screenshot / PDF Bukti Transfer</label>
        <input type="file" name="bukti" id="bukti" accept="image/*,.pdf" required>
      </div>

      <button type="submit" class="btn-submit">Kirim Bukti Pembayaran</button>
    </form>
  </div>
</div>

<script>
  function updateInfoRekening(metode) {
    const infoBox = document.getElementById('infoRekening');
    if (metode === 'Transfer Mandiri') {
      infoBox.innerHTML = 'Transfer Mandiri: <strong>0987654321</strong> a.n NookNook';
    } else if (metode === 'E-Wallet (Dana/OVO/Gopay)') {
      infoBox.innerHTML = 'E-Wallet (Dana/OVO/Gopay): <strong>081234567890</strong> a.n NookNook';
    } else {
      infoBox.innerHTML = 'Transfer BCA: <strong>1234567890</strong> a.n NookNook';
    }
  }

  function openPaymentModal(bookingId, namaRuangan, totalHarga) {
    document.getElementById('modalRuanganNama').innerText = namaRuangan;
    document.getElementById('modalTotalHarga').innerText = totalHarga;
    
    const form = document.getElementById('paymentForm');
    form.action = `/booking/${bookingId}/pembayaran`;

    document.getElementById('paymentModal').classList.add('active');
  }

  function closePaymentModal() {
    document.getElementById('paymentModal').classList.remove('active');
  }
</script>

</body>
</html>
