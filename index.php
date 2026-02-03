<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inkingi Arts Space | Where Culture Lives</title>
    <link rel="icon" type="image/png" href="assets/images/logo.svg">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500;600;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- 1. CORE VARIABLES & RESET --- */
        :root {
            --primary: #2C3E50;
            --accent-yellow: #FDB913;
            --accent-green: #009E60;
            --accent-red: #C8102E;
            --bg-light: #FAFAFA;
            --text-dark: #1A1A1A;
            --text-gray: #666666;
            --font-marker: 'Permanent Marker', cursive;
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Poppins', sans-serif;
            --transition-soft: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            --transition-bouncy: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: var(--font-sans);
            background-color: var(--bg-light);
            color: var(--text-dark);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            scroll-behavior: smooth;
        }

        /* --- CUSTOM SCROLLBAR --- */
        ::-webkit-scrollbar { width: 14px; }
        ::-webkit-scrollbar-track { background: var(--bg-light); }
        ::-webkit-scrollbar-thumb { 
            background: var(--primary); 
            border-radius: 10px; 
            border: 3px solid var(--bg-light);
        }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent-yellow); }

        /* --- 2. UTILITY CLASSES & ANIMATIONS --- */
        .text-yellow { color: var(--accent-yellow); }
        .text-green { color: var(--accent-green); }
        .text-red { color: var(--accent-red); }
        
        /* Floating Background Shapes Animation */
        .floating-shape {
            position: absolute;
            opacity: 0.06;
            z-index: -1;
            animation: float 25s infinite linear;
            pointer-events: none;
        }
        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg) scale(1); }
            33% { transform: translate(80px, 40px) rotate(120deg) scale(1.1); }
            66% { transform: translate(-40px, 80px) rotate(240deg) scale(0.9); }
            100% { transform: translate(0, 0) rotate(360deg) scale(1); }
        }

        /* --- 3. PRELOADER --- */
        #preloader {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: white; z-index: 9999;
            display: flex; justify-content: center; align-items: center;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }
        .loader-text span {
            font-family: var(--font-marker); font-size: 4rem;
            display: inline-block; animation: bounce 1.5s infinite;
        }
        .loader-text span:nth-child(2) { animation-delay: 0.2s; }
        .loader-text span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }

        /* --- 4. NAVIGATION (Desktop & Mobile) --- */
        .main-nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 5%; position: absolute; top: 0; left: 0; width: 100%; z-index: 100;
        }
        .nav-logo img { height: 50px; }
        .nav-links { display: flex; gap: 30px; list-style: none; }
        .nav-links a {
            color: white; text-decoration: none; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; transition: var(--transition-soft);
            position: relative;
        }
        .nav-links a::after {
            content: ''; position: absolute; bottom: -5px; left: 0; width: 0; height: 2px; background: var(--accent-yellow); transition: width 0.3s ease;
        }
        .nav-links a:hover::after { width: 100%; }
        .hamburger { display: none; color: white; font-size: 1.8rem; cursor: pointer; z-index: 101; }

        /* Mobile Menu Drawer */
        .mobile-menu {
            position: fixed; top: 0; right: -100%; width: 80%; max-width: 400px; height: 100vh;
            background: var(--primary); z-index: 100; padding: 100px 40px;
            transition: right 0.4s cubic-bezier(0.77, 0, 0.175, 1);
            box-shadow: -10px 0 30px rgba(0,0,0,0.2);
        }
        .mobile-menu.active { right: 0; }
        .mobile-menu ul { list-style: none; display: flex; flex-direction: column; gap: 25px; }
        .mobile-menu a { color: white; text-decoration: none; font-size: 1.5rem; font-weight: 700; }
        .menu-overlay {
            position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.7); z-index: 99;
            opacity: 0; visibility: hidden; transition: 0.3s;
        }
        .menu-overlay.active { opacity: 1; visibility: visible; }


        /* --- 5. HERO SECTION (Updated with Auto-Scroll & Progress) --- */
        .hero {
            height: 100vh; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; perspective: 1000px;
        }
        .hero-bg {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-size: cover; background-position: center;
            transform: scale(1.15); transition: opacity 0.8s ease, transform 0.1s linear; will-change: transform, opacity;
        }
        .hero-bg.fade-out { opacity: 0; }

        .hero-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(180deg, rgba(44, 62, 80, 0.3) 0%, rgba(26, 26, 26, 0.9) 100%); backdrop-filter: blur(2px);
        }
        .hero-content {
            position: relative; z-index: 10; text-align: center; color: white; opacity: 0;
            transform: translateY(40px) scale(0.95);
            animation: heroEntrance 1.2s cubic-bezier(0.215, 0.610, 0.355, 1.000) forwards 0.5s;
        }
        @keyframes heroEntrance { to { opacity: 1; transform: translateY(0) scale(1); } }
        .hero-title {
            font-size: clamp(3.5rem, 9vw, 7rem); font-weight: 800; line-height: 0.9; letter-spacing: -3px;
            text-shadow: 0 15px 30px rgba(0,0,0,0.4); margin-bottom: 20px;
        }
        .logo-center { margin-bottom: 30px; perspective: 1000px; display: inline-block; }
        .logo-tilt-box {
            width: 140px; height: auto; transition: transform 0.1s ease-out;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
        }
        .arrow {
            position: absolute; top: 50%; transform: translateY(-50%); color: white; font-size: 1.5rem; cursor: pointer; z-index: 20;
            background: rgba(255,255,255,0.05); width: 100px; height: 100px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; transition: var(--transition-bouncy);
            backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);
        }
        .arrow:hover { transform: translateY(-50%) scale(1.1); border-color: var(--accent-yellow); color: var(--accent-yellow); background: rgba(253, 185, 19, 0.1); }
        .arrow-left { left: 40px; } .arrow-right { right: 40px; }
        
        /* Hero Progress Bar */
        .hero-progress-container {
            position: absolute; bottom: 270px; left: 50%; transform: translateX(-50%);
            width: 200px; height: 4px; background: rgba(255,255,255,0.2); border-radius: 2px; z-index: 20; overflow: hidden;
        }
        .hero-progress-bar {
            height: 100%; width: 0%; background: var(--accent-yellow); border-radius: 2px;
        }
        /* Animation class added by JS */
        .hero-progress-bar.filling {
            animation: fillProgress 5s linear forwards; /* 5s matches autoplay speed */
        }
        @keyframes fillProgress { from { width: 0%; } to { width: 100%; } }

        .wave-bottom { position: absolute; bottom: -2px; left: 0; width: 100%; line-height: 0; z-index: 5; filter: drop-shadow(0 -10px 10px rgba(0,0,0,0.05)); }

        /* --- 6. SECTIONS GENERAL --- */
        .section-padding { padding: 120px 5%; position: relative; }
        .section-header { text-align: center; margin-bottom: 80px; }
        .section-subtitle {
            font-family: var(--font-marker); color: var(--accent-yellow); font-size: 1.3rem; display: block; margin-bottom: 15px; letter-spacing: 1px;
        }
        .section-title {
            font-family: var(--font-serif); font-size: 3.5rem; font-weight: 700; position: relative; display: inline-block;
        }
        .section-title::after {
            content: ''; position: absolute; bottom: 10px; left: 5%; width: 90%; height: 15px; background: rgba(253, 185, 19, 0.2); z-index: -1; transform: skewX(-10deg);
        }

        /* --- 7. SPLIT CONTENT & FRAMES --- */
        .content-row { display: flex; align-items: center; gap: 80px; position: relative; margin-bottom: 120px; }
        .content-row.reverse { flex-direction: row-reverse; }
        .image-col { flex: 1; position: relative; perspective: 1000px; }
        .text-col { flex: 1; }
        .square-frame {
            position: relative; border-radius: 20px; outline: 20px solid var(--accent-yellow); outline-offset: -20px;
            transition: var(--transition-bouncy); overflow: hidden; transform-style: preserve-3d;
        }
        .square-frame:hover { outline-color: var(--accent-green); outline-width: 10px; outline-offset: 10px; transform: rotateX(5deg) rotateY(-5deg); }
        .square-frame img { width: 100%; display: block; transition: transform 0.7s cubic-bezier(0.19, 1, 0.22, 1); border-radius: 20px; }
        .square-frame:hover img { transform: scale(1.08); }
        .text-col h2 { font-family: var(--font-serif); font-size: 2.8rem; margin-bottom: 25px; line-height: 1.2; }
        .text-col p { font-size: 1.15rem; line-height: 1.9; color: var(--text-gray); margin-bottom: 25px; }

        /* --- 8. IMPACT STATS --- */
        .stats-container {
            background: white; border-radius: 30px; margin-bottom: 100px; box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            display: flex; justify-content: space-around; flex-wrap: wrap; padding: 60px 30px; position: relative; overflow: hidden;
        }
        .stats-container::before {
            content: ''; position: absolute; top:0; left:0; width:100%; height:5px; background: linear-gradient(90deg, var(--accent-yellow), var(--accent-green), var(--accent-red));
        }
        .stat-item { text-align: center; padding: 20px; flex: 1 1 250px; transition: transform 0.3s ease; }
        .stat-item:hover { transform: translateY(-10px); }
        .stat-icon { font-size: 3.5rem; margin-bottom: 20px; display: inline-block; }
        .stat-number { font-size: 3.5rem; font-weight: 800; display: block; line-height: 1; margin-bottom: 10px; }
        .stat-label { font-size: 1.1rem; font-weight: 600; color: var(--text-gray); text-transform: uppercase; letter-spacing: 1px; }

        /* --- 9. PROGRAMS GRID (With Read More) --- */
        .programs-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px; }
        .program-card {
            background: white; padding: 50px 40px; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            transition: var(--transition-soft); border: 2px solid transparent; position: relative; overflow: hidden; text-align: left;
            display: flex; flex-direction: column; align-items: flex-start;
        }
        .program-card:hover { transform: translateY(-15px); box-shadow: 0 25px 50px rgba(0,0,0,0.1); border-color: #FDB91333; }
        .program-icon {
            font-size: 4rem; margin-bottom: 25px; display: block;
            background: linear-gradient(45deg, var(--accent-yellow), var(--accent-red)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 5px 5px rgba(0,0,0,0.1));
        }
        .program-card h3 { font-size: 1.8rem; margin-bottom: 15px; font-weight: 700; }
        .program-card p { margin-bottom: 25px; flex-grow: 1; }
        .read-more-link {
            color: var(--primary); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; transition: var(--transition-soft);
        }
        .read-more-link i { transition: transform 0.3s ease; }
        .read-more-link:hover { color: var(--accent-red); }
        .read-more-link:hover i { transform: translateX(5px); }


        /* --- 10. GALLERY & EVENTS --- */
        .gallery-grid { display: grid; grid-template-columns: repeat(4, 1fr); grid-template-rows: repeat(2, 300px); gap: 20px; }
        .gallery-item {
            overflow: hidden; border-radius: 15px; position: relative; cursor: pointer; box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s cubic-bezier(0.19, 1, 0.22, 1); }
        .gallery-item::after {
            content: ''; position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(0deg, rgba(0,0,0,0.6) 0%, transparent 50%); opacity: 0; transition: opacity 0.3s ease;
        }
        .gallery-item:hover::after { opacity: 1; }
        .gallery-item:hover img { transform: scale(1.1); }
        .gallery-item:nth-child(1) { grid-column: span 2; grid-row: span 2; }
        .gallery-item:nth-child(2) { grid-column: span 2; grid-row: span 1; }
        .gallery-item:nth-child(3) { grid-column: span 1; grid-row: span 1; }
        .gallery-item:nth-child(4) { grid-column: span 1; grid-row: span 1; }

        .events-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .event-card {
            background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: var(--transition-soft); display: flex;
        }
        .event-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .event-date {
            background: var(--accent-green); color: white; padding: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; min-width: 100px; text-align: center;
        }
        .event-date .day { font-size: 2rem; font-weight: 800; line-height: 1; }
        .event-date .month { font-size: 1rem; text-transform: uppercase; font-weight: 600; }
        .event-details { padding: 25px; flex: 1; }
        .event-details h4 { font-size: 1.3rem; margin-bottom: 10px; }
        .event-meta { color: var(--text-gray); font-size: 0.9rem; display: flex; gap: 15px; }
        .event-meta i { color: var(--accent-yellow); }

        /* --- 11. REFINED MAGNETIC BUTTONS --- */
        .btn-main, .btn-magnetic {
            display: inline-block; padding: 18px 45px; background: var(--text-dark); color: white;
            text-decoration: none; border-radius: 50px; font-weight: 600; letter-spacing: 1px;
            transition: all 0.3s ease; margin-top: 25px; border: 2px solid var(--text-dark);
            position: relative; z-index: 1; overflow: hidden;
        }
        /* Solid hover state instead of sliding fill */
        .btn-main:hover {
            background: var(--accent-yellow);
            border-color: var(--accent-yellow);
            color: var(--text-dark);
            box-shadow: 0 10px 25px -5px rgba(253, 185, 19, 0.6);
        }

        /* --- 12. ANIMATION TRIGGERS --- */
        .reveal { opacity: 0; transform: translateY(80px) scale(0.95); transition: all 1s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .reveal.active { opacity: 1; transform: translateY(0) scale(1); }
        .reveal.active .stagger-1 { transition-delay: 0.1s; }
        .reveal.active .stagger-2 { transition-delay: 0.2s; }
        .reveal.active .stagger-3 { transition-delay: 0.3s; }

        /* Responsive */
        @media (max-width: 992px) {
             .nav-links { display: none; } /* Hide desktop nav */
             .hamburger { display: block; } /* Show hamburger */
             .hero-title { font-size: 4.5rem; }
             .section-title { font-size: 2.8rem; }
             .content-row, .content-row.reverse { flex-direction: column; gap: 40px; text-align: center; }
             .square-frame:hover { transform: none; outline-width: 15px; }
             .program-card { text-align: center; padding: 40px 25px; align-items: center; }
             .gallery-grid { grid-template-columns: 1fr 1fr; grid-template-rows: auto; }
             .gallery-item:nth-child(1) { grid-column: span 2; height: 350px; }
             .gallery-item:nth-child(2) { grid-column: span 2; height: 250px; }
        }
        @media (max-width: 768px) {
            .arrow { width: 50px; height: 50px; font-size: 1.2rem; }
            .arrow-left { left: 20px; } .arrow-right { right: 20px; }
            .section-padding { padding: 80px 5%; }
            .stat-item { flex: 1 1 100%; }
            .event-card { flex-direction: column; }
            .event-date { flex-direction: row; gap: 10px; padding: 15px; width: 100%; }
            .event-date .day { font-size: 1.5rem; }
            .hero-progress-container { bottom: 140px; }
        }
    </style>
</head>
<?php include_once 'tracker.php'; ?>
<body>

    <div id="preloader">
        <div class="loader-text">
            <span class="text-yellow">I</span><span class="text-green">K</span><span class="text-red">S</span>
        </div>
    </div>

    <nav class="main-nav">
        <a href="index.php" class="nav-logo">
            <img src="assets/images/logo.svg" alt="Ikingi Arts">
        </a>
        <ul class="nav-links">
            <li><a href="#about">About</a></li>
            <li><a href="#programs">Programs</a></li>
            <li><a href="#gallery">Gallery</a></li>
            <li><a href="#events">Events</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <div class="hamburger" id="hamburgerBtn">
            <i class="fas fa-bars"></i>
        </div>
    </nav>

    <div class="menu-overlay" id="menuOverlay"></div>
    <div class="mobile-menu" id="mobileMenu">
        <div style="text-align: right; margin-bottom: 40px;">
            <i class="fas fa-times" id="closeMenuBtn" style="color: white; font-size: 2rem; cursor: pointer;"></i>
        </div>
        <ul>
            <li><a href="index.php" class="mobile-link">Home</a></li>
            <li><a href="#about" class="mobile-link">About Us</a></li>
            <li><a href="#programs" class="mobile-link">Programs</a></li>
            <li><a href="#gallery" class="mobile-link">Gallery</a></li>
            <li><a href="#events" class="mobile-link">Events</a></li>
            <li><a href="#contact" class="mobile-link">Contact</a></li>
        </ul>
    </div>

    <svg class="floating-shape" style="top: 15%; left: 5%; width: 120px;" viewBox="0 0 200 200">
        <path fill="#FDB913" d="M44.7,-76.4C58.9,-69.2,71.8,-59.1,81.6,-46.6C91.4,-34.1,98.2,-19.2,95.8,-5.2C93.5,8.9,82,22.1,70.8,33.4C59.6,44.7,48.7,54.1,36.6,62.3C24.5,70.5,11.2,77.5,-2.9,82.5C-17,87.5,-32,90.5,-44.6,84.9C-57.2,79.3,-67.5,65.1,-75.7,50.7C-83.9,36.3,-90.1,21.7,-90.7,6.8C-91.3,-8.1,-86.3,-23.3,-77.2,-36.4C-68.1,-49.5,-54.9,-60.5,-41.3,-68.1C-27.7,-75.7,-13.9,-79.9,0.5,-80.7C14.8,-81.6,29.7,-79,44.7,-76.4Z" transform="translate(100 100)" />
    </svg>
    <svg class="floating-shape" style="bottom: 25%; right: 8%; width: 180px; animation-delay: -12s; opacity: 0.04;" viewBox="0 0 200 200">
        <path fill="#009E60" d="M41.7,-68.3C52.6,-60.3,59.3,-46.2,65.6,-32.7C71.9,-19.2,77.8,-6.3,77.2,6.3C76.5,18.8,69.4,31,60.3,41.9C51.2,52.8,40.1,62.4,27.7,68.4C15.3,74.4,1.6,76.8,-11.2,75.4C-24,74,-35.9,68.8,-46.9,61.4C-57.9,54,-68,44.4,-74.6,32.6C-81.2,20.8,-84.3,6.8,-82.1,-6.3C-79.9,-19.4,-72.4,-31.6,-62.7,-41.2C-53,-50.8,-41.1,-57.8,-29.1,-64.8C-17.1,-71.8,-5.1,-78.8,7.9,-80C20.9,-81.2,33.9,-76.6,41.7,-68.3Z" transform="translate(100 100)" />
    </svg>

    <header class="hero">
        <div class="hero-bg" id="hero-bg" style="background-image: url('https://images.mindtrip.ai/attractions/96d2/b5fa/9bab/f527/e4a8/29d8/2bd6/ad03');"></div>
        <div class="hero-overlay"></div>

        <div class="arrow arrow-left btn-magnetic" id="btn-prev"><i class="fas fa-chevron-left"></i></div>
        <div class="arrow arrow-right btn-magnetic" id="btn-next"><i class="fas fa-chevron-right"></i></div>

        <div class="hero-content">
            <div class="logo-center">
                <img src="assets/images/logo.svg" alt="Ikingi Arts Logo" class="logo-tilt-box" id="tiltLogo">
            </div>
            <h1 class="hero-title">
                <span class="text-yellow">INKINGI</span>
                <span class="text-green">ARTS</span>
                <span class="text-red">SPACE</span>
            </h1>
            <p style="font-size: 1.5rem; letter-spacing: 3px; text-transform: uppercase; font-weight: 300; color:white;">The Heartbeat of Creativity in Kigali</p>
        </div>

        <div class="hero-progress-container">
            <div class="hero-progress-bar" id="heroProgressBar"></div>
        </div>

        <div class="wave-bottom">
            <svg viewBox="0 0 1440 320" preserveAspectRatio="none" style="width: 100%; height: 140px;">
                <path fill="#ffffff" fill-opacity="1" d="M0,160L48,144C96,128,192,96,288,106.7C384,117,480,171,576,181.3C672,192,768,160,864,138.7C960,117,1056,107,1152,117.3C1248,128,1344,160,1392,176L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,576,320,480,320C384,320,288,320,0,320Z"></path>
            </svg>
        </div>
    </header>

    <main class="container" style="max-width: 1300px; margin: 0 auto; position: relative; z-index: 10;">

        <section class="section-padding reveal" id="about">
            <div class="content-row">
                <div class="image-col">
                    <div class="square-frame" data-tilt>
                        <img src="https://images.mindtrip.ai/attractions/0edf/62ec/d36d/49fd/1a22/4e79/36ab/8d2c" alt="Bonfire Culture">
                    </div>
                </div>
                <div class="text-col">
                    <span class="section-subtitle stagger-1">Welcome Home</span>
                    <h2 class="stagger-2">A Pillar for <span class="text-yellow">Creatives</span></h2>
                    <p class="stagger-3"><strong>"Inkingi"</strong> implies a pillar—a support structure. We are the foundation for the next generation of Rwandan artists.</p>
                    <p class="stagger-3">Located in the heart of Kigali, we are a creative hub where art, culture, and community collide. From vibrant exhibitions to intimate open mic nights, we provide the space for artists to stand tall and stories to be told.</p>
                    <a href="about.php" class="btn-main btn-magnetic stagger-3">Discover Our Story <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></a>
                </div>
            </div>
        </section>

        <section class="section-padding reveal">
            <div class="content-row reverse">
                <div class="image-col">
                    <div class="square-frame" style="outline-color: var(--accent-red);" data-tilt>
                        <img src="https://images.mindtrip.ai/attractions/e3ac/3f38/43c7/bcc8/090c/817c/cbc6/4ebf" alt="Art Gallery">
                    </div>
                </div>
                <div class="text-col">
                    <span class="section-subtitle stagger-1">Our Legacy</span>
                    <h2 class="stagger-2">Bridging <span class="text-red">Tradition</span> & Future</h2>
                    <p class="stagger-3">Founded with a vision to preserve and evolve Rwandan storytelling, Ikingi Arts Space started as a small gathering of painters and poets. Today, it stands as a testament to the resilience of our culture.</p>
                    <p class="stagger-3">We bridge the gap between traditional craftsmanship and contemporary digital art, ensuring that the history of Rwanda is not just remembered, but recreated daily.</p>
                </div>
            </div>
        </section>

        <section class="reveal" style="padding: 0 5%;">
            <div class="stats-container">
                <div class="stat-item stagger-1">
                    <i class="fas fa-palette stat-icon text-yellow"></i>
                    <span class="stat-number" data-target="500">0</span>
                    <span class="stat-label">Art Pieces Created</span>
                </div>
                <div class="stat-item stagger-2">
                    <i class="fas fa-users stat-icon text-green"></i>
                    <span class="stat-number" data-target="150">0</span>
                    <span class="stat-label">Active Artists</span>
                </div>
                <div class="stat-item stagger-3">
                    <i class="fas fa-shoe-prints stat-icon text-red"></i>
                    <span class="stat-number" data-target="1200">0</span>
                    <span class="stat-label">Monthly Visitors</span>
                </div>
            </div>
        </section>

        <section class="section-padding reveal" id="programs">
            <div class="section-header">
                <span class="section-subtitle stagger-1">What We Do</span>
                <h2 class="section-title stagger-2">Our <span class="text-green">Programs</span></h2>
            </div>

            <div class="programs-grid stagger-3">
                <div class="program-card">
                    <i class="fas fa-paint-brush program-icon" style="-webkit-text-fill-color: transparent; -webkit-background-clip: text; background-image: linear-gradient(45deg, var(--accent-yellow), var(--accent-red));"></i>
                    <h3>Workshops</h3>
                    <p>From traditional basket weaving (Agaseke) to modern acrylic painting. Our workshops are open to kids and adults every weekend.</p>
                    <a href="programs.php#workshops" class="read-more-link">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="program-card">
                    <i class="fas fa-microphone-alt program-icon" style="-webkit-text-fill-color: transparent; -webkit-background-clip: text; background-image: linear-gradient(45deg, var(--accent-green), var(--accent-yellow));"></i>
                    <h3>Cultural Nights</h3>
                    <p>Poetry, acoustic music, and storytelling around the fire. Join us every Friday for "Ikingi Vibes."</p>
                    <a href="programs.php#cultural" class="read-more-link">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="program-card">
                    <i class="fas fa-image program-icon" style="-webkit-text-fill-color: transparent; -webkit-background-clip: text; background-image: linear-gradient(45deg, var(--accent-red), var(--accent-green));"></i>
                    <h3>Exhibitions</h3>
                    <p>Monthly rotating art showcases featuring emerging Rwandan artists. Support local talent and buy original art.</p>
                    <a href="programs.php#exhibitions" class="read-more-link">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </section>

        <section class="section-padding reveal">
            <div style="background: var(--primary); color: white; border-radius: 30px; padding: 80px 60px; position: relative; overflow: hidden; box-shadow: 0 30px 60px rgba(44, 62, 80, 0.3);">
                <div style="position: absolute; top: -100px; right: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(253,185,19,0.2) 0%, rgba(255,255,255,0) 70%); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -50px; left: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(0,158,96,0.2) 0%, rgba(255,255,255,0) 70%); border-radius: 50%;"></div>
                
                <div class="content-row" style="margin-bottom: 0; align-items: flex-start;">
                    <div class="image-col stagger-1" style="flex: 0 0 350px;">
                        <div style="position: relative;">
                             <img src="https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=800&q=80" alt="Sarah Uwera" style="width: 100%; border-radius: 20px; border: 5px solid rgba(255,255,255,0.1); box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
                             <div style="position: absolute; bottom: -20px; right: -20px; background: var(--accent-yellow); color: var(--primary); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;"><i class="fas fa-quote-right"></i></div>
                        </div>
                    </div>
                    <div class="text-col stagger-2">
                        <span class="section-subtitle" style="color: rgba(255,255,255,0.7);">Meet The Creative Director</span>
                        <h2 style="color: white; margin-bottom: 20px;">Sarah Uwera</h2>
                        <p style="color: rgba(255,255,255,0.9); font-style: italic; font-size: 1.3rem; font-weight: 300; line-height: 1.6;">"I created Ikingi to be a home. A place where the walls talk through color and the people connect through culture. We are not just a gallery; we are a movement."</p>
                        <div class="stagger-3" style="margin-top: 30px;">
                             <img src="assets/images/signature.png" alt="Signature" style="height: 60px; filter: invert(1); opacity: 0.8;">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-padding reveal" id="gallery">
            <div class="section-header">
                <span class="section-subtitle stagger-1">Visual Journey</span>
                <h2 class="section-title stagger-2">Latest <span class="text-yellow">Masterpieces</span></h2>
            </div>
            
            <div class="gallery-grid stagger-3">
                <div class="gallery-item"><img src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/31/02/2c/ac/social-community-living.jpg?w=1200&h=-1&s=1" alt="Art 1"></div>
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?w=800&q=80" alt="Art 2"></div>
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=800&q=80" alt="Art 3"></div>
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1549490349-8643362247b5?w=800&q=80" alt="Art 4"></div>
            </div>
            
            <div class="stagger-3" style="text-align: center; margin-top: 60px;">
                <a href="artwork.php" class="btn-main btn-magnetic">View Full Gallery <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></a>
            </div>
        </section>

        <section class="section-padding reveal" id="events" style="background: white; border-top: 1px solid rgba(0,0,0,0.05);">
            <div class="section-header">
                 <span class="section-subtitle stagger-1">Join Us</span>
                <h2 class="section-title stagger-2">Upcoming <span class="text-red">Events</span></h2>
            </div>
            <div class="events-grid stagger-3">
                <div class="event-card">
                    <div class="event-date">
                        <span class="day">15</span><span class="month">OCT</span>
                    </div>
                    <div class="event-details">
                        <h4>Kigali Poetry Slam</h4>
                        <div class="event-meta">
                            <span><i class="far fa-clock"></i> 7:00 PM</span>
                            <span><i class="fas fa-map-marker-alt"></i> Main Hall</span>
                        </div>
                    </div>
                </div>
                <div class="event-card">
                     <div class="event-date" style="background: var(--accent-yellow); color: var(--text-dark);">
                        <span class="day">22</span><span class="month">OCT</span>
                    </div>
                    <div class="event-details">
                        <h4>"Roots" Exhibition Opening</h4>
                        <div class="event-meta">
                            <span><i class="far fa-clock"></i> 6:30 PM</span>
                            <span><i class="fas fa-map-marker-alt"></i> Gallery A</span>
                        </div>
                    </div>
                </div>
                <div class="event-card">
                     <div class="event-date" style="background: var(--primary);">
                        <span class="day">01</span><span class="month">NOV</span>
                    </div>
                    <div class="event-details">
                        <h4>Agaseke Weaving Workshop</h4>
                        <div class="event-meta">
                            <span><i class="far fa-clock"></i> 10:00 AM</span>
                            <span><i class="fas fa-map-marker-alt"></i> Studio 2</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <div id="contact">
        <?php include 'includes/footer.php'; ?>
    </div>

    <script>
        // --- 1. PRELOADER & MOBILE MENU ---
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            preloader.style.opacity = '0'; preloader.style.visibility = 'hidden';
        });

        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const closeMenuBtn = document.getElementById('closeMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuOverlay = document.getElementById('menuOverlay');
        const mobileLinks = document.querySelectorAll('.mobile-link');

        function toggleMenu() {
            mobileMenu.classList.toggle('active');
            menuOverlay.classList.toggle('active');
        }
        hamburgerBtn.addEventListener('click', toggleMenu);
        closeMenuBtn.addEventListener('click', toggleMenu);
        menuOverlay.addEventListener('click', toggleMenu);
        mobileLinks.forEach(link => link.addEventListener('click', toggleMenu));


        // --- 2. HERO AUTOPLAY SLIDER WITH PROGRESS ---
        const bgElement = document.getElementById('hero-bg');
        const prevBtn = document.getElementById('btn-prev');
        const nextBtn = document.getElementById('btn-next');
        const progressBar = document.getElementById('heroProgressBar');
        
        const images = [
            'url("https://images.mindtrip.ai/attractions/96d2/b5fa/9bab/f527/e4a8/29d8/2bd6/ad03")',
            'url("https://images.mindtrip.ai/attractions/4ef2/2dc8/a855/4b57/a0dc/9298/eca1/8017")',
            'url("https://images.mindtrip.ai/attractions/ee8a/0069/df4a/7290/dd06/9631/4be1/8414")'
        ];
        let currentSlide = 0;
        let slideInterval;
        const autoplaySpeed = 5000; // 5 seconds

        function resetProgress() {
            progressBar.classList.remove('filling');
            void progressBar.offsetWidth; // Trigger reflow to restart animation
            progressBar.classList.add('filling');
        }

        function updateBackground() {
            bgElement.classList.add('fade-out');
            setTimeout(() => {
                bgElement.style.backgroundImage = images[currentSlide];
                bgElement.classList.remove('fade-out');
                resetProgress(); // Restart progress bar on slide change
            }, 800); // Match the CSS transition time
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % images.length;
            updateBackground();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + images.length) % images.length;
            updateBackground();
        }

        nextBtn.addEventListener('click', () => {
            nextSlide();
            clearInterval(slideInterval); slideInterval = setInterval(nextSlide, autoplaySpeed);
        });
        prevBtn.addEventListener('click', () => {
            prevSlide();
            clearInterval(slideInterval); slideInterval = setInterval(nextSlide, autoplaySpeed);
        });

        // Init Autoplay & Progress
        resetProgress();
        slideInterval = setInterval(nextSlide, autoplaySpeed);

        // Parallax on scroll
        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;
            requestAnimationFrame(() => {
                 bgElement.style.transform = `translate3d(0, ${scrollY * 0.4}px, 0) scale(1.15)`;
            });
        });

        // --- 3. INTERACTIVE 3D TILT LOGO ---
        const tiltLogo = document.getElementById('tiltLogo');
        const heroSection = document.querySelector('.hero');
        heroSection.addEventListener('mousemove', (e) => {
            const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
            tiltLogo.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });
        heroSection.addEventListener('mouseleave', () => {
            tiltLogo.style.transform = `rotateY(0deg) rotateX(0deg)`;
        });

        // --- 4. MAGNETIC BUTTONS (STABLE HOVER) ---
        const magneticBtns = document.querySelectorAll('.btn-magnetic');
        magneticBtns.forEach(btn => {
            btn.addEventListener('mousemove', (e) => {
                const position = btn.getBoundingClientRect();
                const x = e.pageX - position.left - position.width / 2;
                const y = e.pageY - position.top - position.height / 2;
                btn.style.transform = `translate(${x * 0.3}px, ${y * 0.5}px)`;
            });
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = 'translate(0px, 0px)';
            });
        });

        // --- 5. ANIMATED STATS ---
        const statsSection = document.querySelector('.stats-container');
        let statsAnimated = false;
        function animateStats() {
             const counters = document.querySelectorAll('.stat-number');
             const speed = 200;
             counters.forEach(counter => {
                 const target = +counter.getAttribute('data-target');
                 let count = 0; const inc = target / speed;
                 const updateCount = () => {
                     if(count < target) { count += inc; counter.innerText = Math.ceil(count) + "+"; setTimeout(updateCount, 1); }
                     else { counter.innerText = target + "+"; }
                 }
                 updateCount();
             });
        }

        // --- 6. SCROLL REVEAL ---
        const observerOptions = { threshold: 0.15, rootMargin: "0px 0px -100px 0px" };
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    if(entry.target.contains(statsSection) && !statsAnimated) { animateStats(); statsAnimated = true; }
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        document.querySelectorAll('.reveal').forEach(el => { observer.observe(el); });
    </script>

</body>
</html>