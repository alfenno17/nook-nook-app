<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  @include('partials.theme')

  <title>NookNook - Temukan Ruangan Terbaik</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dafru.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
  <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
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

      <section class="listing-hero">
        <span class="section-badge">Katalog Ruangan</span>
        <h1 class="listing-title">Temukan Ruangan <span class="gradient-text">Terbaik</span></h1>
        <p class="listing-subtitle">Booking ruangan dengan mudah untuk kebutuhan tim atau acara Anda.</p>

        <form action="{{ route('ruangan.daftar') }}" method="GET" class="search-box">
          <svg class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input type="text" name="search" class="search-input" placeholder="Cari ruangan berdasarkan nama atau lokasi..." value="{{ request('search') }}">
        </form>
      </section>

      <section class="listing-section">
        <div class="section-header">
          <span class="section-badge">Kategori</span>
          <h2 class="section-title">Jelajahi Berdasarkan Jenis</h2>
        </div>
        <div class="category-grid">
          @php
            $kategori = [
              ['nama' => 'Meeting',   'icon' => '💼', 'gambar' => 'image/images (1).jpeg'],
              ['nama' => 'Coworking', 'icon' => '💻', 'gambar' => 'image/images (2).jpeg'],
              ['nama' => 'Event',     'icon' => '🎉', 'gambar' => 'image/images.jpeg'],
              ['nama' => 'Private',   'icon' => '🔒', 'gambar' => 'image/images3.jpeg'],
            ];
          @endphp

          @foreach ($kategori as $kat)
            <a href="{{ route('ruangan.daftar', ['category' => $kat['nama']]) }}"
              class="category-card"
              style="position:relative; overflow:hidden; min-height:160px; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding:24px 16px; border-radius:12px; background-image:url('{{ asset($kat['gambar']) }}'); background-size:cover; background-position:center; color:#ffffff;">
              <div style="position:absolute; inset:0; background:rgba(0,0,0,0.45);"></div>
              <div style="position:relative; z-index:2; display:flex; flex-direction:column; align-items:center; gap:10px;">
                <div class="category-icon">{{ $kat['icon'] }}</div>
                <span>{{ $kat['nama'] }}</span>
              </div>
            </a>
          @endforeach
        </div>
      </section>

      <section class="listing-section">
        <div class="section-header">
          <span class="section-badge">Rekomendasi</span>
          <h2 class="section-title">Rekomendasi Ruangan</h2>
          <p class="section-subtitle">Pilih ruangan yang paling sesuai dengan kebutuhan Anda.</p>
        </div>

        <div class="room-list">
          @if(isset($ruangan) && $ruangan->count() > 0)
            @foreach ($ruangan as $room)
              {{-- Seluruh Kartu Diklik Masuk ke Detail --}}
              <a href="{{ route('ruangan.detail', $room) }}" class="room-card">
                <div class="room-image-wrap">
                  @php
                    $fotoRaw = $room->foto ?? null;
                    $fotoPertama = null;

                    if (is_string($fotoRaw) && trim($fotoRaw) !== '') {
                      $trimmed = trim($fotoRaw);
                      if (str_starts_with($trimmed, '[')) {
                        $decoded = json_decode($trimmed, true);
                        if (is_array($decoded) && count($decoded) > 0) {
                          $fotoPertama = $decoded[0];
                        }
                      } else {
                        $fotoPertama = $trimmed;
                      }
                    } elseif (is_array($fotoRaw) && count($fotoRaw) > 0) {
                      $fotoPertama = $fotoRaw[0];
                    }
                    $urlFoto = null;
                    if ($fotoPertama) {
                      if (str_starts_with($fotoPertama, 'http')) {
                        $urlFoto = $fotoPertama;
                      } elseif (str_starts_with($fotoPertama, 'image/') || str_starts_with($fotoPertama, 'images/')) {
                        $urlFoto = asset($fotoPertama);
                      } else {
                        $urlFoto = asset('storage/' . $fotoPertama);
                      }
                    }
                  @endphp

                  @if($urlFoto)
                    <img src="{{ $urlFoto }}" alt="{{ $room->nama }}">
                  @else
                    <div class="room-image-placeholder">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      <span>Image Not Found</span>
                    </div>
                  @endif
                </div>

                {{-- Sisi Kanan: Detail Keterangan --}}
                <div class="room-content">
                  <h3 class="room-name">{{ $room->nama }}</h3>
                  
                  <p class="room-info">Kapasitas: {{ $room->kapasitas ?? '-' }} orang</p>
                  
                  <p class="room-price">
                    Rp {{ number_format($room->harga_per_jam ?? 0, 0, ',', '.') }} / jam
                  </p>

                  {{-- Badge Status --}}
                  @if(($room->status ?? 'tersedia') === 'terisi')
                    <div class="room-badge terisi">Terisi</div>
                  @else
                    <div class="room-badge">Tersedia</div>
                  @endif

                  {{-- Tombol Lihat Detail --}}
                  <div class="btn-room">Lihat Detail</div>
                </div>

              </a>
            @endforeach
          @else
            <div class="empty-state">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <h3>Tidak ada ruangan ditemukan</h3>
              <p>Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
            </div>
          @endif
        </div>
      </section>

    </div>
  </main>

@include('partials.footer')
</body>
</html>
