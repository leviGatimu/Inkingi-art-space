<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ikingi Arts Space</title>

    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* Internal CSS Overrides for Animations & Lines */
        .connection-wrapper {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: visible;
        }

        .connector-path {
            fill: none;
            stroke: #FDB913; /* Yellow Line */
            stroke-width: 4;
            stroke-dasharray: 20, 10; /* Dashed */
            opacity: 0.8;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.2));
        }

        .hero-title {
            position: relative;
            z-index: 20 !important; /* Forces text to be on top */
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>

<header class="hero">
    <div class="hero-bg" id="hero-bg"
        style="background-image: url('https://images.mindtrip.ai/attractions/96d2/b5fa/9bab/f527/e4a8/29d8/2bd6/ad03'); transition: background-image 0.5s ease-in-out;">
    </div>
    <div class="hero-overlay"></div>

    <div class="arrow arrow-left" id="btn-prev">&#10094;</div>
    <div class="arrow arrow-right" id="btn-next">&#10095;</div>

    <div class="hero-content">
        <h1 class="hero-title">
            <span class="text-yellow">IKINGI</span>
            <span class="text-green">ARTS</span>
            <span class="text-red">SPACE</span>
        </h1>

        <div class="logo-center">
            <img src="assets/images/logo.svg" alt="Ikingi Arts Logo">
        </div>
    </div>

    <div class="wave-bottom">
        <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#ffffff" fill-opacity="1"
                d="M0,128L40,122.7C80,117,160,107,240,128C320,149,400,203,480,218.7C560,235,640,213,720,186.7C800,160,880,128,960,149.3C1040,171,1120,245,1200,277.3C1280,309,1360,299,1400,293.3L1440,288L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z">
            </path>
        </svg>
    </div>
</header>

<main class="container">

    <div class="connection-wrapper">
        <svg width="100%" height="100%" viewBox="0 0 1000 1200" preserveAspectRatio="none">
            <path id="scrollLine" class="connector-path" d="M 250,200 C 250,500 750,300 750,900" />
        </svg>
    </div>

    <div class="content-row reveal">
        <div class="image-col">
            <div class="square-frame">
                <img src="https://images.mindtrip.ai/attractions/0edf/62ec/d36d/49fd/1a22/4e79/36ab/8d2c" alt="Bonfire">
            </div>
        </div>
        <div class="text-col">
            <h2>Welcome to ikingi arts space</h2>
            <p><strong>"Ikingi"</strong> implies a pillar—a support structure. We are the pillar for creatives in
                Rwanda.</p>
            <p>Located in the heart of Kigali, we are a creative hub where art, culture, and community come together.
                Whether it's through vibrant exhibitions, open mic nights, or hands-on workshops, we provide the space
                for artists to stand tall.</p>
        </div>
    </div>

    <div class="content-row reverse reveal" style="margin-top: 150px;">
        <div class="image-col">
            <div class="square-frame">
                <img src="https://images.mindtrip.ai/attractions/e3ac/3f38/43c7/bcc8/090c/817c/cbc6/4ebf" alt="Gallery">
            </div>
        </div>
        <div class="text-col">
            <h2>Our Story & History</h2>
            <p>Founded with a vision to preserve and evolve Rwandan storytelling, Ikingi Arts Space started as a small
                gathering of painters and poets. Today, it stands as a testament to the resilience of our culture.</p>
            <p>We bridge the gap between traditional craftsmanship and contemporary digital art, ensuring that the
                history of Rwanda is not just remembered, but recreated daily.</p>
        </div>
    </div>

    <div class="programs-section reveal" style="margin-top: 150px;">
        <h2 style="font-family: 'Permanent Marker', cursive; font-size: 3rem;">WHAT WE DO</h2>
        <p style="max-width: 600px; margin: 0 auto; color: #666;">We offer a variety of programs designed to nurture
            talent and engage the community.</p>

        <div class="programs-grid">
            <div class="program-card">
                <span class="program-icon">🎨</span>
                <h3>Workshops</h3>
                <p>From traditional basket weaving (Agaseke) to modern acrylic painting. Our workshops are open to kids
                    and adults every weekend.</p>
            </div>
            <div class="program-card">
                <span class="program-icon">🎤</span>
                <h3>Cultural Nights</h3>
                <p>Poetry, acoustic music, and storytelling around the fire. Join us every Friday for "Ikingi Vibes."
                </p>
            </div>
            <div class="program-card">
                <span class="program-icon">🖼️</span>
                <h3>Exhibitions</h3>
                <p>Monthly rotating art showcases featuring emerging Rwandan artists. Support local talent and buy
                    original art.</p>
            </div>
        </div>
    </div>

    <div class="founder-section reveal">
        <div class="founder-img-wrapper">
            <img src="https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=800&q=80" alt="Founder"
                class="founder-img">
        </div>
        <div class="founder-text">
            <h3>Meet The Founder</h3>
            <span class="founder-role">Creative Director</span>
            <p>"I created Ikingi to be a home. A place where the walls talk through color and the people connect through
                culture. We are not just a gallery; we are a movement."</p>
            <p style="margin-top: 20px; font-weight: bold; font-family: 'Permanent Marker', cursive;">— Sarah Uwera</p>
        </div>
    </div>

    <div class="gallery-section reveal">
        <div class="gallery-header">
            <h2 style="font-family: 'Permanent Marker', cursive; font-size: 3rem;">LATEST WORKS</h2>
        </div>
        <div class="gallery-grid">
            <div class="gallery-item"><img
                    src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/31/02/2c/ac/social-community-living.jpg?w=1200&h=-1&s=1"
                    alt="Art 1"></div>
            <div class="gallery-item"><img src="https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?w=800&q=80"
                    alt="Art 2"></div>
            <div class="gallery-item"><img src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=800&q=80"
                    alt="Art 3"></div>
            <div class="gallery-item"><img src="https://images.unsplash.com/photo-1549490349-8643362247b5?w=800&q=80"
                    alt="Art 4"></div>
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <a href="artwork.php" class="btn-join">View Full Gallery</a>
        </div>
    </div>



</main>

<?php include 'includes/footer.php'; ?>

<script>
    // --- 1. HERO SLIDER SCRIPT ---
    const bgElement = document.getElementById('hero-bg');
    const prevBtn = document.getElementById('btn-prev');
    const nextBtn = document.getElementById('btn-next');

    // List of images for the slider
    const images = [
        'url("https://images.mindtrip.ai/attractions/96d2/b5fa/9bab/f527/e4a8/29d8/2bd6/ad03")',
        'url("https://images.mindtrip.ai/attractions/4ef2/2dc8/a855/4b57/a0dc/9298/eca1/8017")',
        'url("https://images.mindtrip.ai/attractions/ee8a/0069/df4a/7290/dd06/9631/4be1/8414")'
    ];

    let currentSlide = 0;

    function updateBackground() {
        bgElement.style.backgroundImage = images[currentSlide];
    }

    nextBtn.addEventListener('click', () => {
        currentSlide++;
        if (currentSlide >= images.length) {
            currentSlide = 0; // Loop back to start
        }
        updateBackground();
    });

    prevBtn.addEventListener('click', () => {
        currentSlide--;
        if (currentSlide < 0) {
            currentSlide = images.length - 1; // Loop back to end
        }
        updateBackground();
    });

    // --- 2. REVEAL ANIMATION ---
    window.addEventListener('scroll', reveal);
    function reveal() {
        var reveals = document.querySelectorAll('.reveal');
        for (var i = 0; i < reveals.length; i++) {
            var windowHeight = window.innerHeight;
            var elementTop = reveals[i].getBoundingClientRect().top;
            if (elementTop < windowHeight - 100) {
                reveals[i].classList.add('active');
            }
        }
    }

    // --- 3. SCROLL LINE ANIMATION ---
    const line = document.getElementById('scrollLine');
    if (line) {
        const length = line.getTotalLength();
        line.style.strokeDasharray = length;
        line.style.strokeDashoffset = length;

        window.addEventListener("scroll", function () {
            var scrollPercent = (document.documentElement.scrollTop + document.body.scrollTop) / (document.documentElement.scrollHeight - document.documentElement.clientHeight);
            var draw = length - (scrollPercent * length * 3.5);
            if (draw < 0) draw = 0;
            line.style.strokeDashoffset = draw;
        });
    }

    // Trigger reveal on load
    reveal();
</script>

</body>

</html>