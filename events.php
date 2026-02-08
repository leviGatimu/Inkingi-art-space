<?php
require 'includes/db_connect.php';
require 'includes/header.php';

// Fetch all events/news posts from DB
try {
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY date DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $posts = [];
}
?>

<main>
    <!-- Hero – Elegant & Immersive -->
    <header class="hero">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <h1 class="hero-title">Events & News</h1>
            <p class="hero-subtitle">Stay updated with Inkingi’s latest happenings, exhibitions, workshops, and cultural stories</p>
            <div class="hero-tags">
                <span>Exhibitions</span>
                <span>Workshops</span>
                <span>Cultural Nights</span>
            </div>
            <a href="#events" class="scroll-cue">
                <span>Explore Latest</span>
                <i class="fas fa-chevron-down"></i>
            </a>
        </div>
    </header>

    <div class="content">

        <!-- Events & News Grid -->
        <section class="section events" id="events">
            <div class="container">
                <div class="section-header">
                    <span class="label">Latest Updates</span>
                    <h2 class="title">Events, Exhibitions & Stories</h2>
                </div>

                <div class="filter-tabs">
                    <button class="tab-btn active" data-filter="all">All</button>
                    <button class="tab-btn" data-filter="events">Events</button>
                    <button class="tab-btn" data-filter="photos">Photos</button>
                </div>

                <div class="events-grid">
                    <?php if (empty($posts)): ?>
                        <p class="no-posts">No events or news yet. Check back soon!</p>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <div class="event-card" data-category="<?= strtolower($post['category']) ?>" data-aos="fade-up">
                                <div class="event-image">
                                    <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                                </div>
                                <div class="event-info">
                                    <span class="event-date"><?= date('F d, Y', strtotime($post['date'])) ?></span>
                                    <h3 class="event-title"><?= htmlspecialchars($post['title']) ?></h3>
                                    <p class="event-excerpt"><?= nl2br(htmlspecialchars(substr($post['content'], 0, 150))) ?>...</p>
                                    <div class="event-tags">
                                        <span class="tag"><?= htmlspecialchars($post['category']) ?></span>
                                    </div>
                                </div>
                                <a href="post.php?id=<?= $post['id'] ?>" class="event-link">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </div>
</main>

<?php include 'includes/footer.php'; ?>

