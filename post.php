<?php
require 'includes/db_connect.php';
require 'includes/header.php';

// 1. Get the ID from the URL (e.g., post.php?id=5)
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 2. Fetch the specific post from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    // If post doesn't exist, redirect to events page
    if (!$post) {
        header("Location: events.php");
        exit;
    }
} catch (Exception $e) {
    die("Error fetching post.");
}

// 3. Sidebar Logic: Fetch recent posts for the sidebar widget
$latest_widget_posts = $pdo->query("SELECT * FROM posts ORDER BY date DESC LIMIT 4")->fetchAll(PDO::FETCH_ASSOC);
?>

<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<header class="single-hero-section">
    <div class="hero-overlay"></div>
    <div class="container hero-container">
        <div class="breadcrumbs">
            <a href="index.php">Home</a> <i class="fas fa-chevron-right"></i> 
            <a href="events.php">Events</a> <i class="fas fa-chevron-right"></i> 
            <span class="current">Reading</span>
        </div>
        <h1 class="hero-heading"><?= htmlspecialchars($post['title']) ?></h1>
        <div class="hero-meta">
            <span class="meta-tag"><i class="fas fa-folder"></i> <?= htmlspecialchars($post['category']) ?></span>
            <span class="meta-date"><i class="far fa-calendar"></i> <?= date('F j, Y', strtotime($post['date'])) ?></span>
        </div>
    </div>
</header>

<main class="single-page-container">
    <div class="container">
        <div class="page-layout">
            
            <article class="main-content">
                
                <?php if (!empty($post['image'])): ?>
                <div class="featured-image-wrapper">
                    <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                </div>
                <?php endif; ?>

                <div class="post-body-content">
                    <?= nl2br(htmlspecialchars_decode($post['content'])) ?>
                </div>

                <div class="post-navigation">
                    <a href="events.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to All Events</a>
                </div>

            </article>

            <aside class="sidebar">
                
                <div class="widget latest-widget">
                    <h3 class="widget-header">LATEST UPDATES</h3>
                    <div class="widget-list">
                        <?php foreach ($latest_widget_posts as $widget_post): ?>
                            <?php if($widget_post['id'] == $post['id']) continue; ?>
                            
                            <div class="widget-item">
                                <div class="w-thumb">
                                    <a href="post.php?id=<?= $widget_post['id'] ?>">
                                        <img src="<?= htmlspecialchars($widget_post['image']) ?>" alt="Thumb">
                                    </a>
                                </div>
                                <div class="w-content">
                                    <span class="w-date"><?= date('M d, Y', strtotime($widget_post['date'])) ?></span>
                                    <h4 class="w-title">
                                        <a href="post.php?id=<?= $widget_post['id'] ?>"><?= htmlspecialchars($widget_post['title']) ?></a>
                                    </h4>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="widget contact-widget">
                    <h3 class="widget-header">HAVE QUESTIONS?</h3>
                    <p>Interested in this event or artwork? Contact us directly.</p>
                    <a href="contact.php" class="btn-contact">Get in Touch</a>
                </div>

            </aside>

        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<style>
    /* --- CSS Variables --- */
    :root {
        --primary: #3b4d61; /* Slate Blue */
        --accent: #FDB913; /* Gold */
        --text-dark: #222222;
        --text-gray: #555555;
        --bg-light: #f9f9f9;
        --white: #ffffff;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--white);
        color: var(--text-dark);
        margin: 0;
    }

    a { text-decoration: none; color: inherit; transition: 0.3s; }
    .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

    /* --- HERO SECTION --- */
    .single-hero-section {
        position: relative;
        background-color: var(--primary);
        padding: 160px 0 80px; /* High padding to clear header */
        color: white;
        margin-bottom: 60px;
    }

    .hero-overlay {
        position: absolute; inset: 0; background: rgba(59, 77, 97, 0.9); /* Solid color overlay */
    }

    .hero-container { position: relative; z-index: 2; }

    .breadcrumbs {
        font-size: 0.85rem; color: rgba(255,255,255,0.7);
        margin-bottom: 15px; text-transform: uppercase; font-weight: 500;
    }
    .breadcrumbs i { font-size: 0.7rem; margin: 0 10px; opacity: 0.5; }
    .breadcrumbs a:hover { color: var(--accent); }
    .breadcrumbs .current { color: var(--accent); }

    .hero-heading {
        font-family: 'Oswald', sans-serif;
        font-size: 3.5rem;
        margin: 0 0 15px 0;
        line-height: 1.1;
    }

    .hero-meta {
        font-family: 'Oswald', sans-serif;
        font-size: 1rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.9);
    }
    .hero-meta span { margin-right: 20px; }
    .hero-meta i { color: var(--accent); margin-right: 8px; }

    /* --- LAYOUT --- */
    .page-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 60px;
        padding-bottom: 100px;
    }

    /* --- MAIN CONTENT (The Post) --- */
    .featured-image-wrapper {
        margin-bottom: 40px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .featured-image-wrapper img {
        width: 100%;
        height: auto;
        display: block;
    }

    .post-body-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--text-gray);
        margin-bottom: 50px;
    }
    
    /* Formatting admin content */
    .post-body-content p { margin-bottom: 20px; }
    .post-body-content h2, .post-body-content h3 {
        font-family: 'Oswald', sans-serif;
        color: var(--primary);
        margin-top: 30px;
        margin-bottom: 15px;
    }

    .post-navigation {
        border-top: 1px solid #eee;
        padding-top: 30px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: var(--primary);
        font-size: 0.95rem;
    }
    .btn-back:hover { color: var(--accent); gap: 15px; } /* Slide effect */

    /* --- SIDEBAR --- */
    .sidebar { display: flex; flex-direction: column; gap: 50px; }

    .widget-header {
        background-color: var(--primary);
        color: white;
        font-family: 'Oswald', sans-serif;
        font-size: 1.1rem;
        text-transform: uppercase;
        padding: 15px 20px;
        margin-bottom: 25px;
        font-weight: 500;
        letter-spacing: 1px;
    }

    /* Latest List */
    .widget-list { display: flex; flex-direction: column; gap: 20px; }
    .widget-item { display: flex; gap: 15px; align-items: flex-start; }
    
    .w-thumb {
        width: 80px; height: 65px; flex-shrink: 0;
        overflow: hidden; border-radius: 4px;
    }
    .w-thumb img { width: 100%; height: 100%; object-fit: cover; transition: 0.3s; }
    .widget-item:hover .w-thumb img { opacity: 0.8; }

    .w-content { display: flex; flex-direction: column; }
    .w-date {
        font-size: 0.7rem; color: var(--accent); text-transform: uppercase;
        font-weight: 600; margin-bottom: 4px;
    }
    .w-title {
        font-family: 'Oswald', sans-serif; font-size: 0.95rem;
        color: var(--primary); line-height: 1.3; margin: 0;
    }
    .w-title a:hover { color: var(--accent); }

    /* Contact Widget */
    .contact-widget p { color: var(--text-gray); font-size: 0.95rem; margin-bottom: 20px; }
    .btn-contact {
        display: inline-block;
        background: var(--accent);
        color: var(--primary);
        padding: 12px 25px;
        font-weight: 600;
        border-radius: 4px;
    }
    .btn-contact:hover { background: #e6a50a; }

    /* Responsive */
    @media (max-width: 992px) {
        .page-layout { grid-template-columns: 1fr; }
        .single-hero-section { padding: 130px 0 60px; }
        .hero-heading { font-size: 2.5rem; }
    }
</style>