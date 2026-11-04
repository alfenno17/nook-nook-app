<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  {{-- Anti-flicker + theme css --}}
  @include('partials.theme')

  <title>Dashboard Pengguna - NookNook</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dashboardpengguna.css') }}">
  <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
  @vite(['resources/js/app.js'])

  <style>
    body {
      margin: 0;
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: var(--bg-body, #F8FAFC);
      color: var(--text-primary, #0F172A);
    }

    .dashboard-container {
      display: flex;
      width: 100%;
      min-height: 100vh;
    }

    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
      padding: 32px;
      gap: 28px;
    }

    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .section-badge {
      display: inline-block;
      padding: 4px 12px;
      background: #E0E7FF;
      color: #3730A3;
      border-radius: 9999px;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 8px;
    }

    .page-title {
      font-size: 28px;
      font-weight: 800;
      color: var(--text-primary, #0F172A);
      margin: 0 0 4px 0;
      letter-spacing: -0.02em;
    }

    .page-subtitle {
      color: #64748B;
      font-size: 14px;
      margin: 0;
    }

    /* Metrics Grid */
    .metrics-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }

    .metric-card {
      background: var(--bg-card, #ffffff);
      border: 1px solid var(--border-color, #E5E7EB);
      border-radius: 16px;
      padding: 20px 24px;
      display: flex;
      align-items: center;
      gap: 16px;
      box-shadow: 0 1px 3px rgba(15,23,42,0.04);
    }

    .metric-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }

    .icon-indigo { background: #EEF2FF; }
    .icon-emerald { background: #ECFDF5; }
    .icon-pink { background: #FDF2F8; }

    .metric-label {
      display: block;
      font-size: 13px;
      color: #64748B;
      font-weight: 600;
    }

    .metric-value {
      font-size: 24px;
      font-weight: 800;
      color: var(--text-primary, #0F172A);
    }

    /* Data Layout Grid */
    .data-grid {
      display: grid;
      grid-template-columns: 1fr 300px;
      gap: 24px;
      align-items: start;
    }

    @media (max-width: 1024px) {
      .data-grid {
        grid-template-columns: 1fr;
      }
    }

    .card {
      background: var(--bg-card, #ffffff);
      border: 1px solid var(--border-color, #E5E7EB);
      border-radius: 16px;
      box-shadow: 0 1px 3px rgba(15,23,42,0.04);
      overflow: hidden;
    }

    .card-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--border-color, #E5E7EB);
    }

    .card-title {
      font-size: 18px;
      font-weight: 800;
      margin: 0;
      color: var(--text-primary, #0F172A);
    }

    /* Table & Scroll */
    .table-wrapper {
      max-height: 400px;
      overflow-y: auto;
    }

    .booking-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
      text-align: left;
    }

    .booking-table th {
      position: sticky;
      top: 0;
      z-index: 10;
      background: #F8FAFC;
      padding: 14px 20px;
      font-weight: 700;
      color: #64748B;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      border-bottom: 1px solid var(--border-color, #E5E7EB);
    }

    .booking-table td {
      padding: 14px 20px;
      border-bottom: 1px solid #F1F5F9;
      vertical-align: middle;
      color: var(--text-primary, #0F172A);
    }

    .booking-table tr:hover {
      background-color: #FAFAFA;
    }

    /* Badges */
    .badge {
      padding: 4px 12px;
      border-radius: 9999px;
      font-size: 12px;
      font-weight: 700;
      display: inline-block;
    }

    .badge-info { background: #CFFAFE; color: #155E75; }
    .badge-warning { background: #FEF3C7; color: #92400E; }
    .badge-success { background: #DCFCE7; color: #15803D; }
    .badge-danger { background: #FEE2E2; color: #991B1B; }
    .badge-neutral { background: #F1F5F9; color: #475569; }

    .btn-bayar {
      background-color: #0F766E;
      color: white;
      border: none;
      padding: 6px 14px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn-bayar:hover {
      background-color: #0d655e;
    }

    /* Profile Card Side */
    .profile-card {
      padding: 28px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #3B82F6;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      font-weight: 800;
      margin-bottom: 16px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    }

    .avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .user-name {
      font-size: 18px;
      font-weight: 800;
      color: var(--text-primary, #0F172A);
      margin-bottom: 4px;
    }

    .user-email {
      font-size: 13px;
      color: #64748B;
      margin-bottom: 20px;
    }

    .btn-outline {
      display: inline-block;
      width: 100%;
      padding: 10px 0;
      border: 1px solid var(--border-color, #CBD5E1);
      border-radius: 10px;
      color: var(--text-primary, #0F172A);
      text-decoration: none;
      font-weight: 700;
      font-size: 14px;
      transition: all 0.2s;
    }

    .btn-outline:hover {
      background: #F1F5F9;
    }

    .empty-row {
      text-align: center;
      padding: 40px !important;
      color: #94A3B8;
    }
  </style>
</head>
<body>

  <div class="dashboard-container">
    @include('partials.sidebar')

    <main class="main-content">

      <header class="page-header">
        <div>
          <span class="section-badge">Dashboard</span>
          <h1 class="page-title">Halo, {{ Auth::user()->name }} 👋</h1>
          <p class="page-subtitle">Berikut ringkasan aktivitas booking Anda.</p>
        </div>
      </header>

      {{-- Metrics Section --}}
      <section class="metrics-grid">
        <div class="metric-card">
          <div class="metric-icon icon-indigo">📅</div>
          <div>
            <span class="metric-label">Booking Aktif</span>
            <span class="metric-value">{{ $bookingAktif ?? 0 }}</span>
          </div>
        </div>
        <div class="metric-card">
          <div class="metric-icon icon-emerald">🕓</div>
          <div>
            <span class="metric-label">Riwayat</span>
            <span class="metric-value">{{ $totalRiwayat ?? 0 }}</span>
          </div>
        </div>
        <div class="metric-card">
          <div class="metric-icon icon-pink">❤️</div>
          <div>
            <span class="metric-label">Favorit</span>
            <span class="metric-value">{{ $favorit ?? 0 }}</span>
          </div>
        </div>
      </section>

      {{-- Data Layout Grid --}}
      <div class="data-grid">

        {{-- Tabel Riwayat Booking --}}
        <section class="card">
          <div class="card-header">
            <h2 class="card-title">Riwayat Booking</h2>
          </div>
          <div class="table-wrapper">
            <table class="booking-table">
              <thead>
                <tr>
                  <th>Ruangan</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($riwayat_booking ?? [] as $booking)
                  <tr>
                    <td><strong>{{ $booking->ruangan->nama ?? 'N/A' }}</strong></td>
                    <td>{{ isset($booking->tanggal) ? \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') : '-' }}</td>
                    <td>
                      <span class="badge
                        @if(($booking->status ?? '') == 'disetujui') badge-info
                        @elseif(($booking->status ?? '') == 'menunggu_konfirmasi_admin') badge-warning
                        @elseif(($booking->status ?? '') == 'menunggu_verifikasi') badge-warning
                        @elseif(($booking->status ?? '') == 'dibayar') badge-success
                        @elseif(($booking->status ?? '') == 'selesai') badge-info
                        @elseif(($booking->status ?? '') == 'dibatalkan') badge-danger
                        @else badge-neutral @endif">
                        {{ ucfirst(str_replace('_', ' ', $booking->status ?? '-')) }}
                      </span>
                    </td>
                    <td>
                      @if(($booking->status ?? '') == 'disetujui')
                        <form action="{{ route('pembayaran', $booking->id) }}" method="GET" style="display:inline;">
                          @csrf
                          <button type="submit" class="btn-bayar" onclick="return confirm('Lanjut membayar?')">Bayar</button>
                        </form>
                      @else
                        <span style="color:#94a3b8;">-</span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="empty-row">Belum ada riwayat booking</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </section>

        {{-- Kartu Profil Pengguna --}}
        <section class="card profile-card">
          <div class="avatar">
            @if(Auth::user()->avatar)
              <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar">
            @else
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            @endif
          </div>
          <div class="user-name">{{ Auth::user()->name }}</div>
          <div class="user-email">{{ Auth::user()->email }}</div>

          <a href="{{ route('user.pengaturan') }}" class="btn-outline">Edit Profil</a>
        </section>

      </div>
    </main>
  </div>

</body>
</html>
