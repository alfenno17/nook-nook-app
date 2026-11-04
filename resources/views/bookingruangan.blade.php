<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  @include('partials.theme')

  <title>Booking Ruangan - NookNook</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/bookingru.css') }}">
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

    /* Style Slot Jam Interaktif */
    .time-slot-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 6px;
    }

    .time-slot-btn {
      padding: 8px 14px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      border: 1px solid var(--border-color, #cbd5e1);
      background-color: var(--bg-body, #F8FAFC);
      color: var(--text-primary, #334155);
      cursor: pointer;
      transition: all 0.2s ease;
      user-select: none;
    }

    .time-slot-btn:hover {
      border-color: #0F766E;
      color: #0F766E;
    }

    .time-slot-btn.selected {
      background-color: #0F766E !important;
      color: #ffffff !important;
      border-color: #0F766E !important;
      box-shadow: 0 2px 6px rgba(15, 118, 110, 0.3);
    }

    [data-theme="dark"] .time-slot-btn {
      background-color: #1e293b;
      border-color: #334155;
      color: #f1f5f9;
    }

    .selected-time-info {
      font-size: 13px;
      font-weight: 600;
      color: #0F766E;
      margin-top: 8px;
      padding: 8px 12px;
      background: rgba(15, 118, 110, 0.1);
      border-radius: 6px;
      display: none;
    }

    /* Multi-Image Slider Style */
    .slider-container {
      position: relative;
      width: 100%;
      height: 220px;
      overflow: hidden;
      border-radius: 12px 12px 0 0;
      background-color: #f1f5f9;
    }

    .slider-slide {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0;
      transition: opacity 0.35s ease-in-out;
      pointer-events: none;
    }

    .slider-slide.active {
      opacity: 1;
      pointer-events: auto;
    }

    .slider-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: rgba(15, 23, 42, 0.55);
      color: #ffffff;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s, transform 0.2s;
      z-index: 10;
    }

    .slider-btn:hover {
      background: rgba(15, 23, 42, 0.85);
      transform: translateY(-50%) scale(1.08);
    }

    .slider-btn.prev-btn { left: 10px; }
    .slider-btn.next-btn { right: 10px; }

    .slider-dots {
      position: absolute;
      bottom: 10px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 6px;
      z-index: 10;
    }

    .slider-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.5);
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .slider-dot.active {
      background: #ffffff;
      width: 18px;
      border-radius: 8px;
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
      <a href="{{ route('ruangan.detail', $ruangan->id ?? 1) }}">{{ $ruangan->nama_ruangan ?? $ruangan->nama ?? 'Ruangan' }}</a>
      <span>/</span>
      <span class="current">Booking</span>
    </nav>

    @if($errors->any())
      <div class="alert-error">
        <strong>Terjadi kesalahan:</strong>
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="booking-grid">

      <section class="card form-card">
        <div class="card-head">
          <span class="section-badge">Formulir</span>
          <h1 class="form-title">Booking Ruangan</h1>
          <p class="form-subtitle">Isi detail booking di bawah ini.</p>
        </div>

        <form action="{{ route('booking.store') }}" method="POST" class="booking-form" onsubmit="return validateForm()">
          @csrf
          <input type="hidden" name="ruangan_id" value="{{ $ruangan->id ?? 1 }}">

          <!-- Hidden Input untuk Nilai Jam Mulai dan Jam Selesai -->
          <input type="hidden" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" required>
          <input type="hidden" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" required>

          <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}" class="form-control" required>
          </div>

          <!-- Pilihan Slot Jam Interaktif -->
          <div class="form-group">
            <label style="display: block; font-weight: 700; margin-bottom: 4px;">Pilih Jam Ruangan (Bisa Pilih Lebih Dari 1)</label>
            
            <div class="time-slot-grid">
              @php
                $slots = [
                  '08:00' => '09:00',
                  '09:00' => '10:00',
                  '10:00' => '11:00',
                  '11:00' => '12:00',
                  '12:00' => '13:00',
                  '13:00' => '14:00',
                  '14:00' => '15:00',
                  '15:00' => '16:00',
                  '16:00' => '17:00',
                  '17:00' => '18:00',
                  '18:00' => '19:00',
                  '19:00' => '20:00',
                ];
              @endphp

              @foreach($slots as $start => $end)
                @php
                  $labelMulai = \Carbon\Carbon::parse($start)->format('H.i');
                  $labelSelesai = \Carbon\Carbon::parse($end)->format('H.i');
                @endphp
                <button type="button" 
                        class="time-slot-btn" 
                        data-start="{{ $start }}" 
                        data-end="{{ $end }}" 
                        onclick="toggleSlot(this)">
                  {{ $labelMulai }}–{{ $labelSelesai }}
                </button>
              @endforeach
            </div>

            <div id="selectedTimeInfo" class="selected-time-info">
              Waktu dipilih: <span id="timeRangeText">-</span>
            </div>
          </div>

          <div class="form-group">
            <label for="keperluan">Keperluan</label>
            <textarea id="keperluan" name="keperluan" rows="4" placeholder="Tuliskan keperluan booking...">{{ old('keperluan') }}</textarea>
          </div>

          <button type="submit" class="btn-submit">
            Konfirmasi Booking
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </button>
        </form>
      </section>

      <aside class="sidebar-right">

        <div class="card room-preview">
          @php
            $fotoRaw = $ruangan->foto ?? null;
            $fotoArray = [];

            if (is_string($fotoRaw) && trim($fotoRaw) !== '') {
              $trimmed = trim($fotoRaw);
              if (str_starts_with($trimmed, '[')) {
                $decoded = json_decode($trimmed, true);
                if (is_array($decoded)) {
                  $fotoArray = $decoded;
                }
              } else {
                $fotoArray = explode(',', $trimmed);
              }
            } elseif (is_array($fotoRaw)) {
              $fotoArray = $fotoRaw;
            }

            $daftarFotoUrl = [];
            foreach ($fotoArray as $item) {
              $itemTrimmed = trim($item);
              if ($itemTrimmed !== '') {
                if (str_starts_with($itemTrimmed, 'http')) {
                  $daftarFotoUrl[] = $itemTrimmed;
                } elseif (str_starts_with($itemTrimmed, 'image/') || str_starts_with($itemTrimmed, 'images/')) {
                  $daftarFotoUrl[] = asset($itemTrimmed);
                } else {
                  $daftarFotoUrl[] = asset('storage/' . $itemTrimmed);
                }
              }
            }
          @endphp

          <div class="slider-container">
            @if(count($daftarFotoUrl) > 0)
              @foreach($daftarFotoUrl as $index => $url)
                <img src="{{ $url }}" 
                     alt="{{ $ruangan->nama_ruangan ?? $ruangan->nama ?? 'Ruangan' }}" 
                     class="slider-slide {{ $index === 0 ? 'active' : '' }}" 
                     onerror="this.style.display='none';">
              @endforeach

              @if(count($daftarFotoUrl) > 1)
                <!-- Tombol Navigasi Geser Kiri-Kanan -->
                <button type="button" class="slider-btn prev-btn" onclick="moveSlide(-1)">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6"/>
                  </svg>
                </button>
                <button type="button" class="slider-btn next-btn" onclick="moveSlide(1)">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6"/>
                  </svg>
                </button>

                <!-- Indicator Dots -->
                <div class="slider-dots">
                  @foreach($daftarFotoUrl as $index => $url)
                    <span class="slider-dot {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></span>
                  @endforeach
                </div>
              @endif
            @else
              <div class="preview-placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Foto tidak tersedia</span>
              </div>
            @endif
          </div>

          <div class="preview-body">
            <span class="section-badge">Ringkasan</span>
            <h2 class="room-name">{{ $ruangan->nama_ruangan ?? $ruangan->nama ?? 'Meeting Room' }}</h2>

            <div class="preview-meta">
              <div class="meta-item">
                <span class="meta-label">Gedung</span>
                <span class="meta-value">{{ $ruangan->gedung ?? '-' }}</span>
              </div>
              <div class="meta-item">
                <span class="meta-label">Lantai</span>
                <span class="meta-value">{{ $ruangan->lantai ?? '-' }}</span>
              </div>
              <div class="meta-item">
                <span class="meta-label">Kapasitas</span>
                <span class="meta-value">{{ $ruangan->kapasitas ?? '-' }} orang</span>
              </div>
              <div class="meta-item">
                <span class="meta-label">Harga / Jam</span>
                <span class="meta-value highlight">Rp{{ number_format($ruangan->harga_per_jam ?? 150000, 0, ',', '.') }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="card info-card">
          <h3 class="info-title">Informasi Booking</h3>
          <ul class="info-list">
            <li><span class="dot"></span> Booking akan dikonfirmasi oleh admin terlebih dahulu.</li>
            <li><span class="dot"></span> Harap datang 10 menit sebelum jadwal.</li>
            <li><span class="dot"></span> Pembatalan dapat dilakukan minimal 1x24 jam sebelumnya.</li>
          </ul>
        </div>

      </aside>

    </div>

  </div>
</main>

@include('partials.footer')

<script>
  // Slider Management
  let currentSlideIndex = 0;

  function showSlide(index) {
    const slides = document.querySelectorAll('.slider-slide');
    const dots = document.querySelectorAll('.slider-dot');

    if (slides.length === 0) return;

    if (index >= slides.length) {
      currentSlideIndex = 0;
    } else if (index < 0) {
      currentSlideIndex = slides.length - 1;
    } else {
      currentSlideIndex = index;
    }

    slides.forEach((slide, i) => {
      if (i === currentSlideIndex) {
        slide.classList.add('active');
      } else {
        slide.classList.remove('active');
      }
    });

    dots.forEach((dot, i) => {
      if (i === currentSlideIndex) {
        dot.classList.add('active');
      } else {
        dot.classList.remove('active');
      }
    });
  }

  function moveSlide(direction) {
    showSlide(currentSlideIndex + direction);
  }

  function goToSlide(index) {
    showSlide(index);
  }

  // Pengecekan dan Otomatisasi Slot dari URL ketika Halaman Selesai Dimuat
  let selectedSlots = [];

  document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const selectedJamUrl = urlParams.getAll('jam[]').concat(urlParams.getAll('jam'));

    if (selectedJamUrl.length > 0) {
      const slotButtons = document.querySelectorAll('.time-slot-btn');

      slotButtons.forEach(btn => {
        const start = btn.getAttribute('data-start'); // Format e.g., '08:00'
        const end = btn.getAttribute('data-end');     // Format e.g., '09:00'
        
        // Format variasi pencocokan jam (misal 08.00-09.00 / 08.00–09.00 / 08:00-09:00)
        const dotFormat = `${start.replace(':', '.')}-${end.replace(':', '.')}`;
        const dashFormat = `${start}-${end}`;
        const enDashFormat = `${start.replace(':', '.')}–${end.replace(':', '.')}`;

        const isMatch = selectedJamUrl.some(val => {
          const cleanVal = val.replace('–', '-').trim();
          return cleanVal === dotFormat || cleanVal === dashFormat || val.trim() === enDashFormat;
        });

        if (isMatch) {
          btn.classList.add('selected');
          selectedSlots.push({ start, end });
        }
      });

      updateTimeInputs();
    }
  });

  function toggleSlot(btn) {
    const start = btn.getAttribute('data-start');
    const end = btn.getAttribute('data-end');

    if (btn.classList.contains('selected')) {
      btn.classList.remove('selected');
      selectedSlots = selectedSlots.filter(s => s.start !== start);
    } else {
      btn.classList.add('selected');
      selectedSlots.push({ start, end });
    }

    updateTimeInputs();
  }

  function updateTimeInputs() {
    const jamMulaiInput = document.getElementById('jam_mulai');
    const jamSelesaiInput = document.getElementById('jam_selesai');
    const timeInfo = document.getElementById('selectedTimeInfo');
    const timeRangeText = document.getElementById('timeRangeText');

    if (selectedSlots.length === 0) {
      jamMulaiInput.value = '';
      jamSelesaiInput.value = '';
      timeInfo.style.display = 'none';
      return;
    }

    selectedSlots.sort((a, b) => a.start.localeCompare(b.start));

    const minStart = selectedSlots[0].start;
    const maxEnd = selectedSlots[selectedSlots.length - 1].end;

    jamMulaiInput.value = minStart;
    jamSelesaiInput.value = maxEnd;

    const formatMin = minStart.replace(':', '.');
    const formatMax = maxEnd.replace(':', '.');

    timeRangeText.innerText = `${formatMin} - ${formatMax} (${selectedSlots.length} Jam)`;
    timeInfo.style.display = 'block';
  }

  function validateForm() {
    const jamMulai = document.getElementById('jam_mulai').value;
    if (!jamMulai) {
      Swal.fire({
        icon: 'warning',
        title: 'Pilih Waktu Booking',
        text: 'Silakan pilih minimal 1 slot jam terlebih dahulu!',
        confirmColor: '#0F766E'
      });
      return false;
    }
    return true;
  }
</script>

</body>
</html>
