<?php
// programs.php - Completely Remodeled Programs Page
// New Design Concept: "Artistic Mosaic Fusion" - Programs displayed as interlocking mosaic tiles with crystalline facets, using CSS clip-path for gem-like shapes, iridescent gradients, and interactive facet reveals on hover. Background with subtle artistic brush strokes via SVG. Sidebar as a floating palette with color swatches for categories. Hero as a canvas splash with paint drip animations. Standout CSS: Heavy use of clip-path, mix-blend-mode, CSS variables for themes, advanced hover effects with 3D rotations, and particle confetti on interactions.

require 'includes/db_connect.php';
require 'includes/header.php';

// FETCH PROGRAMS FROM DB (logic unchanged)
try {
    $stmt = $pdo->query("SELECT id, title, category, price, schedule, description, image_path FROM programs ORDER BY id DESC");
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // FALLBACK STATIC DATA (logic unchanged)
    $programs = [
        [
            'title' => 'Art Painting Class',
            'category' => 'Class',
            'price' => '20,000 Rwf',
            'schedule' => 'Daily: 10am - 6pm',
            'description' => 'Open every day to kids and adults. We provide the canvas, paint, and brushes. Take your masterpiece home.',
            'image_path' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=900&h=500&s=1'
        ],
        [
            'title' => 'Saturday Pottery',
            'category' => 'Workshop',
            'price' => '25,000 Rwf',
            'schedule' => 'Saturdays: 10am - 5pm',
            'description' => 'A tactile experience in clay. Learn wheel throwing and hand-building techniques from master potters.',
            'image_path' => 'https://mindtrip.ai/attractions/ee8a/0069/df4a/7290/dd06/9631/4be1/8414'
        ],
        [
            'title' => 'Rwandan Cooking',
            'category' => 'Experience',
            'price' => '20,000 Rwf',
            'schedule' => 'Daily (Booking Req)',
            'description' => 'Interactive cooking session with Ikoma Art. Learn to prepare authentic dishes and enjoy the shared meal.',
            'image_path' => 'https://images.mindtrip.ai/attractions/4ef2/2dc8/a855/4b57/a0dc/9298/eca1/8017'
        ]
    ];
}

// Category counts (logic unchanged)
$categories = ['Class' => 0, 'Workshop' => 0, 'Experience' => 0, 'Other' => 0];
foreach ($programs as $p) {
    $cat = $p['category'] ?? 'Other';
    $categories[$cat] = ($categories[$cat] ?? 0) + 1;
}
?>

<!-- Fonts & Libraries (expanded for unique design: Added variable font for dynamic weights, particles.js for confetti) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script> <!-- For particle effects -->

