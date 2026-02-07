<?php
session_start();

// Security Check
if (!isset($_SESSION['artist_id'])) {
    header("Location: artists_login.php");
    exit;
}

$artist_id = $_SESSION['artist_id'];

// Database
require_once __DIR__ . '/../includes/db_connect.php';

// Fetch Artist Profile
$stmt = $pdo->prepare("SELECT * FROM artists WHERE id = ?");
$stmt->execute([$artist_id]);
$artist = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$artist) {
    session_destroy();
    header("Location: artists_login.php");
    exit;
}

// Fetch Artist's Artworks
$stmt = $pdo->prepare("SELECT * FROM artworks WHERE artist_id = ? ORDER BY date_uploaded DESC");
$stmt->execute([$artist_id]);
$artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Messages (prevents undefined variable warning)
$message = "";
$messageType = "info";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($artist['name']) ?> | Inkingi Artist Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.svg">
    <style>
        :root {
            --bg: #0a0c14;
            --canvas: #11151f;
            --card: rgba(20, 24, 32, 0.94);
            --text: #f0f4f8;
            --text-muted: #a1b0c2;
            --accent: #FDB913;
            --accent-glow: rgba(253, 185, 19, 0.25);
            --green: #00d4a8;
            --red: #ff5f6b;
            --border: rgba(48, 54, 61, 0.45);
            --radius: 20px;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            background-image: 
                radial-gradient(circle at 15% 25%, var(--accent-glow) 0%, transparent 30%),
                radial-gradient(circle at 85% 75%, rgba(0,212,168,0.08) 0%, transparent 40%);
            background-attachment: fixed;
        }

        /* Common Header */
        .artist-header {
            background: linear-gradient(to bottom, rgba(10,12,20,0.98), rgba(17,21,31,0.98));
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 16px 24px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 6px 25px rgba(0,0,0,0.45);
        }

        .header-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-area img {
            height: 52px;
            filter: drop-shadow(0 4px 12px rgba(253,185,19,0.35));
            transition: transform 0.4s ease;
        }

        .logo-area img:hover {
            transform: scale(1.12) rotate(8deg);
        }

        .artist-name {
            font-size: 1.45rem;
            font-weight: 600;
            background: linear-gradient(90deg, var(--accent), #ffcc66);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-actions {
            display: flex;
            gap: 18px;
            align-items: center;
        }

        .nav-btn {
            background: rgba(253,185,19,0.12);
            color: var(--accent);
            border: 1px solid rgba(253,185,19,0.28);
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
        }

        .nav-btn:hover {
            background: var(--accent);
            color: #0a0c14;
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 12px 35px rgba(253,185,19,0.4);
        }

        .logout-btn {
            background: rgba(255,95,87,0.12);
            color: var(--red);
            border: 1px solid rgba(255,95,87,0.28);
        }

        .logout-btn:hover {
            background: var(--red);
            color: white;
        }

        .hamburger {
            display: none;
            background: none;
            border: none;
            color: var(--accent);
            font-size: 1.8rem;
            cursor: pointer;
        }

        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            max-width: 320px;
            height: 100vh;
            background: var(--card);
            backdrop-filter: blur(16px);
            z-index: 1100;
            padding: 80px 30px 30px;
            transition: right 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: -15px 0 50px rgba(0,0,0,0.6);
        }

        .mobile-menu.active {
            right: 0;
        }

        .mobile-close {
            position: absolute;
            top: 24px;
            right: 24px;
            background: none;
            border: none;
            color: var(--accent);
            font-size: 2.4rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .mobile-close:hover {
            transform: rotate(90deg) scale(1.15);
        }

        .mobile-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .mobile-link {
            color: white;
            font-size: 1.4rem;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }

        .mobile-link:hover {
            color: var(--accent);
            padding-left: 12px;
        }

        .menu-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s ease;
        }

        .menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Dashboard Content */
        .dashboard-container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 24px;
        }

        .profile-hero {
            background: linear-gradient(135deg, var(--card), #1a1f2e);
            border-radius: var(--radius);
            padding: 60px 40px;
            margin-bottom: 50px;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }

        .profile-hero::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(253,185,19,0.08) 0%, transparent 60%);
            animation: rotateGlow 20s infinite linear;
        }

        @keyframes rotateGlow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .profile-content {
            display: flex;
            align-items: center;
            gap: 50px;
            flex-wrap: wrap;
        }

        .profile-avatar {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--accent);
            box-shadow: 0 10px 30px rgba(253,185,19,0.3);
            transition: var(--transition);
        }

        .profile-avatar:hover {
            transform: scale(1.08);
        }

        .profile-info h1 {
            font-size: 2.8rem;
            margin-bottom: 10px;
            color: white;
        }

        .profile-bio {
            color: var(--text-muted);
            font-size: 1.1rem;
            line-height: 1.7;
            max-width: 700px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin: 50px 0;
        }

        .stat-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 30px;
            text-align: center;
            border: 1px solid var(--border);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .stat-number {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 10px;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 1rem;
        }

        .action-buttons {
            text-align: center;
            margin: 40px 0 60px;
        }

        .btn-action {
            background: linear-gradient(135deg, var(--accent), #ffd166);
            color: #0a0c14;
            padding: 16px 60px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.25rem;
            cursor: pointer;
            transition: var(--transition);
            display: inline-block;
            box-shadow: 0 10px 30px rgba(253,185,19,0.3);
            text-decoration: none;
        }

        .btn-action:hover {
            transform: translateY(-6px) scale(1.04);
            box-shadow: 0 20px 60px rgba(253,185,19,0.5);
        }

        .artworks-section h2 {
            color: white;
            margin-bottom: 30px;
            font-size: 2rem;
            text-align: center;
        }

        .artwork-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        .artwork-card {
            background: var(--card);
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border);
            transition: var(--transition);
        }

        .artwork-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.4);
        }

        .artwork-image {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }

        .artwork-body {
            padding: 25px;
        }

        .artwork-title {
            font-size: 1.4rem;
            color: white;
            margin-bottom: 12px;
        }

        .artwork-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .artwork-actions {
            display: flex;
            gap: 15px;
        }

        .btn-small {
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-edit {
            background: rgba(100,255,218,0.15);
            color: var(--green);
            border: 1px solid rgba(100,255,218,0.3);
        }

        .btn-delete {
            background: rgba(255,95,87,0.15);
            color: var(--red);
            border: 1px solid rgba(255,95,87,0.3);
        }

        .btn-edit:hover, .btn-delete:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .no-art {
            text-align: center;
            color: var(--text-muted);
            padding: 80px 0;
            font-size: 1.2rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .hamburger { display: block; }
            .nav-actions { display: none; }
            .profile-content { flex-direction: column; text-align: center; }
            .profile-avatar { margin: 0 auto; }
            .stats-grid { grid-template-columns: 1fr; }
            .artwork-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 576px) {
            .header-inner { padding: 0 16px; }
            .dashboard-container { padding: 0 16px; }
        }
    </style>
</head>
<body>

    <!-- Common Artist Header -->
    <header class="artist-header">
        <div class="header-inner">
            <div class="logo-area">
                <img src="../assets/images/logo.svg" alt="Inkingi Logo">
                <span class="artist-name"><?= htmlspecialchars($artist['name']) ?></span>
            </div>

            <!-- Desktop Nav -->
            <div class="nav-actions">
                <a href="edit_profile.php" class="nav-btn">Edit Profile</a>
                <a href="artists_upload.php" class="nav-btn" style="background: var(--green); color: #0a0c14;">Upload Artwork</a>
                <a href="logout.php" class="nav-btn logout-btn">Logout</a>
            </div>

            <!-- Hamburger -->
            <button class="hamburger" id="hamburger">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <button class="mobile-close" id="mobileClose">
            <i class="fas fa-times"></i>
        </button>
        <div class="mobile-links">
            <a href="artists_dashboard.php" class="mobile-link">Dashboard</a>
            <a href="edit_profile.php" class="mobile-link">Edit Profile</a>
            <a href="artists_upload.php" class="mobile-link">Upload Artwork</a>
            <a href="logout.php" class="mobile-link" style="color: var(--red);">Logout</a>
        </div>
    </div>

    <div class="menu-overlay" id="menuOverlay"></div>

    <div class="dashboard-container">
        <!-- Profile Hero -->
        <section class="profile-hero">
            <div class="profile-content">
                <div>
                    <?php if (!empty($artist['profile_pic'])): ?>
                        <img src="<?= htmlspecialchars($artist['profile_pic']) ?>" alt="Profile" class="profile-avatar">
                    <?php else: ?>
                        <div class="profile-avatar" style="background:#1a1f2e; display:flex; align-items:center; justify-content:center; font-size:3rem;">
                            <?= strtoupper(substr($artist['name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <h1><?= htmlspecialchars($artist['name']) ?></h1>
                    <p class="profile-bio"><?= nl2br(htmlspecialchars($artist['bio'] ?? 'No bio added yet. Tell the world about your creative journey!')) ?></p>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= count($artworks) ?></div>
                <div class="stat-label">Artworks Uploaded</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">Views (Coming Soon)</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">Sales (Coming Soon)</div>
            </div>
        </div>

        <!-- Upload Call to Action -->
        <?php if (empty($artworks)): ?>
            <div class="action-buttons">
                <a href="artists_upload.php" class="btn-action">
                    <i class="fas fa-upload"></i> Upload Your First Artwork
                </a>
            </div>
        <?php endif; ?>

        <!-- Your Artworks -->
        <section class="artworks-section">
            <h2>Your Artworks</h2>
            <?php if (empty($artworks)): ?>
                <p class="no-art">Your gallery is empty.<br>Start sharing your creations today.</p>
            <?php else: ?>
                <div class="artwork-grid">
                    <?php foreach ($artworks as $art): ?>
                        <div class="artwork-card">
                            <img src="<?= htmlspecialchars($art['image_path']) ?>" alt="<?= htmlspecialchars($art['title']) ?>" class="artwork-image">
                            <div class="artwork-body">
                                <h3 class="artwork-title"><?= htmlspecialchars($art['title']) ?></h3>
                                <p class="artwork-desc"><?= nl2br(htmlspecialchars(substr($art['description'] ?? 'No description', 0, 120))) ?>...</p>
                                <div class="artwork-actions">
                                    <a href="?edit_art=<?= $art['id'] ?>" class="btn-small btn-edit">Edit</a>
                                    <a href="?delete_art=<?= $art['id'] ?>" class="btn-small btn-danger" onclick="return confirm('Delete this artwork?');">Delete</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <script>
        // Hamburger Menu Toggle
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuOverlay = document.getElementById('menuOverlay');
        const mobileClose = document.getElementById('mobileClose');

        function toggleMenu() {
            mobileMenu.classList.toggle('active');
            menuOverlay.classList.toggle('active');
            document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
        }

        hamburger.addEventListener('click', toggleMenu);
        mobileClose.addEventListener('click', toggleMenu);
        menuOverlay.addEventListener('click', toggleMenu);

        // Close on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                toggleMenu();
            }
        });
    </script>

</body>
</html>