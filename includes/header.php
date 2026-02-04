  
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
            <li><a href="#events">Events & News</a></li>
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
    <style>
        /* --- 4. NAVIGATION (Desktop & Mobile) --- */
        .main-nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 5%; position: absolute; top: 0; left: 0; width: 100%; z-index: 100;
        }
        .nav-logo img { 
            cursor: pointer;
            transform: scale(1.4);
        }
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
         #preloader {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: white; z-index: 9999;
            display: flex; justify-content: center; align-items: center;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }

    </style>
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

    </script>