<style>
    :root {
        --primary: #2C3E50;
        --accent: #FDB913;
        --green: #009E60;
        --red: #C8102E;
        --light: #f8f9fa;
        --gray: #6c757d;
        --dark: #212529;
        --radius: 24px;
        --shadow-sm: 0 8px 24px rgba(0,0,0,0.08);
        --shadow-md: 0 20px 60px rgba(0,0,0,0.15);
        --transition: all 0.45s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--light);
        color: var(--dark);
        overflow-x: hidden;
    }

    main {
        padding-bottom: 140px;
    }

    /* Hero */
    .hero {
        height: 100vh;
        min-height: 720px;
        background: url('https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/c3/32/f3/ivuka-arts-centre.jpg?w=1600&q=80') center/cover no-repeat;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .hero-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(44,62,80,0.78), rgba(44,62,80,0.96));
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 960px;
        padding: 0 30px;
    }

    .hero-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(4.5rem, 10vw, 8rem);
        color: white;
        line-height: 1;
        margin-bottom: 20px;
        letter-spacing: -3px;
    }

    .hero-subtitle {
        font-size: clamp(1.6rem, 4vw, 2.4rem);
        color: rgba(255,255,255,0.92);
        margin-bottom: 50px;
    }

    .hero-tags {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 16px 24px;
        margin-bottom: 60px;
    }

    .hero-tags span {
        background: rgba(253,185,19,0.18);
        color: #FDB913;
        padding: 10px 22px;
        border-radius: 50px;
        font-size: 1rem;
        backdrop-filter: blur(6px);
    }

    .scroll-cue {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        color: white;
        font-size: 1.2rem;
        font-weight: 500;
        text-decoration: none;
        transition: var(--transition);
    }

    .scroll-cue:hover {
        color: var(--accent);
        transform: translateY(8px);
    }

    /* Container */
    .container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 30px;
    }

    .section {
        padding: 140px 0;
    }

    .section-label {
        font-family: 'Poppins', sans-serif;
        color: var(--accent);
        font-size: 1.35rem;
        font-weight: 500;
        letter-spacing: 2.5px;
        display: block;
        margin-bottom: 16px;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 4rem;
        color: var(--primary);
        margin-bottom: 48px;
        line-height: 1.1;
    }

    .intro {
        font-size: 1.4rem;
        line-height: 1.75;
        color: #444;
        margin-bottom: 40px;
        max-width: 780px;
    }

    /* Story */
    .story-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
    }

    .story-image .frame {
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        border: 14px solid white;
        outline: 1px solid #eee;
        transition: var(--transition);
    }

    .story-image .frame:hover {
        transform: scale(1.02);
        box-shadow: var(--shadow-lg);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 30px;
        margin-top: 50px;
    }

    .info-item {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .info-item i {
        font-size: 2.2rem;
        color: var(--accent);
        margin-top: 4px;
    }

    .info-item strong {
        display: block;
        font-size: 1.15rem;
        margin-bottom: 8px;
    }

    /* Founder */
    .founder-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        display: grid;
        grid-template-columns: 400px 1fr;
        overflow: hidden;
        transition: var(--transition);
    }

    .founder-card:hover {
        transform: translateY(-12px);
        box-shadow: var(--shadow-lg);
    }

    .founder-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .founder-content {
        padding: 80px 70px;
    }

    .founder-label {
        color: var(--accent);
        font-size: 1.35rem;
        font-weight: 500;
        margin-bottom: 16px;
        display: block;
    }

    .founder-name {
        font-size: 3.2rem;
        margin-bottom: 12px;
    }

    .founder-role {
        font-size: 1.45rem;
        color: var(--gray);
        margin-bottom: 40px;
    }

    .founder-quote {
        font-style: italic;
        font-size: 1.45rem;
        line-height: 1.75;
        margin-bottom: 35px;
        padding-left: 28px;
        border-left: 6px solid var(--accent);
    }

    .founder-bio {
        font-size: 1.15rem;
        line-height: 1.8;
        color: #444;
    }

    .social-link {
        background: var(--accent);
        color: var(--primary);
        padding: 16px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 14px;
        transition: var(--transition);
        margin-top: 40px;
    }

    .social-link:hover {
        background: #e6a50a;
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(253,185,19,0.35);
    }

    /* Mission */
    .mission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 40px;
    }

    .mission-card {
        background: white;
        border-radius: var(--radius);
        padding: 50px 40px;
        text-align: center;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .mission-card:hover {
        transform: translateY(-12px);
        box-shadow: var(--shadow-md);
    }

    .mission-icon {
        font-size: 4rem;
        color: var(--accent);
        margin-bottom: 30px;
    }

    .mission-card h3 {
        font-size: 1.9rem;
        margin-bottom: 20px;
        color: var(--primary);
    }

    .mission-card p {
        color: var(--gray);
        font-size: 1.1rem;
        line-height: 1.8;
    }

    /* Visit */
    .visit-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 100px;
        align-items: center;
    }

    .visit-info {
        display: grid;
        gap: 40px;
        margin-top: 40px;
    }

    .detail-item {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .detail-item i {
        font-size: 2.4rem;
        color: var(--accent);
        margin-top: 6px;
    }

    .detail-item strong {
        display: block;
        font-size: 1.2rem;
        margin-bottom: 8px;
    }

    .btn-main {
        background: var(--accent);
        color: var(--primary);
        padding: 18px 52px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.25rem;
        display: inline-block;
        transition: var(--transition);
        margin-top: 40px;
    }

    .btn-main:hover {
        background: #e6a50a;
        transform: translateY(-6px);
        box-shadow: 0 18px 50px rgba(253,185,19,0.35);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .story-grid, .visit-layout {
            grid-template-columns: 1fr;
            gap: 70px;
        }
        .founder-card {
            grid-template-columns: 1fr;
        }
        .section { padding: 120px 0; }
    }

    @media (max-width: 768px) {
        .hero-title { font-size: 4.5rem; }
        .section-title { font-size: 3rem; }
    }
</style>

<script>
    AOS.init({
        duration: 1200,
        once: true,
        offset: 120,
        easing: 'ease-out-cubic'
    });
</script>