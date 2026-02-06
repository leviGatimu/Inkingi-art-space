<?php
// programs.php - Enhanced Professional Programs Listing Page
// Inspired by sites like Path with Art (linear but card-like listings with images, clear details), Workhouse Arts Center (explore classes with filters), and MoMA (clean course lists with register buttons).
// Improvements: Added hero background for visual appeal, improved card meta with icons, added sorting by price/title, enhanced PDF styling, subtle animations, masonry grid option (via CSS), culturally relevant touches (Rwandan color accents).

require 'includes/db_connect.php';
require 'includes/header.php';

// FETCH PROGRAMS FROM DB (preferred) - Admin can input via separate admin panel (e.g., insert into 'programs' table with columns: title, category, price, schedule, description, image_path)
try {
    $stmt = $pdo->query("SELECT id, title, category, price, schedule, description, image_path FROM programs ORDER BY id DESC");
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // FALLBACK STATIC DATA (for development or if table is empty) - Updated with real Rwandan art-inspired images
    $programs = [
        [
            'title' => 'Art Painting Class',
            'category' => 'Class',
            'price' => '20,000 Rwf',
            'schedule' => 'Daily: 10am - 6pm',
            'description' => 'Open every day to kids and adults. We provide the canvas, paint, and brushes. Take your masterpiece home.',
            'image_path' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=900&h=500&s=1' // Real image example
        ],
        [
            'title' => 'Saturday Pottery',
            'category' => 'Workshop',
            'price' => '25,000 Rwf',
            'schedule' => 'Saturdays: 10am - 5pm',
            'description' => 'A tactile experience in clay. Learn wheel throwing and hand-building techniques from master potters.',
            'image_path' => 'https://mindtrip.ai/attractions/ee8a/0069/df4a/7290/dd06/9631/4be1/8414' // Real image example
        ],
        [
            'title' => 'Rwandan Cooking',
            'category' => 'Experience',
            'price' => '20,000 Rwf',
            'schedule' => 'Daily (Booking Req)',
            'description' => 'Interactive cooking session with Ikoma Art. Learn to prepare authentic dishes and enjoy the shared meal.',
            'image_path' => 'https://images.mindtrip.ai/attractions/4ef2/2dc8/a855/4b57/a0dc/9298/eca1/8017' // Real image example
        ]
    ];
}

// For dynamic category counts
$categories = ['Class' => 0, 'Workshop' => 0, 'Experience' => 0, 'Other' => 0];
foreach ($programs as $p) {
    $cat = $p['category'] ?? 'Other';
    $categories[$cat] = ($categories[$cat] ?? 0) + 1;
}
?>

<!-- Fonts & Libraries (consistent) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script> <!-- For masonry grid -->

