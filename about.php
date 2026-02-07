<?php
require 'includes/db_connect.php';
require 'includes/header.php';
?>

<main>
    <header class="hero">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <h1 class="hero-title">Inkingi Arts Space</h1>
            <p class="hero-subtitle">Kigali’s pillar of contemporary creativity.</p>
            
            <div class="hero-tags">
                <span class="tag">Contemporary Gallery</span>
                <span class="tag">Cultural Hub</span>
                <span class="tag">Creative Community</span>
            </div>
            
            <a href="#story" class="scroll-btn">
                <span>Discover Our Story</span>
                <i class="fas fa-chevron-down"></i>
            </a>
        </div>
    </header>

    <div class="page-content">

        <section class="section story" id="story">
            <div class="container">
                <div class="grid-layout">
                    <div class="image-wrapper reveal-on-scroll">
                        <div class="frame">
                            <img src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=1200&q=80" alt="Inkingi Arts Interior">
                        </div>
                    </div>

                    <div class="text-content reveal-on-scroll">
                        <span class="overline">Our Beginning</span>
                        <h2 class="section-heading">A Space Born from Passion</h2>
                        <p class="lead-text">
                            Founded in 2023 by visual artist Olivier Kwitonda, Inkingi Arts Space is a contemporary gallery and creative hub located at <strong>24 KG 550 Street, Kacyiru, Kigali</strong>.
                        </p>
                        <p>
                            “Inkingi” means “pillar” in Kinyarwanda — symbolizing our mission to uplift emerging Rwandan and African artists while preserving cultural narratives. Starting with funds from Olivier’s own painting sales, Inkingi has grown into one of Kigali’s most vibrant art destinations.
                        </p>

                        <div class="quick-info">
                            <div class="info-row">
                                <div class="icon-box"><i class="far fa-clock"></i></div>
                                <div>
                                    <strong>Opening Hours</strong>
                                    <p>Mon–Fri 8:30–19:30 &bull; Sat–Sun 9:30–20:00</p>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="icon-box"><i class="fab fa-instagram"></i></div>
                                <div>
                                    <strong>Follow Us</strong>
                                    <p><a href="https://www.instagram.com/inkingiarts_space" target="_blank" class="text-link">@inkingiarts_space</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section founder-section">
            <div class="container">
                <div class="founder-card reveal-on-scroll">
                    <div class="founder-image">
                        <img src="http://niyoartscenter.com/wp-content/uploads/2021/06/Olivier.jpeg" alt="Olivier Kwitonda">
                    </div>

                    <div class="founder-content">
                        <span class="overline">The Visionary</span>
                        <h2 class="section-heading">Olivier Kwitonda</h2>
                        <p class="role">Artist • Founder • Cultural Pillar</p>

                        <blockquote class="founder-quote">
                            "I founded Inkingi Arts from the money I earned selling my own paintings. It started as a dream, and now it’s a key place to visit in Rwanda to learn about our history and how to paint. I’m grateful I didn’t quit earlier — my story should inspire many."
                        </blockquote>

                        <p>
                            Through Inkingi, Olivier has created not just a gallery, but a family — a space where emerging artists are nurtured, cultural stories are told, and creativity thrives.
                        </p>

                        <a href="https://www.instagram.com/inkingiarts_space" target="_blank" class="btn btn-outline">
                            Follow on Instagram
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="section mission">
            <div class="container">
                <div class="section-header text-center">
                    <span class="overline">What We Do</span>
                    <h2 class="section-heading">Celebrating Creativity</h2>
                </div>

                <div class="cards-grid">
                    <div class="feature-card reveal-on-scroll">
                        <div class="card-icon"><i class="fas fa-palette"></i></div>
                        <h3>Exhibitions</h3>
                        <p>Monthly showcases of emerging Rwandan and African artists — celebrating bold new voices in painting, sculpture, and mixed media.</p>
                    </div>

                    <div class="feature-card reveal-on-scroll" style="transition-delay: 0.1s;">
                        <div class="card-icon"><i class="fas fa-users"></i></div>
                        <h3>Workshops</h3>
                        <p>Hands-on learning in painting, pottery, and weaving (Agaseke). Open to all ages, skill levels, and backgrounds.</p>
                    </div>

                    <div class="feature-card reveal-on-scroll" style="transition-delay: 0.2s;">
                        <div class="card-icon"><i class="fas fa-music"></i></div>
                        <h3>Cultural Events</h3>
                        <p>Poetry slams, open mics, and storytelling nights accompanied by traditional food — building community through heritage.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section visit-section">
            <div class="container">
                <div class="grid-layout reverse-mobile">
                    <div class="text-content reveal-on-scroll">
                        <span class="overline">Visit Us</span>
                        <h2 class="section-heading">Experience Inkingi</h2>
                        <p class="lead-text">We welcome everyone — artists, families, collectors, students, and tourists — to step into a space where art breathes.</p>

                        <div class="contact-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>24 KG 550 Street, Kacyiru, Kigali</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-phone"></i>
                                <span>+250 788 299 791</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-envelope"></i>
                                <span>yamwamba01@gmail.com</span>
                            </div>
                        </div>

                        <a href="contact.php" class="btn btn-primary">Plan Your Visit</a>
                    </div>

                    <div class="image-wrapper reveal-on-scroll">
                        <div class="frame">
                            <img src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=1200&q=80" alt="Inkingi Entrance">
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>

