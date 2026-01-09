<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMASERV</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hamburger-btn { display: none; background: none; border: none; font-size: 1.5rem; color: #3b82f6; cursor: pointer; padding: 0.5rem; }
        .nav-menu { display: flex; align-items: center; }
        .navbar-custom { padding: 0.5rem 2rem; border-radius: 16px; position: relative; }
        
        @media (max-width: 768px) {
            .navbar-custom { padding: 0.5rem 1rem; }
            .hamburger-btn { display: block; }
            .nav-menu { 
                display: none; 
                position: absolute; 
                top: 100%; 
                left: 0; 
                width: 100%; 
                background: rgba(255,255,255,0.95); 
                backdrop-filter: blur(10px);
                flex-direction: column; 
                padding: 1.5rem; 
                border-radius: 16px; 
                box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1);
                border: 1px solid rgba(255,255,255,0.5);
                z-index: 100;
                margin-top: 10px;
            }
            .nav-menu.active { display: flex; animation: slideIn 0.2s ease-out; }
            .nav-link { margin: 0.5rem 0; width: 100%; text-align: center; padding: 1rem; border-radius: 12px; font-weight: 600; }
            .nav-link:hover { background: #eff6ff; }
        }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <div class="container">
        <nav class="navbar glass navbar-custom">
            <a href="{{ route('home') }}" class="brand" style="display: flex; align-items: center; height: 60px;">
                <img src="{{ asset('images/logo_new.png') }}" alt="SIMASERV" style="height: 50px; width: auto;">
            </a>
            
            <button class="hamburger-btn" onclick="toggleMenu()" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>

            <div class="nav-menu" id="navMenu">
                <a href="{{ route('home') }}" class="nav-link">Ambil Antrian</a>
                <a href="{{ route('public.help') }}" class="nav-link">Bantuan</a>
            </div>
        </nav>

        <script>
            function toggleMenu() {
                const menu = document.getElementById('navMenu');
                const btn = document.querySelector('.hamburger-btn i');
                
                menu.classList.toggle('active');
                
                if(menu.classList.contains('active')) {
                    btn.classList.remove('fa-bars');
                    btn.classList.add('fa-times');
                } else {
                    btn.classList.remove('fa-times');
                    btn.classList.add('fa-bars');
                }
            }
        </script>

        @if(session('success'))
            <div class="glass" style="padding: 1rem; margin-bottom: 2rem; background: #dcfce7; border-color: #86efac; color: #166534;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @yield('content')
        
        <footer class="text-center text-muted" style="margin-top: 4rem; padding-bottom: 2rem;">
            <p>&copy; {{ date('Y') }} Indiana Computer. Professional Service System.</p>
        </footer>
    </div>
</body>
</html>
