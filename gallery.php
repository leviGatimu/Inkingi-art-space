<?php
require 'includes/db_connect.php';
require 'includes/header.php';

// Fetch artworks with artist info
try {
    $stmt = $pdo->query("SELECT a.*, r.name as artist_name, r.id as artist_id FROM artworks a JOIN artists r ON a.artist_id = r.id ORDER BY a.date_uploaded DESC");
    $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Fallback data for visualization
    $artworks = [
        [
            'id' => 1, 'artist_id' => 101, 'artist_name' => 'Olivier Kwitonda',
            'title' => 'Rwandan Hills', 'category' => 'Painting', 'price' => '150,000 Rwf',
            'image_path' => 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?auto=format&fit=crop&w=800&q=80',
            'description' => 'A vibrant abstract representation of the thousand hills, using earthy tones to signify fertility and growth.'
        ],
        [
            'id' => 2, 'artist_id' => 102, 'artist_name' => 'Keza Art',
            'title' => 'Unity Basket', 'category' => 'Mixed Media', 'price' => '80,000 Rwf',
            'image_path' => 'https://images.unsplash.com/photo-1515405295579-ba7f9f92f4e3?auto=format&fit=crop&w=800&q=80',
            'description' => 'Traditional weaving techniques combined with modern acrylics to tell the story of community gathering.'
        ],
        [
            'id' => 3, 'artist_id' => 101, 'artist_name' => 'Olivier Kwitonda',
            'title' => 'Golden Horizon', 'category' => 'Painting', 'price' => '200,000 Rwf',
            'image_path' => 'https://images.unsplash.com/photo-1541963463532-d68292c34b19?auto=format&fit=crop&w=800&q=80',
            'description' => 'Capturing the golden hour over Kigali, focusing on the interplay of light and shadow.'
        ]
    ];
}

// Fetch artists for filters (extract unique from array if DB fails)
if (isset($pdo)) {
    $stmt = $pdo->query("SELECT id, name FROM artists ORDER BY name");
    $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $artists = [
        ['id' => 101, 'name' => 'Olivier Kwitonda'],
        ['id' => 102, 'name' => 'Keza Art']
    ];
}
?>

