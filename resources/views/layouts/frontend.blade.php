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
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="{{ route('home') }}" class="navbar-brand">GOTHIC CLOTHING</a>
            <ul class="navbar-nav">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('collection') }}">Collection</a></li>
                <li><a href="{{ route('accessories') }}">Accessories</a></li>
                <li><a href="{{ route('home') }}#bestseller">Best Seller</a></li>
                <li><a href="{{ route('home') }}#how-to-rent">How To Rent</a></li>
                <li><a href="{{ route('home') }}#testimonials">Testimonials</a></li>
                <li><a href="{{ route('home') }}#faq">FAQ</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
            <div class="navbar-icons">
                <a href="#"><i class="fas fa-search"></i></a>
                <a href="{{ route('login') }}"><i class="far fa-user"></i></a>
            </div>
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
</body>
</html>