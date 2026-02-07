<?php
require 'includes/db_connect.php';
require 'includes/header.php';

// Simple Form Handling Logic (Placeholder)
$messageSent = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // In a real app, you would validate and email this data
    $messageSent = true;
}
?>

<main>
    <header class="page-hero">
        <div class="hero-bg"></div>
        <div class="hero-content" data-aos="fade-up">
            <span class="hero-label">Connect With Us</span>
            <h1 class="hero-title">Get in Touch</h1>
            <p class="hero-subtitle">Whether you’re an artist, a collector, or just curious—we’d love to hear your story.</p>
        </div>
    </header>

    <div class="container main-content">
        
        <div class="contact-layout">
            
            <div class="contact-details" data-aos="fade-right" data-aos-delay="100">
                <div class="detail-header">
                    <h2 class="section-heading">Visit Our Space</h2>
                    <p class="lead-text">Located in the heart of Kacyiru, our doors are always open for inspiration.</p>
                </div>

                <div class="info-cards">
                    <div class="info-card">
                        <div class="icon-box"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="info-text">
                            <h3>Location</h3>
                            <p>24 KG 550 Street<br>Kacyiru, Kigali, Rwanda</p>
                            <a href="https://maps.google.com/?q=Inkingi+Arts+Space+Kigali" target="_blank" class="map-link">View on Google Maps <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="icon-box"><i class="far fa-clock"></i></div>
                        <div class="info-text">
                            <h3>Opening Hours</h3>
                            <p><strong>Mon–Fri:</strong> 8:30 AM – 7:30 PM</p>
                            <p><strong>Sat–Sun:</strong> 9:30 AM – 8:00 PM</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="icon-box"><i class="fas fa-phone-alt"></i></div>
                        <div class="info-text">
                            <h3>Direct Line</h3>
                            <p class="highlight">+250 788 299 791</p>
                            <p>yamwamba01@gmail.com</p>
                        </div>
                    </div>
                </div>

                <div class="social-section">
                    <h3>Follow our journey</h3>
                    <div class="social-icons">
                        <a href="https://www.instagram.com/inkingiarts_space" target="_blank" class="social-circle"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-circle"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-circle"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>

            <div class="form-wrapper" data-aos="fade-left" data-aos-delay="200">
                <div class="form-card">
                    <h2 class="form-title">Send a Message</h2>
                    
                    <?php if ($messageSent): ?>
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            <p>Thank you! Your message has been sent. We'll be in touch shortly.</p>
                        </div>
                    <?php else: ?>
                        <form class="contact-form" id="contactForm" method="POST" action="">
                            <div class="input-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" placeholder="e.g. Keza Amata" required>
                            </div>

                            <div class="input-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" placeholder="name@example.com" required>
                            </div>

                            <div class="input-group">
                                <label for="subject">Subject</label>
                                <select id="subject" name="subject">
                                    <option value="General">General Inquiry</option>
                                    <option value="Booking">Class Booking</option>
                                    <option value="Collaboration">Artist Collaboration</option>
                                    <option value="Purchase">Artwork Purchase</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="5" placeholder="How can we help you?" required></textarea>
                            </div>

                            <button type="submit" class="btn-submit">
                                <span>Send Message</span>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<style>
    /* --- CSS Variables --- */
    :root {
        --primary-dark: #1A2530;
        --accent-gold: #D4AF37;
        --accent-hover: #B5952F;
        --bg-light: #F9FAFB;
        --white: #ffffff;
        --text-gray: #6c757d;
        --border-color: #e9ecef;
        
        --font-heading: 'Playfair Display', serif;
        --font-body: 'Poppins', sans-serif;
        
        --radius: 12px;
        --shadow-soft: 0 10px 30px rgba(0,0,0,0.05);
        --shadow-hover: 0 20px 40px rgba(0,0,0,0.1);
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    /* --- Base --- */
    body { font-family: var(--font-body); background: var(--bg-light); color: var(--primary-dark); margin: 0; }
    h1, h2, h3 { font-family: var(--font-heading); margin: 0; font-weight: 700; }
    p { color: var(--text-gray); line-height: 1.6; margin-bottom: 1rem; }
    
    .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

    /* --- Hero --- */
    .page-hero {
        position: relative;
        height: 50vh;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--white);
        margin-bottom: 80px;
    }

    .hero-bg {
        position: absolute; inset: 0;
        background: url('https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=1600&q=80') center/cover;
        background-attachment: fixed;
    }
    .hero-bg::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(to bottom, rgba(26,37,48,0.7), rgba(26,37,48,0.9));
    }

    .hero-content { position: relative; z-index: 2; max-width: 700px; padding: 0 20px; }
    .hero-label { color: var(--accent-gold); letter-spacing: 2px; text-transform: uppercase; font-size: 0.9rem; font-weight: 600; display: block; margin-bottom: 15px; }
    .hero-title { font-size: clamp(3rem, 6vw, 4.5rem); margin-bottom: 20px; line-height: 1.1; }
    .hero-subtitle { font-size: 1.15rem; font-weight: 300; opacity: 0.9; }

    /* --- Main Layout --- */
    .main-content { padding-bottom: 100px; }
    .contact-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: start; }

    /* --- Left: Info --- */
    .section-heading { font-size: 2.5rem; margin-bottom: 15px; color: var(--primary-dark); }
    .lead-text { font-size: 1.1rem; margin-bottom: 40px; }

    .info-cards { display: grid; gap: 30px; margin-bottom: 50px; }

    .info-card {
        display: flex; gap: 20px; align-items: flex-start;
        padding: 25px; background: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow-soft);
        transition: var(--transition);
        border: 1px solid transparent;
    }
    .info-card:hover { transform: translateY(-5px); border-color: rgba(212, 175, 55, 0.2); box-shadow: var(--shadow-hover); }

    .icon-box {
        width: 50px; height: 50px; background: rgba(212, 175, 55, 0.1);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        color: var(--accent-gold); font-size: 1.2rem; flex-shrink: 0;
    }

    .info-text h3 { font-family: var(--font-body); font-size: 1.1rem; margin-bottom: 8px; color: var(--primary-dark); }
    .info-text p { font-size: 0.95rem; margin-bottom: 5px; color: #555; }
    .highlight { color: var(--accent-gold); font-weight: 600; font-size: 1.1rem; }
    
    .map-link {
        display: inline-flex; align-items: center; gap: 8px;
        color: var(--accent-gold); font-weight: 600; font-size: 0.9rem;
        margin-top: 8px; text-decoration: none;
    }
    .map-link:hover { text-decoration: underline; }

    .social-section h3 { font-size: 1.2rem; margin-bottom: 20px; }
    .social-icons { display: flex; gap: 15px; }
    .social-circle {
        width: 45px; height: 45px; border-radius: 50%; background: var(--white);
        display: flex; align-items: center; justify-content: center;
        color: var(--primary-dark); border: 1px solid var(--border-color);
        transition: var(--transition); text-decoration: none;
    }
    .social-circle:hover { background: var(--accent-gold); color: var(--white); border-color: var(--accent-gold); transform: translateY(-3px); }

    /* --- Right: Form --- */
    .form-wrapper { position: relative; }
    
    .form-card {
        background: var(--white); padding: 50px;
        border-radius: var(--radius); box-shadow: var(--shadow-soft);
        border-top: 5px solid var(--accent-gold);
    }

    .form-title { font-size: 2rem; margin-bottom: 30px; }

    .input-group { margin-bottom: 25px; }
    .input-group label {
        display: block; font-weight: 600; font-size: 0.85rem;
        color: var(--primary-dark); margin-bottom: 8px; letter-spacing: 0.5px;
    }

    .input-group input, 
    .input-group select, 
    .input-group textarea {
        width: 100%; padding: 14px 18px;
        border: 1px solid var(--border-color); border-radius: 8px;
        font-family: var(--font-body); font-size: 0.95rem;
        transition: var(--transition); background: #fdfdfd;
        color: var(--primary-dark);
    }

    .input-group input:focus, 
    .input-group select:focus, 
    .input-group textarea:focus {
        outline: none; border-color: var(--accent-gold);
        background: var(--white); box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
    }

    .btn-submit {
        width: 100%; background: var(--primary-dark); color: var(--white);
        padding: 16px; border: none; border-radius: 50px;
        font-size: 1rem; font-weight: 600; cursor: pointer;
        transition: var(--transition);
    }
    .btn-submit:hover { background: var(--accent-gold); transform: translateY(-2px); }

    .success-message {
        text-align: center; padding: 40px 20px; background: rgba(0, 158, 96, 0.05);
        border-radius: var(--radius); border: 1px solid rgba(0, 158, 96, 0.2);
    }
    .success-message i { font-size: 3rem; color: #009E60; margin-bottom: 20px; }
    .success-message p { font-size: 1.1rem; color: var(--primary-dark); font-weight: 500; }

    /* --- Responsive --- */
    @media (max-width: 992px) {
        .contact-layout { grid-template-columns: 1fr; gap: 50px; }
        .form-card { padding: 30px; }
    }
    
    @media (max-width: 768px) {
        .hero-title { font-size: 3rem; }
    }
</style>

<script>
    // Initialize Animations
    AOS.init({ duration: 800, once: true });
</script>