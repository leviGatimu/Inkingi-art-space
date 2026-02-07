<?php include_once __DIR__ . '/tracker.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inkingi Arts Space</title>
    <!-- Global Fonts & Icons (Consistent with other pages) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- AOS for animations if needed across site -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
</head>
<body>

<!-- Preloader (Different High-Class: Elegant progress bar with logo pulse and subtle particle-like dots for artistic vibe) -->
<div id="preloader">
    <div class="loader">
        <img class="loader-logo" src="assets/images/logo.svg" alt="Inkingi Arts Logo">
        <div class="loader-bar">
            <div class="loader-progress"></div>
        </div>
        <span class="loader-text">Unveiling Masterpieces...</span>
        <div class="loader-dots">
            <span></span><span></span><span></span>
        </div>
    </div>
</div>

<!-- Navigation (Restructured: Cleaner HTML, better semantics, consistent with programs page) -->
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
             <li><a href="index.php#about">Home</a></li>
            <li><a href="index.php#about">About</a></li>
            <li><a href="programs.php">Programs</a></li>
            <li><a href="index.php#gallery">Gallery</a></li>
            <li><a href="index.php#events">Events & News</a></li>
            <li><a href="index.php#contact">Contact</a></li>
            <li><a href="#" class="nav-join">Join now</a></li>
        </ul>
        <button class="hamburger" id="hamburgerBtn" aria-label="Open Mobile Menu">
            <i class="fas fa-bars"></i>
        </button>
    </nav>
</header>

<!-- Mobile Menu (Improved: Better transitions, accessibility, professional styling with icons and logo) -->
<div class="menu-overlay" id="menuOverlay" aria-hidden="true"></div>
<nav class="mobile-menu" id="mobileMenu" aria-hidden="true">
    <button class="close-btn" id="closeMenuBtn" aria-label="Close Mobile Menu">
        <i class="fas fa-times"></i>
    </button>
    <div class="mobile-logo">
        <img src="assets/images/logo.svg" alt="Inkingi Arts Space Logo">
        <div class="logo-text">
            <h1>Inkingi</h1>
            <p>Arts Space</p>
        </div>
    </div>
    <ul class="mobile-links">
        <li><a href="index.php" class="mobile-link"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="index.php#about" class="mobile-link"><i class="fas fa-info-circle"></i> About Us</a></li>
        <li><a href="programs.php" class="mobile-link"><i class="fas fa-book"></i> Programs</a></li>
        <li><a href="index.php#gallery" class="mobile-link"><i class="fas fa-images"></i> Gallery</a></li>
        <li><a href="index.php#events" class="mobile-link"><i class="fas fa-calendar-alt"></i> Events</a></li>
        <li><a href="index.php#contact" class="mobile-link"><i class="fas fa-envelope"></i> Contact</a></li>
        <li><a href="#" class="mobile-join">Join now</a></li>
    </ul>
</nav>

