<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.theme')

    <title>NookNook - Tambah Ruangan</title>
    
    <!-- Font & CSS Topbar Sesuai Halaman Home -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ruangadmin.css') }}">

    <style>
        body {
            display: block !important;
            background-color: var(--bg-body, #f8fafc);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 800px !important;
            margin: 40px auto !important;
            padding: 0 20px !important;
            float: none !important;
            clear: both !important;
        }

        .card { 
            background: var(--bg-card, #ffffff); 
            border-radius: 12px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08); 
            padding: 30px; 
            border: 1px solid var(--border-color, #e5e7eb);
        }

        .card-header { 
            font-size: 24px; 
            font-weight: 700; 
            margin-bottom: 20px; 
            border-bottom: 2px solid var(--border-color, #e5e7eb); 
            padding-bottom: 15px; 
            color: var(--text-primary, #111827);
        }

        .form-label { 
            font-weight: 600; 
            display: block; 
            margin-bottom: 6px; 
            color: var(--text-primary, #374151);
        }

        .form-control, .form-select { 
            width: 100%; 
            padding: 10px 14px; 
            border: 1px solid var(--border-color, #d1d5db); 
            border-radius: 8px; 
            font-size: 15px; 
            background-color: var(--bg-input, #ffffff);
            color: var(--text-primary, #111827);
            box-sizing: border-box;
            font-family: inherit;
        }

        .form-control:focus, .form-select:focus { 
            border-color: #0F766E; 
            outline: none; 
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.2); 
        }

        .row {
            display: flex;
            gap: 16px;
        }

        .col-md-6 {
            flex: 1;
        }

        .mb-3 { margin-bottom: 18px; }
        .text-danger { color: #dc2626; }

        .btn { 
            padding: 10px 24px; 
            border: none; 
            border-radius: 8px; 
            font-weight: 600; 
            cursor: pointer; 
            text-decoration: none; 
            display: inline-block; 
            transition: all 0.2s;
            font-family: inherit;
        }

        .btn-secondary { background: #6b7280; color: white; }
        .btn-secondary:hover { background: #4b5563; }

        .btn-success { background: #0F766E; color: white; }
        .btn-success:hover { background: #0d655e; }

        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }
        .invalid-feedback { color: #dc2626; font-size: 14px; margin-top: 4px; }
        .form-text { color: #6b7280; font-size: 13px; margin-top: 4px; }
        
        #preview-container { margin-top: 10px; position: relative; display: inline-block; }
        .preview-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px; }
        .preview-item { width: 100px; height: 100px; border: 1px solid #d1d5db; border-radius: 8px; object-fit: cover; padding: 4px; background: #fff; }
        
        .btn-cancel-preview {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 38, 38, 0.85);
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

@include('partials.topbar')

    <div class="container">
        <div class="card">
            <div class="card-header">Tambah Ruangan Baru</div>
            <div class="card-body">
                <form action="{{ route('ruangan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- NAMA RUANGAN --}}
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama ruangan" value="{{ old('nama') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- KATEGORI --}}
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori_id" id="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris ?? [] as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- GEDUNG & LANTAI --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gedung" class="form-label">Gedung</label>
                            <input type="text" name="gedung" id="gedung" class="form-control @error('gedung') is-invalid @enderror" placeholder="Contoh: Gedung A" value="{{ old('gedung') }}">
                            @error('gedung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lantai" class="form-label">Lantai</label>
                            <input type="number" name="lantai" id="lantai" class="form-control @error('lantai') is-invalid @enderror" placeholder="Contoh: 3" value="{{ old('lantai') }}">
                            @error('lantai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- KAPASITAS --}}
                    <div class="mb-3">
                        <label for="kapasitas" class="form-label">Kapasitas (orang) <span class="text-danger">*</span></label>
                        <input type="number" name="kapasitas" id="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" placeholder="Contoh: 50" value="{{ old('kapasitas') }}" required>
                        @error('kapasitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- HARGA PER JAM --}}
                    <div class="mb-3">
                        <label for="harga_per_jam" class="form-label">Harga per Jam (Rp)</label>
                        <input type="number" name="harga_per_jam" id="harga_per_jam" class="form-control @error('harga_per_jam') is-invalid @enderror" placeholder="Contoh: 50000" value="{{ old('harga_per_jam') }}">
                        @error('harga_per_jam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Deskripsi ruangan...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- STATUS --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PILIHAN FASILITAS MANUAL (CHECKBOX) --}}
                    <div class="mb-3">
                        <label class="form-label">Fasilitas Ruangan</label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; padding: 12px; border: 1px solid var(--border-color, #d1d5db); border-radius: 8px;">
                            @foreach($fasilitasAll ?? [] as $f)
                                <label style="display: flex; align-items: center; gap: 6px; font-weight: normal; cursor: pointer; font-size: 14px;">
                                    <input type="checkbox" name="fasilitas[]" value="{{ $f->id }}" 
                                        {{ (is_array(old('fasilitas')) && in_array($f->id, old('fasilitas'))) ? 'checked' : '' }}>
                                    {{ $f->nama }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- UPLOAD BANYAK GAMBAR --}}
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Ruangan (Bisa pilih lebih dari satu)</label>
                        <input type="file" name="foto[]" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*" multiple>
                        <div class="form-text">Format: JPG, PNG, JPEG. Maksimal 2MB per foto.</div>
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PREVIEW BANYAK GAMBAR + TOMBOL CANCEL --}}
                    <div class="mb-3" id="preview-container" style="display:none; position: relative;">
                        <label class="form-label">Preview Gambar</label>
                        <div id="preview-list" class="preview-grid"></div>
                        <button type="button" id="btn-cancel-image" class="btn-cancel-preview" title="Batalkan pilihan gambar">&times;</button>
                    </div>

                    {{-- AKSI --}}
                    <div class="d-flex justify-content-between" style="margin-top: 24px;">
                        <a href="{{ route('ruanganadmin') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan Ruangan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        const inputFoto = document.getElementById('foto');
        const previewContainer = document.getElementById('preview-container');
        const previewList = document.getElementById('preview-list');
        const btnCancelImage = document.getElementById('btn-cancel-image');

        inputFoto.addEventListener('change', function(e) {
            previewList.innerHTML = '';
            const files = e.target.files;
            if (files.length > 0) {
                previewContainer.style.display = 'block';
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const img = document.createElement('img');
                        img.src = event.target.result;
                        img.className = 'preview-item';
                        previewList.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                previewContainer.style.display = 'none';
            }
        });

        btnCancelImage.addEventListener('click', function() {
            inputFoto.value = '';
            previewContainer.style.display = 'none';
            previewList.innerHTML = '';
        });
    </script>
</body>
</html>
