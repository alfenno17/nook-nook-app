<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  {{-- TAMBAHAN: Anti-flicker + theme css --}}
  @include('partials.theme')

  <title>Booking Saya - NookNook</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Style tambahan untuk tombol toggle tema --}}
  <style>
    .theme-toggle {
      position: fixed;
      top: 24px;
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
<body class="bg-slate-100 font-sans text-slate-800">



  <div class="flex h-screen w-full">

      <!-- Content Body -->
      <main class="flex-1 overflow-y-auto p-8">
        
        <!-- Controls Bar (Search & Filter) -->
        <div class="flex flex-wrap items-center gap-4 mb-6">
          <!-- Search Input -->
          <div class="relative w-72">
            <input 
              type="text" 
              placeholder="Cari Booking..." 
              class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm"
            />
          </div>

          <!-- Filter Status Dropdown -->
          <select class="w-44 px-4 py-2 border border-slate-300 rounded-lg text-sm bg-white text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="disetujui">Disetujui</option>
            <option value="dibatalkan">Dibatalkan</option>
          </select>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-700 uppercase tracking-wider">
                <th class="py-4 px-6">Kode</th>
                <th class="py-4 px-6">Ruangan</th>
                <th class="py-4 px-6">Tanggal</th>
                <th class="py-4 px-6 text-center">Status</th>
                <th class="py-4 px-6 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
              <!-- Item 1: Pending -->
              <tr class="hover:bg-slate-50 transition-colors">
                <td class="py-4 px-6 font-medium text-slate-900">BK001</td>
                <td class="py-4 px-6 text-slate-700">Meeting Room A</td>
                <td class="py-4 px-6 text-slate-600">05 Agu 2026</td>
                <td class="py-4 px-6 text-center">
                  <span class="inline-block px-3 py-1 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">
                    Pending
                  </span>
                </td>
                <td class="py-4 px-6">
                  <div class="flex items-center justify-center gap-2">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-md transition-colors">
                      Detail
                    </button>
                    <button class="bg-red-600 hover:bg-red-700 text-white text-xs font-medium px-3 py-1.5 rounded-md transition-colors">
                      Batal
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Item 2: Disetujui -->
              <tr class="hover:bg-slate-50 transition-colors">
                <td class="py-4 px-6 font-medium text-slate-900">BK002</td>
                <td class="py-4 px-6 text-slate-700">Meeting Room B</td>
                <td class="py-4 px-6 text-slate-600">08 Agu 2026</td>
                <td class="py-4 px-6 text-center">
                  <span class="inline-block px-3 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">
                    Disetujui
                  </span>
                </td>
                <td class="py-4 px-6 text-center">
                  <button class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-md transition-colors">
                    Detail
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </main>
    </div>
  </div>

</body>
</html>