// Gothic Clothing - Modern Interactive & Animation Script

document.addEventListener('DOMContentLoaded', () => {
    // 1. Hamburger Navigation Toggle (Mobile)
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const navLinks = document.getElementById('navLinks');

    if (hamburgerBtn && navLinks) {
        hamburgerBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            const icon = hamburgerBtn.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-xmark');
            }
        });
    }

    // 2. Gothic Scroll Reveal Animations (Intersection Observer)
    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -50px 0px',
        threshold: 0.15
    };

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Automatically attach reveal animation to key UI components
    const revealSelectors = [
        '.section-title',
        '.section-title-left',
        '.who-we-are-image',
        '.who-we-are-desc',
        '.stat-box',
        '.product-card',
        '.product-card-v2',
        '.step-box',
        '.testi-card',
        '.team-card',
        '.faq-item',
        '.reveal-on-scroll'
    ];

    document.querySelectorAll(revealSelectors.join(', ')).forEach((el, index) => {
        if (!el.classList.contains('reveal-on-scroll')) {
            el.classList.add('reveal-on-scroll');
            // Stagger animation for grid children
            const delayIndex = (index % 4) + 1;
            el.classList.add(`stagger-delay-${delayIndex}`);
        }
        revealObserver.observe(el);
    });

    // 3. Navbar background blur on scroll
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(10, 10, 10, 0.98)';
                navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.7)';
            } else {
                navbar.style.background = 'rgba(10, 10, 10, 0.95)';
                navbar.style.boxShadow = 'none';
            }
        });
    }
});