<main>
    <header class="hero-header">
        <div class="hero-bg"></div>
        <div class="hero-content" data-aos="fade-up">
            <span class="hero-label">The Collection</span>
            <h1 class="page-title">Inkingi Arts Gallery</h1>
            <p class="page-subtitle">Curated Rwandan masterpieces. Each piece tells a story of heritage, vision, and creativity.</p>
        </div>
        <div class="scroll-indicator">
            <div class="mouse"></div>
        </div>
    </header>

    <div class="gallery-wrapper">
        
        <div class="filter-bar" data-aos="fade-down" data-aos-delay="100">
            <div class="filter-container">
                <button class="filter-btn active" data-filter="all">All Collection</button>
                <?php foreach ($artists as $artist): ?>
                    <button class="filter-btn" data-filter="<?= $artist['id'] ?>"><?= htmlspecialchars($artist['name']) ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="container">
            <div class="gallery-grid" id="galleryGrid">
                <?php if (empty($artworks)): ?>
                    <div class="empty-state">
                        <i class="far fa-images"></i>
                        <p>No artworks found in the collection yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($artworks as $index => $art): ?>
                        <article class="art-card" 
                                 data-artist="<?= $art['artist_id'] ?>" 
                                 data-aos="fade-up" 
                                 data-aos-delay="<?= $index * 100 ?>">
                            
                            <div class="card-image-wrap" 
                                 onclick="openLightbox(<?= htmlspecialchars(json_encode($art)) ?>)">
                                <img src="<?= htmlspecialchars($art['image_path']) ?>" 
                                     alt="<?= htmlspecialchars($art['title']) ?>" 
                                     loading="lazy">
                                <div class="view-overlay">
                                    <span><i class="fas fa-expand-arrows-alt"></i> View Detail</span>
                                </div>
                            </div>

                            <div class="card-details">
                                <div class="card-meta">
                                    <span class="category"><?= htmlspecialchars($art['category'] ?? 'Art') ?></span>
                                    <span class="price"><?= htmlspecialchars($art['price'] ?? 'Inquire') ?></span>
                                </div>
                                <h3 class="card-title"><?= htmlspecialchars($art['title']) ?></h3>
                                <p class="card-artist">by <?= htmlspecialchars($art['artist_name']) ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="lightbox" id="lightbox">
        <div class="lightbox-backdrop" onclick="closeLightbox()"></div>
        
        <div class="lightbox-modal">
            <button class="close-btn" onclick="closeLightbox()">&times;</button>
            
            <div class="lightbox-grid">
                <div class="lightbox-media">
                    <img id="lb-image" src="" alt="Artwork">
                </div>

                <div class="lightbox-info">
                    <div class="info-header">
                        <span class="lb-category" id="lb-category">Painting</span>
                        <h2 id="lb-title">Artwork Title</h2>
                        <a href="#" id="lb-artist-link" class="lb-artist">Artist Name</a>
                    </div>

                    <div class="info-body">
                        <div class="story-block">
                            <h4>The Story</h4>
                            <p id="lb-desc">Description goes here...</p>
                        </div>

                        <div class="meta-block">
                            <div class="meta-item">
                                <span>Price</span>
                                <strong id="lb-price">100,000 Rwf</strong>
                            </div>
                            </div>
                    </div>

                    <div class="info-footer">
                        <a href="contact.php" class="btn-inquire">Inquire to Purchase</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<style>
    /* --- Variables --- */
    :root {
        --primary-dark: #1A2530;
        --primary-text: #2c3e50;
        --accent-gold: #D4AF37;
        --accent-hover: #B5952F;
        --bg-light: #F9FAFB;
        --white: #ffffff;
        --text-gray: #6c757d;
        --font-heading: 'Playfair Display', serif;
        --font-body: 'Poppins', sans-serif;
        --radius: 12px;
        --shadow-card: 0 10px 30px rgba(0,0,0,0.05);
        --transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    /* --- Base --- */
    body { font-family: var(--font-body); background: var(--bg-light); color: var(--primary-text); margin: 0; }
    h1, h2, h3, h4 { font-family: var(--font-heading); margin: 0; font-weight: 700; }
    
    /* --- Hero --- */
    .hero-header {
        position: relative;
        height: 60vh;
        min-height: 500px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: white;
        overflow: hidden;
    }

    .hero-bg {
        position: absolute;
        inset: 0;
        background: url('https://images.unsplash.com/photo-1569783721854-33a99b4c0bae?q=80&w=1600') center/cover;
        filter: brightness(0.4);
        transform: scale(1.05); /* Slight zoom for parallax feel */
    }

    .hero-content { position: relative; z-index: 2; max-width: 800px; padding: 0 20px; }
    
    .hero-label {
        display: inline-block;
        font-size: 0.9rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--accent-gold);
        margin-bottom: 15px;
        font-weight: 600;
    }

    .page-title { font-size: clamp(3.5rem, 6vw, 5rem); margin-bottom: 20px; line-height: 1.1; }
    .page-subtitle { font-size: 1.25rem; font-weight: 300; opacity: 0.9; max-width: 600px; margin: 0 auto; }

    /* Scroll Indicator */
    .scroll-indicator {
        position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%);
        z-index: 2; opacity: 0.7;
    }
    .mouse {
        width: 26px; height: 40px; border: 2px solid white; border-radius: 20px;
        position: relative;
    }
    .mouse::after {
        content:''; position: absolute; top: 6px; left: 50%; transform: translateX(-50%);
        width: 4px; height: 4px; background: var(--accent-gold); border-radius: 50%;
        animation: scroll 2s infinite;
    }
    @keyframes scroll { 0% {top: 6px; opacity: 1;} 100% {top: 20px; opacity: 0;} }

    /* --- Filters --- */
    .filter-bar {
        position: sticky; top: 0; z-index: 10;
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #eee;
        padding: 20px 0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    
    .filter-container {
        display: flex; justify-content: center; flex-wrap: wrap; gap: 10px;
        max-width: 1200px; margin: 0 auto; padding: 0 20px;
    }

    .filter-btn {
        border: none; background: transparent;
        padding: 8px 24px; border-radius: 50px;
        font-family: var(--font-body); font-weight: 500; font-size: 0.95rem;
        color: var(--text-gray); cursor: pointer;
        transition: var(--transition);
        border: 1px solid transparent;
    }

    .filter-btn:hover { color: var(--accent-gold); background: rgba(212, 175, 55, 0.05); }
    .filter-btn.active {
        background: var(--accent-gold); color: white;
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    /* --- Gallery Grid --- */
    .container { max-width: 1300px; margin: 0 auto; padding: 60px 24px; }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 40px;
    }

    .art-card {
        background: white;
        border-radius: 0 0 var(--radius) var(--radius); /* Top is handled by image */
        overflow: hidden;
        transition: var(--transition);
        /* No shadow by default for cleaner look, shadow on hover */
    }

    .art-card:hover { transform: translateY(-8px); }

    .card-image-wrap {
        position: relative;
        padding-top: 120%; /* Portrait Aspect Ratio */
        overflow: hidden;
        cursor: pointer;
        background: #f0f0f0;
        border-radius: var(--radius);
    }

    .card-image-wrap img {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover; transition: transform 0.6s ease;
    }

    .art-card:hover .card-image-wrap img { transform: scale(1.05); }

    .view-overlay {
        position: absolute; inset: 0;
        background: rgba(26,37,48,0.4);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: var(--transition);
    }
    
    .art-card:hover .view-overlay { opacity: 1; }

    .view-overlay span {
        background: white; color: var(--primary-dark);
        padding: 10px 24px; border-radius: 50px;
        font-weight: 600; font-size: 0.9rem;
        transform: translateY(10px); transition: var(--transition);
    }
    
    .art-card:hover .view-overlay span { transform: translateY(0); }

    .card-details { padding: 20px 5px; }

    .card-meta {
        display: flex; justify-content: space-between;
        font-size: 0.85rem; color: var(--text-gray); margin-bottom: 8px;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    
    .card-meta .price { color: var(--accent-gold); font-weight: 700; }

    .card-title { font-size: 1.3rem; margin-bottom: 4px; color: var(--primary-dark); }
    .card-artist { color: var(--text-gray); font-size: 0.95rem; font-style: italic; }

    /* --- Lightbox Modal --- */
    .lightbox {
        position: fixed; inset: 0; z-index: 1000;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
        padding: 20px;
    }
    
    .lightbox.active { opacity: 1; pointer-events: all; }

    .lightbox-backdrop {
        position: absolute; inset: 0; background: rgba(10,10,10,0.9);
        backdrop-filter: blur(5px);
    }

    .lightbox-modal {
        position: relative;
        background: white;
        width: 100%; max-width: 1100px;
        height: 85vh;
        border-radius: var(--radius);
        overflow: hidden;
        display: flex; flex-direction: column;
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        transform: scale(0.95); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .lightbox.active .lightbox-modal { transform: scale(1); }

    .close-btn {
        position: absolute; top: 15px; right: 20px; z-index: 10;
        background: rgba(255,255,255,0.8); border: none;
        width: 40px; height: 40px; border-radius: 50%;
        font-size: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: var(--transition);
    }
    .close-btn:hover { background: var(--accent-gold); color: white; }

    .lightbox-grid {
        display: grid; grid-template-columns: 55% 45%; height: 100%;
    }

    .lightbox-media {
        background: #f4f4f4;
        display: flex; align-items: center; justify-content: center;
        padding: 40px;
    }
    
    .lightbox-media img {
        max-width: 100%; max-height: 100%;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .lightbox-info {
        padding: 50px; overflow-y: auto;
        display: flex; flex-direction: column;
    }

    .lb-category {
        color: var(--accent-gold); font-weight: 600; text-transform: uppercase;
        font-size: 0.85rem; letter-spacing: 1px; display: block; margin-bottom: 10px;
    }

    .info-header h2 { font-size: 2.5rem; line-height: 1.1; margin-bottom: 10px; color: var(--primary-dark); }
    .lb-artist { font-size: 1.2rem; color: var(--text-gray); text-decoration: none; border-bottom: 1px solid #ddd; padding-bottom: 2px; }

    .info-body { margin-top: 40px; flex-grow: 1; }
    
    .story-block h4 { font-size: 1.1rem; margin-bottom: 15px; color: var(--primary-dark); }
    .story-block p { color: #555; line-height: 1.8; margin-bottom: 40px; font-size: 1.05rem; }

    .meta-block {
        border-top: 1px solid #eee; padding-top: 25px;
        display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
    }
    
    .meta-item span { display: block; font-size: 0.85rem; color: #999; text-transform: uppercase; margin-bottom: 5px; }
    .meta-item strong { display: block; font-size: 1.2rem; color: var(--primary-dark); }

    .info-footer { margin-top: 30px; }
    .btn-inquire {
        display: block; width: 100%; text-align: center;
        background: var(--primary-dark); color: white;
        padding: 16px; border-radius: 50px; text-decoration: none;
        font-weight: 600; letter-spacing: 0.5px;
        transition: var(--transition);
    }
    .btn-inquire:hover { background: var(--accent-gold); }

    /* --- Responsive --- */
    @media (max-width: 992px) {
        .lightbox-grid { grid-template-columns: 1fr; overflow-y: auto; }
        .lightbox-media { height: 300px; padding: 20px; }
        .lightbox-info { padding: 30px; height: auto; }
        .lightbox-modal { height: 90vh; }
    }

    @media (max-width: 768px) {
        .page-title { font-size: 3rem; }
        .gallery-grid { grid-template-columns: 1fr; }
        .filter-container { flex-wrap: nowrap; overflow-x: auto; justify-content: flex-start; padding-bottom: 5px; }
        .filter-btn { white-space: nowrap; }
    }
</style>

<script>
    AOS.init({ duration: 800, once: true });

    // --- Filter Logic ---
    const filterBtns = document.querySelectorAll('.filter-btn');
    const artCards = document.querySelectorAll('.art-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class
            document.querySelector('.filter-btn.active').classList.remove('active');
            btn.classList.add('active');

            const filter = btn.dataset.filter;

            artCards.forEach(card => {
                const artistId = card.dataset.artist;

                if (filter === 'all' || artistId === filter) {
                    // Show animation
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    // Hide animation
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 400); // Wait for transition
                }
            });
            
            // Re-trigger AOS layout refresh if using it
            setTimeout(() => AOS.refresh(), 450);
        });
    });

    // --- Lightbox Logic ---
    const lightbox = document.getElementById('lightbox');
    
    // Elements to populate
    const elImg = document.getElementById('lb-image');
    const elTitle = document.getElementById('lb-title');
    const elArtist = document.getElementById('lb-artist-link');
    const elCategory = document.getElementById('lb-category');
    const elDesc = document.getElementById('lb-desc');
    const elPrice = document.getElementById('lb-price');

    function openLightbox(artData) {
        // Populate Data
        elImg.src = artData.image_path;
        elTitle.textContent = artData.title;
        elArtist.textContent = artData.artist_name;
        elArtist.href = 'artists.php?id=' + artData.artist_id; // Assuming artist page link
        elCategory.textContent = artData.category || 'Artwork';
        elDesc.innerHTML = artData.description || 'No description available.';
        elPrice.textContent = artData.price || 'Inquire';

        // Show Modal
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Keyboard Close (Esc)
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && lightbox.classList.contains('active')) {
            closeLightbox();
        }
    });
</script>