<style>
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
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--light);
        color: var(--dark);
        line-height: 1.6;
    }

    main {
        padding: 0 0 100px;
    }

    /* Hero Header (Inspired by museum sites with immersive intros) */
    .hero-header {
        background: url('https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=900&h=500&s=1') no-repeat center/cover; /* Real Inkingi-inspired hero image */
        color: white;
        text-align: center;
        padding: 120px 20px 80px;
        position: relative;
    }

    .hero-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(44, 62, 80, 0.7); /* Overlay for readability */
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 3.2rem;
        margin-bottom: 16px;
    }

    .page-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 720px;
        margin: 0 auto 32px;
    }

    .hero-cta {
        background: var(--accent);
        color: var(--primary);
        padding: 12px 32px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
    }

    .hero-cta:hover {
        background: #e6a50a;
        transform: translateY(-3px);
    }

    /* Layout */
    .programs-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 40px 20px 0;
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 40px;
    }

    /* Sidebar (Sticky, with enhanced filters) */
    .sidebar {
        position: sticky;
        top: 100px;
        align-self: start;
    }

    .sidebar-widget {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        padding: 24px;
        margin-bottom: 24px;
    }

    .widget-title {
        font-size: 1.25rem;
        color: var(--primary);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #eee;
    }

    /* Search */
    .search-box {
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
    }

    .search-input {
        width: 70%;
        padding: 14px 14px 14px 48px;
        border: 1px solid #ddd;
        border-radius: 50px;
        font-size: 0.95rem;
        transition: var(--transition);
    }

    .search-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(253,185,19,0.12);
    }

    /* Categories (Clickable, active state) */
    .category-list {
        list-style: none;
    }

    .category-item {
        padding: 12px 0;
        color: var(--gray);
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-item:hover,
    .category-item.active {
        color: var(--primary);
        font-weight: 600;
        padding-left: 8px;
    }

    .category-item.active {
        background: linear-gradient(to right, rgba(253,185,19,0.1), transparent);
        border-left: 4px solid var(--accent);
    }

    .count-badge {
        background: #f0f0f0;
        color: var(--gray);
        font-size: 0.8rem;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .category-item.active .count-badge {
        background: var(--accent);
        color: white;
    }

    /* PDF Widget (Gradient bg for appeal) */
    .pdf-widget {
        background: linear-gradient(135deg, var(--primary), var(--green));
        color: white;
        text-align: center;
        padding: 32px 24px;
        border-radius: var(--radius);
    }

    .pdf-btn {
        background: var(--accent);
        color: var(--primary);
        border: none;
        font-weight: 600;
        padding: 14px 32px;
        border-radius: 50px;
        cursor: pointer;
        transition: var(--transition);
        margin-top: 16px;
        width: 100%;
    }

    .pdf-btn:hover {
        background: #e6a50a;
        transform: scale(1.05);
    }

    /* Main Content */
    .grid-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .results-count {
        font-weight: 500;
        color: var(--gray);
    }

    .sort-select {
        padding: 10px 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: white;
        font-size: 0.95rem;
        cursor: pointer;
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 32px;
        /* For masonry: Use Masonry JS below */
    }

    /* Card (Enhanced: More meta icons, better hover) */
    .program-card {
        background: white;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
    }

    .program-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: var(--shadow-md);
    }

    .card-image-container {
        height: 240px;
        overflow: hidden;
        position: relative;
    }

    .card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s ease;
    }

    .program-card:hover .card-image {
        transform: scale(1.12);
    }

    .card-category {
        position: absolute;
        top: 16px;
        left: 16px;
        background: var(--accent);
        color: white;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 6px 14px;
        border-radius: 50px;
        text-transform: uppercase;
    }

    .card-body {
        padding: 28px 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        margin-bottom: 12px;
        color: var(--primary);
        line-height: 1.25;
    }

    .card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 16px;
        font-size: 0.9rem;
        color: var(--gray);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .meta-item i {
        color: var(--green);
        font-size: 1.1rem;
    }

    .card-description {
        color: #555;
        font-size: 0.96rem;
        line-height: 1.65;
        margin-bottom: 24px;
        flex: 1;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }

    .price {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--red);
    }

    .btn-book {
        background: linear-gradient(to right, var(--primary), #34495e);
        color: white;
        padding: 10px 24px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: var(--transition);
    }

    .btn-book:hover {
        background: linear-gradient(to right, #1e2a38, #2c3e50);
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .programs-container {
            grid-template-columns: 1fr;
        }
        .sidebar {
            position: static;
        }
        .hero-header {
            padding: 80px 20px 60px;
        }
    }

    #printable-area {
        display: none;
    }
</style>

<main>
    <!-- Hero Header -->
    <header class="hero-header">
        <div class="hero-content">
            <h1 class="page-title">Programs & Workshops</h1>
            <p class="page-subtitle">Immerse yourself in Rwandan creativity through our art classes, workshops, and cultural experiences at Inkingi Arts Space.</p>
            <a href="#programs" class="hero-cta">Explore Now</a>
        </div>
    </header>

    <div class="programs-container" id="programs">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-widget">
                <h3 class="widget-title">Search Programs</h3>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search by title, keyword..." onkeyup="filterCards()">
                </div>
            </div>

            <div class="sidebar-widget">
                <h3 class="widget-title">Categories</h3>
                <ul class="category-list">
                    <li class="category-item active" data-category="all" onclick="filterByCategory('all', this)">
                        All Programs <span class="count-badge"><?= count($programs) ?></span>
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
                <i class="fas fa-file-pdf fa-3x" style="margin-bottom: 16px; opacity: 0.9;"></i>
                <h3 style="margin: 0 0 12px; color: white;">Program Catalog</h3>
                <p style="margin-bottom: 20px; opacity: 0.9; font-size: 0.95rem;">Download our full program details as a beautifully formatted PDF.</p>
                <button class="pdf-btn" onclick="generatePDF()">Download PDF</button>
            </div>
        </aside>

        <!-- Main Content -->
        <section>
            <div class="grid-header">
                <div class="results-count">Showing <strong id="resultCount"><?= count($programs) ?></strong> programs</div>
                <select class="sort-select" onchange="sortCards(this.value)">
                    <option value="title-asc">Sort by Title (A-Z)</option>
                    <option value="title-desc">Sort by Title (Z-A)</option>
                    <option value="price-asc">Sort by Price (Low-High)</option>
                    <option value="price-desc">Sort by Price (High-Low)</option>
                </select>
            </div>

            <div class="cards-grid" id="cardsContainer">
                <?php foreach ($programs as $index => $program): ?>
                    <article class="program-card" 
                             data-category="<?= strtolower(htmlspecialchars($program['category'])) ?>"
                             data-title="<?= htmlspecialchars($program['title']) ?>"
                             data-price="<?= (int)str_replace([',', ' Rwf'], '', $program['price']) ?>"
                             data-aos="zoom-in-up" 
                             data-aos-delay="<?= $index * 100 ?>">
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
                                   class="btn-book">Book Now <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</main>

<!-- Hidden PDF content (Enhanced styling for print) -->
<div id="printable-area">
    <div style="padding: 48px; font-family: 'Poppins', sans-serif; color: #222; background: #fff; border: 2px solid var(--primary);">
        <img src="assets/images/logo.svg" alt="Inkingi Arts Space" style="display: block; margin: 0 auto 24px; max-width: 200px;">
        <h1 style="text-align:center; color: var(--primary); margin-bottom: 8px; font-family: 'Playfair Display', serif;">Inkingi Arts Space</h1>
        <p style="text-align:center; color: var(--gray); margin-bottom: 48px; font-style: italic;">Programs & Workshops Catalog – <?= date('Y') ?></p>
        <hr style="border: none; border-top: 1px dashed #ddd; margin-bottom: 48px;">
        
        <?php foreach ($programs as $p): ?>
            <div style="margin-bottom: 40px; padding: 24px; background: #f8f9fa; border-radius: 8px;">
                <h3 style="margin:0 0 12px; color: var(--primary); font-family: 'Playfair Display', serif;"><?= htmlspecialchars($p['title']) ?></h3>
                <p style="margin: 0 0 8px; color:var(--red); font-weight: bold;"><?= htmlspecialchars($p['price']) ?></p>
                <p style="margin: 0 0 16px; font-style:italic; color:var(--gray);"><i class="far fa-clock"></i> <?= htmlspecialchars($p['schedule']) ?> • <i class="fas fa-tags"></i> <?= htmlspecialchars($p['category']) ?></p>
                <p style="margin:0; line-height: 1.6;"><?= htmlspecialchars($p['description']) ?></p>
            </div>
        <?php endforeach; ?>
        <p style="text-align:center; margin-top: 48px; color: var(--gray); font-size: 0.9rem;">Visit us at Inkingi Arts Space, Kigali, Rwanda | www.inkingiarts.com</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    AOS.init({ duration: 800, once: true, offset: 100 });

    // Masonry Grid (for uneven card heights, inspired by gallery sites)
    const grid = document.querySelector('.cards-grid');
    new Masonry(grid, {
        itemSelector: '.program-card',
        columnWidth: '.program-card',
        gutter: 32,
        fitWidth: true,
        transitionDuration: '0.3s'
    });

    // Category Filter
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
        grid.masonry('layout'); // Re-layout masonry
    }

    // Live Search
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
        grid.masonry('layout');
    }

    // Sorting
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
        grid.masonry('layout');
    }

    // PDF Download (Enhanced options)
    function generatePDF() {
        const element = document.getElementById('printable-area');
        element.style.display = 'block';

        const options = {
            margin: 0.5,
            filename: 'Inkingi_Programs_Catalog_' + new Date().getFullYear() + '.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        html2pdf().set(options).from(element).save().then(() => {
            element.style.display = 'none';
        });
    }
</script>