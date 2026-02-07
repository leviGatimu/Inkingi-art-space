<?php
require 'includes/db_connect.php';
require 'includes/header.php';
?>

<main>
    <!-- Hero – Dramatic & Welcoming -->
    <header class="contact-hero">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <h1 class="hero-title" data-aos="fade-up">Get in Touch</h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="150">
                We’d love to hear from you — whether you’re an artist, visitor, collaborator, or just curious.
            </p>
            <div class="hero-tags" data-aos="fade-up" data-aos-delay="300">
                <span>Questions?</span>
                <span>Collaborations?</span>
                <span>Visit Us?</span>
            </div>
        </div>
    </header>

    <div class="content">

        <!-- Contact Layout -->
        <section class="section contact-main">
            <div class="container">
                <div class="contact-grid">
                    <!-- Left – Contact Info & Map -->
                    <div class="contact-info" data-aos="fade-right">
                        <div class="info-card">
                            <h2 class="info-title">Reach Out</h2>
                            <p class="info-lead">We’re here to help — drop us a message, call, or stop by.</p>

                            <div class="info-items">
                                <div class="info-item">
                                    <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                                    <div>
                                        <strong>Address</strong>
                                        <p>24 KG 550 Street<br>Kacyiru, Kigali, Rwanda</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="icon"><i class="far fa-clock"></i></div>
                                    <div>
                                        <strong>Opening Hours</strong>
                                        <p>Mon–Fri: 8:30 AM – 7:30 PM<br>Sat–Sun: 9:30 AM – 8:00 PM</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="icon"><i class="fas fa-phone-alt"></i></div>
                                    <div>
                                        <strong>Phone</strong>
                                        <p>+250 788 299 791</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="icon"><i class="fas fa-envelope"></i></div>
                                    <div>
                                        <strong>Email</strong>
                                        <p>yamwamba01@gmail.com</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="icon"><i class="fab fa-instagram"></i></div>
                                    <div>
                                        <strong>Instagram</strong>
                                        <a href="https://www.instagram.com/inkingiarts_space" target="_blank">@inkingiarts_space</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Links -->
                            <div class="social-links">
                                <a href="https://www.instagram.com/inkingiarts_space" target="_blank" class="social-btn insta">
                                    <i class="fab fa-instagram"></i> Instagram
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right – Contact Form -->
                    <div class="contact-form-wrapper" data-aos="fade-left" data-aos-delay="200">
                        <div class="contact-form-card">
                            <h2 class="form-title">Send Us a Message</h2>
                            <p class="form-subtitle">We usually reply within 24–48 hours</p>

                            <form class="contact-form" id="contactForm">
                                <div class="form-group">
                                    <label for="name">Your Name</label>
                                    <input type="text" id="name" name="name" required placeholder="How should we call you?">
                                </div>

                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" required placeholder="Where can we reach you?">
                                </div>

                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input type="text" id="subject" name="subject" required placeholder="What’s on your mind?">
                                </div>

                                <div class="form-group">
                                    <label for="message">Your Message</label>
                                    <textarea id="message" name="message" rows="6" required placeholder="Tell us everything..."></textarea>
                                </div>

                                <button type="submit" class="btn-submit">
                                    <span>Send Message</span>
                                    <i class="fas fa-paper-plane"></i>
                                </button>

                                <div class="form-status" id="formStatus"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Visit Info -->
        <section class="section quick-visit" data-aos="fade-up">
            <div class="container">
                <div class="quick-grid">
                    <div class="quick-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>Visit Us</h3>
                        <p>24 KG 550 Street, Kacyiru</p>
                    </div>
                    <div class="quick-item">
                        <i class="far fa-clock"></i>
                        <h3>We're Open</h3>
                        <p>Every day until 8 PM</p>
                    </div>
                    <div class="quick-item">
                        <i class="fas fa-phone-alt"></i>
                        <h3>Call Us</h3>
                        <p>+250 788 299 791</p>
                    </div>
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
        --radius: 24px;
        --shadow-sm: 0 8px 24px rgba(0,0,0,0.08);
        --shadow-md: 0 20px 60px rgba(0,0,0,0.15);
        --transition: all 0.45s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--light);
        color: var(--dark);
        overflow-x: hidden;
    }

    main {
        padding-bottom: 140px;
    }

    /* Hero */
    .hero {
        height: 100vh;
        min-height: 720px;
        background: url('https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=1600&q=80') center/cover no-repeat;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .hero-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(44,62,80,0.78), rgba(44,62,80,0.96));
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 960px;
        padding: 0 30px;
    }

    .hero-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(4.5rem, 10vw, 8rem);
        color: white;
        line-height: 1;
        margin-bottom: 20px;
        letter-spacing: -3px;
    }

    .hero-subtitle {
        font-size: clamp(1.6rem, 4vw, 2.4rem);
        color: rgba(255,255,255,0.92);
        margin-bottom: 50px;
    }

    .hero-tags {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 16px 24px;
        margin-bottom: 60px;
    }

    .hero-tags span {
        background: rgba(253,185,19,0.18);
        color: #FDB913;
        padding: 10px 22px;
        border-radius: 50px;
        font-size: 1rem;
        backdrop-filter: blur(6px);
    }

    .scroll-cue {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        color: white;
        font-size: 1.2rem;
        font-weight: 500;
        text-decoration: none;
        transition: var(--transition);
    }

    .scroll-cue:hover {
        color: var(--accent);
        transform: translateY(8px);
    }

    /* Container */
    .container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 30px;
    }

    .section {
        padding: 140px 0;
    }

    .section-label {
        font-family: 'Poppins', sans-serif;
        color: var(--accent);
        font-size: 1.35rem;
        font-weight: 500;
        letter-spacing: 2.5px;
        display: block;
        margin-bottom: 16px;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 4rem;
        color: var(--primary);
        margin-bottom: 48px;
        line-height: 1.1;
    }

    .intro {
        font-size: 1.4rem;
        line-height: 1.75;
        color: #444;
        margin-bottom: 40px;
        max-width: 780px;
    }

    /* Story */
    .story-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
    }

    .story-image .frame {
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        border: 14px solid white;
        outline: 1px solid #eee;
        transition: var(--transition);
    }

    .story-image .frame:hover {
        transform: scale(1.02);
        box-shadow: var(--shadow-lg);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 30px;
        margin-top: 50px;
    }

    .info-item {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .info-item i {
        font-size: 2.2rem;
        color: var(--accent);
        margin-top: 4px;
    }

    .info-item strong {
        display: block;
        font-size: 1.15rem;
        margin-bottom: 8px;
    }

    /* Founder */
    .founder-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        display: grid;
        grid-template-columns: 400px 1fr;
        overflow: hidden;
        transition: var(--transition);
    }

    .founder-card:hover {
        transform: translateY(-12px);
        box-shadow: var(--shadow-lg);
    }

    .founder-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .founder-content {
        padding: 80px 70px;
    }

    .founder-label {
        color: var(--accent);
        font-size: 1.35rem;
        font-weight: 500;
        margin-bottom: 16px;
        display: block;
    }

    .founder-name {
        font-size: 3.2rem;
        margin-bottom: 12px;
    }

    .founder-role {
        font-size: 1.45rem;
        color: var(--gray);
        margin-bottom: 40px;
    }

    .founder-quote {
        font-style: italic;
        font-size: 1.45rem;
        line-height: 1.75;
        margin-bottom: 35px;
        padding-left: 28px;
        border-left: 6px solid var(--accent);
    }

    .founder-bio {
        font-size: 1.15rem;
        line-height: 1.8;
        color: #444;
    }

    .social-link {
        background: var(--accent);
        color: var(--primary);
        padding: 16px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 14px;
        transition: var(--transition);
        margin-top: 40px;
    }

    .social-link:hover {
        background: #e6a50a;
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(253,185,19,0.35);
    }

    /* Mission */
    .mission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 40px;
    }

    .mission-card {
        background: white;
        border-radius: var(--radius);
        padding: 50px 40px;
        text-align: center;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .mission-card:hover {
        transform: translateY(-12px);
        box-shadow: var(--shadow-md);
    }

    .mission-icon {
        font-size: 4rem;
        color: var(--accent);
        margin-bottom: 30px;
    }

    .mission-card h3 {
        font-size: 1.9rem;
        margin-bottom: 20px;
        color: var(--primary);
    }

    .mission-card p {
        color: var(--gray);
        font-size: 1.1rem;
        line-height: 1.8;
    }

    /* Visit */
    .visit-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 100px;
        align-items: center;
    }

    .visit-info {
        display: grid;
        gap: 40px;
        margin-top: 40px;
    }

    .detail-item {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .detail-item i {
        font-size: 2.4rem;
        color: var(--accent);
        margin-top: 6px;
    }

    .detail-item strong {
        display: block;
        font-size: 1.2rem;
        margin-bottom: 8px;
    }

    .btn-main {
        background: var(--accent);
        color: var(--primary);
        padding: 18px 52px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.25rem;
        display: inline-block;
        transition: var(--transition);
        margin-top: 40px;
    }

    .btn-main:hover {
        background: #e6a50a;
        transform: translateY(-6px);
        box-shadow: 0 18px 50px rgba(253,185,19,0.35);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .story-grid, .visit-layout {
            grid-template-columns: 1fr;
            gap: 70px;
        }
        .founder-card {
            grid-template-columns: 1fr;
        }
        .section { padding: 120px 0; }
    }

    @media (max-width: 768px) {
        .hero-title { font-size: 4.5rem; }
        .section-title { font-size: 3rem; }
    }
</style>

<script>
    AOS.init({
        duration: 1200,
        once: true,
        offset: 120,
        easing: 'ease-out-cubic'
    });
</script>