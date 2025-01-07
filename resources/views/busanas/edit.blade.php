@extends('layouts.admaster')

@section('content')
    <div class="px-5">
        <h2 class="fw-bold pb-3">Edit Busana</h2>
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4 class="fw-bold">Form Edit Busana</h4>
                </div>

                <form action="{{ route('busana.update', $busana->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <hr>
                    <div class="mb-3">
                        <label for="nama_busana" class="form-label">Nama Busana</label>
                        <input type="text" name="nama_busana" id="nama_busana" class="form-control"
                            value="{{ old('nama_busana', $busana->nama_busana) }}" placeholder="Masukkan Nama Busana">
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" name="harga" id="harga" class="form-control"
                            value="{{ old('harga', $busana->harga) }}" placeholder="Masukkan Harga Busana">
                    </div>

                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" name="stok" id="stok" class="form-control"
                            value="{{ old('stok', $busana->stok) }}" placeholder="Masukkan Stok Busana">
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control" placeholder="Masukkan Deskripsi Busana">{{ old('deskripsi', $busana->deskripsi) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="gambar" class="form-label">Gambar</label>
                        <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*"
                            onchange="previewImage(event)">
                        <div class="mb-2 mt-2">
                            <img id="preview" src="{{ asset('storage/' . $busana->gambar) }}"
                                alt="{{ $busana->nama_busana }}"
                                style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('busana.index') }}" class="btn btn-danger me-2">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush
