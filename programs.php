<?php
// 1. SETUP
require 'includes/db_connect.php';
require 'includes/header.php';

// 2. FETCH PROGRAMS (Database or Fallback)
try {
    $stmt = $pdo->query("SELECT * FROM programs ORDER BY id DESC");
    $programs = $stmt->fetchAll();
} catch (Exception $e) {
    // FALLBACK DATA
    $programs = [
        [
            'title' => 'Art Painting Class',
            'category' => 'Daily Class',
            'price' => '20,000 Rwf',
            'schedule' => 'Open Every Day',
            'description' => 'Open to kids and adults. Includes all materials and you take your own masterpiece home.',
            'image_path' => 'assets/images/image_49929b.jpg'
        ],
        [
            'title' => 'Saturday Pottery',
            'category' => 'Weekly Workshop',
            'price' => '25,000 Rwf',
            'schedule' => 'Saturdays 10am - 5pm',
            'description' => 'Two hours session to mold your creativity. Open to everyone. Learn wheel and hand techniques.',
            'image_path' => 'assets/images/image_49929b.jpg'
        ],
        [
            'title' => 'Rwandan Cooking',
            'category' => 'Cultural Experience',
            'price' => '20,000 Rwf',
            'schedule' => 'Available Daily',
            'description' => 'Interactive cooking session with Ikoma Art. Learn to prepare authentic dishes and enjoy the shared meal.',
            'image_path' => 'assets/images/image_49929b.jpg'
        ]
    ];
}
?>

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
    /* =========================================
       1. CRITICAL FIXES (LOGO & LAYOUT)
       ========================================= */
    body, html { margin: 0; padding: 0; overflow-x: hidden; scroll-behavior: smooth; }
    
    /* NUCLEAR LOGO FIX: Force logo to stay small */
    .nav-logo img, header img, nav img {
        max-height: 60px !important;
        width: auto !important;
        max-width: 180px !important;
        object-fit: contain !important;
    }

    /* =========================================
       2. HIGH-END VARIABLES
       ========================================= */
    :root {
        --navy: #0a192f;
        --navy-light: #112240;
        --gold: #FDB913;
        --green: #64ffda;
        --white: #e6f1ff;
        --slate: #8892b0;
    }

    /* =========================================
       3. IMMERSIVE HERO SECTION
       ========================================= */
    .prog-hero {
        position: relative;
        height: 100vh; /* FILLS WHOLE SCREEN */
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        margin-top: -80px; /* Pulls behind fixed header */
        padding-top: 80px;
        /* Background Image with Fallback */
        background: linear-gradient(rgba(10, 25, 47, 0.85), rgba(10, 25, 47, 0.7)), 
                    url('https://images.unsplash.com/photo-1513364776144-60967b0f800f?q=80&w=1920&auto=format&fit=crop'); 
        background-size: cover;
        background-position: center;
        background-attachment: fixed; /* Parallax Effect */
    }

    .hero-content {
        max-width: 900px;
        padding: 0 20px;
        z-index: 2;
    }

    .hero-label {
        color: var(--gold);
        font-family: 'Permanent Marker', cursive;
        font-size: 1.5rem;
        letter-spacing: 2px;
        display: block;
        margin-bottom: 20px;
    }

    .hero-title {
        color: var(--white);
        font-family: 'Playfair Display', serif;
        font-size: clamp(3.5rem, 6vw, 6rem);
        line-height: 1.1;
        margin-bottom: 30px;
        text-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .hero-desc {
        color: var(--slate);
        font-size: 1.2rem;
        line-height: 1.8;
        max-width: 700px;
        margin: 0 auto 50px;
    }

    /* Polished Buttons */
    .btn-group { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; }
    
    .btn-gold {
        background: var(--gold); color: var(--navy); padding: 18px 45px;
        border-radius: 4px; font-weight: 700; text-decoration: none;
        letter-spacing: 1px; transition: 0.3s; border: 2px solid var(--gold);
        text-transform: uppercase; font-size: 0.9rem;
    }
    .btn-gold:hover { background: transparent; color: var(--gold); transform: translateY(-5px); }

    .btn-outline {
        background: transparent; color: var(--gold); padding: 18px 45px;
        border-radius: 4px; font-weight: 700; text-decoration: none;
        letter-spacing: 1px; transition: 0.3s; border: 2px solid var(--gold);
        text-transform: uppercase; font-size: 0.9rem; cursor: pointer;
    }
    .btn-outline:hover { background: rgba(253, 185, 19, 0.1); transform: translateY(-5px); }

    /* =========================================
       4. SECTIONS & CARDS
       ========================================= */
    .section { padding: 120px 5%; background: #fff; }
    .section-alt { background: #f8f9fa; }

    .sec-header { text-align: center; margin-bottom: 80px; }
    .sec-header h2 { font-size: 3rem; color: var(--navy); font-family: 'Playfair Display', serif; margin: 0; }
    .sec-header span { color: var(--gold); font-family: 'Permanent Marker', cursive; font-size: 1.2rem; }

    /* Polished Card */
    .prog-card {
        display: grid; grid-template-columns: 1.5fr 2fr;
        background: white; border-radius: 0;
        box-shadow: 0 20px 50px rgba(0,0,0,0.05);
        margin-bottom: 60px; overflow: hidden;
        transition: 0.4s ease; border: 1px solid rgba(0,0,0,0.05);
    }
    .prog-card:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.1); }
    
    .prog-img-wrap { overflow: hidden; height: 100%; min-height: 350px; }
    .prog-img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
    .prog-card:hover .prog-img { transform: scale(1.05); }

    .prog-info { padding: 60px; display: flex; flex-direction: column; justify-content: center; }
    .prog-cat { color: var(--gold); font-weight: 700; letter-spacing: 2px; text-transform: uppercase; font-size: 0.8rem; margin-bottom: 10px; }
    .prog-title { font-size: 2.2rem; margin: 0 0 20px; color: var(--navy); font-family: 'Playfair Display', serif; }
    .prog-meta { display: flex; gap: 30px; margin-bottom: 25px; color: #666; font-size: 0.95rem; border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 15px 0; }
    .prog-desc { color: #555; line-height: 1.8; margin-bottom: 30px; font-size: 1rem; }

    /* Mobile Responsive */
    @media (max-width: 900px) {
        .prog-card { grid-template-columns: 1fr; }
        .prog-img-wrap { height: 300px; }
        .prog-info { padding: 40px 30px; }
        .hero-title { font-size: 3rem; }
    }

    /* PDF Template (Hidden) */
    #printable-area { display: none; }
</style>

<section class="prog-hero">
    <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
        <span class="hero-label">Inkingi Arts Space</span>
        <h1 class="hero-title">Master Your Craft</h1>
        <p class="hero-desc">
            Immerse yourself in a world of creativity. Join our daily workshops lead by Rwanda's finest resident artists. Materials included.
        </p>
        <div class="btn-group">
            <a href="#sessions" class="btn-gold">View Sessions</a>
            <button onclick="downloadPDF()" class="btn-outline">
                <i class="fas fa-arrow-down"></i> Program Guide
            </button>
        </div>
    </div>
    
    <div style="position: absolute; bottom: 40px; animation: bounce 2s infinite; color: white; opacity: 0.7;">
        <i class="fas fa-chevron-down fa-2x"></i>
    </div>
</section>

<section class="section section-alt">
    <div class="sec-header" data-aos="fade-up">
        <span>Why Join Us?</span>
        <h2>The Studio Experience</h2>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; max-width: 1200px; margin: 0 auto;">
        <div style="background:white; padding:40px; text-align:center; transition:0.3s;" data-aos="fade-up" data-aos-delay="100">
            <i class="fas fa-paint-brush" style="font-size:3rem; color:var(--gold); margin-bottom:20px;"></i>
            <h3 style="color:var(--navy); font-size:1.5rem; margin-bottom:15px;">All Inclusive</h3>
            <p style="color:#666; line-height:1.6;">We provide professional canvases, paints, aprons, and tools. Just bring your creativity.</p>
        </div>
        <div style="background:white; padding:40px; text-align:center; transition:0.3s;" data-aos="fade-up" data-aos-delay="200">
            <i class="fas fa-user-astronaut" style="font-size:3rem; color:var(--gold); margin-bottom:20px;"></i>
            <h3 style="color:var(--navy); font-size:1.5rem; margin-bottom:15px;">Expert Mentors</h3>
            <p style="color:#666; line-height:1.6;">Learn techniques directly from established resident artists in a real studio environment.</p>
        </div>
        <div style="background:white; padding:40px; text-align:center; transition:0.3s;" data-aos="fade-up" data-aos-delay="300">
            <i class="fas fa-coffee" style="font-size:3rem; color:var(--gold); margin-bottom:20px;"></i>
            <h3 style="color:var(--navy); font-size:1.5rem; margin-bottom:15px;">Cultural Vibe</h3>
            <p style="color:#666; line-height:1.6;">Enjoy Rwandan hospitality, music, and networking with other creatives while you work.</p>
        </div>
    </div>
</section>

<section class="section" id="sessions">
    <div class="sec-header" data-aos="fade-up">
        <span>Curated For You</span>
        <h2>Available Workshops</h2>
    </div>

    <div style="max-width: 1100px; margin: 0 auto;">
        <?php foreach($programs as $index => $prog): ?>
        <div class="prog-card" data-aos="fade-up">
            <div class="prog-img-wrap">
                <img src="<?= htmlspecialchars($prog['image_path']) ?>" class="prog-img" alt="Class Image">
            </div>
            <div class="prog-info">
                <span class="prog-cat"><?= htmlspecialchars($prog['category']) ?></span>
                <h3 class="prog-title"><?= htmlspecialchars($prog['title']) ?></h3>
                
                <div class="prog-meta">
                    <span><i class="fas fa-tag" style="color:var(--gold)"></i> <?= htmlspecialchars($prog['price']) ?></span>
                    <span><i class="far fa-clock" style="color:var(--gold)"></i> <?= htmlspecialchars($prog['schedule']) ?></span>
                </div>

                <p class="prog-desc"><?= htmlspecialchars($prog['description']) ?></p>
                
                <a href="contact.php?book=<?= urlencode($prog['title']) ?>" style="display:inline-flex; align-items:center; color:var(--navy); font-weight:700; text-decoration:none; transition:0.3s;">
                    Book This Session <i class="fas fa-long-arrow-alt-right" style="margin-left:10px; color:var(--gold);"></i>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="section section-alt">
    <div style="max-width: 800px; margin: 0 auto; text-align: center;" data-aos="zoom-in">
        <h2 style="font-size: 2.5rem; font-family: 'Playfair Display', serif; color: var(--navy); margin-bottom: 20px;">
            Ready to Start?
        </h2>
        <p style="font-size: 1.1rem; color: #666; margin-bottom: 40px;">
            Bookings are required at least 2 days in advance for large groups. Individual walk-ins are welcome for daily painting sessions.
        </p>
        <a href="contact.php" class="btn-gold">Contact Us Now</a>
    </div>
</section>

<div id="printable-area">
    <div style="padding: 40px; text-align: center; border: 10px solid #0a192f;">
        <h1 style="font-size: 40px; color: #0a192f; text-transform: uppercase;">Inkingi Arts Space</h1>
        <p style="font-size: 20px; color: #FDB913;">Official Program Guide 2026</p>
        <br><br>
        <div style="text-align: left;">
        <?php foreach($programs as $prog): ?>
            <div style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #ccc;">
                <h2 style="color: #0a192f; margin: 0;"><?= htmlspecialchars($prog['title']) ?></h2>
                <p style="color: #666; font-size: 14px; margin-top: 5px;"><?= htmlspecialchars($prog['category']) ?></p>
                <p><strong>Price:</strong> <?= htmlspecialchars($prog['price']) ?> | <strong>When:</strong> <?= htmlspecialchars($prog['schedule']) ?></p>
                <p style="font-style: italic;"><?= htmlspecialchars($prog['description']) ?></p>
            </div>
        <?php endforeach; ?>
        </div>
        <br>
        <p style="color: #888;">Contact: +250 787 177 805 | Kigail, Rwanda</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    AOS.init({ duration: 1000, once: true });

    function downloadPDF() {
        const element = document.getElementById('printable-area');
        element.style.display = 'block';
        const opt = {
            margin: 0.5,
            filename: 'Inkingi_Brochure.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save().then(() => {
            element.style.display = 'none';
        });
    }
</script>

<style>
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(-10px);}
        60% {transform: translateY(-5px);}
    }
</style>