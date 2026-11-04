<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Anti-flicker + theme css --}}
    @include('partials.theme')

    <title>NookNook - Edit Ruangan</title>
    <link rel="stylesheet" href="{{ asset('css/sidebaradmin.css') }}">
    <style>
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .card { background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 30px; }
        .card-header { font-size: 24px; font-weight: 600; margin-bottom: 20px; border-bottom: 2px solid #e5e7eb; padding-bottom: 15px; }
        .form-label { font-weight: 500; display: block; margin-bottom: 6px; }
        .form-control, .form-select { width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px; }
        .form-control:focus, .form-select:focus { border-color: #2563eb; outline: none; box-shadow: 0 0 0 3px rgba(37,99,235,0.2); }
        .mb-3 { margin-bottom: 18px; }
        .text-danger { color: #dc2626; }
        .btn { padding: 10px 24px; border: none; border-radius: 24px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-secondary { background: #6b7280; color: white; }
        .btn-success { background: #16a34a; color: white; }
        .btn-success:hover { background: #15803d; }
        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }
        .invalid-feedback { color: #dc2626; font-size: 14px; margin-top: 4px; }
        .form-text { color: #6b7280; font-size: 14px; }
        .layout { display: flex; min-height: 100vh; }
        .main-content { flex: 1; padding: 32px; background: #f9fafb; }
        
        #preview-container { margin-top: 10px; position: relative; display: inline-block; }
        .preview-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px; }
        .preview-item { width: 100px; height: 100px; border: 1px solid #d1d5db; border-radius: 8px; object-fit: cover; padding: 4px; background: #fff; }

        /* Tombol Cancel Preview */
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
            transition: background 0.2s;
        }
        .btn-cancel-preview:hover {
            background: rgba(220, 38, 38, 1);
        }
    </style>
</head>
<body>

<div class="layout">
    @include('partials.sidebaradmin')

    <div class="main-content">
        <div class="container">
            <div class="card">
                <div class="card-header">Edit Ruangan</div>
                <div class="card-body">
                    <form action="{{ route('ruangan.update', $ruangan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- NAMA RUANGAN --}}
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $ruangan->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- KATEGORI --}}
                        <div class="mb-3">
                            <label for="id_kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="id_kategori_id" id="id_kategori_id" class="form-select @error('id_kategori_id') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('id_kategori_id', $ruangan->id_kategori_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- GEDUNG & LANTAI --}}
                        <div class="row" style="display: flex; gap: 16px;">
                            <div class="mb-3" style="flex: 1;">
                                <label for="gedung" class="form-label">Gedung</label>
                                <input type="text" name="gedung" id="gedung" class="form-control @error('gedung') is-invalid @enderror" value="{{ old('gedung', $ruangan->gedung) }}">
                                @error('gedung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3" style="flex: 1;">
                                <label for="lantai" class="form-label">Lantai</label>
                                <input type="number" name="lantai" id="lantai" class="form-control @error('lantai') is-invalid @enderror" value="{{ old('lantai', $ruangan->lantai) }}">
                                @error('lantai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- KAPASITAS --}}
                        <div class="mb-3">
                            <label for="kapasitas" class="form-label">Kapasitas (orang) <span class="text-danger">*</span></label>
                            <input type="number" name="kapasitas" id="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" value="{{ old('kapasitas', $ruangan->kapasitas) }}" required>
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- HARGA PER JAM (DI-CAST KE INTEGER) --}}
                        <div class="mb-3">
                            <label for="harga_per_jam" class="form-label">Harga per Jam (Rp)</label>
                            <input type="number" 
                                   name="harga_per_jam" 
                                   id="harga_per_jam" 
                                   class="form-control @error('harga_per_jam') is-invalid @enderror" 
                                   value="{{ (int) old('harga_per_jam', $ruangan->harga_per_jam) }}" 
                                   step="1">
                            @error('harga_per_jam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DESKRIPSI --}}
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $ruangan->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- PILIHAN FASILITAS MANUAL (CHECKBOX) --}}
                        <div class="mb-3">
                            <label class="form-label">Fasilitas Ruangan</label>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: #fff;">
                                @foreach($fasilitasAll ?? [] as $f)
                                    <label style="display: flex; align-items: center; gap: 6px; font-weight: normal; cursor: pointer; font-size: 14px;">
                                        <input type="checkbox" name="fasilitas[]" value="{{ $f->id }}" 
                                            {{ isset($ruangan) && $ruangan->fasilitas->contains($f->id) ? 'checked' : '' }}>
                                        {{ $f->nama }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="aktif" {{ old('status', $ruangan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $ruangan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- UPLOAD BANYAK GAMBAR --}}
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Ruangan (Bisa pilih lebih dari satu)</label>
                            @if($ruangan->foto)
                                <div style="margin-bottom: 8px;">
                                    @php
                                        $oldFotos = json_decode($ruangan->foto, true) ?? [$ruangan->foto];
                                    @endphp
                                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                        @foreach($oldFotos as $old)
                                            @php
                                                $urlOld = str_starts_with($old, 'http') ? $old : (str_starts_with($old, 'image/') || str_starts_with($old, 'images/') ? asset($old) : asset('storage/' . $old));
                                            @endphp
                                            <img src="{{ $urlOld }}" alt="Foto saat ini" style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid #d1d5db;">
                                        @endforeach
                                    </div>
                                    <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Foto saat ini</p>
                                </div>
                            @endif
                            <input type="file" name="foto[]" id="foto" class="form-control @error('foto.*') is-invalid @enderror" accept="image/*" multiple>
                            <div class="form-text">Format: JPG, PNG, JPEG. Maksimal 2MB per foto. Kosongkan jika tidak ingin mengganti foto.</div>
                            @error('foto.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- PREVIEW GAMBAR BARU + TOMBOL CANCEL --}}
                        <div class="mb-3" id="preview-container" style="display:none; position: relative;">
                            <label class="form-label">Preview Gambar Baru</label>
                            <div id="preview-list" class="preview-grid"></div>
                            <button type="button" id="btn-cancel-image" class="btn-cancel-preview" title="Batalkan pilihan gambar">&times;</button>
                        </div>

                        {{-- AKSI --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('ruanganadmin') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">Update Ruangan</button>
                        </div>

                    </form>
                </div>
            </div>
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
