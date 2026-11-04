<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  {{-- TAMBAHAN: Anti-flicker + theme css --}}
  @include('partials.theme')

  <title>Detail Ruangan - {{ $room->nama }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  {{-- CSS detail ruangan (paling akhir agar menang dari CSS lain) --}}
  <link rel="stylesheet" href="{{ asset('css/detailruangan.css') }}">
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
      <span class="current">{{ $room->nama }}</span>
    </nav>

    <section class="room-overview">

      <div class="room-gallery-wrap">

        @php
          $fotoRaw = $room->foto ?? null;
          $fotos = [];

          if (is_string($fotoRaw) && trim($fotoRaw) !== '') {
            $trimmed = trim($fotoRaw);
            if (str_starts_with($trimmed, '[')) {
              $decoded = json_decode($trimmed, true);
              if (is_array($decoded)) {
                $fotos = array_values(array_filter($decoded));
              }
            } else {
              $fotos = [$trimmed];
            }
          } elseif (is_array($fotoRaw)) {
            $fotos = array_values(array_filter($fotoRaw));
          }

          $mainFoto = $fotos[0] ?? null;
          $mainFotoUrl = $mainFoto ? asset($mainFoto) : null;
        @endphp

        <div class="room-gallery-main">
          @if($mainFotoUrl)
            <img id="mainImageSrc" src="{{ $mainFotoUrl }}" alt="{{ $room->nama }}" onerror="replaceWithPlaceholder(this)">
          @else
            <div class="gallery-placeholder">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              <span>Foto Ruangan Belum Tersedia</span>
            </div>
          @endif

          <span class="gallery-badge">Tersedia</span>
        </div>

        @if(count($fotos) > 1)
          <div class="room-gallery-thumbs">
            @foreach($fotos as $index => $foto)
              <button type="button" class="thumb {{ $index === 0 ? 'active' : '' }}" data-src="{{ asset($foto) }}" onclick="switchImage(this)">
                <img src="{{ asset($foto) }}" alt="Foto {{ $index + 1 }}">
              </button>
            @endforeach
          </div>
        @endif

      </div>

      <div class="room-info-card">

        <div class="room-header">
          <span class="section-badge">Detail Ruangan</span>
          <h1 class="room-title">{{ $room->nama }}</h1>
          <p class="room-location">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ $room->gedung ?? 'Gedung' }} • Lantai {{ $room->lantai ?? '-' }}
          </p>
        </div>

        <div class="room-meta-grid">
          <div class="meta-item">
            <span class="meta-label">Kapasitas</span>
            <span class="meta-value">{{ $room->kapasitas ?? '-' }} Orang</span>
          </div>
          <div class="meta-item">
            <span class="meta-label">Status</span>
            <span class="meta-value status-aktif">{{ ucfirst($room->status ?? 'aktif') }}</span>
          </div>
        </div>

        <div class="facilities-section">
          <span class="facilities-title">Fasilitas</span>
          <ul class="facilities-list">
            @forelse($room->fasilitas ?? [] as $fasilitas)
              <li class="facility-item">
                <span class="facility-dot"></span>
                {{ is_object($fasilitas) ? $fasilitas->nama : $fasilitas }}
              </li>
            @empty
              <li class="facility-item">
                <span class="facility-dot"></span>
                Belum ada data fasilitas
              </li>
            @endforelse
          </ul>
        </div>

        <div class="booking-action">
          <div class="price-tag">
            Rp{{ number_format($room->harga_per_jam ?? 0, 0, ',', '.') }}
            <span class="price-unit">/ jam</span>
          </div>
          <button type="button" class="btn-booking" onclick="window.location='{{ route('booking.ruangan', $room->id) }}'">
            Booking Sekarang
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </button>
        </div>

      </div>
    </section>

    <section class="room-description-card">
      <h2 class="description-title">Deskripsi Ruangan</h2>
      <p class="description-text">{{ $room->deskripsi ?? 'Belum ada deskripsi untuk ruangan ini.' }}</p>
    </section>

  </div>
</main>

<script>
  function switchImage(el) {
    const src = el.getAttribute('data-src');
    const wrap = document.querySelector('.room-gallery-main');
    if (!wrap || !src) return;
    let img = document.getElementById('mainImageSrc');
    if (!img) {
      wrap.insertAdjacentHTML('afterbegin', '<img id="mainImageSrc" src="' + src + '" alt="Foto" onerror="replaceWithPlaceholder(this)">');
    } else {
      img.src = src;
      img.style.display = '';
    }
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
  }

  function replaceWithPlaceholder(img) {
    const wrap = img.closest('.room-gallery-main');
    if (!wrap) return;
    const placeholder = document.createElement('div');
    placeholder.className = 'gallery-placeholder';
    placeholder.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span>Gambar tidak dapat dimuat</span>';
    img.replaceWith(placeholder);
  }
</script>

</body>
</html>