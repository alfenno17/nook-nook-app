<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  @include('partials.theme')

  @php
    // Normalisasi variabel agar aman jika controller mengirim $room atau $ruangan
    $roomData = $room ?? $ruangan ?? null;
  @endphp

  <title>Detail Ruangan - {{ $roomData->nama_ruangan ?? $roomData->nama ?? 'Ruangan' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/detailruangan.css') }}">
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

    /* Grid Utama Terkunci (Paten 500px) */
    .room-overview {
      display: grid;
      grid-template-columns: 1fr 420px;
      gap: 24px;
      height: 500px; /* Locked Total Height */
    }

    @media (max-width: 992px) {
      .room-overview {
        grid-template-columns: 1fr;
        height: auto;
      }
    }

    /* Container Kiri: Foto Utama + Thumbnail */
    .room-gallery-wrap {
      display: flex;
      flex-direction: column;
      height: 500px; /* Locked Height Kiri */
      gap: 12px;
    }

    .room-gallery-main {
      position: relative;
      width: 100%;
      height: 400px; /* Locked Tinggi Foto Utama */
      border-radius: 16px;
      overflow: hidden;
      background: var(--bg-card, #f3f4f6);
      border: 1px solid var(--border-color, #e5e7eb);
    }

    .room-gallery-main img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      cursor: pointer;
      transition: opacity 0.2s;
    }
    .room-gallery-main img:hover {
      opacity: 0.95;
    }

    .gallery-badge {
      position: absolute;
      top: 16px;
      left: 16px;
      background: #10b981;
      color: white;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      z-index: 2;
    }

    /* Thumbnail Terkunci Sempurna */
    .selection-pictures-scroll {
      display: flex;
      gap: 10px;
      height: 88px; /* Locked Tinggi Thumbnail */
      overflow-x: auto;
      scroll-behavior: smooth;
      padding-bottom: 4px;
      flex-shrink: 0;
      -webkit-overflow-scrolling: touch;
    }

    .selection-pictures-scroll::-webkit-scrollbar {
      height: 5px;
    }

    .selection-pictures-scroll::-webkit-scrollbar-thumb {
      background: var(--border-color, #cbd5e1);
      border-radius: 4px;
    }

    .thumb-item {
      flex: 0 0 110px;
      height: 80px;
      border-radius: 8px;
      overflow: hidden;
      border: 2px solid transparent;
      cursor: pointer;
      transition: all 0.2s ease;
      background: #e2e8f0;
    }

    .thumb-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .thumb-item.active, .thumb-item:hover {
      border-color: #0F766E;
      transform: scale(1.02);
    }

    .gallery-placeholder {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: #94a3b8;
      gap: 8px;
      font-size: 13px;
    }
    .gallery-placeholder svg {
      width: 48px;
      height: 48px;
    }

    /* Container Kanan: Detail Card Terkunci Ketinggiannya */
    .room-info-card {
      background: var(--bg-card, #ffffff);
      border: 1px solid var(--border-color, #e5e7eb);
      border-radius: 16px;
      padding: 24px;
      height: 500px; /* Locked Height Kanan sama persis dengan Kiri */
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      box-sizing: border-box;
      overflow: hidden;
    }

    .info-scrollable-body {
      overflow-y: auto;
      padding-right: 4px;
    }
    .info-scrollable-body::-webkit-scrollbar {
      width: 4px;
    }
    .info-scrollable-body::-webkit-scrollbar-thumb {
      background: var(--border-color, #cbd5e1);
      border-radius: 4px;
    }

    .btn-pesan-sekarang {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      width: 100%;
      background-color: #0F766E;
      color: #ffffff;
      padding: 12px 20px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 700;
      border: none;
      cursor: pointer;
      text-decoration: none;
      transition: background-color 0.2s, transform 0.1s;
      box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);
    }
    .btn-pesan-sekarang:hover {
      background-color: #115E59;
      transform: translateY(-1px);
    }

    .date-input-custom {
      background: var(--bg-body, #F8FAFC);
      color: var(--text-primary, #1e293b);
      border: 1px solid var(--border-color, #cbd5e1);
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      font-family: inherit;
    }
    [data-theme="dark"] .date-input-custom {
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
      <span class="current">{{ $roomData->nama_ruangan ?? $roomData->nama ?? 'Ruangan' }}</span>
    </nav>

    <section class="room-overview">

      <!-- Gallery Foto (Kiri - Locked 500px) -->
      <div class="room-gallery-wrap">
        @php
          $fotoRaw = $roomData->foto ?? null;
          $fotosList = [];

          if (!empty($fotos) && is_array($fotos)) {
            $fotosList = $fotos;
          } elseif (is_string($fotoRaw) && trim($fotoRaw) !== '') {
            $trimmed = trim($fotoRaw);
            if (str_starts_with($trimmed, '[')) {
              $decoded = json_decode($trimmed, true);
              if (is_array($decoded)) {
                $fotosList = $decoded;
              }
            } else {
              $fotosList = [$trimmed];
            }
          } elseif (is_array($fotoRaw)) {
            $fotosList = $fotoRaw;
          }

          $fotoPertama = $fotosList[0] ?? null;
          $urlFotoUtama = $primeFotoUrl ?? null;

          if (!$urlFotoUtama && $fotoPertama) {
            if (str_starts_with($fotoPertama, 'http')) {
              $urlFotoUtama = $fotoPertama;
            } elseif (str_starts_with($fotoPertama, 'image/') || str_starts_with($fotoPertama, 'images/')) {
              $urlFotoUtama = asset($fotoPertama);
            } else {
              $urlFotoUtama = asset('storage/' . $fotoPertama);
            }
          }
        @endphp

        <!-- Tampilan Foto Utama (Locked 400px) -->
        <div class="room-gallery-main">
          @if($urlFotoUtama)
            <img id="primeImage" src="{{ $urlFotoUtama }}" alt="{{ $roomData->nama_ruangan ?? $roomData->nama ?? 'Ruangan' }}" onclick="openImageModal(this.src)" onerror="replaceWithPlaceholder(this)">
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

        <!-- Thumbnail Multiple Images (Locked 88px) -->
        @if(count($fotosList) > 1)
          <div class="selection-pictures-scroll">
            @foreach($fotosList as $index => $foto)
              @php
                $thumbUrl = null;
                if (str_starts_with($foto, 'http')) {
                  $thumbUrl = $foto;
                } elseif (str_starts_with($foto, 'image/') || str_starts_with($foto, 'images/')) {
                  $thumbUrl = asset($foto);
                } else {
                  $thumbUrl = asset('storage/' . $foto);
                }
              @endphp
              <div class="thumb-item {{ $index === 0 ? 'active' : '' }}" onclick="selectPrimeImage(this, '{{ $thumbUrl }}')">
                <img src="{{ $thumbUrl }}" alt="Foto Thumbnail {{ $index + 1 }}">
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Card Informasi Ruangan (Kanan - Locked 500px) -->
      <div class="room-info-card">

        <div class="info-scrollable-body">
          <div class="room-header">
            <span class="section-badge">Detail Ruangan</span>
            <h1 class="room-title" style="margin-top: 4px; font-size: 24px;">{{ $roomData->nama_ruangan ?? $roomData->nama ?? 'Ruangan' }}</h1>
            <p class="room-location">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              {{ $roomData->gedung ?? 'Gedung' }}  •  Lantai {{ $roomData->lantai ?? '-' }}
            </p>
          </div>

          <div class="room-meta-grid" style="margin-top: 14px;">
            <div class="meta-item">
              <span class="meta-label">Kapasitas</span>
              <span class="meta-value">{{ $roomData->kapasitas ?? '-' }} Orang</span>
            </div>
          </div>

          <!-- Fasilitas Ruangan -->
          <div style="margin-top: 14px;">
            <h3 style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary, #64748b); margin-bottom: 8px;">Fasilitas Ruangan</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
              @if(!empty($fasilitas) && count($fasilitas) > 0)
                @foreach($fasilitas as $f)
                  <span style="padding: 4px 12px; background: var(--bg-body, #F8FAFC); color: var(--text-primary, #334155); border: 1px solid var(--border-color, #cbd5e1); border-radius: 20px; font-size: 12px; font-weight: 600;">
                    {{ $f->nama ?? $f->nama_fasilitas ?? 'Fasilitas' }}
                  </span>
                @endforeach
              @else
                <span style="font-size: 13px; color: var(--text-secondary, #94a3b8);">Tidak ada fasilitas khusus untuk ruangan ini.</span>
              @endif
            </div>
          </div>

          <!-- Deskripsi Ruangan -->
          <div style="margin-top: 14px;">
            <h3 style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary, #64748b); margin-bottom: 4px;">Deskripsi Ruangan</h3>
            <p style="font-size: 13px; color: var(--text-primary, #334155); line-height: 1.5; margin: 0;">
              {{ $roomData->deskripsi ?? 'Belum ada deskripsi untuk ruangan ini.' }}
            </p>
          </div>
        </div>

        <!-- Tombol Booking di Bagian Paling Bawah -->
        <div class="booking-action" style="margin-top: 16px; border-top: 1px solid var(--border-color, #e5e7eb); padding-top: 14px; flex-shrink: 0;">
          <div class="price-tag" style="margin-bottom: 12px;">
            Rp{{ number_format($roomData->harga_per_jam ?? 0, 0, ',', '.') }}
            <span class="price-unit">/ jam</span>
          </div>
          
          <button type="button" class="btn-pesan-sekarang" onclick="keHalamanBooking()">
            Pesan Sekarang
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </button>
        </div>

      </div>
    </section>

    <!-- Card Jadwal Ketersediaan -->
    <section class="room-description-card" style="margin-top: 24px; background: var(--bg-card); padding: 24px; border-radius: 16px; border: 1px solid var(--border-color);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h2 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0;">Jadwal Ketersediaan</h2>
        
        <!-- Filter Tanggal Auto-Reload -->
        <form method="GET" action="{{ url()->current() }}" id="formTanggal">
          <input type="date" name="tanggal" id="selectedTanggal" value="{{ $tanggal ?? date('Y-m-d') }}" 
                 onchange="document.getElementById('formTanggal').submit()"
                 class="date-input-custom">
        </form>
      </div>

      <!-- Grid Slot Jam 1 Jam-an -->
      <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
        @if(!empty($ketersediaan) && count($ketersediaan) > 0)
          @foreach($ketersediaan as $slot)
            @php
              $isTersedia = (isset($slot->status) && $slot->status === 'tersedia');
              $jamMulai = \Carbon\Carbon::parse($slot->jam_mulai)->format('H.i');
              $jamSelesai = \Carbon\Carbon::parse($slot->jam_selesai)->format('H.i');
              $slotValue = $jamMulai . '–' . $jamSelesai;
            @endphp

            <div class="slot-item {{ $isTersedia ? 'available' : 'booked' }}"
                 data-slot="{{ $slotValue }}"
                 @if($isTersedia) onclick="toggleSlotSelect(this)" @endif
                 style="padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: {{ $isTersedia ? 'pointer' : 'not-allowed' }}; border: 1px solid {{ $isTersedia ? 'rgba(16, 185, 129, 0.4)' : 'rgba(239, 68, 68, 0.4)' }}; background-color: {{ $isTersedia ? 'rgba(16, 185, 129, 0.15)' : 'rgba(239, 68, 68, 0.15)' }}; color: {{ $isTersedia ? '#10b981' : '#f87171' }}; transition: all 0.2s ease;">
              {{ $jamMulai }}–{{ $jamSelesai }}
            </div>
          @endforeach
        @else
          <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">Belum ada jadwal ketersediaan untuk tanggal ini.</p>
        @endif
      </div>

      <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">
        <span style="color: #10b981; font-weight: 600;">Hijau</span> = tersedia (klik untuk memilih), 
        <span style="color: #f87171; font-weight: 600;">Merah</span> = sudah dipesan, 
        <span style="color: #0F766E; font-weight: 600;">Gelap/Teal</span> = terpilih
      </p>
    </section>

  </div>
</main>

@include('partials.footer')

<script>
  function selectPrimeImage(element, imageUrl) {
    const primeImg = document.getElementById('primeImage');
    if (primeImg) {
      primeImg.src = imageUrl;
      primeImg.setAttribute('onclick', `openImageModal('${imageUrl}')`);
    }
    document.querySelectorAll('.thumb-item').forEach(el => el.classList.remove('active'));
    element.classList.add('active');
  }

  function openImageModal(imageUrl) {
    Swal.fire({
      imageUrl: imageUrl,
      imageAlt: 'Detail Gambar Ruangan',
      showCloseButton: true,
      showConfirmButton: false,
      width: 'auto',
      customClass: {
        popup: 'swal2-fullscreen-image-popup'
      },
      background: 'transparent',
      backdrop: 'rgba(0, 0, 0, 0.85)'
    });
  }

  function replaceWithPlaceholder(img) {
    const wrap = img.closest('.room-gallery-main');
    if (!wrap) return;
    const placeholder = document.createElement('div');
    placeholder.className = 'gallery-placeholder';
    placeholder.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span>Gambar tidak dapat dimuat</span>';
    img.replaceWith(placeholder);
  }

  let selectedSlots = [];

  function toggleSlotSelect(element) {
    const slotValue = element.getAttribute('data-slot');

    if (element.classList.contains('selected-slot')) {
      element.classList.remove('selected-slot');
      element.style.backgroundColor = 'rgba(16, 185, 129, 0.15)';
      element.style.color = '#10b981';
      element.style.borderColor = 'rgba(16, 185, 129, 0.4)';
      selectedSlots = selectedSlots.filter(s => s !== slotValue);
    } else {
      element.classList.add('selected-slot');
      element.style.backgroundColor = '#0F766E';
      element.style.color = '#ffffff';
      element.style.borderColor = '#0F766E';
      selectedSlots.push(slotValue);
    }
  }

  function keHalamanBooking() {
    const tanggal = document.getElementById('selectedTanggal').value;
    const bookingUrl = "{{ route('booking.ruangan', $roomData->id ?? $roomData) }}";
    
    let queryParams = new URLSearchParams();
    queryParams.append('tanggal', tanggal);

    selectedSlots.forEach(slot => {
      queryParams.append('jam[]', slot);
    });

    window.location.href = `${bookingUrl}?${queryParams.toString()}`;
  }
</script>

</body>
</html>