<style>
    /* Enhanced Global Variables: Added theme colors for categories, iridescent gradients, facet angles */
    :root {
        --primary: #2C3E50;
        --accent: #FDB913;
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
        --cat-class: linear-gradient(135deg, #FDB913, #E67E22);
        --cat-workshop: linear-gradient(135deg, #009E60, #27AE60);
        --cat-experience: linear-gradient(135deg, #C8102E, #E74C3C);
        --cat-other: linear-gradient(135deg, #2C3E50, #34495E);
        --iridescent: linear-gradient(45deg, rgba(255,255,255,0.2), rgba(253,185,19,0.4), rgba(0,158,96,0.3));
        --facet-clip: polygon(0 0, 100% 0, 100% 85%, 85% 100%, 0 100%);
        --drip-color: rgba(253,185,19,0.6);
    }

    body {
        font-family: var(--font-sans);
        background: var(--light) url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="none" stroke="%23FDB913" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" d="M0,160L48,144C96,128,192,96,288,112C384,128,480,192,576,208C672,224,768,192,864,160C960,128,1056,96,1152,112C1248,128,1344,192,1392,224L1440,256"></path></svg>') repeat-x bottom;
        color: var(--dark);
        line-height: 1.6;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        position: relative;
    }

    /* Particle Background for Artistic Flair */
    #particles-js {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        opacity: 0.3;
    }

    main {
        padding: 0 0 120px;
        position: relative;
        z-index: 1;
    }

    /* Hero Header: Canvas Splash with Paint Drip Animations */
    .hero-header {
        background: var(--primary);
        color: white;
        text-align: center;
        padding: 140px 24px 100px;
        position: relative;
        overflow: hidden;
    }

    .hero-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=900&h=500&s=1') no-repeat center/cover;
        opacity: 0.4;
        mix-blend-mode: overlay;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
    }

    .page-title {
        font-family: var(--font-serif);
        font-size: 4rem;
        font-variation-settings: 'wght' 900;
        margin-bottom: 20px;
        text-shadow: 0 4px 12px rgba(0,0,0,0.3);
        position: relative;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        width: 100px;
        height: 4px;
        background: var(--accent);
        transform: translateX(-50%);
        animation: glowPulse 2s infinite alternate;
    }

    @keyframes glowPulse {
        0% { box-shadow: 0 0 5px var(--accent); }
        100% { box-shadow: 0 0 20px var(--accent); }
    }

    .page-subtitle {
        font-size: 1.4rem;
        opacity: 0.85;
        margin-bottom: 40px;
        font-variation-settings: 'wght' 300;
    }

    .hero-cta {
        background: var(--iridescent);
        color: var(--primary);
        padding: 16px 40px;
        border-radius: 100px;
        text-decoration: none;
        font-weight: 700;
        font-variation-settings: 'wght' 600;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .hero-cta::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 200%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s;
    }

    .hero-cta:hover::before {
        left: 100%;
    }

    .hero-cta:hover {
        transform: scale(1.05) rotate(2deg);
        box-shadow: 0 8px 24px rgba(253,185,19,0.4);
    }

    /* Paint Drip Effect */
    .drip {
        position: absolute;
        top: 0;
        width: 20px;
        height: 100px;
        background: var(--drip-color);
        animation: dripFall 3s infinite ease-in-out;
        border-radius: 0 0 50% 50%;
        opacity: 0.7;
    }

    .drip1 { left: 20%; animation-delay: 0s; }
    .drip2 { left: 50%; animation-delay: 1s; width: 30px; }
    .drip3 { left: 80%; animation-delay: 2s; }

    @keyframes dripFall {
        0% { transform: translateY(-100%); }
        50% { transform: translateY(50%); height: 150px; }
        100% { transform: translateY(200%); height: 50px; }
    }

    /* Programs Container: Artistic Layout with Overlap */
    .programs-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 60px 30px 0;
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 50px;
        position: relative;
    }

    .programs-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(253,185,19,0.1), transparent);
        z-index: -1;
    }

    /* Sidebar: Floating Palette with Swatches */
    .sidebar {
        position: sticky;
        top: 120px;
        align-self: start;
        background: rgba(255,255,255,0.95);
        border-radius: 24px;
        box-shadow: var(--shadow-md), inset 0 0 20px rgba(253,185,19,0.1);
        padding: 32px;
        transform: perspective(800px) rotateY(-5deg);
        transition: transform 0.5s;
    }

    .sidebar:hover {
        transform: perspective(800px) rotateY(0deg);
    }

    .sidebar-widget {
        margin-bottom: 32px;
    }

    .widget-title {
        font-family: var(--font-serif);
        font-size: 1.5rem;
        color: var(--primary);
        margin-bottom: 24px;
        position: relative;
        font-variation-settings: 'wght' 800;
    }

    .widget-title::before {
        content: '\f53f'; /* Paint brush icon */
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 12px;
        color: var(--accent);
    }

    /* Search: Artistic Input with Magnifier Pulse */
    .search-box {
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--accent);
        font-size: 1.2rem;
        animation: pulseSearch 1.5s infinite;
    }

    @keyframes pulseSearch {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 1; }
    }

    .search-input {
        width: 100%;
        padding: 16px 16px 16px 52px;
        border: none;
        background: linear-gradient(135deg, #f0f0f0, #ffffff);
        border-radius: 100px;
        font-size: 1rem;
        box-shadow: inset var(--shadow-sm);
        transition: var(--transition);
        font-variation-settings: 'wght' 400;
    }

    .search-input:focus {
        box-shadow: 0 0 0 4px rgba(253,185,19,0.2), inset var(--shadow-sm);
    }

    /* Categories: Color Swatches with Hover Expand */
    .category-list {
        list-style: none;
        padding: 0;
    }

    .category-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 14px 20px;
        margin-bottom: 12px;
        background: #f8f9fa;
        border-radius: 12px;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        font-variation-settings: 'wght' 500;
    }

    .category-item::before {
        content: '';
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--accent);
        transition: transform 0.3s;
    }

    .category-item[data-category="class"]::before { background: var(--cat-class); }
    .category-item[data-category="workshop"]::before { background: var(--cat-workshop); }
    .category-item[data-category="experience"]::before { background: var(--cat-experience); }
    .category-item[data-category="other"]::before { background: var(--cat-other); }
    .category-item[data-category="all"]::before { background: var(--iridescent); }

    .category-item:hover::before {
        transform: scale(1.5);
    }

    .category-item:hover {
        background: linear-gradient(to right, rgba(253,185,19,0.1), transparent);
        transform: translateX(10px);
    }

    .category-item.active {
        font-variation-settings: 'wght' 700;
        box-shadow: 0 4px 12px rgba(253,185,19,0.3);
    }

    .count-badge {
        margin-left: auto;
        background: rgba(0,0,0,0.1);
        color: var(--dark);
        font-size: 0.85rem;
        padding: 6px 12px;
        border-radius: 50px;
        transition: var(--transition);
    }

    .category-item.active .count-badge {
        background: var(--accent);
        color: white;
    }

    /* PDF Widget: Canvas Frame Style */
    .pdf-widget {
        background: var(--iridescent);
        color: var(--primary);
        text-align: center;
        padding: 40px 28px;
        border-radius: 20px;
        box-shadow: var(--shadow-md);
        position: relative;
    }

    .pdf-widget::before {
        content: '';
        position: absolute;
        top: -10px;
        left: -10px;
        right: -10px;
        bottom: -10px;
        border: 2px solid var(--primary);
        border-radius: 24px;
        z-index: -1;
        opacity: 0.2;
    }

    .pdf-btn {
        background: var(--primary);
        color: white;
        border: none;
        font-weight: 700;
        padding: 16px 36px;
        border-radius: 100px;
        cursor: pointer;
        transition: var(--transition);
        margin-top: 20px;
        width: 100%;
        font-variation-settings: 'wght' 600;
    }

    .pdf-btn:hover {
        background: #1e2a38;
        transform: rotate(3deg) scale(1.05);
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    /* Main Content: Mosaic Grid Header */
    .grid-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding: 20px;
        background: rgba(255,255,255,0.8);
        border-radius: 20px;
        box-shadow: var(--shadow-sm);
        flex-wrap: wrap;
        gap: 20px;
    }

    .results-count {
        font-variation-settings: 'wght' 500;
        color: var(--gray);
    }

    .sort-select {
        padding: 12px 20px;
        border: none;
        background: linear-gradient(135deg, #f0f0f0, #ffffff);
        border-radius: 12px;
        font-size: 1rem;
        cursor: pointer;
        box-shadow: inset var(--shadow-sm);
        transition: var(--transition);
    }

    .sort-select:hover {
        box-shadow: 0 0 0 4px rgba(253,185,19,0.1);
    }

    /* Cards Grid – Strong equal height enforcement */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
    gap: 48px 40px;
    padding: 32px 0 80px;
    align-items: stretch;           /* Key: makes grid items stretch */
    justify-content: center;
}

