<?php
require 'includes/db_connect.php';
require 'includes/header.php';

// Fetch all posts from DB
try {
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY date DESC");
    $all_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $all_posts = [];
}

// Sidebar logic: Get the latest 3-4 posts for the widget
$latest_widget_posts = array_slice($all_posts, 0, 4);
?>

<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<header class="page-hero-section">
    <div class="hero-overlay"></div>
    <div class="container hero-container">
        <div class="breadcrumbs">
            <a href="index.php">Inkingi Arts Space</a> <i class="fas fa-chevron-right"></i> <span class="current">Events & Gallery</span>
        </div>
        <h1 class="hero-heading">EVENTS & PHOTOS</h1>
    </div>
</header>

<main class="events-page-container">
    <div class="container">
        
        <div class="page-layout">
            
            <div class="main-content">
                <?php if (!empty($all_posts)): ?>
                    <?php foreach ($all_posts as $post): ?>
                    <article class="event-list-item">
                        <div class="item-image">
                            <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                            <div class="icon-overlay">
                                <i class="fas fa-camera"></i>
                            </div>
                        </div>
                        <div class="item-details">
                            <h2 class="item-title">
                                <a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                            </h2>
                            <p class="item-excerpt">
                                <?= htmlspecialchars(substr(strip_tags($post['content'] ?? ''), 0, 150)) ?>...
                            </p>
                        </div>
                    </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <aside class="sidebar">
                
                <div class="widget search-widget">
                    <form action="" method="GET">
                        <input type="text" placeholder="Search..." name="s">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <div class="widget latest-widget">
                    <h3 class="widget-header">LATEST UPDATES</h3>
                    <div class="widget-list">
                        <?php if(!empty($latest_widget_posts)): ?>
                            <?php foreach ($latest_widget_posts as $post): ?>
                            <div class="widget-item">
                                <div class="w-thumb">
                                    <a href="post.php?id=<?= $post['id'] ?>">
                                        <img src="<?= htmlspecialchars($post['image']) ?>" alt="Thumb">
                                    </a>
                                </div>
                                <div class="w-content">
                                    <span class="w-date"><?= date('M d, Y', strtotime($post['date'])) ?></span>
                                    <h4 class="w-title">
                                        <a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                                    </h4>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="widget categories-widget">
                    <h3 class="widget-header">CATEGORIES</h3>
                    <ul class="cat-list">
                        <li><a href="#">Exhibitions</a></li>
                        <li><a href="#">Workshops</a></li>
                        <li><a href="#">Gallery</a></li>
                        <li><a href="#">Community</a></li>
                        <li><a href="#">Residencies</a></li>
                    </ul>
                </div>

            </aside>

        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<style>
    /* --- CSS Variables --- */
    :root {
        --primary: #3b4d61; /* Slate Blue from your image */
        --accent: #FDB913; /* Gold/Yellow Accent for Art feel */
        --text-dark: #222222;
        --text-gray: #666666;
        --border-color: #e5e5e5;
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
    ul { list-style: none; padding: 0; margin: 0; }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* --- HERO SECTION --- */
    .page-hero-section {
        position: relative;
        /* Art Gallery Background Image */
        background-image: url('https://images.unsplash.com/photo-1765375382570-d87a9bc70c25?q=80&w=1074&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
        background-size: cover;
        background-position: center;
        padding: 160px 0 80px; /* High top padding to clear fixed header */
        color: white;
        margin-bottom: 60px;
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: rgba(59, 77, 97, 0.85); /* Slate Blue Overlay */
    }

    .hero-container {
        position: relative;
        z-index: 2;
    }

    /* Breadcrumbs */
    .breadcrumbs {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.8);
        margin-bottom: 10px;
        text-transform: uppercase;
        font-weight: 500;
        letter-spacing: 1px;
    }
    .breadcrumbs i { font-size: 0.7rem; margin: 0 10px; opacity: 0.7; }
    .breadcrumbs a {
        color: inherit;
    }
    .breadcrumbs a:hover {
        color: var(--accent);
    }
    .breadcrumbs .current { color: var(--accent); }

    /* Hero Heading */
    .hero-heading {
        font-family: 'Oswald', sans-serif;
        font-size: 4rem;
        color: white;
        text-transform: uppercase;
        margin: 0;
        font-weight: 700;
        letter-spacing: -1px;
        line-height: 1.1;
    }

    /* --- MAIN LAYOUT --- */
    .events-page-container {
        padding-bottom: 100px;
    }

    .page-layout {
        display: grid;
        grid-template-columns: 2fr 1fr; /* 66% / 33% Split */
        gap: 60px;
    }

    /* --- LEFT COLUMN --- */
    .main-content {
        display: flex;
        flex-direction: column;
        gap: 60px;
    }

    .event-list-item {
        display: grid;
        grid-template-columns: 1.2fr 1fr; /* Image wider than text */
        gap: 30px;
        align-items: flex-start;
    }

    .item-image {
        position: relative;
        overflow: hidden;
        border-radius: 4px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .item-image img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
        aspect-ratio: 4/3;
        transition: transform 0.5s ease;
    }

    .event-list-item:hover .item-image img {
        transform: scale(1.05);
    }

    .icon-overlay {
        position: absolute;
        top: 0;
        left: 0;
        background-color: var(--accent);
        color: var(--primary);
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .item-details {
        padding-top: 5px;
    }

    .item-title {
        font-family: 'Oswald', sans-serif;
        font-size: 1.8rem;
        color: var(--primary);
        text-transform: uppercase;
        line-height: 1.3;
        margin: 0 0 15px 0;
        font-weight: 700;
    }

    .item-title a:hover {
        color: var(--accent);
    }

    .item-excerpt {
        color: var(--text-gray);
        font-size: 1rem;
        line-height: 1.7;
    }

    /* --- SIDEBAR --- */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 50px;
    }

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

    /* Search Widget */
    .search-widget form {
        position: relative;
        background: #f4f4f4;
    }
    
    .search-widget input {
        width: 100%;
        border: none;
        background: transparent;
        padding: 15px 50px 15px 20px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        color: #555;
        outline: none;
    }
    
    .search-widget button {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: none;
        color: #888;
        font-size: 1.1rem;
        cursor: pointer;
    }

    /* Latest Widget */
    .widget-list {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .widget-item {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .w-thumb {
        width: 90px;
        flex-shrink: 0;
        height: 70px;
        overflow: hidden;
    }

    .w-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.3s;
    }
    
    .widget-item:hover .w-thumb img {
        opacity: 0.8;
    }

    .w-content {
        display: flex;
        flex-direction: column;
    }

    .w-date {
        font-size: 0.75rem;
        color: var(--accent);
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 5px;
        letter-spacing: 0.5px;
    }

    .w-title {
        font-family: 'Oswald', sans-serif;
        font-size: 1rem;
        color: var(--primary);
        line-height: 1.3;
        margin: 0;
        font-weight: 500;
    }

    .w-title a:hover { color: var(--accent); }


    /* Categories Widget */
    .cat-list li {
        border-bottom: 1px solid #eee;
    }
    .cat-list li:last-child { border-bottom: none; }

    .cat-list li a {
        display: block;
        padding: 12px 0;
        color: var(--text-dark);
        font-size: 0.95rem;
        font-weight: 400;
    }

    .cat-list li a:hover {
        color: var(--accent);
        padding-left: 5px; /* Slight movement effect */
    }

    /* Responsive */
    @media (max-width: 992px) {
        .page-layout { grid-template-columns: 1fr; }
        .hero-heading { font-size: 3rem; }
    }
    
    @media (max-width: 768px) {
        .event-list-item { grid-template-columns: 1fr; }
        .item-image img { aspect-ratio: 16/9; }
        .page-hero-section { padding: 130px 0 60px; }
    }
</style>