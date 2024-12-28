@extends('layouts.custmaster')

@section('content')
    <div class="container my-4">
        <h1 class="fw-bold mb-4 text-center">Keranjang</h1>

        <!-- Step Navigation -->
        <div class="d-flex justify-content-center mb-5">
            <div class="text-center mx-3 text-success fw-bold">
                <span>1</span>
                <p class="text-decoration-underline">Keranjang Belanja</p>
            </div>
            <div class="text-center mx-3 text-muted">
                <span class="fw-bold">2</span>
                <p>Detail Checkout</p>
            </div>
            <div class="text-center mx-3 text-muted">
                <span class="fw-bold">3</span>
                <p>Pesanan Selesai</p>
            </div>
        </div>

        @if (count($cart) > 0)
            <!-- Produk Section -->
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <table class="table align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Busana</th>
                                <th class="text-center">Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart as $key => $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ Storage::url($item['image']) }}" target="_blank"><img
                                                    src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}"
                                                    class="me-3"
                                                    style="width: 180px; height: 180px; object-fit: contain" /></a>
                                            <span>{{ $item['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="form-control text-center update-cart"
                                            data-id="{{ $key }}" value="{{ $item['quantity'] }}" min="1"
                                            style="width: 60px;">
                                    </td>
                                    <td>Rp. {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td class="total">Rp.
                                        {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('customer.cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="busana_id" value="{{ $key }}">
                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="bi bi-x"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-4">
                    <div class="card p-4">
                        <h5 class="fw-bold">Ringkasan Keranjang</h5>
                        <form action="" method="GET">
                            <div class="form-check mt-3 mb-3">
                                <input class="form-check-input" type="radio" name="shipping" id="standard"
                                    value="standard" {{ $shippingOption == 'standard' ? 'checked' : '' }}>
                                <label class="form-check-label border border-2 rounded border-secondary p-2 w-100 fw-bold"
                                    for="standard">Standard
                                    (Gratis)</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="shipping" id="fast" value="fast"
                                    {{ $shippingOption == 'fast' ? 'checked' : '' }}>
                                <label class="form-check-label border border-2 rounded border-secondary p-2 w-100 fw-bold"
                                    for="fast">Pengiriman Cepat (Rp 10.000)</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="shipping" id="pickup" value="pickup"
                                    {{ $shippingOption == 'pickup' ? 'checked' : '' }}>
                                <label class="form-check-label border border-2 rounded border-secondary p-2 w-100 fw-bold"
                                    for="pickup">Ambil di Tempat</label>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span>Total:</span>
                                <span id="grand-total">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                            <button type="submit" class="btn btn-dark w-100 mt-3">Checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <p>Keranjang belanja kosong.</p>
        @endif
    </div>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const updateTotal = () => {
                let grandTotal = 0;

                document.querySelectorAll('.input-group').forEach(group => {
                    const quantityInput = group.querySelector('.quantity');
                    const totalCell = group.closest('tr').querySelector('.total');
                    const price = parseInt(group.dataset.price);
                    const quantity = parseInt(quantityInput.value);

                    const total = price * quantity;
                    totalCell.textContent = 'Rp ' + total.toLocaleString('id-ID');
                    grandTotal += total;
                });

                document.getElementById('grand-total').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
            };

            document.querySelectorAll('.decrease').forEach(button => {
                button.addEventListener('click', () => {
                    const quantityInput = button.nextElementSibling;
                    let value = parseInt(quantityInput.value);
                    if (value > 1) {
                        quantityInput.value = value - 1;
                        updateTotal();
                    }
                });
            });

            document.querySelectorAll('.increase').forEach(button => {
                button.addEventListener('click', () => {
                    const quantityInput = button.previousElementSibling;
                    let value = parseInt(quantityInput.value);
                    quantityInput.value = value + 1;
                    updateTotal();
                });
            });

            updateTotal();
        });
    </script> --}}
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shippingOptions = document.querySelectorAll('input[name="shipping"]');
            const grandTotalElement = document.getElementById('grand-total');

            document.querySelectorAll('.update-cart').forEach(input => {
                input.addEventListener('change', function(e) {
                    e.preventDefault();
                    const busanaId = this.getAttribute('data-id');
                    const quantity = parseInt(this.value);
                    const shippingOption = document.querySelector('input[name="shipping"]:checked')
                        .value;

                    fetch('{{ route('customer.cart.update') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                busana_id: busanaId,
                                quantity: quantity,
                                shipping_option: shippingOption
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                const totalElement = this.closest('tr').querySelector('.total');
                                const price = parseInt(this.closest('tr').querySelector(
                                    'td:nth-child(3)').innerText.replace(/[^0-9]/g, ""));

                                const newTotal = Math.round(price * quantity);
                                totalElement.innerText = 'Rp. ' + newTotal.toLocaleString(
                                    'id-ID', {
                                        minimumFractionDigits: 0
                                    });
                                updateGrandTotal();
                            } else {
                                alert(data.message);
                            }
                        });
                });
            });

            shippingOptions.forEach(option => {
                option.addEventListener('change', function() {
                    updateGrandTotal();
                });
            });

            function updateGrandTotal() {
                let grandTotal = 0;

                // Hitung total dari produk di tabel
                document.querySelectorAll('tbody tr').forEach(row => {
                    const total = parseInt(row.querySelector('.total').innerText.replace(/[^0-9]/g, ""));
                    grandTotal += total;
                });

                // Tambahkan biaya pengiriman jika pengiriman cepat dipilih
                const selectedShippingOption = document.querySelector('input[name="shipping"]:checked').value;
                if (selectedShippingOption === 'fast') {
                    grandTotal += 10000;
                }

                // Perbarui elemen total ringkasan keranjang
                grandTotalElement.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID', {
                    minimumFractionDigits: 0
                });
            }

            // Inisialisasi nilai total saat halaman dimuat
            updateGrandTotal();
        });
    </script>
    <script>
        @if (session('success'))
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
                icon: "success",
                title: "{{ session('success') }}"
            });
        @endif
    </script>
@endpush
