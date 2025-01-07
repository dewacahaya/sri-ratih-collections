@extends('layouts.custmaster')

@section('content')
    <div class="container my-5">
        <div class="row">
            <!-- Gambar Busana -->
            <div class="col-md-6">
                <img src="{{ Storage::url($busana->gambar) }}" class="img-fluid rounded border-3 shadow-sm"
                    alt="{{ $busana->nama_busana }}" style="height: 400px; width: 100%; object-fit: cover;">
            </div>

            <!-- Informasi Busana -->
            <div class="col-md-6">
                <h2 class="fw-bold mb-3 text-capitalize">{{ $busana->nama_busana }}</h2>
                <p class="text-muted"><span class="fs-5 fw-semibold">Harga:</span> Rp.
                    {{ number_format($busana->harga, 0, ',', '.') }}</p>
                <p class="text-muted"><span class="fs-5 fw-semibold">Stok:</span> {{ $busana->stok }}</p>
                <p class="mt-4">
                    <span class="fs-5 fw-semibold">Deskripsi Produk:</span><br>
                    <span class="text-muted">{{ $busana->deskripsi }}</span>
                </p>
                <!-- Tombol Add to Cart -->
                <form action="{{ route('customer.cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="busana_id" value="{{ $busana->id }}">
                    <button type="submit" class="btn btn-primary {{ $busana->stok <= 0 ? 'btn-danger' : '' }}"
                        {{ $busana->stok <= 0 ? 'disabled' : '' }}>
                        {{ $busana->stok <= 0 ? 'Stok Habis' : 'Add to Cart' }}
                    </button>
                </form>

            </div>
        </div>

        <!-- Rekomendasi Section -->
        <div class="mt-5">
            <h2 class="fw-bold mb-4">Rekomendasi Lainnya</h2>
            <div class="row">
                @foreach ($recommendations as $rb)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <a href="{{ route('customer.busana.detail', $rb->id) }}"
                                class="text-decoration-none text-dark">
                                <img src="{{ Storage::url($rb->gambar) }}"
                                    class="card-img-top rounded border-3 border-bottom shadow-sm"
                                    alt="{{ $rb->nama_busana }}" style="height: 230px; width: 100%; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $rb->nama_busana }}</h5>
                                    <p class="card-text text-muted">{{ $rb->deskripsi }}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        @if (session('error'))
            <
            div class = "alert alert-danger mt-3" >
            {{ session('error') }}
                <
                /div>
        @endif

        @if (session('success'))
            <
            div class = "alert alert-success mt-3" >
            {{ session('success') }}
                <
                /div>
        @endif
    </script>
@endpush
