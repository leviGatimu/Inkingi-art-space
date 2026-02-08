<?php
require 'includes/db_connect.php';
require 'includes/header.php';

// 1. Get Artist ID
$artist_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // 2. Fetch Artist Details
    $stmt = $pdo->prepare("SELECT * FROM artists WHERE id = ?");
    $stmt->execute([$artist_id]);
    $artist = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$artist) {
        // Redirect if invalid ID
        echo "<script>window.location.href='artists.php';</script>";
        exit;
    }

    // 3. Fetch Artist's Artworks
    $stmt_art = $pdo->prepare("SELECT * FROM artworks WHERE artist_id = ? ORDER BY date_uploaded DESC");
    $stmt_art->execute([$artist_id]);
    $artworks = $stmt_art->fetchAll(PDO::FETCH_ASSOC);

    // 4. Fetch "Other Artists" for the bottom section
    $stmt_other = $pdo->prepare("
        SELECT a.id, a.name, a.profile_pic, COUNT(w.id) as artwork_count 
        FROM artists a 
        LEFT JOIN artworks w ON a.id = w.artist_id 
        WHERE a.id != ? 
        GROUP BY a.id 
        ORDER BY RAND() LIMIT 3
    ");
    $stmt_other->execute([$artist_id]);
    $other_artists = $stmt_other->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    // Fallback/Error handling
    $artist = []; 
    $artworks = [];
}
?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<main class="profile-page">
    
    <section class="profile-header">
        <div class="header-bg"></div>
        <div class="container">
            <div class="profile-card" data-aos="fade-up">
                <div class="profile-avatar-wrapper">
                    <?php if (!empty($artist['profile_pic'])): ?>
                        <img src="<?= htmlspecialchars($artist['profile_pic']) ?>" alt="<?= htmlspecialchars($artist['name']) ?>" class="profile-avatar">
                    <?php else: ?>
                        <div class="profile-avatar-placeholder">
                            <?= strtoupper(substr($artist['name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div class="verified-badge" title="Verified Artist"><i class="fas fa-check"></i></div>
                </div>

                <div class="profile-info">
                    <h1 class="artist-name"><?= htmlspecialchars($artist['name']) ?></h1>
                    <p class="artist-role">Visual Artist &bull; Joined <?= date('Y', strtotime($artist['created_at'])) ?></p>
                    
                    <div class="artist-bio">
                        <?= nl2br(htmlspecialchars($artist['bio'])) ?>
                    </div>

                    <div class="profile-actions">
                        <div class="social-row">
                            <?php if(!empty($artist['instagram'])): ?>
                                <a href="<?= htmlspecialchars($artist['instagram']) ?>" target="_blank" class="social-btn"><i class="fab fa-instagram"></i></a>
                            <?php endif; ?>
                            <?php if(!empty($artist['twitter'])): ?>
                                <a href="<?= htmlspecialchars($artist['twitter']) ?>" target="_blank" class="social-btn"><i class="fab fa-twitter"></i></a>
                            <?php endif; ?>
                            <?php if(!empty($artist['email'])): ?>
                                <a href="mailto:<?= htmlspecialchars($artist['email']) ?>" class="social-btn"><i class="far fa-envelope"></i></a>
                            <?php endif; ?>
                        </div>
                        
                        <a href="contact.php?subject=Inquiry about <?= urlencode($artist['name']) ?>" class="btn-contact">
                            <i class="far fa-comment-dots"></i> Contact Artist
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-portfolio">
        <div class="container">
            <div class="section-title-wrap">
                <h2 class="section-title">Portfolio</h2>
                <span class="artwork-counter"><?= count($artworks) ?> Works</span>
            </div>

            <?php if (empty($artworks)): ?>
                <div class="empty-portfolio">
                    <i class="fas fa-paint-brush"></i>
                    <p>This artist hasn't uploaded any artworks yet.</p>
                </div>
            <?php else: ?>
                <div class="artwork-masonry">
                    <?php foreach ($artworks as $art): ?>
                        <div class="art-item" data-aos="zoom-in">
                            <div class="art-image-box">
                                <img src="<?= htmlspecialchars($art['image_path']) ?>" alt="<?= htmlspecialchars($art['title']) ?>">
                                <div class="art-overlay">
                                    <button class="btn-zoom" onclick="openLightbox('<?= htmlspecialchars($art['image_path']) ?>', '<?= htmlspecialchars(addslashes($art['title'])) ?>')">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="art-details">
                                <h3><?= htmlspecialchars($art['title']) ?></h3>
                                <div class="art-meta">
                                    <span><?= htmlspecialchars($art['category']) ?></span>
                                    <span class="price"><?= htmlspecialchars($art['price']) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="section-discover">
        <div class="container">
            <h3 class="discover-title">Discover More Artists</h3>
            <div class="other-artists-grid">
                <?php foreach ($other_artists as $other): ?>
                    <a href="artist_profile.php?id=<?= $other['id'] ?>" class="mini-artist-card">
                        <img src="<?= !empty($other['profile_pic']) ? htmlspecialchars($other['profile_pic']) : 'assets/images/default_user.png' ?>" alt="Artist">
                        <div class="mini-info">
                            <h4><?= htmlspecialchars($other['name']) ?></h4>
                            <span><?= $other['artwork_count'] ?> Artworks</span>
                        </div>
                    </a>
                <?php endforeach; ?>
                <a href="artists.php" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <div id="artLightbox" class="lightbox">
        <span class="close-lb" onclick="closeLightbox()">&times;</span>
        <img class="lightbox-content" id="lbImg">
        <div id="lbCaption"></div>
    </div>

</main>

<?php include 'includes/footer.php'; ?>

<style>
    /* --- Variables (Matches your Inkingi Theme) --- */
    :root {
        --primary: #2C3E50;
        --accent: #FDB913;
        --accent-hover: #e6a50a;
        --light: #f8f9fa;
        --white: #ffffff;
        --dark: #212529;
        --gray: #6c757d;
        --radius: 16px;
        --shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--light);
        color: var(--dark);
        margin: 0;
    }

    /* --- Header Section --- */
    .profile-header {
        position: relative;
        padding-top: 140px; /* Space for navbar */
        padding-bottom: 60px;
        background: linear-gradient(135deg, var(--primary), #1a2530);
        color: white;
        overflow: hidden;
    }

    /* Abstract BG Pattern */
    .header-bg {
        position: absolute; inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.6;
    }

    .container { max-width: 1100px; margin: 0 auto; padding: 0 20px; position: relative; z-index: 2; }

    .profile-card {
        display: flex;
        align-items: center;
        gap: 50px;
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(10px);
        padding: 40px;
        border-radius: var(--radius);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .profile-avatar-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .profile-avatar {
        width: 180px; height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--accent);
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }

    .profile-avatar-placeholder {
        width: 180px; height: 180px;
        border-radius: 50%;
        background: var(--accent);
        color: var(--primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 4rem; font-family: 'Playfair Display', serif; font-weight: 700;
        border: 4px solid rgba(255,255,255,0.2);
    }

    .verified-badge {
        position: absolute; bottom: 10px; right: 10px;
        background: var(--accent); color: var(--primary);
        width: 35px; height: 35px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; border: 3px solid var(--primary);
    }

    .profile-info { flex: 1; }

    .artist-name {
        font-family: 'Playfair Display', serif;
        font-size: 3rem; margin: 0; line-height: 1.1;
    }

    .artist-role {
        font-size: 1rem; color: var(--accent); margin: 5px 0 20px; text-transform: uppercase; letter-spacing: 1px;
    }

    .artist-bio {
        font-size: 1.05rem; line-height: 1.7; color: rgba(255,255,255,0.85); margin-bottom: 30px;
        max-width: 600px;
    }

    .profile-actions { display: flex; align-items: center; gap: 20px; }

    .social-row { display: flex; gap: 10px; }
    .social-btn {
        width: 45px; height: 45px; border-radius: 50%;
        background: rgba(255,255,255,0.1); color: white;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; transition: 0.3s; border: 1px solid rgba(255,255,255,0.2);
    }
    .social-btn:hover { background: var(--accent); color: var(--primary); transform: translateY(-3px); }

    .btn-contact {
        display: inline-flex; align-items: center; gap: 10px;
        background: var(--accent); color: var(--primary);
        padding: 12px 30px; border-radius: 30px;
        text-decoration: none; font-weight: 600; transition: 0.3s;
    }
    .btn-contact:hover { background: var(--accent-hover); transform: translateY(-3px); }

    /* --- Portfolio Section --- */
    .section-portfolio { padding: 80px 0; background: var(--light); }

    .section-title-wrap {
        display: flex; justify-content: space-between; align-items: baseline;
        margin-bottom: 40px; border-bottom: 2px solid #e9ecef; padding-bottom: 15px;
    }

    .section-title {
        font-family: 'Playfair Display', serif; font-size: 2.5rem; color: var(--primary); margin: 0;
    }

    .artwork-counter { font-size: 1.1rem; color: var(--gray); font-weight: 500; }

    .artwork-masonry {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
    }

    .art-item {
        background: var(--white);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: 0.3s;
    }
    .art-item:hover { transform: translateY(-5px); }

    .art-image-box { position: relative; height: 280px; overflow: hidden; }
    .art-image-box img {
        width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;
    }
    .art-item:hover img { transform: scale(1.08); }

    .art-overlay {
        position: absolute; inset: 0;
        background: rgba(44, 62, 80, 0.4);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: 0.3s;
    }
    .art-item:hover .art-overlay { opacity: 1; }

    .btn-zoom {
        background: var(--white); color: var(--primary); border: none;
        width: 50px; height: 50px; border-radius: 50%; font-size: 1.2rem;
        cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center;
    }
    .btn-zoom:hover { background: var(--accent); }

    .art-details { padding: 20px; }
    .art-details h3 { font-size: 1.2rem; margin: 0 0 5px 0; color: var(--primary); }
    .art-meta {
        display: flex; justify-content: space-between; font-size: 0.9rem; color: var(--gray);
    }
    .art-meta .price { color: var(--accent-hover); font-weight: 700; }

    /* --- Discover More Section --- */
    .section-discover {
        padding: 60px 0 100px;
        background: #fff;
        border-top: 1px solid #eee;
    }

    .discover-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 30px; color: var(--primary); }

    .other-artists-grid {
        display: flex; gap: 20px; align-items: center; flex-wrap: wrap;
    }

    .mini-artist-card {
        display: flex; align-items: center; gap: 15px;
        background: var(--light); padding: 10px 20px; border-radius: 50px;
        text-decoration: none; transition: 0.3s; border: 1px solid transparent;
    }
    .mini-artist-card:hover { border-color: var(--accent); background: white; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }

    .mini-artist-card img {
        width: 50px; height: 50px; border-radius: 50%; object-fit: cover;
    }

    .mini-info h4 { margin: 0; font-size: 1rem; color: var(--primary); }
    .mini-info span { font-size: 0.8rem; color: var(--gray); }

    .view-all-link {
        margin-left: auto; color: var(--primary); font-weight: 600; text-decoration: none;
        display: flex; align-items: center; gap: 8px;
    }
    .view-all-link:hover { color: var(--accent-hover); gap: 12px; transition: 0.3s; }

    /* Lightbox */
    .lightbox {
        display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.9); justify-content: center; align-items: center; flex-direction: column;
    }
    .lightbox-content { max-width: 90%; max-height: 80vh; border-radius: 5px; }
    .close-lb {
        position: absolute; top: 20px; right: 35px; color: #fff; font-size: 40px; font-weight: bold; cursor: pointer;
    }
    #lbCaption { color: #ccc; margin-top: 15px; font-size: 1.2rem; font-family: 'Playfair Display', serif; }

    /* Responsive */
    @media (max-width: 992px) {
        .profile-card { flex-direction: column; text-align: center; gap: 30px; }
        .profile-actions { justify-content: center; }
        .artist-name { font-size: 2.5rem; }
    }
</style>

<script>
    AOS.init({ duration: 800, once: true });

    // Lightbox Logic
    function openLightbox(src, title) {
        const lb = document.getElementById('artLightbox');
        const img = document.getElementById('lbImg');
        const cap = document.getElementById('lbCaption');
        
        img.src = src;
        cap.innerText = title;
        lb.style.display = "flex";
    }

    function closeLightbox() {
        document.getElementById('artLightbox').style.display = "none";
    }

    // Close on click outside
    document.getElementById('artLightbox').addEventListener('click', function(e) {
        if (e.target !== document.getElementById('lbImg')) {
            closeLightbox();
        }
    });
</script>