/* Card – Full height, flex column structure */
.program-card {
    background: white;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    transition: var(--transition);
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;                   /* Fills grid row height */
    min-height: 620px;              /* Ensures minimum consistent size – tune this value */
    clip-path: var(--facet-clip);
    transform: perspective(1000px) rotateX(0deg) rotateY(0deg);
}

.program-card:hover {
    transform: perspective(1000px) rotateX(6deg) rotateY(9deg) translateY(-14px);
    box-shadow: 0 24px 52px rgba(0,0,0,0.18);
    z-index: 2;
}

.program-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--iridescent);
    opacity: 0;
    transition: opacity 0.4s;
    mix-blend-mode: screen;
    z-index: 1;
}

.program-card:hover::before {
    opacity: 0.28;
}

/* Image – Fixed aspect, no height variation */
.card-image-container {
    position: relative;
    width: 100%;
    padding-top: 66.67%;            /* 3:2 – consistent */
    overflow: hidden;
    flex-shrink: 0;
}

.card-image {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
    filter: brightness(0.92);
}

.program-card:hover .card-image {
    transform: scale(1.14) rotate(1.8deg);
    filter: brightness(1.08);
}

/* Body – Flex column to control vertical distribution */
.card-body {
    padding: 32px 28px 28px;
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    z-index: 2;
}

