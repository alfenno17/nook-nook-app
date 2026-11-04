<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  {{-- Anti-flicker + theme css --}}
  @include('partials.theme')

  <title>Laporan Booking - NookNook</title>

  {{-- CSS Asli Aplikasi --}}
  <link rel="stylesheet" href="{{ asset('css/sidebaradmin.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/laporanadmin.css') }}" />

  <style>
    /* Tambahan khusus untuk Sticky Header dan Scrollable Table */
    .table-card {
      max-height: calc(100vh - 320px);
      min-height: 350px;
      overflow-y: auto;
      overflow-x: auto;
      position: relative;
    }

    .table-card table th {
      position: sticky;
      top: 0;
      z-index: 10;
      background-color: #F8FAFC; /* Menjaga header tetap solid saat di-scroll */
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
  </style>
</head>
<body>

<div class="layout">
  @include('partials.sidebaradmin')

  <div class="main-area">
    <header class="page-header">
      <h1 class="page-title">Laporan Booking</h1>
    </header>

    <main class="content-body">

      <!-- Filter Form -->
      <form method="GET" action="{{ route('admin.laporan') }}" class="filter-form">
        <div class="filter-group">
          <!-- Filter Bulan -->
          <select name="bulan" class="filter-select">
            <option value="">Semua Bulan</option>
            @foreach(range(1, 12) as $m)
              <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
              </option>
            @endforeach
          </select>

          <!-- Filter Tahun -->
          <select name="tahun" class="filter-select">
            <option value="">Semua Tahun</option>
            <option value="2029" {{ request('tahun') == '2029' ? 'selected' : '' }}>2029</option>
            <option value="2028" {{ request('tahun') == '2028' ? 'selected' : '' }}>2028</option>
            <option value="2027" {{ request('tahun') == '2027' ? 'selected' : '' }}>2027</option>
            <option value="2026" {{ request('tahun') == '2026' ? 'selected' : '' }}>2026</option>
            <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>2025</option>
          </select>

          <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>

        <button type="button" onclick="window.print()" class="btn btn-export">Export PDF</button>
      </form>

      <!-- Metrics Dynamic -->
      <div class="metrics-grid">
        <div class="metric-card">
          <span class="metric-label">Total Booking</span>
          <span class="metric-value large">{{ $totalBooking ?? 0 }}</span>
        </div>

        <div class="metric-card">
          <span class="metric-label">Pendapatan</span>
          <span class="metric-value">Rp{{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</span>
        </div>

        <div class="metric-card">
          <span class="metric-label">Ruangan Terpakai</span>
          <span class="metric-value large">{{ $ruanganTerpakai ?? 0 }}</span>
        </div>
      </div>

      <!-- Report Table Scrollable + Sticky Header -->
      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>KODE</th>
              <th>TANGGAL</th>
              <th>RUANGAN</th>
              <th>PENYEWA</th>
              <th class="text-right">TOTAL</th>
            </tr>
          </thead>
          <tbody>
            @forelse($bookings as $booking)
              <tr>
                <td>
                  <strong>{{ $booking->kode ?? '#BK-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</strong>
                </td>
                <td>
                  {{ \Carbon\Carbon::parse($booking->tanggal_booking ?? $booking->tanggal)->format('d-m-Y') }}
                </td>
                <td>
                  {{ $booking->ruangan->nama ?? $booking->ruangan->nama_ruangan ?? '-' }}
                </td>
                <td>
                  {{ $booking->pengguna->name ?? $booking->user->name ?? 'User' }}
                </td>
                <td class="text-right">
                  <strong>Rp{{ number_format($booking->harga_total ?? $booking->total_harga ?? 0, 0, ',', '.') }}</strong>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="empty-row">Tidak ada data booking.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </main>
  </div>
</div>

</body>
</html>
