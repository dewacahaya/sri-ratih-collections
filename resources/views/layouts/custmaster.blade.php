<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon web" href="{{ asset('images/LOGO24x.png') }}">
    {{-- BOOTSTRAP --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- FONTS --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </style>

    {{-- CSS --}}
    <style></style>
    <title>Sri Ratih Collections</title>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg py-4" style="background-color: #001F54; color: white;">
        <div class="container">
            <!-- Nama Website -->
            <a class="navbar-brand text-white fw-bold" style="font-size: 30px" href="{{ route('customer.home') }}">Sri
                Ratih
                Collection</a>

            <!-- Bagian Ikon -->
            <div class="d-flex align-items-center">
                <!-- Keranjang -->
                <div class="position-relative me-3">
                    <a href="{{ route('customer.cart') }}" class="text-white">
                        <i class="bi bi-cart3" style="font-size: 1.5rem;"></i>
                    </a>
                </div>

                <!-- Tas -->
                <a href="{{ route('customer.orders') }}" class="text-white me-3">
                    <i class="bi bi-bag" style="font-size: 1.5rem;"></i>
                </a>

                {{-- DROPDOWN LOGIN/REGIS --}}
                <!-- Dropdown Trigger -->

                @if (Auth::guard('customers')->check())
                    <form method="POST" action="{{ route('customer.logout') }}">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link"
                            style="display: inline; padding: 0; border: none; background: none;"> <i
                                class="bi bi-box-arrow-right" style="font-size: 1.8rem;"></i></button>
                    </form>
                @else
                    <a href="#" class="text-white me-3" data-bs-toggle="modal" data-bs-target="#authModal">
                        <i class="bi bi-list" style="font-size: 1.8rem;"></i>
                    </a>
                @endif


                <!-- Modal Popup -->
                <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg text-dark">
                        <div class="modal-content">
                            <div class="row g-0">
                                <!-- Bagian Gambar -->
                                <div class="col-md-6 d-none d-md-block">
                                    <img src="{{ asset('images/tari-kecak-home.webp') }}" alt="Background"
                                        class="img-fluid h-100 object-fit-cover">
                                </div>
                                <!-- Bagian Form -->
                                <div class="col-md-6 p-5">
                                    <div class="tab-content" id="authTabsContent">
                                        <!-- Login Form -->
                                        <div class="tab-pane fade show active" id="login" role="tabpanel">
                                            <h3 class="text-center fw-bold mb-3">Welcome</h3>
                                            <p class="text-center text-muted mb-4">
                                                We are welcoming you, please sign in first before we can continue.
                                            </p>
                                            <form method="POST" action="{{ route('customer.login') }}">
                                                @csrf
                                                <div class="mb-3">
                                                    <input type="email"
                                                        class="form-control form-control-lg rounded-pill fs-6"
                                                        name="email" id="login_email" placeholder="Enter your email"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="password"
                                                        class="form-control form-control-lg rounded-pill fs-6"
                                                        name="password" id="login_password" placeholder="Put a password"
                                                        required>
                                                </div>
                                                <button type="submit" class="btn btn-dark w-100 rounded-pill">Sign
                                                    In</button>
                                                <p class="text-center mt-3">
                                                    Don't have any account yet? <a href="#"
                                                        onclick="switchToRegister()">Sign Up</a>
                                                </p>
                                            </form>
                                        </div>
                                        <!-- Register Form -->
                                        <div class="tab-pane fade" id="register" role="tabpanel">
                                            <h3 class="text-center fw-bold mb-3">Get Started</h3>
                                            <p class="text-center text-muted mb-4">
                                                Create your account to get started. <br>
                                                <small class="text-muted">* Masukkan data dengan jujur dan valid untuk
                                                    memudahkan transaksi dan pengiriman</small>
                                            </p>
                                            <form method="POST" action="{{ route('customer.register') }}">
                                                @csrf
                                                <div class="mb-3">
                                                    <input type="text"
                                                        class="form-control form-control-lg rounded-pill fs-6"
                                                        name="name" id="register_name" placeholder="Enter your name"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="email"
                                                        class="form-control form-control-lg rounded-pill fs-6"
                                                        name="email" id="register_email"
                                                        placeholder="Enter your email" required>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="password"
                                                        class="form-control form-control-lg rounded-pill fs-6"
                                                        name="password" id="register_password"
                                                        placeholder="Put a password" required>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="password" name="password_confirmation"
                                                        id="register_password_confirmation"
                                                        class="form-control form-control-lg rounded-pill fs-6"
                                                        placeholder="Put a confirmation password" required>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="text"
                                                        class="form-control form-control-lg rounded-pill fs-6"
                                                        name="no_telp" id="register_no_telp"
                                                        placeholder="Enter your phone" required>
                                                </div>
                                                <div class="mb-3">
                                                    <textarea class="form-control form-control-lg rounded fs-6" name="alamat" id="register_alamat" rows="2"
                                                        placeholder="Enter your address" required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-dark w-100 rounded-pill">Sign
                                                    Up</button>
                                                <p class="text-center mt-3">
                                                    Already have an account? <a href="#"
                                                        onclick="switchToLogin()">Sign In</a>
                                                </p>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </nav>


    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function switchToRegister() {
            // Pindah ke tab register
            document.getElementById('login').classList.remove('show', 'active');
            document.getElementById('register').classList.add('show', 'active');

            // Tampilkan teks informasi register
            document.getElementById('registerInfo').classList.remove('d-none');
        }

        function switchToLogin() {
            // Pindah ke tab login
            document.getElementById('register').classList.remove('show', 'active');
            document.getElementById('login').classList.add('show', 'active');

            // Sembunyikan teks informasi register
            document.getElementById('registerInfo').classList.add('d-none');
        }
    </script>



    @stack('js')
</body>

<footer class="py-4" style="background-color: #6889B1; color: white; margin-top : 120px;">
    <div class="container text-center text-md-start">
        <!-- Nama dan Alamat -->
        <h5 class="fw-bold">Sri Ratih Collection</h5>
        <span>Admin : 081234567890</span>
        <p class="mb-4">
            Jl. Yudistira No.10, Sukawati, Kec. Sukawati, Kabupaten Gianyar, Bali 80582
        </p>

        <!-- Ikon Media Sosial -->
        <div class="d-flex justify-content-center justify-content-md-start">
            <a href="#" class="text-white me-3" style="font-size: 1.5rem;">
                <i class="bi bi-facebook"></i>
            </a>
            <a href="#" class="text-white me-3" style="font-size: 1.5rem;">
                <i class="bi bi-linkedin"></i>
            </a>
            <a href="#" class="text-white me-3" style="font-size: 1.5rem;">
                <i class="bi bi-youtube"></i>
            </a>
            <a href="#" class="text-white me-3" style="font-size: 1.5rem;">
                <i class="bi bi-instagram"></i>
            </a>
        </div>
    </div>
</footer>


</html>
