@extends('layouts.custmaster')

@section('content')
    <div class="text-center mt-5" style="padding: 0; margin: 0;">

        <h1 class="fw-bold py-2" style="font-size: 60px">Sri Ratih Collection</h1>


        <p class="text-muted py-2" style="font-size: 1.2rem;">Pusat Busana Adat Bali</p>


        <a href="{{ route('customer.recommendation') }}" class="btn btn-dark text-white fw-bold py-2 w-25">Lihat
            produk</a>
        <div class="mt-5">
            <img src="{{ asset('images/pexels-saturnus99-9196830.webp') }}" alt="Tari Kecak" class="w-75 rounded-4"
                style="height: 500px; object-fit: cover;">

        </div>

        <!-- Section Preview Busana -->
        <div class="container">
            <div class="pb-4 mt-5 d-flex align-start justify-content-start fs-3 fw-bold">
                Preview Busana
            </div>
            <div class="row">
                @foreach ($busanas as $b)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="{{ Storage::url($b->gambar) }}"
                                class="img-fluid card-img-top rounded border-3 border-bottom shadow-sm"
                                alt="{{ $b->nama_busana }}" style="height: 230px; width: 100%; object-fit: cover;">
                            <div class="card-body" style="text-align: left; padding-left: 10px;">
                                <h5 class="card-title" style="font-size: 1.2rem;">{{ $b->nama_busana }}</h5>
                                <p class="card-text text-muted" style="font-size: 0.9rem;">{{ $b->deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endsection

    @push('js')
        <script>
            @if (session('success'))

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                });
            @endif

            @if (session('failed'))

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('failed') }}',
                });
            @endif

            @if ($errors->any())

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ $errors->first() }}',
                });
            @endif

            @if (session('registered'))

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('registered') }}',
                });
            @endif

            @if ($errors->any())

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ $errors->first() }}',
                });
            @endif

            @if (session('popup'))
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                // Tampilkan toast dengan pesan dari session
                Toast.fire({
                    icon: "error",
                    title: "{{ session('popup') }}"
                });
            @endif
        </script>
    @endpush
