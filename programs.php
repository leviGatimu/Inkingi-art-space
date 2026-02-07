<?php
// programs.php - Polished Version
require 'includes/db_connect.php';
require 'includes/header.php';

// FETCH PROGRAMS (Simulated logic maintained for reliability)
try {
    $stmt = $pdo->query("SELECT id, title, category, price, schedule, description, image_path FROM programs ORDER BY id DESC");
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Fallback data for demonstration
    $programs = [
        [
            'id' => 1,
            'title' => 'Art Painting Class',
            'category' => 'Class',
            'price' => '20,000 Rwf',
            'schedule' => 'Daily: 10am - 6pm',
            'description' => 'Open every day to kids and adults. We provide the canvas, paint, and brushes. Take your masterpiece home.',
            'image_path' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=900&h=500&s=1'
        ],
        [
            'id' => 2,
            'title' => 'Saturday Pottery',
            'category' => 'Workshop',
            'price' => '25,000 Rwf',
            'schedule' => 'Saturdays: 10am - 5pm',
            'description' => 'A tactile experience in clay. Learn wheel throwing and hand-building techniques from master potters.',
            'image_path' => 'https://mindtrip.ai/attractions/ee8a/0069/df4a/7290/dd06/9631/4be1/8414'
        ],
        [
            'id' => 3,
            'title' => 'Rwandan Cooking',
            'category' => 'Experience',
            'price' => '20,000 Rwf',
            'schedule' => 'Daily (Booking Req)',
            'description' => 'Interactive cooking session with Ikoma Art. Learn to prepare authentic dishes and enjoy the shared meal.',
            'image_path' => 'https://images.mindtrip.ai/attractions/4ef2/2dc8/a855/4b57/a0dc/9298/eca1/8017'
        ],
        [
            'id' => 4,
            'title' => 'Kids Creative Camp',
            'category' => 'Class',
            'price' => '15,000 Rwf',
            'schedule' => 'Weekends: 9am - 12pm',
            'description' => 'A fun morning of mixed media art designed specifically to unlock creativity in children.',
            'image_path' => 'https://images.pexels.com/photos/102127/pexels-photo-102127.jpeg?auto=compress&cs=tinysrgb&w=800'
        ]
    ];
}

