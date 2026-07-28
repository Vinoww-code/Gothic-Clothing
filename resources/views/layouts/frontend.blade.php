<!DOCTYPE html>
<html lang="en">
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
            /* TAMBAHKAN KODE INI KE DALAM @media (max-width: 768px) DI FILE frontend.blade.php */

            .footer-grid {
                display: flex !important;
                flex-direction: column !important; /* Memaksa footer berbaris dari atas ke bawah di HP */
                gap: 40px !important; /* Jarak antar kolom footer */
            }

            .footer-col {
                width: 100% !important;
                text-align: left; /* Sesuaikan jika ingin rata tengah (center) */
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
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('collection') }}">Collection</a></li>
                <li><a href="{{ route('accessories') }}">Accessories</a></li>
                <li><a href="{{ route('home') }}#bestseller">Best Seller</a></li>
                <li><a href="{{ route('home') }}#how-to-rent">How To Rent</a></li>
                <li><a href="{{ route('home') }}#testimonials">Testimonials</a></li>
                <li><a href="{{ route('home') }}#faq">FAQ</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
            
            <!-- BAGIAN BARU: Tombol Auth -->
            <div class="navbar-icons" style="display: flex; align-items: center; gap: 15px;">
                @guest
                    <!-- Jika belum login -->
                    <a href="{{ route('login') }}" style="color: #fff; text-decoration: none; font-size: 14px;">
                        <i class="fa-solid fa-user"></i> <span class="auth-text">Login</span>
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
                            <button type="submit" title="Logout" style="background: transparent; border: 1px solid #ff4444; color: #ff4444; padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 13px; transition: 0.3s;" onmouseover="this.style.background='#ff4444'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#ff4444';">
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
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="#" class="footer-logo">GOTHIC CLOTHING</a>
                    <p>Premium Gothic Costume & Accessories<br>Rental for your special moments.</p>
                    <div style="margin-top: 15px; display:flex; gap:15px; font-size: 18px;">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#">Home</a></li>
                        <li><a href="{{ route('collection') }}">Collection</a></li>
                        <li><a href="{{ route('accessories') }}">Accessories</a></li>
                        <li><a href="{{ route('home') }}#bestseller">Best Seller</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Categories</h4>
                    <ul class="footer-links">
                        <li><a href="#">Costumes</a></li>
                        <li><a href="#">Accessories</a></li>
                        <li><a href="#">New Arrivals</a></li>
                        <li><a href="#">Victorian</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Newsletter</h4>
                    <p>Subscribe to get special offers and latest updates.</p>
                    <form action="#" class="newsletter-form">
                        <input type="email" placeholder="Your email address" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <div>&copy; {{ date('Y') }} Gothic Clothing. All rights reserved.</div>
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms & Conditions</a>
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