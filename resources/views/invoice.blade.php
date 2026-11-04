<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  {{-- Anti-flicker + theme css --}}
  @include('partials.theme')

  <title>Invoice - {{ $booking->kode }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    /* Reset & Center Container */
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      background-color: var(--bg-body, #f8fafc);
      color: var(--text-primary, #1e293b);
      font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    }

    .invoice-container {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 16px;
      box-sizing: border-box;
    }

    .status-card {
      background: var(--bg-card, #ffffff);
      border: 1px solid var(--border-color, #e2e8f0);
      border-radius: 16px;
      padding: 36px;
      width: 100%;
      max-width: 800px;
      margin: 0 auto;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      box-sizing: border-box;
    }

    /* Style Tombol Aksi */
    .btn-action {
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.2s ease;
      text-decoration: none !important;
      border: none;
      box-sizing: border-box;
    }

    .btn-secondary-custom {
      background-color: var(--bg-body, #f1f5f9);
      color: var(--text-primary, #475569) !important;
      border: 1px solid var(--border-color, #cbd5e1);
    }

    .btn-secondary-custom:hover {
      background-color: #e2e8f0;
      color: #0f172a !important;
    }

    .btn-primary-custom {
      background-color: #0f172a;
      color: #ffffff !important;
    }

    .btn-primary-custom:hover {
      background-color: #1e293b;
    }

    /* Mode Cetak / Print */
    @media print {
      html, body, .invoice-container {
        background-color: #ffffff !important;
        color: #000000 !important;
        padding: 0 !important;
        height: auto !important;
        display: block !important;
      }
      .no-print {
        display: none !important;
      }
      .status-card {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        max-width: 100% !important;
      }
    }
  </style>
</head>
<body>

  <div class="invoice-container">
    <div class="status-card">

      {{-- Perhitungan durasi & total --}}
      @php
          $jamMulai   = \Carbon\Carbon::parse($booking->jam_mulai);
          $jamSelesai = \Carbon\Carbon::parse($booking->jam_selesai);
          $durasiJam  = max(1, $jamMulai->diffInMinutes($jamSelesai) / 60);
          $hargaPerJam = $booking->ruangan->harga_per_jam ?? 0;
          $subtotal   = $durasiJam * $hargaPerJam;
      @endphp

      <!-- Header Invoice -->
      <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid var(--border-color, #e2e8f0); padding-bottom: 20px; margin-bottom: 24px;">
        <div>
          <h1 style="font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -0.02em;">INVOICE</h1>
          <p style="color: var(--text-secondary, #64748b); margin-top: 4px; font-size: 14px;">NookNook Room Booking</p>
        </div>
        <div style="text-align: right;">
          <p style="font-weight: 700; font-size: 18px; margin: 0;">#{{ $booking->kode }}</p>
          <p style="color: var(--text-secondary, #64748b); font-size: 13px; margin-top: 4px;">
            Tanggal: {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}
          </p>
        </div>
      </div>

      <!-- Informasi Pelanggan & Status -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        <div>
          <h3 style="font-size: 12px; color: var(--text-secondary, #64748b); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 8px;">Ditagihkan Kepada:</h3>
          <p style="font-weight: 700; font-size: 16px; margin: 0;">{{ $booking->user->name ?? 'Pelanggan' }}</p>
          <p style="color: var(--text-secondary, #475569); font-size: 14px; margin-top: 2px;">{{ $booking->user->email ?? '-' }}</p>
        </div>
        <div style="text-align: right;">
          <h3 style="font-size: 12px; color: var(--text-secondary, #64748b); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 8px;">Status Pembayaran:</h3>
          @if($booking->status == 'menunggu_pembayaran')
            <span style="background-color: #fef3c7; color: #92400e; padding: 6px 14px; border-radius: 9999px; font-size: 13px; font-weight: 700; display: inline-block;">Menunggu Pembayaran</span>
          @elseif($booking->status == 'menunggu_verifikasi' || $booking->status == 'menunggu_konfirmasi_admin')
            <span style="background-color: #fde68a; color: #b45309; padding: 6px 14px; border-radius: 9999px; font-size: 13px; font-weight: 700; display: inline-block;">Menunggu Konfirmasi</span>
          @elseif($booking->status == 'dibayar' || $booking->status == 'lunas' || $booking->status == 'disetujui')
            <span style="background-color: #d1fae5; color: #065f46; padding: 6px 14px; border-radius: 9999px; font-size: 13px; font-weight: 700; display: inline-block;">Lunas / Disetujui</span>
          @else
            <span style="background-color: #f1f5f9; color: #334155; padding: 6px 14px; border-radius: 9999px; font-size: 13px; font-weight: 700; display: inline-block;">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
          @endif
        </div>
      </div>

      <!-- Tabel Detail Booking -->
      <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
        <thead>
          <tr style="background-color: var(--bg-body, #f8fafc); border-bottom: 1px solid var(--border-color, #e2e8f0);">
            <th style="text-align: left; padding: 12px 14px; font-size: 13px; font-weight: 700; color: var(--text-secondary, #475569);">Deskripsi</th>
            <th style="text-align: center; padding: 12px 14px; font-size: 13px; font-weight: 700; color: var(--text-secondary, #475569);">Tanggal & Waktu</th>
            <th style="text-align: right; padding: 12px 14px; font-size: 13px; font-weight: 700; color: var(--text-secondary, #475569);">Harga</th>
          </tr>
        </thead>
        <tbody>
          <tr style="border-bottom: 1px solid var(--border-color, #e2e8f0);">
            <td style="padding: 16px 14px; vertical-align: top;">
              <p style="font-weight: 700; font-size: 15px; margin: 0 0 4px 0;">{{ $booking->ruangan->nama ?? $booking->ruangan->nama_ruangan ?? 'Ruangan' }}</p>
              <p style="font-size: 13px; color: var(--text-secondary, #64748b); margin: 0 0 6px 0;">{{ $booking->keperluan ?? 'Tidak ada keterangan' }}</p>

              <p style="font-size: 12px; color: var(--text-secondary, #64748b); margin: 0;">
                Durasi: {{ number_format($durasiJam, 1) }} jam × Rp{{ number_format($hargaPerJam, 0, ',', '.') }}/jam
              </p>
            </td>
            <td style="text-align: center; padding: 16px 14px; font-size: 13px; vertical-align: top;">
              {{ \Carbon\Carbon::parse($booking->tanggal)->format('d/m/Y') }} <br>
              <span style="color: var(--text-secondary, #64748b); font-size: 12px;">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</span>
            </td>
            <td style="text-align: right; padding: 16px 14px; font-weight: 700; font-size: 14px; vertical-align: top;">
              Rp{{ number_format($subtotal, 0, ',', '.') }}
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Rincian Total -->
      <div style="display: flex; justify-content: flex-end; border-top: 2px solid var(--border-color, #e2e8f0); padding-top: 16px;">
        <div style="width: 280px;">

          <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
            <span style="color: var(--text-secondary, #64748b);">Subtotal ({{ number_format($durasiJam, 1) }} jam)</span>
            <span style="font-weight: 600;">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
          </div>

          <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
            <span style="color: var(--text-secondary, #64748b);">Pajak (0%)</span>
            <span style="font-weight: 600;">Rp0</span>
          </div>

          <div style="display: flex; justify-content: space-between; border-top: 1px solid var(--border-color, #e2e8f0); padding-top: 10px; margin-top: 8px;">
            <span style="font-weight: 800; font-size: 16px;">Total</span>
            <span style="font-weight: 800; font-size: 18px; color: var(--text-primary, #0f172a);">
              Rp{{ number_format($booking->harga_total ?? $subtotal, 0, ',', '.') }}
            </span>
          </div>

        </div>
      </div>

      <!-- Tombol Aksi -->
      <div class="action-buttons no-print" style="margin-top: 36px; display: flex; gap: 12px; justify-content: flex-end;">
        <a href="{{ route('user.dashboard') }}" 
           onclick="if (history.length > 1 && document.referrer.includes(window.location.host)) { history.back(); return false; }" 
           class="btn-action btn-secondary-custom">
          Kembali
        </a>
        
        <button type="button" class="btn-action btn-primary-custom" onclick="window.print()">
          Cetak / Simpan PDF
        </button>
      </div>

    </div>
  </div>

</body>
</html>