// Calculate Categories
$categories = ['Class' => 0, 'Workshop' => 0, 'Experience' => 0];
$otherCount = 0;
foreach ($programs as $p) {
    $cat = ucfirst(strtolower($p['category']));
    if (array_key_exists($cat, $categories)) {
        $categories[$cat]++;
    } else {
        $otherCount++;
    }
}
?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<main>
    <header class="page-hero">
        <div class="hero-bg"></div>
        <div class="hero-content" data-aos="fade-up">
            <h1 class="hero-title">Programs & Workshops</h1>
            <p class="hero-subtitle">Discover your creative voice through our curated classes, immersive workshops, and cultural experiences.</p>
        </div>
    </header>

    <div class="main-layout container">
        
        <aside class="sidebar" data-aos="fade-right" data-aos-delay="100">
            <div class="sidebar-sticky">
                
                <div class="widget search-widget">
                    <h3 class="widget-title">Search</h3>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Find a program..." onkeyup="applyFilters()">
                    </div>
                </div>

                <div class="widget category-widget">
                    <h3 class="widget-title">Categories</h3>
                    <ul class="category-list">
                        <li class="cat-item active" onclick="setCategory('all', this)">
                            <span>All Programs</span>
                            <span class="badge"><?= count($programs) ?></span>
                        </li>
                        <?php foreach ($categories as $cat => $count): ?>
                        <li class="cat-item" onclick="setCategory('<?= strtolower($cat) ?>', this)">
                            <span><?= $cat ?></span>
                            <span class="badge"><?= $count ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="help-box">
                    <i class="far fa-question-circle"></i>
                    <h4>Need Help?</h4>
                    <p>Not sure which class is right for you?</p>
                    <a href="contact.php" class="text-link">Contact Us</a>
                </div>
            </div>
        </aside>

        <section class="content-area">
            
            <div class="toolbar" data-aos="fade-down" data-aos-delay="200">
                <p class="results-text">Showing <strong id="resultCount"><?= count($programs) ?></strong> experiences</p>
                <div class="sort-wrapper">
                    <span>Sort by:</span>
                    <select id="sortSelect" onchange="applyFilters()">
                        <option value="newest">Newest First</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                        <option value="title-asc">Name: A-Z</option>
                    </select>
                </div>
            </div>

            <div class="program-grid" id="programGrid">
                <?php foreach ($programs as $index => $program): 
                    $priceRaw = (int)str_replace([',', ' Rwf', ' '], '', $program['price']);
                    $catSlug = strtolower(htmlspecialchars($program['category']));
                ?>
                <article class="program-card" 
                         data-category="<?= $catSlug ?>" 
                         data-price="<?= $priceRaw ?>" 
                         data-title="<?= htmlspecialchars($program['title']) ?>"
                         data-aos="fade-up" 
                         data-aos-delay="<?= 100 + ($index * 50) ?>">
                    
                    <div class="card-media">
                        <span class="cat-tag"><?= htmlspecialchars($program['category']) ?></span>
                        <img src="<?= htmlspecialchars($program['image_path']) ?>" alt="<?= htmlspecialchars($program['title']) ?>">
                        <div class="overlay"></div>
                    </div>

                    <div class="card-details">
                        <div class="card-header">
                            <h3 class="card-title"><?= htmlspecialchars($program['title']) ?></h3>
                            <div class="meta-row">
                                <span><i class="far fa-clock"></i> <?= htmlspecialchars($program['schedule']) ?></span>
                            </div>
                        </div>
                        
                        <p class="card-desc"><?= htmlspecialchars($program['description']) ?></p>
                        
                        <div class="card-footer">
                            <div class="price-block">
                                <span class="label">Price</span>
                                <span class="value"><?= htmlspecialchars($program['price']) ?></span>
                            </div>
                            <a href="contact.php?book=<?= urlencode($program['title']) ?>" class="btn-book">
                                Book Now
                            </a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <div id="noResults" class="no-results" style="display: none;">
                <i class="fas fa-search"></i>
                <h3>No programs found</h3>
                <p>Try adjusting your search or category filters.</p>
                <button onclick="resetFilters()" class="btn-outline">Clear Filters</button>
            </div>

        </section>
    </div>

    <section class="section-faq">
        <div class="container-narrow">
            <h2 class="section-heading">Frequently Asked Questions</h2>
            <div class="accordion">
                <div class="accordion-item">
                    <button class="accordion-header">
                        What materials do I need to bring?
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="accordion-body">
                        <p>For most classes (Painting, Pottery), we provide all necessary materials including canvas, paints, aprons, and tools. Just bring yourself! For specialized workshops, specific requirements will be listed.</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">
                        Do I need previous art experience?
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="accordion-body">
                        <p>Not at all! Our "Class" and "Experience" categories are designed for all skill levels, from complete beginners to seasoned artists looking for a creative space.</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header">
                        Can I book a private session?
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="accordion-body">
                        <p>Yes, we offer private bookings for team building, birthday parties, or 1-on-1 tuition. Please contact us directly via email to arrange a private schedule.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>

