<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  {{-- Anti-flicker + theme css --}}
  @include('partials.theme')

  <title>NookNook - Manajemen Ruangan Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/sidebaradmin.css') }}">
  <link rel="stylesheet" href="{{ asset('css/ruangadmin.css') }}">

  <style>
    body {
      margin: 0;
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: var(--bg-body, #F8FAFC);
      color: var(--text-primary, #0F172A);
    }

    .main-wrapper {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
      height: 100vh;
      overflow: hidden;
    }

    .header-admin {
      height: 80px;
      background-color: var(--bg-card, #ffffff);
      border-bottom: 1px solid var(--border-color, #e5e7eb);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 32px;
      flex-shrink: 0;
    }

    .page-title {
      font-size: 24px;
      font-weight: 800;
      color: var(--text-primary, #0F172A);
      margin: 0;
      letter-spacing: -0.02em;
    }

    .btn-add {
      background-color: #0F766E;
      color: #ffffff;
      padding: 10px 20px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-add:hover {
      background-color: #0d655e;
      transform: translateY(-1px);
    }

    .content-area {
      padding: 32px;
      display: flex;
      flex-direction: column;
      gap: 24px;
      flex: 1;
      overflow: hidden;
    }

    .search-box {
      display: flex;
      align-items: center;
      gap: 12px;
      background: var(--bg-card, #ffffff);
      padding: 10px 18px;
      border-radius: 12px;
      border: 1px solid var(--border-color, #e5e7eb);
      box-shadow: 0 1px 2px rgba(15,23,42,0.04);
      flex-shrink: 0;
    }

    .search-input {
      flex: 1;
      border: none;
      outline: none;
      font-size: 14px;
      font-family: inherit;
      background: transparent;
      color: var(--text-primary, #0F172A);
    }

    /* SCROLLABLE TABLE CONTAINER CONTAINER */
    .card-table-wrapper {
      background: var(--bg-card, #ffffff);
      border-radius: 16px;
      border: 1px solid var(--border-color, #e5e7eb);
      box-shadow: 0 1px 3px rgba(15,23,42,0.04);
      flex: 1;
      overflow-y: auto;
      overflow-x: auto;
      position: relative;
    }

    /* Custom Scrollbar Stylings */
    .card-table-wrapper::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    .card-table-wrapper::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 8px;
    }
    .card-table-wrapper::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 8px;
    }
    .card-table-wrapper::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    .admin-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
      text-align: left;
    }

    .admin-table th {
      position: sticky;
      top: 0;
      z-index: 10;
      background: #f8fafc;
      padding: 14px 20px;
      font-weight: 700;
      color: #64748b;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      border-bottom: 1px solid var(--border-color, #e5e7eb);
    }

    .admin-table td {
      padding: 14px 20px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
      color: var(--text-primary, #0F172A);
    }

    .admin-table tbody tr:hover {
      background-color: #fafafa;
    }

    .admin-table tbody tr:last-child td {
      border-bottom: none;
    }

    .room-thumb {
      width: 72px;
      height: 52px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
      background: #f1f5f9;
    }

    .no-img-thumb {
      width: 72px;
      height: 52px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f1f5f9;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
      color: #94a3b8;
      font-size: 11px;
      font-weight: 600;
    }

    .badge-status {
      padding: 4px 12px;
      border-radius: 9999px;
      font-size: 12px;
      font-weight: 700;
      display: inline-block;
    }

    .badge-aktif {
      background-color: #dcfce7;
      color: #15803d;
    }

    .badge-nonaktif {
      background-color: #fee2e2;
      color: #991b1b;
    }

    .btn-edit {
      background-color: #3b82f6;
      color: #ffffff;
      padding: 6px 14px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 12px;
      font-weight: 600;
      display: inline-block;
      margin-right: 4px;
      transition: background 0.2s;
    }

    .btn-edit:hover {
      background-color: #2563eb;
    }

    .btn-delete {
      background-color: #fee2e2;
      color: #991b1b;
      padding: 6px 14px;
      border-radius: 8px;
      border: none;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn-delete:hover {
      background-color: #fecaca;
    }
  </style>
</head>
<body>

  <div style="display: flex; width: 100%; min-height: 100vh;">
    @include('partials.sidebaradmin')

    <!-- Content Wrapper -->
    <div class="main-wrapper">
      
      <!-- Header Admin -->
      <header class="header-admin">
        <h1 class="page-title">Manajemen Ruangan</h1>
        <a href="{{ route('ruangan.create') }}" class="btn-add">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
          Tambah Ruangan
        </a>
      </header>

      <!-- Content Area -->
      <main class="content-area">
        
        <!-- Flash Message -->
        @if(session('success'))
          <div style="background: #dcfce7; color: #15803d; padding: 14px 18px; border-radius: 12px; font-weight: 600; font-size: 14px;">
            {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div style="background: #fee2e2; color: #991b1b; padding: 14px 18px; border-radius: 12px; font-weight: 600; font-size: 14px;">
            {{ session('error') }}
          </div>
        @endif

        <!-- Input Pencarian -->
        <form action="{{ route('ruanganadmin') }}" method="GET" class="search-box">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #94a3b8;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input type="text" name="search" class="search-input" placeholder="Cari ruangan berdasarkan nama atau kategori..." value="{{ request('search') }}">
        </form>

        <!-- Tabel Ruangan Scrollable -->
        <div class="card-table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Gambar</th>
                <th>Nama Ruangan</th>
                <th>Kategori</th>
                <th>Kapasitas</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($ruangan as $room)
                <tr>
                  <td>
                    @php
                      $fotoRaw = $room->foto ?? null;
                      $fileName = null;

                      if (is_string($fotoRaw) && trim($fotoRaw) !== '') {
                        $trimmed = trim($fotoRaw);
                        if (str_starts_with($trimmed, '[')) {
                          $decoded = json_decode($trimmed, true);
                          if (is_array($decoded) && !empty($decoded)) {
                            $fileName = $decoded[0];
                          }
                        } else {
                          $exploded = explode(',', $trimmed);
                          $fileName = trim($exploded[0]);
                        }
                      } elseif (is_array($fotoRaw) && !empty($fotoRaw)) {
                        $fileName = $fotoRaw[0];
                      }

                      $fotoUrl = null;
                      if ($fileName) {
                        if (str_starts_with($fileName, 'http')) {
                          $fotoUrl = $fileName;
                        } else {
                          $cleanPath = ltrim(preg_replace('/^storage\//', '', $fileName), '/');
                          $fotoUrl = asset('storage/' . $cleanPath);
                        }
                      }
                    @endphp

                    @if($fotoUrl)
                      <img src="{{ $fotoUrl }}" alt="{{ $room->nama }}" class="room-thumb" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                      <div class="no-img-thumb" style="display:none;">No Image</div>
                    @else
                      <div class="no-img-thumb">No Image</div>
                    @endif
                  </td>

                  <td style="font-weight: 700;">{{ $room->nama }}</td>
                  <td>{{ $room->kategori->nama ?? 'Tanpa Kategori' }}</td>
                  <td>{{ $room->kapasitas }} orang</td>
                  <td>
                    <span class="badge-status {{ in_array(strtolower($room->status), ['aktif', 'tersedia']) ? 'badge-aktif' : 'badge-nonaktif' }}">
                      {{ ucfirst($room->status) }}
                    </span>
                  </td>
                  <td>
                    <!-- Tombol EDIT -->
                    <a href="{{ route('ruangan.edit', $room->id) }}" class="btn-edit">Edit</a>
                    
                    <!-- Tombol HAPUS -->
                    <form action="{{ route('ruangan.delete', $room->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus ruangan {{ $room->nama }}?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-delete">Hapus</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" style="padding: 40px; text-align: center; color: #94a3b8;">Belum ada data ruangan.</td>
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
