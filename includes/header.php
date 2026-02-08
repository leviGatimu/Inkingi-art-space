<?php include_once __DIR__ . '/tracker.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inkingi Arts Space</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
</head>
<body>

<div id="preloader">
    <div class="loader">
        <img src="assets/images/logo.svg" alt="Inkingi Arts Logo" class="loader-logo">
    </div>
</div>

<header class="main-header">
    <nav class="main-nav">
        <a href="index.php" class="nav-logo">
            <img src="assets/images/logo.svg" alt="Inkingi Arts Space Logo">
            <div class="logo-text">
                <h1>Inkingi</h1>
                <p>Arts Space</p>
            </div>
        </a>
        
        <ul class="nav-links">
             <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="programs.php">Programs</a></li>
            <li><a href="events.php">Events & News</a></li>
             <li><a href="gallery.php">Gallery</a></li>
            <li><a href="artists.php"> Artists</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="artist/artist_login.php" class="nav-join">Artist login</a></li>
        </ul>

        <button class="hamburger" id="hamburgerBtn" aria-label="Open Mobile Menu">
            <i class="fas fa-bars"></i>
        </button>
    </nav>
</header>

<div class="menu-overlay" id="menuOverlay" aria-hidden="true"></div>

<nav class="mobile-menu glass-effect" id="mobileMenu" aria-hidden="true">
    
    <div class="mobile-header-section">
        <div class="mobile-brand">
            <img src="assets/images/logo.svg" alt="Logo">
            <div>
                <h3>Inkingi</h3>
                <span>Arts Space</span>
            </div>
        </div>
        <button class="close-btn" id="closeMenuBtn" aria-label="Close Mobile Menu">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="mobile-scroll-area">
        <ul class="mobile-links">
            <li style="--i:1;"><a href="index.php" class="mobile-link"><i class="fas fa-home"></i> Home</a></li>
            <li style="--i:2;"><a href="about.php" class="mobile-link"><i class="fas fa-info-circle"></i> About Us</a></li>
            <li style="--i:3;"><a href="programs.php" class="mobile-link"><i class="fas fa-book"></i> Programs</a></li>
            <li style="--i:4;"><a href="events.php" class="mobile-link"><i class="fas fa-calendar-alt"></i> Events</a></li>
            <li style="--i:4;"><a href="gallery.php" class="mobile-link"><i class="fas fa-calendar-alt"></i> Gallery</a></li>
            <li style="--i:4;"><a href="artists.php" class="mobile-link"><i class="fas fa-calendar-alt"></i> Artists</a></li>
            <li style="--i:5;"><a href="contact.php" class="mobile-link"><i class="fas fa-envelope"></i> Contact</a></li>
            <li style="--i:6;"><a href="artist/artist_login.php" class="mobile-link highlight"><i class="fas fa-user-circle"></i> Artist Login</a></li>
        </ul>
    </div>

    <div class="mobile-footer">
        <div class="mobile-socials">
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-facebook-f"></i></a>
        </div>
        <p class="mobile-copyright">&copy; 2024 Inkingi Arts Space</p>
    </div>
</nav>