<style>
    /* --- CSS Variables (Matching previous page) --- */
    :root {
        --primary-dark: #1A2530;
        --accent-gold: #D4AF37;
        --accent-hover: #B5952F;
        --bg-light: #F9FAFB;
        --white: #ffffff;
        --text-gray: #5a6a7e;
        --font-heading: 'Playfair Display', serif;
        --font-body: 'Poppins', sans-serif;
        --radius: 12px;
        --shadow-soft: 0 10px 30px rgba(0,0,0,0.05);
        --shadow-hover: 0 20px 40px rgba(0,0,0,0.1);
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    /* --- Base --- */
    body {
        font-family: var(--font-body);
        background: var(--bg-light);
        color: var(--primary-dark);
        margin: 0;
        line-height: 1.6;
    }

    h1, h2, h3, h4 { font-family: var(--font-heading); margin: 0; font-weight: 700; }
    
    .container { max-width: 1300px; margin: 0 auto; padding: 0 24px; }
    .container-narrow { max-width: 800px; margin: 0 auto; padding: 0 24px; }

    /* --- Hero --- */
    .page-hero {
        position: relative;
        height: 60vh;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--white);
        margin-bottom: 60px;
    }

    .hero-bg {
        position: absolute;
        inset: 0;
        background: url('https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=1600&q=80') center/cover;
        background-attachment: fixed;
    }

    .hero-bg::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(to bottom, rgba(26,37,48,0.6), rgba(26,37,48,0.9));
    }

    .hero-content { position: relative; z-index: 2; max-width: 700px; padding: 20px; }
    .hero-title { font-size: clamp(3rem, 6vw, 4.5rem); margin-bottom: 16px; }
    .hero-subtitle { font-size: 1.2rem; opacity: 0.9; font-weight: 300; }

    /* --- Layout --- */
    .main-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 60px;
        padding-bottom: 100px;
        align-items: start;
    }

    /* --- Sidebar --- */
    .sidebar-sticky { position: sticky; top: 120px; }
    
    .widget { margin-bottom: 40px; }
    .widget-title { font-size: 1.4rem; margin-bottom: 20px; color: var(--primary-dark); }

    .search-box {
        position: relative;
        background: var(--white);
        border: 1px solid #eee;
        border-radius: 50px;
        padding: 5px 20px;
        display: flex;
        align-items: center;
        transition: var(--transition);
    }
    .search-box:focus-within { border-color: var(--accent-gold); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); }
    .search-box input { border: none; outline: none; width: 100%; padding: 10px; font-family: inherit; font-size: 0.95rem; }
    .search-box i { color: var(--accent-gold); }

    .category-list { list-style: none; padding: 0; margin: 0; }
    .cat-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: var(--transition);
        color: var(--text-gray);
    }
    .cat-item:hover, .cat-item.active { color: var(--accent-gold); transform: translateX(5px); }
    .cat-item.active { font-weight: 600; border-bottom-color: var(--accent-gold); }
    
    .badge {
        background: #eee; color: #666; font-size: 0.75rem;
        padding: 2px 10px; border-radius: 20px; transition: var(--transition);
    }
    .cat-item.active .badge { background: var(--accent-gold); color: white; }

    .help-box {
        background: var(--primary-dark);
        color: white;
        padding: 30px;
        border-radius: var(--radius);
        text-align: center;
    }
    .help-box i { font-size: 2rem; color: var(--accent-gold); margin-bottom: 15px; }
    .text-link { color: var(--accent-gold); text-decoration: underline; font-weight: 600; }

    /* --- Toolbar --- */
    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    .sort-wrapper select {
        padding: 8px 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-family: inherit;
        color: var(--primary-dark);
        cursor: pointer;
    }

    /* --- Grid & Cards --- */
    .program-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
    }

    .program-card {
        background: var(--white);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0,0,0,0.03);
    }

    .program-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-hover);
    }

    .card-media {
        position: relative;
        height: 220px;
        overflow: hidden;
    }
    .card-media img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.6s ease;
    }
    .program-card:hover .card-media img { transform: scale(1.08); }
    
    .cat-tag {
        position: absolute;
        top: 15px; right: 15px;
        background: rgba(255,255,255,0.95);
        color: var(--primary-dark);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 50px;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .card-details { padding: 25px; display: flex; flex-direction: column; flex: 1; }
    .card-title { font-size: 1.5rem; margin-bottom: 10px; line-height: 1.3; }
    .meta-row { color: var(--text-gray); font-size: 0.9rem; margin-bottom: 15px; }
    .meta-row i { color: var(--accent-gold); margin-right: 5px; }
    
    .card-desc {
        color: #666; font-size: 0.95rem; margin-bottom: 25px;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }

    .card-footer {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }

    .price-block { display: flex; flex-direction: column; }
    .price-block .label { font-size: 0.75rem; text-transform: uppercase; color: #aaa; font-weight: 600; }
    .price-block .value { font-size: 1.25rem; font-weight: 700; color: var(--primary-dark); }

    .btn-book {
        background: var(--accent-gold);
        color: var(--white);
        padding: 10px 24px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
        font-size: 0.9rem;
    }
    .btn-book:hover { background: var(--accent-hover); box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3); }

    .btn-outline {
        padding: 10px 24px; border: 1px solid #ddd; background: transparent; 
        cursor: pointer; border-radius: 50px; margin-top: 20px;
    }

    .no-results { text-align: center; padding: 60px; color: #999; width: 100%; grid-column: 1/-1; }
    .no-results i { font-size: 3rem; margin-bottom: 20px; display: block; opacity: 0.5; }

    /* --- FAQ --- */
    .section-faq { padding: 100px 0; background: white; }
    .section-heading { text-align: center; font-size: 2.5rem; margin-bottom: 50px; }
    
    .accordion-item { border-bottom: 1px solid #eee; }
    .accordion-header {
        width: 100%; text-align: left; padding: 25px 0;
        background: none; border: none; cursor: pointer;
        font-family: var(--font-body); font-size: 1.1rem; font-weight: 600;
        display: flex; justify-content: space-between; align-items: center;
        color: var(--primary-dark); transition: color 0.3s;
    }
    .accordion-header:hover { color: var(--accent-gold); }
    .accordion-header i { transition: transform 0.3s; color: var(--accent-gold); }
    .accordion-item.active .accordion-header i { transform: rotate(45deg); }
    
    .accordion-body {
        max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out;
        color: var(--text-gray); padding-right: 40px;
    }
    .accordion-item.active .accordion-body { max-height: 200px; padding-bottom: 25px; }

    /* --- Responsive --- */
    @media (max-width: 992px) {
        .main-layout { grid-template-columns: 1fr; gap: 40px; }
        .sidebar-sticky { position: static; }
        .sidebar { order: -1; } /* Move sidebar to top */
        .page-hero { height: 50vh; }
    }
</style>

<script>
    // Initialize Animations
    AOS.init({ duration: 800, once: true });

    // --- Unified Filtering Logic ---
    let currentCategory = 'all';
    let currentSearch = '';

    function setCategory(cat, element) {
        currentCategory = cat;
        
        // Update UI classes
        document.querySelectorAll('.cat-item').forEach(el => el.classList.remove('active'));
        if(element) element.classList.add('active');

        applyFilters();
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        setCategory('all', document.querySelector('.cat-item:first-child'));
    }

    function applyFilters() {
        currentSearch = document.getElementById('searchInput').value.toLowerCase();
        const sortValue = document.getElementById('sortSelect').value;
        const container = document.getElementById('programGrid');
        const cards = Array.from(document.querySelectorAll('.program-card'));
        let visibleCount = 0;

        // 1. Filter
        cards.forEach(card => {
            const cardCat = card.dataset.category;
            const cardTitle = card.dataset.title.toLowerCase();
            
            const matchesCategory = currentCategory === 'all' || cardCat === currentCategory;
            const matchesSearch = cardTitle.includes(currentSearch);

            if (matchesCategory && matchesSearch) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // 2. Sort (Only sort visible cards technically, but sorting all is fine)
        const sortedCards = cards.sort((a, b) => {
            const priceA = parseInt(a.dataset.price);
            const priceB = parseInt(b.dataset.price);
            const titleA = a.dataset.title;
            const titleB = b.dataset.title;

            if (sortValue === 'price-asc') return priceA - priceB;
            if (sortValue === 'price-desc') return priceB - priceA;
            if (sortValue === 'title-asc') return titleA.localeCompare(titleB);
            // Default newest (simulated by DOM order usually, or ID if we had it)
            return 0; 
        });

        // Re-append sorted cards
        sortedCards.forEach(card => container.appendChild(card));

        // 3. UI Updates
        document.getElementById('resultCount').textContent = visibleCount;
        document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
        
        // Refresh AOS layout if needed
        setTimeout(() => AOS.refresh(), 100);
    }

    // --- Accordion Logic ---
    document.querySelectorAll('.accordion-header').forEach(button => {
        button.addEventListener('click', () => {
            const item = button.parentElement;
            
            // Close others (optional - strictly one open at a time)
            document.querySelectorAll('.accordion-item').forEach(i => {
                if (i !== item) i.classList.remove('active');
            });

            item.classList.toggle('active');
        });
    });
</script>