<!-- Template layout for admin side -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sri Ratih Admin</title>
    <link rel="icon web" href="{{ asset('images/LOGO24x.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- FONTS --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
    </style>

    {{-- CSS --}}
    <style>
        * {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            min-height: 100vh;
            position: sticky;
            top: 0;
            z-index: 999;

        }
    </style>

</head>

<body class="container-fluid p-0">
    <nav class="w-100 bg-dark text-light p-4 fw-bold fs-3 border-bottom border-light">Admin Page</nav>

    <main class="d-flex">
        <aside class="sidebar">
            <div class="sidebar bg-dark text-white d-flex flex-column" style="width: 250px; height: 100vh;">
                <ul class="list-unstyled flex-grow-1">

                    <!-- Sidebar Menu -->
                    <li class="py-3 {{ Request::is('admin/dashboard') ? 'bg-primary' : '' }}">
                        <a href="{{ route('dashboard') }}"
                            class="text-decoration-none text-white d-flex align-items-center ps-3">
                            <i class="bi bi-speedometer me-3 fs-5"></i> Dashboard
                        </a>
                    </li>
                    <li class="py-3 {{ Request::is('admin/profile') ? 'bg-primary' : '' }}">
                        <a href="{{ route('admins.profile') }}"
                            class="text-decoration-none text-white d-flex align-items-center ps-3">
                            <i class="bi bi-person-circle me-3 fs-5"></i> Profile
                        </a>
                    </li>
                    <li
                        class="py-3 {{ Request::is('admin/busanas', 'admin/busanas/*', 'admin/busana/*') ? 'bg-primary' : '' }}">
                        <a href="{{ route('busana.index') }}"
                            class="text-decoration-none text-white d-flex align-items-center ps-3">
                            <i class="bi bi-bag-plus me-3 fs-5"></i> Busanas
                        </a>
                    </li>
                    <li class="py-3 {{ Request::is('admin/orders') ? 'bg-primary' : '' }}">
                        <a href="{{ route('orders.index') }}"
                            class="text-decoration-none text-white d-flex align-items-center ps-3">
                            <i class="bi bi-cart2 me-3 fs-5"></i> Orders Page
                        </a>
                    </li>
                    <li class="py-3 {{ Request::is('admin/reports') ? 'bg-primary' : '' }}">
                        <a href="{{ route('reports.index') }}"
                            class="text-decoration-none text-white d-flex align-items-center ps-3">
                            <i class="bi bi-clipboard-data me-3 fs-5"></i> Sales Reports
                        </a>
                    </li>

                    <!-- Logout -->
                    <li class="py-3 border-top mt-auto">
                        <a href="{{ route('admin.logout') }}"
                            class="text-decoration-none text-white d-flex align-items-center ps-3">
                            <i class="bi bi-box-arrow-right me-3 fs-5"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>
    </main>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('js')
</body>

</html>
