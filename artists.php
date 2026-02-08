<?php
require 'includes/db_connect.php';
require 'includes/header.php';

// Fetch artists with error handling & Fallback for design preview
try {
    $stmt = $pdo->query("
        SELECT 
            a.id, a.name, a.bio, a.profile_pic, a.created_at,
            COUNT(w.id) AS artwork_count
        FROM artists a
        LEFT JOIN artworks w ON a.id = w.artist_id
        GROUP BY a.id
        ORDER BY artwork_count DESC, a.name ASC
    ");
    $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Fallback data for visualization if DB fails
    $artists = [
        [
            'id' => 1, 'name' => 'Olivier Kwitonda', 'bio' => 'Visual artist specializing in abstract expressionism and cultural narratives.', 
            'profile_pic' => 'http://niyoartscenter.com/wp-content/uploads/2021/06/Olivier.jpeg', 
            'artwork_count' => 12, 'created_at' => '2023-01-15'
        ],
        [
            'id' => 2, 'name' => 'Keza Art', 'bio' => 'Mixed media artist focusing on traditional weaving combined with modern sculpture.', 
            'profile_pic' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80', 
            'artwork_count' => 8, 'created_at' => '2023-03-22'
        ],
        [
            'id' => 3, 'name' => 'Manzi Leon', 'bio' => 'Contemporary painter exploring themes of urban life in Kigali.', 
            'profile_pic' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80', 
            'artwork_count' => 5, 'created_at' => '2023-06-10'
        ]
    ];
}
?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<main>
    <section class="page-hero">
        <div class="hero-bg"></div>
        <div class="hero-content" data-aos="fade-up">
            <span class="hero-label">The Visionaries</span>
            <h1 class="hero-title">Our Artists</h1>
            <p class="hero-subtitle">
                Meet the creative minds shaping Rwanda’s contemporary art scene.
            </p>
        </div>
    </section>

    <section class="section-content">
        <div class="container">
            
            <div class="artists-grid">
                <?php if (empty($artists)): ?>
                    <div class="empty-state">
                        <i class="fas fa-palette"></i>
                        <h3>No Artists Found</h3>
                        <p>We are currently curating our list. Check back soon!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($artists as $index => $artist): ?>
                        <a href="artist_profile.php?id=<?= $artist['id'] ?>" class="artist-card" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                            
                            <div class="card-header-visual"></div>
                            
                            <div class="avatar-container">
                                <?php if (!empty($artist['profile_pic'])): ?>
                                    <img src="<?= htmlspecialchars($artist['profile_pic']) ?>" alt="<?= htmlspecialchars($artist['name']) ?>" class="avatar-img">
                                <?php else: ?>
                                    <div class="avatar-placeholder">
                                        <?= strtoupper(substr($artist['name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="card-body">
                                <h3 class="artist-name"><?= htmlspecialchars($artist['name']) ?></h3>
                                <p class="artist-bio">
                                    <?= htmlspecialchars(substr($artist['bio'] ?? 'No bio available yet.', 0, 80)) ?>...
                                </p>

                                <div class="divider"></div>

                                <div class="artist-stats">
                                    <div class="stat">
                                        <span class="count"><?= $artist['artwork_count'] ?></span>
                                        <span class="label">Artworks</span>
                                    </div>
                                    <div class="stat">
                                        <span class="count"><?= date('Y', strtotime($artist['created_at'])) ?></span>
                                        <span class="label">Member Since</span>
                                    </div>
                                </div>
                                
                                <span class="view-profile">View Profile <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="join-cta" data-aos="zoom-in">
                <div class="cta-content">
                    <h2>Are you an Artist?</h2>
                    <p>Join the Inkingi community to showcase your work, connect with buyers, and participate in exclusive workshops.</p>
                    <a href="artist/artist_login.php" class="btn-gold">Join the Collective</a>
                </div>
            </div>

        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<style>
    /* --- CSS Variables --- */
    :root {
        --primary-dark: #1A2530;
        --accent-gold: #D4AF37;
        --accent-hover: #B5952F;
        --bg-light: #F9FAFB;
        --white: #ffffff;
        --text-gray: #5a6a7e;
        --font-heading: 'Playfair Display', serif;
        --font-body: 'Poppins', sans-serif;
        --radius: 16px;
        --shadow-soft: 0 10px 30px rgba(0,0,0,0.05);
        --shadow-hover: 0 20px 40px rgba(0,0,0,0.12);
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    body {
        font-family: var(--font-body);
        background: var(--bg-light);
        color: var(--primary-dark);
        margin: 0;
    }

    /* --- Hero Section --- */
    .page-hero {
        position: relative;
        height: 50vh;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--white);
        overflow: hidden;
    }

    .hero-bg {
        position: absolute; inset: 0;
        background: url('https://images.unsplash.com/photo-1569783721854-33a99b4c0bae?auto=format&fit=crop&w=1600&q=80') center/cover;
        background-attachment: fixed; /* Parallax feel */
    }
    
    .hero-bg::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(to bottom, rgba(26,37,48,0.7), rgba(26,37,48,0.9));
    }

    .hero-content { position: relative; z-index: 2; max-width: 800px; padding: 20px; }
    
    .hero-label {
        display: block; font-size: 0.9rem; letter-spacing: 3px;
        text-transform: uppercase; color: var(--accent-gold); margin-bottom: 15px; font-weight: 600;
    }

    .hero-title {
        font-family: var(--font-heading);
        font-size: clamp(3rem, 6vw, 5rem);
        margin: 0 0 20px 0;
        line-height: 1.1;
    }

    .hero-subtitle { font-size: 1.2rem; opacity: 0.9; font-weight: 300; }

    /* --- Content Layout --- */
    .section-content { padding: 80px 0; }
    .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

    .artists-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 40px;
        margin-bottom: 100px;
    }

    /* --- Artist Card --- */
    .artist-card {
        background: var(--white);
        border-radius: var(--radius);
        overflow: hidden;
        text-decoration: none;
        box-shadow: var(--shadow-soft);
        transition: var(--transition);
        position: relative;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0,0,0,0.02);
    }

    .artist-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-hover);
        border-color: rgba(212, 175, 55, 0.3);
    }

    .card-header-visual {
        height: 120px;
        background: linear-gradient(135deg, var(--primary-dark), #2c3e50);
        position: relative;
    }
    
    /* Decorative pattern overlay */
    .card-header-visual::after {
        content: ''; position: absolute; inset: 0; opacity: 0.1;
        background-image: radial-gradient(var(--accent-gold) 1px, transparent 1px);
        background-size: 10px 10px;
    }

    .avatar-container {
        width: 140px; height: 140px;
        margin: -70px auto 0; /* Pull up to overlap header */
        position: relative;
        z-index: 2;
        border-radius: 50%;
        padding: 5px;
        background: var(--white);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .avatar-img {
        width: 100%; height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #eee;
        transition: var(--transition);
    }
    
    .artist-card:hover .avatar-img { transform: scale(1.05); }

    .avatar-placeholder {
        width: 100%; height: 100%;
        border-radius: 50%;
        background: #eee;
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem; font-family: var(--font-heading);
        color: var(--text-gray);
    }

    .card-body {
        padding: 20px 30px 35px;
        text-align: center;
        display: flex; flex-direction: column; flex: 1;
    }

    .artist-name {
        font-family: var(--font-heading);
        font-size: 1.8rem;
        color: var(--primary-dark);
        margin: 15px 0 10px;
    }

    .artist-bio {
        color: var(--text-gray);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
        flex-grow: 1; /* Pushes footer down */
    }

    .divider { height: 1px; background: #f0f0f0; margin: 0 20px 20px; }

    .artist-stats {
        display: flex; justify-content: center; gap: 40px; margin-bottom: 25px;
    }

    .stat { display: flex; flex-direction: column; }
    .stat .count { font-size: 1.4rem; font-weight: 700; color: var(--primary-dark); font-family: var(--font-heading); }
    .stat .label { font-size: 0.75rem; text-transform: uppercase; color: var(--text-gray); letter-spacing: 1px; margin-top: 4px; }

    .view-profile {
        font-size: 0.9rem; font-weight: 600; color: var(--accent-gold);
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        transition: var(--transition);
    }
    .artist-card:hover .view-profile { color: var(--accent-hover); gap: 12px; }

    /* --- Join CTA --- */
    .join-cta {
        background: var(--primary-dark);
        color: var(--white);
        border-radius: var(--radius);
        padding: 60px 40px;
        text-align: center;
        background-image: url('assets/images/pattern.png'); /* Optional pattern */
        box-shadow: var(--shadow-hover);
    }

    .cta-content h2 { font-family: var(--font-heading); font-size: 2.5rem; margin-bottom: 15px; }
    .cta-content p { max-width: 600px; margin: 0 auto 30px; opacity: 0.9; font-size: 1.1rem; }

    .btn-gold {
        display: inline-block;
        background: var(--accent-gold);
        color: var(--primary-dark);
        padding: 14px 40px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
    }
    .btn-gold:hover {
        background: var(--accent-hover);
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
    }

    .empty-state { text-align: center; padding: 60px; color: var(--text-gray); width: 100%; grid-column: 1/-1; }
    .empty-state i { font-size: 3rem; margin-bottom: 20px; opacity: 0.3; }

    /* --- Responsive --- */
    @media (max-width: 768px) {
        .hero-title { font-size: 3rem; }
        .artists-grid { grid-template-columns: 1fr; }
        .join-cta { padding: 40px 20px; }
    }
</style>

<script>
    AOS.init({
        duration: 800,
        once: true,
        easing: 'ease-out-cubic'
    });
</script>