<style>
    /* Global Variables (Consistent with programs.php) */
    :root {
        --primary: #2C3E50;
        --accent: #FDB913; /* Renamed to --accent for simplicity */
        --green: #009E60;
        --red: #C8102E;
        --light: #f8f9fa;
        --gray: #6c757d;
        --dark: #212529;
        --radius: 16px;
        --shadow-sm: 0 4px 12px rgba(0,0,0,0.06);
        --shadow-md: 0 10px 30px rgba(0,0,0,0.08);
        --transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
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

    /* Preloader (Different High-Class: Elegant progress bar with logo pulse and subtle particle-like dots) */
    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--light), white);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 1;
        transition: opacity 1.2s ease, visibility 1.2s ease;
    }

    #preloader.hidden {
        opacity: 0;
        visibility: hidden;
    }

    .loader {
        text-align: center;
        position: relative;
        animation: loaderFadeIn 1.2s ease forwards;
    }

    @keyframes loaderFadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .loader-logo {
        width: 120px;
        height: auto;
        margin-bottom: 25px;
        animation: logoPulse 1.5s infinite ease-in-out;
        filter: drop-shadow(0 5px 15px rgba(253,185,19,0.1));
    }

    @keyframes logoPulse {
        0%, 100% { transform: scale(1); opacity: 0.9; }
        50% { transform: scale(1.05); opacity: 1; }
    }

    .loader-bar {
        width: 250px;
        height: 4px;
        background: rgba(253,185,19,0.2);
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 15px;
        position: relative;
    }

    .loader-progress {
        height: 100%;
        width: 0;
        background: linear-gradient(90deg, var(--accent), var(--green));
        animation: progressFill 3s linear infinite;
    }

    @keyframes progressFill {
        0% { width: 0; }
        100% { width: 100%; }
    }

    .loader-text {
        font-family: var(--font-serif);
        font-size: 1.2rem;
        color: var(--primary);
        letter-spacing: 1.5px;
        text-transform: uppercase;
        opacity: 0.8;
        animation: textGlow 2s infinite alternate;
    }

    @keyframes textGlow {
        0% { text-shadow: 0 0 5px rgba(253,185,19,0.3); }
        100% { text-shadow: 0 0 15px rgba(253,185,19,0.6); }
    }

    .loader-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .loader-dots span {
        width: 8px;
        height: 8px;
        background: var(--accent);
        border-radius: 50%;
        animation: dotBounce 1.5s infinite ease-in-out;
    }

    .loader-dots span:nth-child(2) { animation-delay: 0.3s; }
    .loader-dots span:nth-child(3) { animation-delay: 0.6s; }

    @keyframes dotBounce {
        0%, 100% { transform: translateY(0); opacity: 0.6; }
        50% { transform: translateY(-10px); opacity: 1; }
    }

    /* Header & Nav (Restructured: Fixed on scroll with blurry background, better spacing) */
    .main-header {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
        background: transparent;
        transition: background 0.3s ease, backdrop-filter 0.3s ease;
    }

    .main-header.scrolled {
        position: fixed;
        background: rgba(44, 62, 80, 0.9); /* Semi-transparent primary color */
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: var(--shadow-sm);
    }

    .main-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1280px;
        margin: 0 auto;
        padding: 20px 40px;
    }

    .nav-logo {
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .nav-logo img {
        height: 60px;
        width: auto;
        transition: var(--transition);
    }

    .main-header.scrolled .nav-logo img {
        height: 50px;
    }

    .logo-text {
        display: flex;
        flex-direction: column;
        margin-left: 12px;
        line-height: 1.1;
    }

    .logo-text h1 {
        font-family: var(--font-serif);
        font-size: 1.4rem;
        margin: 0;
        color: white;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .logo-text p {
        font-family: var(--font-sans);
        font-size: 0.85rem;
        margin: 0;
        color: var(--accent);
        font-weight: 400;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .nav-links {
        display: flex;
        gap: 32px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        font-size: 1rem;
        position: relative;
        transition: var(--transition);
    }

    .nav-links a::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--accent);
        transition: width 0.3s ease;
    }

    .nav-links a:hover::after,
    .nav-links a.active::after {
        width: 100%;
    }

    .nav-join {
        border: none;
        box-shadow: 0px 0px 8px grey;
        padding: 10px 20px;
        border-radius: 5px;
        background-color: var(--accent);
        color: var(--primary) !important;
        font-weight: 600;
    }

    .nav-join:hover {
        transform: translateY(-2px);
        box-shadow: 0px 4px 12px rgba(253,185,19,0.4);
    }

    .hamburger {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 1.8rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .hamburger:hover {
        color: var(--accent);
    }

    /* Mobile Menu (Improved: Professional styling inspired by modern designs - icons, logo, join button, subtle shadows, better spacing) */
    .mobile-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 80%;
        max-width: 320px;
        height: 100vh;
        background: var(--primary);
        z-index: 1100;
        padding: 40px 32px;
        transition: right 0.4s var(--transition);
        box-shadow: -8px 0 24px rgba(0,0,0,0.15);
        display: flex;
        flex-direction: column;
    }

    .mobile-menu.active {
        right: 0;
    }

    .close-btn {
        position: absolute;
        top: 24px;
        right: 24px;
        background: none;
        border: none;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .close-btn:hover {
        transform: rotate(90deg);
        color: var(--accent);
    }

    .mobile-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 48px;
        flex-direction: column;
    }

    .mobile-logo img {
        height: 80px;
        width: auto;
        margin-bottom: 8px;
    }

    .mobile-logo .logo-text h1 {
        font-size: 1.8rem;
        color: white;
    }

    .mobile-logo .logo-text p {
        font-size: 1rem;
        color: var(--accent);
    }

    .mobile-links {
        list-style: none;
        padding: 0;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 24px;
        width: 100%;
        flex-grow: 1;
    }

    .mobile-link {
        color: white;
        text-decoration: none;
        font-size: 1.4rem;
        font-weight: 500;
        font-family: var(--font-sans);
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: var(--radius);
        background: rgba(255,255,255,0.05);
        box-shadow: var(--shadow-sm);
    }

    .mobile-link:hover {
        color: var(--accent);
        background: rgba(255,255,255,0.1);
        transform: translateX(5px);
    }

    .mobile-link i {
        font-size: 1.2rem;
        color: inherit;
    }

    .mobile-join {
        color: var(--primary);
        background: var(--accent);
        text-decoration: none;
        font-size: 1.4rem;
        font-weight: 600;
        text-align: center;
        padding: 16px;
        border-radius: var(--radius);
        transition: var(--transition);
        margin-top: auto;
        box-shadow: var(--shadow-md);
    }

    .mobile-join:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(253,185,19,0.3);
    }

    .menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        z-index: 1050;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease;
    }

    .menu-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Responsive Design (Consistent breakpoints with programs.php) */
    @media (max-width: 992px) {
        .nav-links {
            display: none;
        }

        .hamburger {
            display: block;
        }

        .main-nav {
            padding: 20px 24px;
        }

        .logo-text h1 {
            font-size: 1.2rem;
        }

        .logo-text p {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .nav-logo img {
            height: 50px;
        }
    }
</style>

<script>
    // Preloader (High-Class: Delay for smoothness, elegant fade)
    window.addEventListener('load', () => {
        setTimeout(() => {
            const preloader = document.getElementById('preloader');
            preloader.classList.add('hidden');
        }, 500); // Slight delay for smoothness
    });

    // Mobile Menu Toggle (Improved: Accessibility, prevent scroll when open)
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const closeMenuBtn = document.getElementById('closeMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuOverlay = document.getElementById('menuOverlay');
    const body = document.body;

    function toggleMenu() {
        const isActive = mobileMenu.classList.toggle('active');
        menuOverlay.classList.toggle('active', isActive);
        body.style.overflow = isActive ? 'hidden' : ''; // Prevent scroll
        mobileMenu.setAttribute('aria-hidden', !isActive);
        menuOverlay.setAttribute('aria-hidden', !isActive);
    }

    hamburgerBtn.addEventListener('click', toggleMenu);
    closeMenuBtn.addEventListener('click', toggleMenu);
    menuOverlay.addEventListener('click', toggleMenu);

    // Close on ESC key for accessibility
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
            toggleMenu();
        }
    });

    // Header Scroll Effect (Add 'scrolled' class for sticky/fixed nav with blur)
    window.addEventListener('scroll', () => {
        const header = document.querySelector('.main-header');
        header.classList.toggle('scrolled', window.scrollY > 50);
    });

    // Initialize AOS if used site-wide (for added interactivity)
    AOS.init({ duration: 800, once: true, easing: 'ease-in-out-back' });
</script>