<?php include 'includes/footer.php'; ?>

<style>
    /* --- CSS Variables & Reset --- */
    :root {
        --primary-dark: #1A2530; /* Deep Navy */
        --primary-text: #2c3e50;
        --accent-gold: #D4AF37; /* Premium Gold */
        --accent-hover: #B5952F;
        --bg-light: #F9FAFB;
        --white: #ffffff;
        --text-gray: #5a6a7e;
        
        --font-heading: 'Playfair Display', serif;
        --font-body: 'Poppins', sans-serif;
        
        --radius-lg: 20px;
        --radius-sm: 8px;
        --shadow-soft: 0 10px 40px rgba(0,0,0,0.06);
        --shadow-hover: 0 20px 50px rgba(0,0,0,0.12);
        --transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    * { box-sizing: border-box; }
    
    body {
        font-family: var(--font-body);
        color: var(--primary-text);
        background: var(--bg-light);
        line-height: 1.7;
        overflow-x: hidden;
        margin: 0;
    }

    h1, h2, h3 { font-family: var(--font-heading); margin: 0; font-weight: 700; }
    p { margin: 0 0 1.5rem 0; color: var(--text-gray); }
    a { text-decoration: none; transition: var(--transition); }

    /* --- Typography Helpers --- */
    .overline {
        display: block;
        font-family: var(--font-body);
        text-transform: uppercase;
        letter-spacing: 3px;
        font-size: 0.85rem;
        color: var(--accent-gold);
        font-weight: 600;
        margin-bottom: 12px;
    }

    .section-heading {
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        color: var(--primary-dark);
        margin-bottom: 1.5rem;
        line-height: 1.1;
    }

    .lead-text {
        font-size: 1.15rem;
        color: var(--primary-dark);
        margin-bottom: 2rem;
    }

    .text-link { color: var(--primary-dark); font-weight: 600; position: relative; }
    .text-link::after {
        content: ''; position: absolute; left: 0; bottom: -2px; width: 0%; height: 2px;
        background: var(--accent-gold); transition: var(--transition);
    }
    .text-link:hover { color: var(--accent-gold); }
    .text-link:hover::after { width: 100%; }

    /* --- Buttons --- */
    .btn {
        display: inline-block;
        padding: 14px 36px;
        border-radius: 50px;
        font-weight: 500;
        letter-spacing: 0.5px;
        transition: var(--transition);
        font-size: 1rem;
    }

    .btn-primary {
        background: var(--accent-gold);
        color: var(--white);
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }
    .btn-primary:hover {
        background: var(--accent-hover);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
    }

    .btn-outline {
        border: 2px solid var(--primary-dark);
        color: var(--primary-dark);
        background: transparent;
    }
    .btn-outline:hover {
        background: var(--primary-dark);
        color: var(--white);
    }

    /* --- Layout Structure --- */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .section { padding: 100px 0; }
    
    .text-center { text-align: center; }

    .grid-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
    }

    /* --- Hero Section --- */
    .hero {
        position: relative;
        height: 90vh;
        min-height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--white);
        overflow: hidden;
    }

    .hero-bg {
        position: absolute;
        inset: 0;
        background: url('https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=1600&q=80') center/cover no-repeat;
        /* Parallax effect */
        background-attachment: fixed; 
        z-index: 0;
    }
    
    .hero-bg::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(26,37,48,0.7), rgba(26,37,48,0.9));
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        padding: 0 20px;
        animation: fadeInUp 1s ease-out;
    }

    .hero-title {
        font-size: clamp(3.5rem, 8vw, 6rem);
        margin-bottom: 20px;
        letter-spacing: -1px;
    }

    .hero-subtitle {
        font-size: clamp(1.2rem, 3vw, 1.8rem);
        color: rgba(255,255,255,0.9);
        margin-bottom: 40px;
        font-weight: 300;
    }

    .hero-tags {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        margin-bottom: 60px;
    }

    .tag {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 8px 24px;
        border-radius: 50px;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        color: var(--accent-gold);
    }

    .scroll-btn {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        color: var(--white);
        font-size: 0.9rem;
        opacity: 0.8;
        gap: 10px;
    }
    .scroll-btn i { animation: bounce 2s infinite; }
    .scroll-btn:hover { opacity: 1; color: var(--accent-gold); }

    /* --- Image Frames --- */
    .frame {
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transform: rotate(-2deg); /* Artistic tilt */
        transition: var(--transition);
        border: 8px solid var(--white);
    }
    .frame:hover { transform: rotate(0deg) scale(1.02); box-shadow: var(--shadow-hover); }
    .frame img { display: block; width: 100%; height: auto; }

    /* --- Founder Card --- */
    .founder-card {
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-soft);
        display: grid;
        grid-template-columns: 400px 1fr;
        overflow: hidden;
    }

    .founder-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .founder-content { padding: 60px 50px; }

    .founder-quote {
        font-family: var(--font-heading);
        font-size: 1.3rem;
        font-style: italic;
        color: var(--primary-dark);
        border-left: 4px solid var(--accent-gold);
        padding-left: 24px;
        margin: 30px 0;
        line-height: 1.6;
    }

    .role { color: var(--accent-gold); font-weight: 600; margin-bottom: 20px; display: block; }

    /* --- Mission Cards --- */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 50px;
    }

    .feature-card {
        background: var(--white);
        padding: 40px;
        border-radius: var(--radius-lg);
        text-align: center;
        box-shadow: var(--shadow-soft);
        transition: var(--transition);
        border-bottom: 3px solid transparent;
    }
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-hover);
        border-bottom-color: var(--accent-gold);
    }

    .card-icon {
        font-size: 2.5rem;
        color: var(--accent-gold);
        margin-bottom: 24px;
    }

    /* --- Visit/Contact Details --- */
    .contact-details { margin: 30px 0; }
    .detail-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        font-size: 1.05rem;
        color: var(--primary-dark);
    }
    .detail-item i { color: var(--accent-gold); width: 24px; text-align: center; }

    .quick-info { margin-top: 30px; display: flex; gap: 30px; flex-wrap: wrap; }
    .info-row { display: flex; gap: 15px; align-items: start; }
    .icon-box { color: var(--accent-gold); font-size: 1.2rem; margin-top: 3px; }

    /* --- Animations --- */
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(10px);}
        60% {transform: translateY(5px);}
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- Responsive --- */
    @media (max-width: 992px) {
        .grid-layout { grid-template-columns: 1fr; gap: 50px; }
        .founder-card { grid-template-columns: 1fr; }
        .founder-image { height: 400px; }
        .reverse-mobile { display: flex; flex-direction: column-reverse; }
        .hero-bg { background-attachment: scroll; } /* Disable parallax on mobile for performance */
    }

    @media (max-width: 768px) {
        .section { padding: 70px 0; }
        .founder-content { padding: 40px 30px; }
        .hero-title { font-size: 3rem; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        const hiddenElements = document.querySelectorAll('.reveal-on-scroll');
        hiddenElements.forEach((el) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s ease-out';
            observer.observe(el);
        });
    });
</script>