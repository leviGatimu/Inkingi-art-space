<?php
require 'includes/db_connect.php';
require 'includes/header.php';

// Fetch artworks with artist info
try {
    $stmt = $pdo->query("SELECT a.*, r.name as artist_name, r.id as artist_id FROM artworks a JOIN artists r ON a.artist_id = r.id ORDER BY a.date_uploaded DESC");
    $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $artworks = [];
}

// Fetch artists for filters
$stmt = $pdo->query("SELECT id, name FROM artists ORDER BY name");
$artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <!-- Hero Section -->
    <header class="hero-header" data-aos="fade-in">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="page-title">Inkingi Arts Gallery</h1>
            <p class="page-subtitle">Discover captivating Rwandan artworks, each with a unique story</p>
        </div>
    </header>

    <div class="gallery-container">
        <!-- Artist Filters -->
        <div class="filters" data-aos="fade-down">
            <button class="filter-btn active" data-filter="all">All Artworks</button>
            <?php foreach ($artists as $artist): ?>
                <button class="filter-btn" data-filter="<?= $artist['id'] ?>"><?= htmlspecialchars($artist['name']) ?></button>
            <?php endforeach; ?>
        </div>

        <!-- Gallery Grid -->
        <div class="gallery-grid" id="galleryGrid">
            <?php if (empty($artworks)): ?>
                <p class="no-art">No artworks available yet. Check back soon!</p>
            <?php else: ?>
                <?php foreach ($artworks as $art): ?>
                    <div class="art-card" data-artist="<?= $art['artist_id'] ?>" data-aos="zoom-in">
                        <div class="art-front">
                            <img src="<?= htmlspecialchars($art['image_path']) ?>" alt="<?= htmlspecialchars($art['title']) ?>">
                            <div class="art-info">
                                <h3><?= htmlspecialchars($art['title']) ?></h3>
                                <p>By <?= htmlspecialchars($art['artist_name']) ?></p>
                            </div>
                        </div>
                        <div class="art-back">
                            <h3><?= htmlspecialchars($art['title']) ?></h3>
                            <p class="art-story"><?= nl2br(htmlspecialchars($art['description'])) ?></p>
                            <p><strong>Category:</strong> <?= htmlspecialchars($art['category'] ?? 'N/A') ?></p>
                            <p><strong>Price:</strong> <?= htmlspecialchars($art['price'] ?? 'Inquire') ?></p>
                            <a href="artists.php?id=<?= $art['artist_id'] ?>" class="artist-link">View Artist Profile</a>
                            <button class="lightbox-btn" onclick="openLightbox('<?= htmlspecialchars($art['image_path']) ?>', '<?= htmlspecialchars(addslashes($art['title'])) ?>', '<?= htmlspecialchars(addslashes($art['description'])) ?>')">Full View</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div class="lightbox" id="lightbox">
        <span class="close-lightbox" onclick="closeLightbox()">&times;</span>
        <div class="lightbox-content">
            <img id="lightbox-img" src="" alt="">
            <div class="lightbox-caption">
                <h3 id="lightbox-title"></h3>
                <p id="lightbox-story"></p>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<style>
    /* High-Class CSS - Unique 3D Flip Gallery with Rwandan Motifs */
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
        --shadow-md: 0 12px 30px rgba(0,0,0,0.12);
        --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--light);
        color: var(--dark);
        overflow-x: hidden;
    }

    main {
        padding-bottom: 120px;
    }

    .hero-header {
        height: 80vh;
        min-height: 600px;
        background: url('https://images.unsplash.com/photo-1569783721854-33a99b4c0bae?q=80&w=1128&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center/cover;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(44,62,80,0.7), rgba(44,62,80,0.9));
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        padding: 0 20px;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(3rem, 6vw, 4.5rem);
        color: white;
        margin-bottom: 20px;
        line-height: 1.1;
    }

    .page-subtitle {
        font-size: 1.3rem;
        color: rgba(255,255,255,0.9);
        margin-bottom: 40px;
    }

    .hero-cta {
        background: var(--accent);
        color: var(--primary);
        padding: 14px 40px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
    }

    .hero-cta:hover {
        background: #e6a50a;
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(253,185,19,0.4);
    }

    .gallery-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 60px 20px;
    }

    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 50px;
        justify-content: center;
    }

    .filter-btn {
        background: white;
        border: 1px solid #e0e0e0;
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
    }

    .filter-btn:hover {
        border-color: var(--accent);
        color: var(--accent);
    }

    .filter-btn.active {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
    }

    .art-card {
        perspective: 1500px;
        height: 420px;
        cursor: pointer;
        transition: var(--transition);
    }

    .art-card:hover {
        transform: translateY(-8px);
    }

    .art-front, .art-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: transform 0.6s ease;
        transform-style: preserve-3d;
    }

    .art-front {
        transform: rotateY(0deg);
    }

    .art-back {
        transform: rotateY(180deg);
        background: white;
        padding: 30px;
        display: flex;
        flex-direction: column;
    }

    .art-card.flipped .art-front {
        transform: rotateY(-180deg);
    }

    .art-card.flipped .art-back {
        transform: rotateY(0deg);
    }

    .art-front img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .art-card:hover .art-front img {
        transform: scale(1.08);
    }

    .art-info {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 20px;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        color: white;
    }

    .art-info h3 {
        margin-bottom: 8px;
        font-size: 1.4rem;
    }

    .art-info p {
        font-size: 0.95rem;
        opacity: 0.9;
    }

    .art-back h3 {
        font-size: 1.5rem;
        margin-bottom: 15px;
        color: var(--primary);
    }

    .art-story {
        flex: 1;
        font-size: 1rem;
        color: #555;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .artist-link {
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
    }

    .artist-link:hover {
        color: #e6a50a;
    }

    .lightbox-btn {
        background: var(--primary);
        color: white;
        padding: 10px 20px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
        margin-top: auto;
    }

    .lightbox-btn:hover {
        background: #1e2a38;
        transform: translateY(-2px);
    }

    /* Lightbox */
    .lightbox {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.95);
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .lightbox.active {
        display: flex;
    }

    .lightbox-content {
        max-width: 90%;
        max-height: 90%;
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    #lightbox-img {
        width: 100%;
        max-height: 60vh;
        object-fit: contain;
    }

    .lightbox-caption {
        padding: 25px;
    }

    #lightbox-title {
        font-size: 1.8rem;
        margin-bottom: 15px;
        color: var(--primary);
    }

    #lightbox-story {
        font-size: 1.1rem;
        color: #555;
        line-height: 1.7;
    }

    .close-lightbox {
        position: absolute;
        top: 20px;
        right: 20px;
        color: white;
        font-size: 2.5rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .close-lightbox:hover {
        color: var(--accent);
        transform: rotate(90deg);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
        .art-card {
            height: 380px;
        }
    }

    @media (max-width: 768px) {
        .filters {
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 10px;
        }
        .filter-btn {
            white-space: nowrap;
        }
    }
</style>

<script>
    // Filter Artworks
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelector('.filter-btn.active').classList.remove('active');
            btn.classList.add('active');
            const filter = btn.dataset.filter;

            document.querySelectorAll('.art-card').forEach(card => {
                if (filter === 'all' || card.dataset.artist === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Flip Cards on Hover
    document.querySelectorAll('.art-card').forEach(card => {
        card.addEventListener('mouseenter', () => card.classList.add('flipped'));
        card.addEventListener('mouseleave', () => card.classList.remove('flipped'));
    });

    // Lightbox
    function openLightbox(imgSrc, title, story) {
        document.getElementById('lightbox-img').src = imgSrc;
        document.getElementById('lightbox-title').textContent = title;
        document.getElementById('lightbox-story').textContent = story;
        document.getElementById('lightbox').classList.add('active');
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
    }

    document.querySelector('.close-lightbox').addEventListener('click', closeLightbox);
    document.getElementById('lightbox').addEventListener('click', (e) => {
        if (e.target.id === 'lightbox') closeLightbox();
    });

    AOS.init({ duration: 1000, once: true });
</script>