.card-title {
    font-family: var(--font-serif);
    font-size: 1.8rem;
    margin-bottom: 16px;
    color: var(--primary);
    line-height: 1.3;
    font-variation-settings: 'wght' 800;
    position: relative;
}

.card-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 60px;
    height: 3px;
    background: var(--accent);
    transition: width 0.4s ease;
}

.program-card:hover .card-title::after {
    width: 140px;
}

.card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 20px;
    font-size: 1rem;
    color: var(--gray);
}

.card-description {
    color: #444;
    font-size: 1.05rem;
    line-height: 1.75;
    margin-bottom: 28px;
    flex-grow: 1;                   /* Grows to fill space → equalizes height */
    font-variation-settings: 'wght' 400;
    opacity: 0.92;
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 1px dashed #eee;
    margin-top: auto;               /* Always at bottom */
}

/* Responsive */
@media (max-width: 1100px) {
    .cards-grid {
        gap: 44px 32px;
    }
}

@media (max-width: 992px) {
    .programs-container {
        grid-template-columns: 1fr;
        padding: 40px 24px 0;
    }
    .sidebar {
        position: static;
        transform: none;
        margin-bottom: 48px;
    }
    .program-card {
        min-height: 600px;
    }
}

@media (max-width: 576px) {
    .cards-grid {
        grid-template-columns: 1fr;
        gap: 36px;
    }
    .program-card {
        min-height: 580px;
        clip-path: none;
    }
    .card-image-container {
        padding-top: 75%;           /* Taller on mobile */
    }
}
</style>

