<?php
// programs.php - Cleaned & Fixed Version
require 'includes/db_connect.php';
require 'includes/header.php';

// FETCH PROGRAMS
try {
    $stmt = $pdo->query("SELECT id, title, category, price, schedule, description, image_path FROM programs ORDER BY id DESC");
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
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

$categories = ['Class' => 0, 'Workshop' => 0, 'Experience' => 0, 'Other' => 0];
foreach ($programs as $p) {
    $cat = $p['category'] ?? 'Other';
    $categories[$cat] = ($categories[$cat] ?? 0) + 1;
}
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

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
        --shadow-sm: 0 4px 12px rgba(0,0,0,0.08);
        --shadow-md: 0 12px 32px rgba(0,0,0,0.12);
        --transition: all 0.35s ease;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--light);
        color: var(--dark);
        margin: 0;
        padding: 0;
        line-height: 1.6;
    }

    #particles-js {
        position: fixed;
        inset: 0;
        z-index: -1;
        opacity: 0.15;
        pointer-events: none;
    }

    main {
        padding-bottom: 120px;
    }

    /* Hero - Simplified */
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
        inset: 0;
        background: url('https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=900&h=500&s=1') center/cover;
        opacity: 0.25;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 900px;
        margin: 0 auto;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 4.2rem;
        margin-bottom: 20px;
        font-weight: 900;
    }

    .page-subtitle {
        font-size: 1.35rem;
        opacity: 0.9;
        margin-bottom: 40px;
        max-width: 720px;
        margin-left: auto;
        margin-right: auto;
    }

    .hero-cta {
        display: inline-block;
        background: var(--accent);
        color: var(--primary);
        font-weight: 700;
        font-size: 1.15rem;
        padding: 16px 48px;
        border-radius: 50px;
        text-decoration: none;
        box-shadow: 0 8px 24px rgba(253,185,19,0.35);
        transition: all 0.4s ease;
    }

    .hero-cta:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(253,185,19,0.5);
        background: #ffca28;
    }

    /* Programs Container */
    .programs-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 80px 30px 0;
        display: grid;
        grid-template-columns: 340px 1fr;
        gap: 60px;
    }

    /* Sidebar */
    .sidebar {
        position: sticky;
        top: 100px;
        align-self: start;
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        padding: 32px;
    }

    .sidebar-widget {
        margin-bottom: 40px;
    }

    .widget-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        color: var(--primary);
        margin-bottom: 20px;
        font-weight: 700;
    }

    .widget-title i {
        color: var(--accent);
        margin-right: 10px;
    }

    .search-box {
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--accent);
    }

    .search-input {
        width: 100%;
        padding: 14px 14px 14px 50px;
        border: 1px solid #ddd;
        border-radius: 50px;
        font-size: 1rem;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(253,185,19,0.15);
    }

    .category-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .category-item {
        padding: 12px 16px;
        margin-bottom: 8px;
        cursor: pointer;
        border-radius: 10px;
        transition: all 0.3s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-item:hover,
    .category-item.active {
        background: rgba(253,185,19,0.1);
        color: var(--primary);
        font-weight: 600;
    }

    .count-badge {
        background: #eee;
        color: #555;
        font-size: 0.85rem;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .category-item.active .count-badge {
        background: var(--accent);
        color: white;
    }

    /* Grid Header */
    .grid-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .results-count {
        font-weight: 500;
        color: var(--gray);
    }

    .sort-select {
        padding: 12px 20px;
        border: 1px solid #ddd;
        border-radius: 12px;
        background: white;
        font-size: 1rem;
        cursor: pointer;
    }

    /* Cards - FIXED SAME SIZE */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
        gap: 50px 40px;
        align-items: stretch;
    }

    .program-card {
        background: white;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.4s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 640px;
    }

    .program-card:hover {
        transform: translateY(-12px);
        box-shadow: var(--shadow-md);
    }

    .card-image-container {
        position: relative;
        width: 100%;
        padding-top: 66.67%; /* 3:2 ratio */
        overflow: hidden;
        flex-shrink: 0;
    }

    .card-image {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
    }

    .program-card:hover .card-image {
        transform: scale(1.08);
    }

    .card-category {
        position: absolute;
        top: 20px;
        right: 20px;
        background: var(--accent);
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 8px 16px;
        border-radius: 50px;
        text-transform: uppercase;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    [data-category="class"] .card-category { background: var(--cat-class); }
    [data-category="workshop"] .card-category { background: var(--cat-workshop); }
    [data-category="experience"] .card-category { background: var(--cat-experience); }
    [data-category="other"] .card-category { background: var(--cat-other); }

    .card-body {
        padding: 32px 28px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.9rem;
        margin-bottom: 16px;
        color: var(--primary);
        line-height: 1.3;
        font-weight: 700;
    }

    .card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 20px;
        font-size: 0.98rem;
        color: var(--gray);
    }

    .meta-item i {
        color: var(--green);
        margin-right: 6px;
    }

    .card-description {
        flex-grow: 1;
        color: #444;
        font-size: 1.02rem;
        line-height: 1.7;
        margin-bottom: 28px;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .price {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--red);
    }

    .btn-book {
        background: var(--primary);
        color: white;
        padding: 12px 28px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.35s ease;
    }

    .btn-book:hover {
        background: #1e2a38;
        transform: translateY(-2px);
    }

    /* FAQ Section */
    .faq-section {
        max-width: 1100px;
        margin: 100px auto;
        padding: 0 30px;
    }

    .faq-section h2 {
        text-align: center;
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        margin-bottom: 60px;
        color: var(--primary);
    }

    .faq-item {
        margin-bottom: 16px;
        border-radius: 12px;
        overflow: hidden;
        background: white;
        box-shadow: var(--shadow-sm);
    }

    .faq-question {
        width: 100%;
        padding: 20px 32px;
        text-align: left;
        background: none;
        border: none;
        font-size: 1.18rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .faq-question::after {
        content: '\f078';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        transition: transform 0.4s;
    }

    .faq-item.active .faq-question::after {
        transform: rotate(180deg);
    }

    .faq-answer {
        max-height: 0;
        overflow: hidden;
        padding: 0 32px;
        transition: all 0.4s ease;
        background: #fafafa;
    }

    .faq-item.active .faq-answer {
        max-height: 600px;
        padding: 28px 32px;
    }

    @media (max-width: 992px) {
        .programs-container {
            grid-template-columns: 1fr;
            padding: 60px 24px 0;
        }
        .sidebar {
            position: static;
        }
        .hero-header {
            padding: 100px 20px 80px;
        }
        .page-title {
            font-size: 3.2rem;
        }
    }

    @media (max-width: 576px) {
        .cards-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        .program-card {
            min-height: 600px;
        }
        .card-image-container {
            padding-top: 75%;
        }
    }
</style>

<main>
    <div id="particles-js"></div>

    <header class="hero-header">
        <div class="hero-content">
            <h1 class="page-title">Programs & Workshops</h1>
            <p class="page-subtitle">Discover creativity through our vibrant classes, workshops and cultural experiences at Inkingi Arts Space.</p>
            <a href="#programs" class="hero-cta">Explore Programs</a>
        </div>
    </header>

    <div class="programs-container" id="programs">
        <aside class="sidebar">
            <div class="sidebar-widget">
                <h3 class="widget-title"><i class="fas fa-search"></i> Search Programs</h3>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search by title..." onkeyup="filterCards()">
                </div>
            </div>

            <div class="sidebar-widget">
                <h3 class="widget-title"><i class="fas fa-palette"></i> Categories</h3>
                <ul class="category-list">
                    <li class="category-item active" data-category="all" onclick="filterByCategory('all', this)">
                        All <span class="count-badge"><?= count($programs) ?></span>
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
        </aside>

        <section>
            <div class="grid-header">
                <div class="results-count">Showing <strong id="resultCount"><?= count($programs) ?></strong> programs</div>
                <select class="sort-select" onchange="sortCards(this.value)">
                    <option value="title-asc">Title (A-Z)</option>
                    <option value="title-desc">Title (Z-A)</option>
                    <option value="price-asc">Price (Low to High)</option>
                    <option value="price-desc">Price (High to Low)</option>
                </select>
            </div>

            <div class="cards-grid" id="cardsContainer">
                <?php foreach ($programs as $index => $program): ?>
                    <article class="program-card" 
                             data-category="<?= strtolower(htmlspecialchars($program['category'])) ?>"
                             data-title="<?= htmlspecialchars($program['title']) ?>"
                             data-price="<?= (int)str_replace([',', ' Rwf'], '', $program['price']) ?>"
                             data-aos="fade-up" 
                             data-aos-delay="<?= $index * 120 ?>">
                        <div class="card-image-container">
                            <span class="card-category"><?= htmlspecialchars($program['category']) ?></span>
                            <img src="<?= htmlspecialchars($program['image_path']) ?>" 
                                 class="card-image" 
                                 alt="<?= htmlspecialchars($program['title']) ?>">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title"><?= htmlspecialchars($program['title']) ?></h3>
                            
                            <div class="card-meta">
                                <div class="meta-item"><i class="far fa-clock"></i> <?= htmlspecialchars($program['schedule']) ?></div>
                                <div class="meta-item"><i class="fas fa-tag"></i> <?= htmlspecialchars($program['price']) ?></div>
                            </div>

                            <p class="card-description"><?= htmlspecialchars($program['description']) ?></p>

                            <div class="card-footer">
                                <span class="price"><?= htmlspecialchars($program['price']) ?></span>
                                <a href="contact.php?book=<?= urlencode($program['title']) ?>" class="btn-book">Book Now</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <!-- FAQ -->
    <section class="faq-section">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-accordion">
            <div class="faq-item">
                <button class="faq-question">What materials are provided in classes?</button>
                <div class="faq-answer">
                    <p>We provide all necessary materials including canvas, paints, brushes, clay, tools etc. You only need to bring your creativity!</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Is previous experience required?</button>
                <div class="faq-answer">
                    <p>No — our programs are suitable for complete beginners as well as advanced artists.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">How do I book a program?</button>
                <div class="faq-answer">
                    <p>Click "Book Now" on any program card or contact us directly. Early booking is recommended.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Are children welcome?</button>
                <div class="faq-answer">
                    <p>Yes! Many programs are family-friendly and suitable for children aged 6+.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">What is your cancellation policy?</button>
                <div class="faq-answer">
                    <p>Free cancellation up to 24 hours before the session. Please contact us for any changes.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<script>
    // Particles - subtle
    particlesJS('particles-js', {
        particles: {
            number: { value: 30, density: { enable: true, value_area: 800 } },
            color: { value: ['#FDB913', '#009E60', '#C8102E'] },
            shape: { type: 'circle' },
            opacity: { value: 0.4, random: true },
            size: { value: 4, random: true },
            move: { enable: true, speed: 1.2, random: true }
        },
        interactivity: {
            events: { onhover: { enable: true, mode: 'grab' } }
        }
    });

    AOS.init({ duration: 800, once: true });

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
    }

    // Search
    function filterCards() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.program-card');
        let visible = 0;

        cards.forEach(card => {
            const title = card.dataset.title.toLowerCase();
            const desc = card.querySelector('.card-description').textContent.toLowerCase();

            if (title.includes(query) || desc.includes(query)) {
                if (card.style.display !== 'none') {
                    visible++;
                }
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('resultCount').textContent = visible;
    }

    // Sorting
    function sortCards(sortBy) {
        const container = document.getElementById('cardsContainer');
        const cards = Array.from(container.children);

        cards.sort((a, b) => {
            if (sortBy === 'title-asc') return a.dataset.title.localeCompare(b.dataset.title);
            if (sortBy === 'title-desc') return b.dataset.title.localeCompare(a.dataset.title);
            if (sortBy === 'price-asc') return a.dataset.price - b.dataset.price;
            if (sortBy === 'price-desc') return b.dataset.price - a.dataset.price;
            return 0;
        });

        cards.forEach(card => container.appendChild(card));
    }

    // FAQ Accordion
    document.querySelectorAll('.faq-question').forEach(btn => {
        btn.addEventListener('click', () => {
            const parent = btn.parentElement;
            parent.classList.toggle('active');
        });
    });
</script>