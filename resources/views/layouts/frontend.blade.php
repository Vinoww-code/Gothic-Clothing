<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Gothic Clothing') }}</title>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Load Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Mengunci agar halaman tidak bisa di-scroll ke samping */
        html, body {
            overflow-x: hidden;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        /* Sembunyikan tombol burger di Laptop/PC */
        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            color: inherit;
            font-size: 24px;
            cursor: pointer;
            margin-left: 15px;
        }

        /* Pengaturan Khusus Layar HP/Tablet */
        @media (max-width: 768px) {
            .hamburger-btn {
                display: block; /* Munculkan tombol burger */
            }

            .auth-text {
                display: none;
            }

            
            /* Pastikan kontainer navbar rata tengah/kiri-kanan */
            .navbar .container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: relative; 
                width: 100%;
                box-sizing: border-box;
            }

            /* Dorong icon user ke sebelah kiri tombol burger */
            .navbar-icons {
                margin-left: auto; 
                margin-right: 15px; 
                gap: 10px !important;
            }

            /* Sembunyikan menu link secara default di HP */
            .navbar-nav {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 100%; /* Turun ke bawah header */
                left: 0;
                width: 100%;
                background-color: #0a0a0a; /* Warna background menu pas dibuka */
                padding: 20px 0;
                text-align: center;
                gap: 15px;
                border-top: 1px solid #333;
                z-index: 999;
                margin: 0;
            }

            /* Saat class 'active' ditambah lewat JS, menu akan muncul */
            .navbar-nav.active {
                display: flex;
            }

            .footer-grid {
                display: flex !important;
                flex-direction: column !important; /* Memaksa footer berbaris dari atas ke bawah di HP */
                gap: 40px !important; /* Jarak antar kolom footer */
            }

            .footer-col {
                width: 100% !important;
                text-align: left;
            }

            /* Merapikan bagian bawah footer (Copyright & Link) di HP */
            .footer-bottom {
                display: flex;
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
        }
    </style>
</head>
<body>
   <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="{{ route('home') }}" class="navbar-brand">GOTHIC CLOTHING</a>
            
            <!-- Tambahkan ID 'navLinks' di sini -->
            <ul class="navbar-nav" id="navLinks">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="{{ route('collection') }}">Koleksi</a></li>
                <li><a href="{{ route('accessories') }}">Aksesoris</a></li>
                <li><a href="{{ route('home') }}#bestseller">Terlaris</a></li>
                <li><a href="{{ route('home') }}#how-to-rent">Cara Sewa</a></li>
                <li><a href="{{ route('home') }}#testimonials">Testimoni</a></li>
                <li><a href="{{ route('home') }}#faq">FAQ</a></li>
                <li><a href="{{ route('contact') }}">Kontak</a></li>
            </ul>
            
            <!-- BAGIAN BARU: Tombol Auth -->
            <div class="navbar-icons" style="display: flex; align-items: center; gap: 15px;">
                @guest
                    <!-- Jika belum login -->
                    <a href="{{ route('login') }}" style="color: #fff; text-decoration: none; font-size: 14px;">
                        <i class="fa-solid fa-user"></i> <span class="auth-text">Masuk</span>
                    </a>
                    <a href="{{ route('register') }}" style="background: #8b0000; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: bold;">
                        <span class="auth-text">Daftar</span>
                    </a>
                @endguest
                @auth
                    <!-- Jika sudah login, sapa nama usernya -->
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="color: #fff; font-size: 14px; font-weight: bold;">
                            <i class="fa-solid fa-user-check"></i> 
                            <span class="auth-text">Halo, {{ strtok(Auth::user()->name, ' ') }}</span>
                        </span>
                        
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline;">
                            @csrf
                            <button type="submit" title="Keluar" style="background: transparent; border: 1px solid #ff4444; color: #ff4444; padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 13px; transition: 0.3s;" onmouseover="this.style.background='#ff4444'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#ff4444';">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </button>
                        </form>
                    </div>
                @endauth
            </div>

            <!-- Tombol Burger Tambahan -->
            <button class="hamburger-btn" id="hamburgerBtn">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 75px; min-height: 50vh;">
        <!-- Global Flash Notifications -->
        @if(session('success') || session('error') || session('info') || session('status'))
            <div class="container" style="padding-top: 20px;">
                @if(session('success'))
                    <div style="background: rgba(25, 135, 84, 0.2); border: 1px solid #198754; color: #75b798; padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; font-size: 14px;">
                        <span><i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}</span>
                        <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #75b798; cursor: pointer; font-size: 16px;">&times;</button>
                    </div>
                @endif
                @if(session('error'))
                    <div style="background: rgba(139, 0, 0, 0.25); border: 1px solid #8b0000; color: #ff6b6b; padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; font-size: 14px;">
                        <span><i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i> {{ session('error') }}</span>
                        <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #ff6b6b; cursor: pointer; font-size: 16px;">&times;</button>
                    </div>
                @endif
                @if(session('info') || session('status'))
                    <div style="background: rgba(13, 110, 253, 0.2); border: 1px solid #0d6efd; color: #6ea8fe; padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; font-size: 14px;">
                        <span><i class="fa-solid fa-circle-info" style="margin-right: 8px;"></i> {{ session('info') ?? session('status') }}</span>
                        <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #6ea8fe; cursor: pointer; font-size: 16px;">&times;</button>
                    </div>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="#" class="footer-logo">GOTHIC CLOTHING</a>
                    <p>Penyewaan Kostum & Aksesoris Gothic Premium<br>untuk momen spesial Anda.</p>
                    <div style="margin-top: 15px; display:flex; gap:15px; font-size: 18px;">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Tautan Cepat</h4>
                    <ul class="footer-links">
                        <li><a href="#">Beranda</a></li>
                        <li><a href="{{ route('collection') }}">Koleksi</a></li>
                        <li><a href="{{ route('accessories') }}">Aksesoris</a></li>
                        <li><a href="{{ route('home') }}#bestseller">Terlaris</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Kategori</h4>
                    <ul class="footer-links">
                        <li><a href="#">Kostum</a></li>
                        <li><a href="#">Aksesoris</a></li>
                        <li><a href="#">Terbaru</a></li>
                        <li><a href="#">Victorian</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Berlangganan</h4>
                    <p>Dapatkan penawaran khusus dan informasi terbaru langsung di email Anda.</p>
                    <form action="#" class="newsletter-form">
                        <input type="email" placeholder="Alamat email Anda" required>
                        <button type="submit">Langganan</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <div>&copy; {{ date('Y') }} Gothic Clothing. Hak Cipta Dilindungi.</div>
                <div class="footer-bottom-links">
                    <a href="#">Kebijakan Privasi</a>
                    <a href="#">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ========================================== -->
    <!-- JAVASCRIPT UNTUK MENU HAMBURGER            -->
    <!-- ========================================== -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const navLinks = document.getElementById('navLinks');
            const icon = hamburgerBtn.querySelector('i');

            hamburgerBtn.addEventListener('click', function() {
                // Munculkan / Sembunyikan menu
                navLinks.classList.toggle('active');
                
                // Ubah icon dari garis tiga ke tanda silang (X)
                if (navLinks.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-xmark');
                } else {
                    icon.classList.remove('fa-xmark');
                    icon.classList.add('fa-bars');
                }
            });
        });
    </script>
</body>
</html>