<main>
    <!-- Particle Container -->
    <div id="particles-js"></div>

    <!-- Hero Header with Drips -->
    <header class="hero-header">
        <div class="drip drip1"></div>
        <div class="drip drip2"></div>
        <div class="drip drip3"></div>
        <div class="hero-content">
            <h1 class="page-title">Programs & Workshops</h1>
            <p class="page-subtitle">Unleash your inner artist in our vibrant Rwandan-inspired creative spaces at Inkingi Arts Space.</p>
            <a href="#programs" class="hero-cta">Dive into Creativity</a>
        </div>
    </header>

    <div class="programs-container" id="programs">
        <!-- Sidebar Palette -->
        <aside class="sidebar">
            <div class="sidebar-widget">
                <h3 class="widget-title">Search Masterpieces</h3>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Hunt for titles or keywords..." onkeyup="filterCards()">
                </div>
            </div>

            <div class="sidebar-widget">
                <h3 class="widget-title">Art Categories</h3>
                <ul class="category-list">
                    <li class="category-item active" data-category="all" onclick="filterByCategory('all', this)">
                        All Creations <span class="count-badge"><?= count($programs) ?></span>
                    </li>
                    <li class="category-item" data-category="Class" onclick="filterByCategory('Class', this)">
                        Classes <span class="count-badge"><?= $categories['Class'] ?? 0 ?></span>
                    </li>
                    <li class="category-item" data-category="Workshop" onclick="filterByCategory('Workshop', this)">
                        Workshops <span class="count-badge"><?= $categories['Workshop'] ?? 0 ?></span>
                    </li>
                    <li class="category-item" data-category="Experience" onclick="filterByCategory('Experience', this)">
                        Experiences <span class="count-badge"><?= $categories['Experience'] ?? 0 ?></span>
                    </li>
                </ul>
            </div>

            <div class="pdf-widget">
                <i class="fas fa-palette fa-3x" style="margin-bottom: 20px; color: white;"></i>
                <h3 style="margin: 0 0 16px; color: white; font-family: var(--font-serif);">Art Catalog</h3>
                <p style="margin-bottom: 24px; color: white; opacity: 0.85; font-size: 1rem;">Capture our programs in a stunning PDF palette.</p>
                <button class="pdf-btn" onclick="generatePDF()">Download Canvas</button>
            </div>
        </aside>

        <!-- Main Mosaic -->
        <section>
            <div class="grid-header">
                <div class="results-count">Revealing <strong id="resultCount"><?= count($programs) ?></strong> artistic journeys</div>
                <select class="sort-select" onchange="sortCards(this.value)">
                    <option value="title-asc">Title (A-Z)</option>
                    <option value="title-desc">Title (Z-A)</option>
                    <option value="price-asc">Price (Low-High)</option>
                    <option value="price-desc">Price (High-Low)</option>
                </select>
            </div>

            <div class="cards-grid" id="cardsContainer">
                <?php foreach ($programs as $index => $program): ?>
                    <article class="program-card" 
                             data-category="<?= strtolower(htmlspecialchars($program['category'])) ?>"
                             data-title="<?= htmlspecialchars($program['title']) ?>"
                             data-price="<?= (int)str_replace([',', ' Rwf'], '', $program['price']) ?>"
                             data-aos="fade-up" 
                             data-aos-delay="<?= $index * 150 ?>">
                        <div class="card-image-container">
                            <span class="card-category"><?= htmlspecialchars($program['category']) ?></span>
                            <img src="<?= htmlspecialchars($program['image_path']) ?>" 
                                 class="card-image" 
                                 alt="<?= htmlspecialchars($program['title']) ?> - Inkingi Arts Space" 
                                 loading="lazy">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title"><?= htmlspecialchars($program['title']) ?></h3>
                            
                            <div class="card-meta">
                                <div class="meta-item">
                                    <i class="far fa-clock"></i> <?= htmlspecialchars($program['schedule']) ?>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-tag"></i> <?= htmlspecialchars($program['price']) ?>
                                </div>
                            </div>

                            <p class="card-description"><?= htmlspecialchars($program['description']) ?></p>

                            <div class="card-footer">
                                <span class="price"><?= htmlspecialchars($program['price']) ?></span>
                                <a href="contact.php?book=<?= urlencode($program['title']) ?>" 
                                   class="btn-book">Embark <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</main>

