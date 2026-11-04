<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; color: #1e293b; padding: 20px; }
        .card { background: #ffffff; border-radius: 12px; padding: 24px; max-width: 550px; margin: 0 auto; border: 1px solid #e2e8f0; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .btn { display: inline-block; padding: 10px 20px; background: #0F766E; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Halo, {{ $booking->user->name }}!</h2>

        @if($statusType === 'menunggu_pembayaran')
            <p>Booking Anda telah dibuat. Silakan lakukan pembayaran untuk menyelesaikan proses booking.</p>
            <span class="badge badge-warning">Menunggu Pembayaran</span>
        @elseif($statusType === 'menunggu_konfirmasi')
            <p>Bukti pembayaran telah diterima dan sedang dalam proses konfirmasi oleh Admin.</p>
            <span class="badge badge-info">Menunggu Konfirmasi Admin</span>
        @elseif($statusType === 'berhasil')
            <p>Selamat! Booking Anda telah **Disetujui/Berhasil**. Silakan tunjukkan email ini saat datang ke lokasi.</p>
            <span class="badge badge-success">Booking Berhasil</span>
        @endif

        <div style="margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 14px;">
            <p><strong>Kode Booking:</strong> #{{ $booking->kode }}</p>
            <p><strong>Ruangan:</strong> {{ $booking->ruangan->nama ?? $booking->ruangan->nama_ruangan }}</p>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}</p>
            <p><strong>Jam:</strong> {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</p>
        </div>

        <a href="{{ route('invoice', $booking->id) }}" class="btn">Lihat Invoice</a>
    </div>
</body>
</html>