<style>
    /* Global Variables */
    :root {
        --primary: #2C3E50;
        --accent: #FDB913;
        --light: #f8f9fa;
        --dark: #212529;
        --radius: 16px;
        --shadow-sm: 0 4px 12px rgba(0,0,0,0.06);
        --font-serif: 'Playfair Display', serif;
        --font-sans: 'Poppins', sans-serif;
    }

    body {
        font-family: var(--font-sans);
        background: var(--light);
        color: var(--dark);
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    /* --- Header Styles (Existing) --- */
    .main-header {
        position: absolute; top: 0; left: 0; width: 100%; z-index: 1000;
        transition: background 0.3s ease, backdrop-filter 0.3s ease;
    }
    .main-header.scrolled {
        position: fixed; background: rgba(44, 62, 80, 0.9); backdrop-filter: blur(10px); box-shadow: var(--shadow-sm);
    }
    .main-nav {
        display: flex; justify-content: space-between; align-items: center;
        max-width: 1280px; margin: 0 auto; padding: 20px 40px;
    }
    .nav-logo { display: flex; align-items: center; text-decoration: none; }
    .nav-logo img { height: 60px; width: auto; transition: 0.3s; }
    .main-header.scrolled .nav-logo img { height: 50px; }
    .logo-text { margin-left: 12px; line-height: 1.1; }
    .logo-text h1 { font-family: var(--font-serif); font-size: 1.4rem; color: white; margin: 0; font-weight: 700; }
    .logo-text p { font-family: var(--font-sans); font-size: 0.85rem; color: var(--accent); margin: 0; text-transform: uppercase; }
    
    .nav-links { display: flex; gap: 32px; list-style: none; margin: 0; padding: 0; }
    .nav-links a { color: white; text-decoration: none; font-weight: 500; font-size: 1rem; position: relative; }
    .nav-links a::after { content: ''; position: absolute; bottom: -4px; left: 0; width: 0; height: 2px; background: var(--accent); transition: width 0.3s ease; }
    .nav-links a:hover::after { width: 100%; }
    
    .nav-join {
        border: none; box-shadow: 0px 0px 8px grey; padding: 10px 20px; border-radius: 5px;
        background-color: var(--accent); color: var(--primary) !important; font-weight: 600;
    }
    .nav-join:hover { transform: translateY(-2px); box-shadow: 0px 4px 12px rgba(253,185,19,0.4); }
    .nav-join::after { display: none; }

    .hamburger {
        display: none; background: none; border: none; color: white;
        font-size: 1.8rem; cursor: pointer; transition: 0.3s;
    }
    .hamburger:hover { color: var(--accent); }

    /* --- IMPROVED MOBILE SIDEBAR CSS --- */
    
    /* 1. Glassmorphism Container */
    .mobile-menu {
        position: fixed; top: 0; right: -100%;
        width: 85%; max-width: 360px; height: 100vh;
        z-index: 1100;
        /* The Blur Effect */
        background: rgba(20, 30, 40, 0.75); 
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-left: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: -10px 0 30px rgba(0,0,0,0.3);
        
        display: flex; flex-direction: column;
        transition: right 0.5s cubic-bezier(0.77, 0, 0.175, 1);
    }

    .mobile-menu.active { right: 0; }

    /* 2. Mobile Header */
    .mobile-header-section {
        padding: 30px 25px;
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .mobile-brand { display: flex; align-items: center; gap: 12px; }
    .mobile-brand img { height: 45px; width: auto; }
    .mobile-brand h3 { color: white; margin: 0; font-family: var(--font-serif); font-size: 1.4rem; line-height: 1; }
    .mobile-brand span { color: var(--accent); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }

    .close-btn {
        background: rgba(255,255,255,0.1); border: none; color: white;
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; cursor: pointer; transition: 0.3s;
    }
    .close-btn:hover { background: var(--accent); color: var(--primary); transform: rotate(90deg); }

    /* 3. Mobile Links Area */
    .mobile-scroll-area {
        flex: 1; padding: 30px 20px; overflow-y: auto;
    }

    .mobile-links { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 15px; }

    .mobile-link {
        display: flex; align-items: center; gap: 15px;
        color: rgba(255,255,255,0.9); text-decoration: none;
        font-size: 1.1rem; font-weight: 500;
        padding: 14px 20px; border-radius: 12px;
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.02);
        transition: 0.3s;
        /* Animation Setup */
        opacity: 0; transform: translateX(20px);
    }

    /* Link Hover State */
    .mobile-link:hover {
        background: rgba(253, 185, 19, 0.15); /* Accent tint */
        color: var(--accent);
        border-color: rgba(253, 185, 19, 0.3);
        transform: translateX(5px);
    }

    .mobile-link i { width: 24px; text-align: center; color: var(--accent); font-size: 1.2rem; transition: 0.3s; }
    .mobile-link:hover i { transform: scale(1.1); }

    /* Special style for Artist Login */
    .mobile-link.highlight {
        background: var(--accent); color: var(--primary); font-weight: 700;
        margin-top: 10px; border: none; box-shadow: 0 4px 15px rgba(253,185,19,0.3);
    }
    .mobile-link.highlight i { color: var(--primary); }
    .mobile-link.highlight:hover { background: white; transform: translateY(-3px); }

    /* 4. Mobile Footer */
    .mobile-footer {
        padding: 25px;
        border-top: 1px solid rgba(255,255,255,0.1);
        text-align: center;
        background: rgba(0,0,0,0.2);
    }

    .mobile-socials { display: flex; justify-content: center; gap: 20px; margin-bottom: 15px; }
    .mobile-socials a {
        width: 36px; height: 36px; border-radius: 50%;
        background: rgba(255,255,255,0.1); color: white;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; transition: 0.3s; font-size: 1rem;
    }
    .mobile-socials a:hover { background: var(--accent); color: var(--primary); transform: translateY(-3px); }

    .mobile-copyright { color: rgba(255,255,255,0.4); font-size: 0.8rem; margin: 0; }

    /* 5. Menu Animations */
    .mobile-menu.active .mobile-link {
        animation: slideInLinks 0.5s ease forwards;
        animation-delay: calc(0.1s * var(--i));
    }

    @keyframes slideInLinks {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .menu-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
        z-index: 1050; opacity: 0; visibility: hidden; transition: 0.4s;
    }
    .menu-overlay.active { opacity: 1; visibility: visible; }

    /* Loader */
    #preloader {
        position: fixed; inset: 0; background: #fff; z-index: 9999;
        display: flex; align-items: center; justify-content: center;
        transition: opacity 0.8s ease, visibility 0.8s ease;
    }
    #preloader.hidden { opacity: 0; visibility: hidden; }
    .loader-logo { width: 140px; animation: logoPulse 2s infinite ease-in-out; }
    @keyframes logoPulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.08); } }

    /* Responsive */
    @media (max-width: 992px) {
        .nav-links { display: none; }
        .hamburger { display: block; }
        .main-nav { padding: 15px 20px; }
        .logo-text h1 { font-size: 1.2rem; }
    }
</style>

<script>
    // Preloader
    window.addEventListener('load', () => {
        const preloader = document.getElementById('preloader');
        setTimeout(() => {
            preloader.classList.add('hidden');
            setTimeout(() => preloader.remove(), 800);
        }, 500);
    });

    // Mobile Menu Logic
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const closeMenuBtn = document.getElementById('closeMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuOverlay = document.getElementById('menuOverlay');
    const body = document.body;

    function toggleMenu() {
        const isActive = mobileMenu.classList.contains('active');
        if (!isActive) {
            mobileMenu.classList.add('active');
            menuOverlay.classList.add('active');
            body.style.overflow = 'hidden'; // Lock scroll
        } else {
            mobileMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
            body.style.overflow = ''; // Unlock scroll
        }
    }

    hamburgerBtn.addEventListener('click', toggleMenu);
    closeMenuBtn.addEventListener('click', toggleMenu);
    menuOverlay.addEventListener('click', toggleMenu);

    // Header Scroll
    window.addEventListener('scroll', () => {
        const header = document.querySelector('.main-header');
        header.classList.toggle('scrolled', window.scrollY > 50);
    });

    AOS.init({ duration: 800, once: true });
</script>

</body>
</html>