<!-- Hidden PDF Content: Artistic Print Layout -->
<div id="printable-area">
    <div style="padding: 60px; font-family: var(--font-sans); color: #111; background: linear-gradient(180deg, #fff, #f8f9fa); border: 4px double var(--accent);">
        <img src="assets/images/logo.svg" alt="Inkingi Arts Space" style="display: block; margin: 0 auto 32px; max-width: 240px;">
        <h1 style="text-align:center; color: var(--primary); margin-bottom: 12px; font-family: var(--font-serif); font-variation-settings: 'wght' 900;">Inkingi Arts Space</h1>
        <p style="text-align:center; color: var(--gray); margin-bottom: 60px; font-style: italic; font-size: 1.2rem;">Creative Catalog – <?= date('Y') ?></p>
        <hr style="border: none; border-top: 2px solid var(--accent); margin-bottom: 60px;">
        
        <?php foreach ($programs as $p): ?>
            <div style="margin-bottom: 48px; padding: 32px; background: #fff; border-radius: 16px; box-shadow: var(--shadow-sm);">
                <h3 style="margin:0 0 16px; color: var(--primary); font-family: var(--font-serif); font-variation-settings: 'wght' 800;"><?= htmlspecialchars($p['title']) ?></h3>
                <p style="margin: 0 0 12px; color:var(--red); font-weight: 900; font-variation-settings: 'wght' 900;"><?= htmlspecialchars($p['price']) ?></p>
                <p style="margin: 0 0 20px; font-style:italic; color:var(--gray);"><i class="far fa-clock"></i> <?= htmlspecialchars($p['schedule']) ?> • <i class="fas fa-palette"></i> <?= htmlspecialchars($p['category']) ?></p>
                <p style="margin:0; line-height: 1.8; font-size: 1.1rem;"><?= htmlspecialchars($p['description']) ?></p>
            </div>
        <?php endforeach; ?>
        <p style="text-align:center; margin-top: 60px; color: var(--gray); font-size: 1rem;">Discover creativity at Inkingi Arts Space, Kigali, Rwanda | www.inkingiarts.com</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    // Particles Init: Artistic Confetti Background
    particlesJS('particles-js', {
        particles: {
            number: { value: 50, density: { enable: true, value_area: 800 } },
            color: { value: ['#FDB913', '#009E60', '#C8102E'] },
            shape: { type: 'circle' },
            opacity: { value: 0.5, random: true },
            size: { value: 5, random: true },
            line_linked: { enable: false },
            move: { enable: true, speed: 2, direction: 'none', random: true, straight: false, out_mode: 'out', bounce: false }
        },
        interactivity: {
            detect_on: 'canvas',
            events: { onhover: { enable: true, mode: 'bubble' }, onclick: { enable: true, mode: 'push' }, resize: true },
            modes: { bubble: { distance: 200, size: 10, duration: 2, opacity: 0.8 } }
        },
        retina_detect: true
    });

    AOS.init({ duration: 1000, once: false, offset: 150, easing: 'ease-in-out-quart' });

    // Masonry for Mosaic Layout
    const grid = document.querySelector('.cards-grid');
    const masonry = new Masonry(grid, {
        itemSelector: '.program-card',
        columnWidth: '.program-card',
        gutter: 40,
        fitWidth: true,
        transitionDuration: '0.4s',
        stagger: 30
    });

    // Category Filter (logic unchanged, added confetti on click)
    function filterByCategory(cat, element) {
        document.querySelectorAll('.category-item').forEach(el => el.classList.remove('active'));
        element.classList.add('active');

        const cards = document.querySelectorAll('.program-card');
        let visible = 0;

        cards.forEach(card => {
            if (cat === 'all' || card.dataset.category === cat.toLowerCase()) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('resultCount').textContent = visible;
        masonry.layout();

        // Confetti Burst on Filter
        confetti({
            particleCount: 50,
            spread: 70,
            origin: { y: 0.6 },
            colors: ['#FDB913', '#009E60', '#C8102E']
        });
    }

    // Live Search (logic unchanged)
    function filterCards() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.program-card');
        let visible = 0;

        cards.forEach(card => {
            const title = card.dataset.title.toLowerCase();
            const desc = card.querySelector('.card-description').textContent.toLowerCase();
            const visibleByCat = card.style.display !== 'none';

            if ((title.includes(query) || desc.includes(query)) && visibleByCat) {
                card.style.display = '';
                visible++;
            } else if (visibleByCat) {
                card.style.display = 'none';
            }
        });

        document.getElementById('resultCount').textContent = visible;
        masonry.layout();
    }

    // Sorting (logic unchanged)
    function sortCards(sortBy) {
        const container = document.getElementById('cardsContainer');
        const cards = Array.from(container.querySelectorAll('.program-card'));

        cards.sort((a, b) => {
            if (sortBy === 'title-asc') {
                return a.dataset.title.localeCompare(b.dataset.title);
            } else if (sortBy === 'title-desc') {
                return b.dataset.title.localeCompare(a.dataset.title);
            } else if (sortBy === 'price-asc') {
                return a.dataset.price - b.dataset.price;
            } else if (sortBy === 'price-desc') {
                return b.dataset.price - a.dataset.price;
            }
        });

        cards.forEach(card => container.appendChild(card));
        masonry.layout();
    }

    // PDF Generation (logic unchanged, enhanced options)
    function generatePDF() {
        const element = document.getElementById('printable-area');
        element.style.display = 'block';

        const options = {
            margin: 0.6,
            filename: 'Inkingi_Art_Catalog_' + new Date().getFullYear() + '.pdf',
            image: { type: 'jpeg', quality: 0.99 },
            html2canvas: { scale: 3, useCORS: true },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        html2pdf().set(options).from(element).save().then(() => {
            element.style.display = 'none';
        });
    }
</script>