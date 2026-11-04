<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  {{-- Anti-flicker + theme css --}}
  @include('partials.theme')

  <title>Manajemen Pengguna - NookNook</title>

  {{-- Font & Stylesheet --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/sidebaradmin.css') }}">

  <style>
    body {
      margin: 0;
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: var(--bg-body, #F8FAFC);
      color: var(--text-primary, #0F172A);
    }

    /* Layout Wrapper */
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
      height: 100vh;
      overflow: hidden;
    }

    /* Top Header */
    .top-header {
      height: 80px;
      background-color: var(--bg-card, #ffffff);
      border-bottom: 1px solid var(--border-color, #E5E7EB);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 32px;
      flex-shrink: 0;
    }

    .header-title { 
      font-size: 24px; 
      font-weight: 800; 
      color: var(--text-primary, #0F172A); 
      margin: 0;
      letter-spacing: -0.02em;
    }

    /* Primary Button */
    .btn-primary { 
      background-color: #0F766E; 
      color: #ffffff; 
      padding: 10px 20px; 
      border-radius: 10px; 
      text-decoration: none; 
      font-weight: 600; 
      font-size: 14px;
      transition: all 0.2s ease; 
      border: none; 
      cursor: pointer; 
      display: inline-flex; 
      align-items: center; 
      gap: 6px; 
    }
    .btn-primary:hover { 
      background-color: #0d655e; 
      transform: translateY(-1px);
    }

    /* Content Body */
    .content-body { 
      padding: 32px; 
      display: flex; 
      flex-direction: column; 
      gap: 24px; 
      flex: 1;
      overflow: hidden;
    }

    /* Search Box Form */
    .search-box { 
      display: flex; 
      align-items: center; 
      gap: 12px; 
      background: var(--bg-card, #ffffff); 
      padding: 10px 18px; 
      border-radius: 12px; 
      border: 1px solid var(--border-color, #E5E7EB); 
      width: 320px; 
      box-shadow: 0 1px 2px rgba(15,23,42,0.04);
      flex-shrink: 0;
    }

    .search-box input { 
      border: none; 
      outline: none; 
      font-size: 14px; 
      flex: 1; 
      background: transparent; 
      color: var(--text-primary, #0F172A);
      font-family: inherit;
    }

    .search-box svg { 
      color: #94a3b8; 
      flex-shrink: 0; 
    }

    /* Table Scrollable Wrapper */
    .table-wrapper { 
      background: var(--bg-card, #ffffff); 
      border-radius: 16px; 
      border: 1px solid var(--border-color, #E5E7EB); 
      box-shadow: 0 1px 3px rgba(15,23,42,0.04);
      flex: 1;
      overflow-y: auto;
      overflow-x: auto;
      position: relative;
    }

    /* Custom Scrollbar */
    .table-wrapper::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    .table-wrapper::-webkit-scrollbar-track {
      background: #F1F5F9;
      border-radius: 8px;
    }
    .table-wrapper::-webkit-scrollbar-thumb {
      background: #CBD5E1;
      border-radius: 8px;
    }
    .table-wrapper::-webkit-scrollbar-thumb:hover {
      background: #94A3B8;
    }

    table { 
      width: 100%; 
      border-collapse: collapse; 
      font-size: 14px; 
      text-align: left;
    }

    th { 
      position: sticky;
      top: 0;
      z-index: 10;
      background: #F8FAFC; 
      padding: 14px 20px; 
      font-weight: 700; 
      color: #64748B; 
      border-bottom: 1px solid var(--border-color, #E5E7EB); 
      text-transform: uppercase; 
      font-size: 12px; 
      letter-spacing: 0.05em; 
    }

    td { 
      padding: 14px 20px; 
      border-bottom: 1px solid #F1F5F9; 
      vertical-align: middle; 
      color: var(--text-primary, #0F172A);
    }

    tr:hover {
      background-color: #FAFAFA;
    }

    tr:last-child td {
      border-bottom: none;
    }

    /* Badges */
    .badge-role { 
      padding: 4px 12px; 
      border-radius: 9999px; 
      font-size: 12px; 
      font-weight: 700; 
      display: inline-block;
    }
    .badge-admin { 
      background: #DBEAFE; 
      color: #1E40AF; 
    }
    .badge-user { 
      background: #DCFCE7; 
      color: #15803D; 
    }
    .badge-status { 
      padding: 4px 12px; 
      border-radius: 9999px; 
      font-size: 12px; 
      font-weight: 700; 
      background: #DCFCE7; 
      color: #15803D; 
      display: inline-block;
    }

    /* Action Buttons */
    .btn-edit { 
      background: #3B82F6; 
      color: #ffffff; 
      padding: 6px 14px; 
      border-radius: 8px; 
      border: none; 
      font-size: 12px; 
      font-weight: 600;
      cursor: pointer; 
      text-decoration: none; 
      display: inline-block; 
      transition: background 0.2s; 
      margin-right: 4px;
    }
    .btn-edit:hover { 
      background: #2563EB; 
    }

    .btn-delete { 
      background: #FEE2E2; 
      color: #991B1B; 
      padding: 6px 14px; 
      border-radius: 8px; 
      border: none; 
      font-size: 12px; 
      font-weight: 600;
      cursor: pointer; 
      transition: background 0.2s; 
    }
    .btn-delete:hover { 
      background: #FECACA; 
    }

    /* Flash Messages & Avatar */
    .flash-success { 
      background: #DCFCE7; 
      color: #15803D; 
      padding: 14px 18px; 
      border-radius: 12px; 
      font-weight: 600; 
      font-size: 14px;
    }
    .flash-error { 
      background: #FEE2E2; 
      color: #991B1B; 
      padding: 14px 18px; 
      border-radius: 12px; 
      font-weight: 600; 
      font-size: 14px;
    }
    .empty-state { 
      text-align: center; 
      padding: 40px !important; 
      color: #94A3B8; 
    }
    .avatar { 
      width: 36px; 
      height: 36px; 
      border-radius: 50%; 
      background: #E2E8F0; 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      font-weight: 700; 
      color: #475569; 
      font-size: 13px; 
      flex-shrink: 0; 
    }
  </style>
</head>
<body>

<div class="dashboard-container">
    @include('partials.sidebaradmin')

    <main class="main-content">
        <header class="top-header">
            <h1 class="header-title">Manajemen Pengguna</h1>
            <a href="{{ route('admin.user.create') }}" class="btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Pengguna
            </a>
        </header>

        <div class="content-body">
            @if(session('success'))
                <div class="flash-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="flash-error">{{ session('error') }}</div>
            @endif

            {{-- Form Pencarian --}}
            <form action="{{ route('admin.user') }}" method="GET" class="search-box">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <path d="M21 21l-4.35-4.35" />
                </svg>
                <input type="text" name="search" placeholder="Cari nama atau email pengguna..." value="{{ request('search') }}" />
            </form>

            {{-- Tabel Data Pengguna Scrollable --}}
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <div class="avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                        <span style="font-weight:700;">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse($user->roles as $role)
                                        <span class="badge-role {{ $role->name == 'admin' ? 'badge-admin' : 'badge-user' }}">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @empty
                                        <span class="badge-role badge-user">User</span>
                                    @endforelse
                                </td>
                                <td>
                                    <span class="badge-status">Aktif</span>
                                </td>
                                <td style="text-align:center;">
                                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn-edit">Edit</a>

                                    <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" onclick="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">Belum ada pengguna terdaftar.</td>
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
