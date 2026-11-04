<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  {{-- TAMBAHAN: Anti-flicker + theme css --}}
  @include('partials.theme')

  <title>Profil Saya - NookNook</title>
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

        <!-- Profile Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 max-w-4xl">
          <form class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Left Column: Avatar & Change Photo -->
            <div class="flex flex-col items-center justify-center space-y-4 md:border-r md:border-slate-100 md:pr-6">
              <div class="w-36 h-36 bg-slate-300 rounded-full flex items-center justify-center overflow-hidden shadow-inner">
                <!-- Placeholder Avatar Icon / Image -->
                <svg class="w-20 h-20 text-slate-500" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
              </div>
              <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
                Ubah Foto
              </button>
            </div>

            <!-- Right Column: Profile Form Fields -->
            <div class="md:col-span-2 space-y-5">

              <!-- Nama Lengkap -->
              <div>
                <label for="nama" class="block text-sm font-bold text-slate-800 mb-1">Nama Lengkap</label>
                <input
                  type="text"
                  id="nama"
                  value="Andi Saputra"
                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm bg-slate-50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-sm"
                />
              </div>

              <!-- Email -->
              <div>
                <label for="email" class="block text-sm font-bold text-slate-800 mb-1">Email</label>
                <input
                  type="email"
                  id="email"
                  value="andi@email.com"
                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm bg-slate-50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-sm"
                />
              </div>

              <!-- Nomor HP -->
              <div>
                <label for="phone" class="block text-sm font-bold text-slate-800 mb-1">Nomor HP</label>
                <input
                  type="tel"
                  id="phone"
                  value="081234567890"
                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm bg-slate-50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-sm"
                />
              </div>

              <!-- Action Buttons -->
              <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg text-sm transition-colors shadow-sm">
                  Simpan
                </button>
                <button type="button" class="bg-slate-500 hover:bg-slate-600 text-white font-medium px-6 py-2.5 rounded-lg text-sm transition-colors shadow-sm">
                  Batal
                </button>
              </div>

            </div>

          </form>
        </div>

      </main>
    </div>
  </div>

</body>
</html>