<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

{{-- Anti-flicker + theme css --}}
@include('partials.theme')

<title>Dashboard Admin - NookNook</title>
<link rel="stylesheet" href="{{ asset('css/sidebaradmin.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { 
        margin: 0; 
        background: #F8FAFC; 
        font-family: 'Plus Jakarta Sans', 'Segoe UI', Tahoma, sans-serif; 
        color: #0F172A; 
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
    }
    .top-header { 
        height: 80px; 
        background-color: #ffffff; 
        border-bottom: 1px solid #E5E7EB; 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        padding: 0 32px; 
    }
    .header-title { 
        font-size: 24px; 
        font-weight: 800; 
        color: #0F172A; 
        margin: 0; 
        letter-spacing: -0.02em; 
    }
    .content-body { 
        padding: 32px; 
        display: flex; 
        flex-direction: column; 
        gap: 32px; 
        flex: 1;
    }
    .metrics-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
        gap: 20px; 
    }
    .metric-card { 
        background-color: #ffffff; 
        border: 1px solid #E5E7EB; 
        border-radius: 20px; 
        padding: 24px; 
        display: flex; 
        flex-direction: column; 
        gap: 8px; 
        box-shadow: 0 1px 3px rgba(15,23,42,0.04); 
        transition: all 0.25s ease; 
    }
    .metric-card:hover { 
        transform: translateY(-3px); 
        box-shadow: 0 8px 24px rgba(15,23,42,0.06); 
        border-color: #C7D2FE; 
    }
    .metric-label { font-size: 13px; color: #64748B; font-weight: 500; }
    .metric-value { font-size: 28px; font-weight: 800; color: #0F172A; letter-spacing: -0.02em; }
    
    /* SCROLLABLE TABLE CONTAINMENT */
    .table-wrapper { 
        background: white; 
        border-radius: 20px; 
        border: 1px solid #E5E7EB; 
        box-shadow: 0 1px 3px rgba(15,23,42,0.04); 
        max-height: calc(100vh - 300px); 
        min-height: 350px;
        overflow-y: auto; 
        overflow-x: auto; 
    }
    .table-wrapper table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .table-wrapper th { 
        position: sticky;
        top: 0;
        z-index: 10;
        background: #F8FAFC; 
        padding: 14px 20px; 
        text-align: left; 
        font-weight: 600; 
        color: #64748B; 
        font-size: 12px; 
        text-transform: uppercase; 
        letter-spacing: 0.06em; 
        border-bottom: 1px solid #E5E7EB; 
    }
    .table-wrapper td { padding: 14px 20px; border-bottom: 1px solid #F1F5F9; vertical-align: middle; }
    .table-wrapper tbody tr:hover { background: #FAFAFA; }
    .table-wrapper tbody tr:last-child td { border-bottom: none; }
    
    .badge-status { padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; display: inline-block; }
    .badge-menunggu { background: #FEF3C7; color: #92400E; }
    .badge-menunggu-verifikasi { background: #FDE68A; color: #B45309; }
    .badge-menunggu-konfirmasi { background: #E0E7FF; color: #3730A3; }
    .badge-disetujui { background: #CFFAFE; color: #155E75; }
    .badge-dibayar { background: #DBEAFE; color: #1E40AF; }
    .badge-selesai { background: #DCFCE7; color: #15803D; }
    .badge-dibatalkan { background: #FEE2E2; color: #991B1B; }
    .empty-state { text-align: center; padding: 40px; color: #94A3B8; }
    .btn-verifikasi { background-color: #10B981; color: white; border: none; padding: 6px 14px; border-radius: 8px; font-size: 12px; cursor: pointer; font-weight: 600; transition: all 0.2s; }
    .btn-verifikasi:hover { background-color: #059669; transform: translateY(-1px); }
    .btn-konfirmasi { background-color: #6366F1; color: white; border: none; padding: 6px 14px; border-radius: 8px; font-size: 12px; cursor: pointer; font-weight: 600; transition: all 0.2s; margin-right: 6px; }
    .btn-konfirmasi:hover { background-color: #4F46E5; transform: translateY(-1px); }
    .btn-delete { background-color: #FEE2E2; color: #991B1B; border: none; padding: 6px 14px; border-radius: 8px; font-size: 12px; cursor: pointer; font-weight: 600; transition: all 0.2s; margin-left: 6px; }
    .btn-delete:hover { background-color: #FECACA; }
    .bukti-thumbnail { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #E5E7EB; cursor: pointer; transition: transform 0.2s; }
    .bukti-thumbnail:hover { transform: scale(1.1); }
    .btn-pdf { display: inline-block; padding: 6px 12px; background: #E2E8F0; color: #334155; border-radius: 6px; font-size: 11px; font-weight: 700; text-decoration: none; }
    .btn-pdf:hover { background: #CBD5E1; }
    .bukti-empty { color: #94A3B8; font-size: 12px; }
    @media (max-width: 768px) { .top-header { padding: 0 20px; height: 64px; } .header-title { font-size: 20px; } .content-body { padding: 20px; } .metrics-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 480px) { .metrics-grid { grid-template-columns: 1fr; } }
</style>
</head>
<body>
<div class="dashboard-container">
    @include('partials.sidebaradmin')

    <main class="main-content">
        <header class="top-header">
            <h1 class="header-title">Dashboard Admin</h1>
        </header>

        <div class="content-body">
            {{-- METRICS --}}
            <section class="metrics-grid">
            <div class="metric-card">
                <span class="metric-label">Total Ruangan</span>
                <span class="metric-value">{{ $totalRuangan ?? 0 }}</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Booking Hari Ini</span>
                <span class="metric-value">{{ $bookingHariIni ?? 0 }}</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Pengguna</span>
                <span class="metric-value">{{ $totalPengguna ?? 0 }}</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Pendapatan</span>
                <span class="metric-value">Rp{{ number_format($pendapatan ?? 0, 0, ',', '.') }}</span>
            </div>
            </section>

            {{-- TABLE BOOKING AKTIF (SCROLLABLE) --}}
            <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Ruangan</th>
                    <th>Penyewa</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($bookingAktif as $index => $booking)
                    <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $booking->ruangan->nama ?? 'Tidak diketahui' }}</strong></td>
                    <td>{{ $booking->user->name ?? 'Tidak diketahui' }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}</td>
                    <td>{{ $booking->keperluan ?? '-' }}</td>
                    <td>
                        <span class="badge-status
                        @if($booking->status == 'menunggu_konfirmasi_admin') badge-menunggu-konfirmasi
                        @elseif($booking->status == 'disetujui') badge-disetujui
                        @elseif($booking->status == 'menunggu_pembayaran') badge-menunggu
                        @elseif($booking->status == 'menunggu_verifikasi') badge-menunggu-verifikasi
                        @elseif($booking->status == 'dibayar') badge-dibayar
                        @elseif($booking->status == 'selesai') badge-selesai
                        @elseif($booking->status == 'dibatalkan') badge-dibatalkan
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </td>

                    {{-- BUKTI PEMBAYARAN --}}
                    <td>
                        @if($booking->pembayaran && $booking->pembayaran->bukti)
                            @php
                                $extension = pathinfo($booking->pembayaran->bukti, PATHINFO_EXTENSION);
                            @endphp

                            @if(strtolower($extension) === 'pdf')
                                <a href="{{ route('admin.booking.bukti', $booking->id) }}" target="_blank" class="btn-pdf">📄 Lihat PDF</a>
                            @else
                                <a href="{{ route('admin.booking.bukti', $booking->id) }}" target="_blank">
                                    <img src="{{ route('admin.booking.bukti', $booking->id) }}" alt="Bukti Pembayaran" class="bukti-thumbnail">
                                </a>
                            @endif
                        @else
                            <span class="bukti-empty">Belum ada</span>
                        @endif
                    </td>

                    <td>
                        @if($booking->status == 'menunggu_konfirmasi_admin')
                            <form action="{{ route('admin.konfirmasi', $booking->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-konfirmasi" onclick="return confirm('Konfirmasi booking ini?')">Konfirmasi Booking</button>
                            </form>
                        @endif

                        @if($booking->status == 'menunggu_verifikasi')
                            <form action="{{ route('admin.verifikasi', $booking->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-verifikasi" onclick="return confirm('Verifikasi pembayaran ini?')">Konfirmasi</button>
                            </form>
                        @endif

                        <form action="{{ route('admin.booking.delete', $booking->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Yakin ingin menghapus booking ini?')">Hapus</button>
                        </form>
                    </td>
                    </tr>
                @empty
                    <tr>
                    <td colspan="9" class="empty-state">Tidak ada booking aktif saat ini.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>
