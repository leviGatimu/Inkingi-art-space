<?php
require 'includes/db_connect.php';
require 'includes/header.php';
?>

<main>
    <!-- Hero Section -->
    <header class="hero-header">
        <div class="hero-overlay"></div>
        <div class="hero-content" data-aos="fade-up">
            <h1 class="page-title">About Inkingi Arts Space</h1>
            <p class="page-subtitle">A pillar of Rwandan creativity, supporting artists and preserving culture since 2023</p>
            <a href="#founder" class="hero-cta">Meet Our Founder</a>
        </div>
    </header>

    <div class="container" style="max-width: 1280px; margin: 0 auto; padding: 0 20px; position: relative; z-index: 2;">

        <!-- About the Space -->
        <section id="about" class="section-padding" data-aos="fade-up">
            <div class="content-row">
                <div class="image-col" data-aos="zoom-in" data-aos-delay="200">
                    <div class="square-frame">
                        <img src="https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=900&h=500&s=1" alt="Inkingi Arts Space Gallery">
                    </div>
                </div>
                <div class="text-col" data-aos="fade-left" data-aos-delay="300">
                    <span class="section-subtitle">Our Foundation</span>
                    <h2>A Creative Pillar in Kigali</h2>
                    <p><strong>Inkingi Arts Space</strong> is a contemporary art gallery and vibrant creative hub located at <strong>24 KG 550 Street, Kacyiru, Kigali, Rwanda</strong>. Founded in 2023 by visual artist Olivier Kwitonda, the name "Inkingi" means "pillar" in Kinyarwanda — symbolizing our mission to uplift and support the next generation of Rwandan and African artists.</p>
                    <p>Originally funded through sales of Kwitonda’s own paintings, Inkingi has grown into a key destination for art lovers, collectors, and creatives. We host monthly exhibitions, workshops, poetry slams, open mic nights, and cultural storytelling events — often paired with traditional Rwandan food (every third Saturday for only 5,000 RWF).</p>
                    <p><strong>Opening Hours:</strong> Mon–Fri 8:30 AM – 7:30 PM | Sat–Sun 9:30 AM – 8:00 PM</p>
                    <p><strong>Contact:</strong> +250 788 299 791 | <a href="https://www.instagram.com/inkingiarts_space" target="_blank" style="color: var(--accent-yellow);">Instagram @inkingiarts_space</a></p>
                </div>
            </div>
        </section>

        <!-- Founder Section -->
        <section id="founder" class="section-padding" data-aos="fade-up">
            <div class="founder-card" data-aos="zoom-in-up" data-aos-delay="200">
                <div class="founder-image">
                    <img src="http://niyoartscenter.com/wp-content/uploads/2021/06/Olivier.jpeg" alt="Olivier Kwitonda" class="founder-photo">
                    <div class="quote-bubble">
                        <i class="fas fa-quote-right"></i>
                    </div>
                </div>
                <div class="founder-info">
                    <h2>Olivier Kwitonda</h2>
                    <span class="founder-role">Founder & Creative Director</span>
                    <blockquote>
                        "I founded Inkingi Arts from the money I earned selling my own paintings. It started as a dream, and now it’s one of the key places to visit in Rwanda to learn about our history, learn how to paint, and more. I’m really grateful I didn’t quit earlier — my story should inspire many."
                    </blockquote>
                    <p>Olivier is a passionate visual artist whose vision has shaped Inkingi into a thriving hub for contemporary Rwandan art. His work and leadership continue to inspire emerging talents and celebrate cultural narratives.</p>
                    <div class="social-buttons">
                        <a href="https://www.instagram.com/inkingiarts_space" target="_blank" class="social-btn insta">
                            <i class="fab fa-instagram"></i> Follow on Instagram
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- What We Do -->
        <section class="section-padding" data-aos="fade-up">
            <div class="section-header">
                <span class="section-subtitle stagger-1">What We Do</span>
                <h2 class="section-title stagger-2">Our <span class="text-green">Mission & Activities</span></h2>
            </div>

            <div class="programs-grid stagger-3">
                <div class="program-card" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-palette program-icon"></i>
                    <h3>Contemporary Art Exhibitions</h3>
                    <p>Monthly rotating showcases featuring emerging Rwandan and African artists — paintings, sculptures, photography, and mixed media that tell powerful stories.</p>
                </div>
                <div class="program-card" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-people-group program-icon"></i>
                    <h3>Workshops & Skill-Building</h3>
                    <p>Hands-on sessions in painting, pottery, weaving (Agaseke), and more. Open to kids, adults, beginners, and professionals.</p>
                </div>
                <div class="program-card" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-music program-icon"></i>
                    <h3>Cultural Nights & Events</h3>
                    <p>Poetry slams, open mic nights, storytelling with traditional food, and community gatherings every Friday and third Saturday.</p>
                </div>
            </div>
        </section>

    </div>
</main>

<?php include 'includes/footer.php'; ?>

