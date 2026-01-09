<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMASERV Admin</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Modern Select Overrides */
        .ts-control {
            border-radius: 8px;
            padding: 10px 12px;
            border-color: #e2e8f0;
            box-shadow: none;
        }
        .ts-control.focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .ts-dropdown {
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .ts-dropdown .option {
            padding: 10px 12px;
        }
        .ts-dropdown .active {
            background-color: #eff6ff;
            color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <div class="mobile-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <img src="{{ asset('images/logo_new.png') }}" alt="SIMASERV" height="32">
        </div>
        <aside class="sidebar">
            <div class="brand" style="justify-content: center; padding: 20px 10px;">
                <img src="{{ asset('images/logo_new.png') }}" alt="SIMASERV" style="max-width: 200px; height: auto;">
            </div>

            <!-- Search in sidebar -->
            <form action="{{ route('admin.services.index') }}" method="GET" style="margin-bottom: 2rem; position: relative;">
                <input type="text" name="search" placeholder="Search (Ticket/Name/Phone)" style="width: 100%; padding: 8px 12px 8px 32px; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb; font-size: 0.85rem;">
                <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #9ca3af; font-size: 0.8rem;"></i>
            </form>

            <ul class="sidebar-menu">
                <li class="menu-category">Main Menu</li>
                <li class="menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.services.index') }}" class="menu-link {{ request()->is('admin/services*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag"></i> <span>Services / Orders</span>
                    </a>
                </li>
                <!-- Placeholders from Image -->
                <li class="menu-item"><a href="#" class="menu-link"><i class="fas fa-box"></i> <span>Products</span></a></li>
                <li class="menu-item"><a href="#" class="menu-link"><i class="fas fa-link"></i> <span>Integrations</span></a></li>
                <li class="menu-item">
                    <a href="{{ route('admin.reports.index') }}" class="menu-link {{ request()->is('admin/reports*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i> <span>Reports</span>
                    </a>
                </li>
            </ul>

            <div style="margin-top: auto;">
                <ul class="sidebar-menu">
                    <li class="menu-item">
                        <a href="{{ route('admin.help') }}" class="menu-link {{ request()->is('admin/help*') ? 'active' : '' }}">
                            <i class="fas fa-circle-question"></i> <span>Help</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="menu-link" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
                <div class="user-profile">
                    <div style="width: 35px; height: 35px; background: #dbeafe; color: #1e40af; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">AD</div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 0.9rem;">Admin Toko</div>
                        <div style="font-size: 0.75rem; color: #6b7280;">admin@toko.com</div>
                    </div>
                </div>
            </div>
        </aside>
        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }

        // Init Modern Select
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('select').forEach((el) => {
                new TomSelect(el, {
                    create: false,
                    controlInput: null, // Disable search input for simple dropdowns if needed, or keep it
                    dropdownParent: 'body' // Fix clipping issues
                });
            });
        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif
        
        @if($errors->any())
            Toast.fire({
                icon: 'error',
                title: 'Terdapat kesalahan pada inputan Anda.'
            });
        @endif
    </script>
</body>
</html>