<style>
    :root {
        --primary: #2C3E50;
        --accent: #FDB913;
        --green: #009E60;
        --red: #C8102E;
        --light: #f8f9fa;
        --gray: #6c757d;
        --dark: #212529;
        --radius: 20px;
        --shadow-sm: 0 6px 20px rgba(0,0,0,0.08);
        --shadow-md: 0 15px 40px rgba(0,0,0,0.15);
        --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--light);
        color: var(--dark);
        overflow-x: hidden;
    }

    main {
        padding-bottom: 100px;
    }

    /* Hero Header */
    .hero-header {
        height: 80vh;
        min-height: 600px;
        background-size: cover;
        background-position: center;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(44,62,80,0.75) 0%, rgba(44,62,80,0.95) 100%);
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 900px;
        padding: 0 20px;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(3.5rem, 8vw, 5.5rem);
        color: white;
        margin-bottom: 20px;
        line-height: 1.1;
    }

    .page-subtitle {
        font-size: 1.4rem;
        color: rgba(255,255,255,0.9);
        margin-bottom: 40px;
        max-width: 720px;
        margin-left: auto;
        margin-right: auto;
    }

    .hero-cta {
        background: var(--accent);
        color: var(--primary);
        padding: 16px 48px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.2rem;
        text-decoration: none;
        display: inline-block;
        transition: var(--transition);
    }

    .hero-cta:hover {
        background: #e6a50a;
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(253,185,19,0.4);
    }

    /* Section Padding & Header */
    .section-padding {
        padding: 120px 0;
    }

    .section-header {
        text-align: center;
        margin-bottom: 80px;
    }

    .section-subtitle {
        font-family: var(--font-marker);
        color: var(--accent);
        font-size: 1.4rem;
        display: block;
        margin-bottom: 12px;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 3.2rem;
        color: var(--primary);
    }

    /* Content Row */
    .content-row {
        display: flex;
        align-items: center;
        gap: 80px;
        flex-wrap: wrap;
    }

    .content-row.reverse {
        flex-direction: row-reverse;
    }

    .image-col, .text-col {
        flex: 1;
        min-width: 320px;
    }

    .square-frame {
        border-radius: var(--radius);
        overflow: hidden;
        outline: 12px solid var(--accent);
        outline-offset: -12px;
        transition: var(--transition);
        box-shadow: var(--shadow-md);
    }

    .square-frame:hover {
        outline-color: var(--green);
        outline-width: 16px;
        outline-offset: -16px;
        transform: scale(1.02);
    }

    .square-frame img {
        width: 100%;
        display: block;
        transition: transform 0.7s ease;
    }

    .square-frame:hover img {
        transform: scale(1.1);
    }

    /* Founder Card */
    .founder-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
        transition: var(--transition);
    }

    .founder-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 60px rgba(0,0,0,0.15);
    }

    .founder-image {
        flex: 0 0 350px;
        position: relative;
    }

    .founder-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .founder-card:hover .founder-photo {
        transform: scale(1.08);
    }

    .quote-bubble {
        position: absolute;
        bottom: -20px;
        right: -20px;
        background: var(--accent);
        color: var(--primary);
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        box-shadow: 0 8px 20px rgba(253,185,19,0.3);
    }

    .founder-info {
        flex: 1;
        padding: 60px 50px;
    }

    .founder-info h2 {
        font-size: 2.8rem;
        margin-bottom: 10px;
    }

    .founder-role {
        font-size: 1.3rem;
        color: var(--accent);
        display: block;
        margin-bottom: 25px;
        font-weight: 500;
    }

    blockquote {
        font-style: italic;
        font-size: 1.3rem;
        line-height: 1.6;
        margin-bottom: 25px;
        padding-left: 20px;
        border-left: 4px solid var(--accent);
    }

    /* What We Do Grid */
    .programs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 40px;
    }

    .program-card {
        background: white;
        border-radius: var(--radius);
        padding: 40px 30px;
        text-align: center;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .program-card:hover {
        transform: translateY(-12px);
        box-shadow: var(--shadow-md);
    }

    .program-icon {
        font-size: 3.5rem;
        margin-bottom: 20px;
        background: linear-gradient(45deg, var(--accent), var(--green));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        transition: transform 0.5s ease;
    }

    .program-card:hover .program-icon {
        transform: scale(1.15) rotate(10deg);
    }

    .program-card h3 {
        font-size: 1.7rem;
        margin-bottom: 16px;
        color: var(--primary);
    }

    .program-card p {
        color: var(--gray);
        font-size: 1rem;
        line-height: 1.7;
    }

    /* Pattern Background */
    body::before {
        content: '';
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%239C92AC' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
        z-index: -1;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .content-row, .content-row.reverse {
            flex-direction: column;
            gap: 40px;
        }
        .founder-card {
            flex-direction: column;
        }
        .founder-image {
            flex: none;
        }
    }
</style>

<script>
    AOS.init({
        duration: 1200,
        once: true,
        offset: 100
    });
</script>