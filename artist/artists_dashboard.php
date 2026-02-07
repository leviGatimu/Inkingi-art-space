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

// Initialize messages (prevents undefined variable warning)
$message = "";
$messageType = "info";

// Example: Profile updated message (you can set this after form submit)
if (isset($_GET['updated']) && $_GET['updated'] === 'profile') {
    $message = "Profile updated successfully!";
    $messageType = "success";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($artist['name']) ?> | Inkingi Artist Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.svg">
    <style>
        :root {
            --bg: #0a0e17;
            --card: rgba(22, 27, 34, 0.92);
            --text: #e6e6e6;
            --text-muted: #8892b0;
            --accent: #FDB913;
            --accent-hover: #e6a50a;
            --green: #64ffda;
            --red: #ff5f57;
            --border: rgba(48, 54, 61, 0.6);
            --glow: rgba(253, 185, 19, 0.12);
            --radius: 16px;
            --transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* Common Artist Header (reusable across all artist pages) */
        .artist-header {
            background: linear-gradient(135deg, rgba(15,15,26,0.95), rgba(22,27,34,0.95));
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 20px 40px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
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
            gap: 15px;
        }

        .logo-area img {
            height: 50px;
            transition: transform 0.3s;
        }

        .logo-area img:hover {
            transform: scale(1.08);
        }

        .artist-name {
            font-size: 1.4rem;
            font-weight: 600;
            color: white;
        }

        .nav-actions {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-btn {
            background: rgba(253,185,19,0.15);
            color: var(--accent);
            border: 1px solid rgba(253,185,19,0.3);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }

        .nav-btn:hover {
            background: var(--accent);
            color: #0f0f1a;
            transform: translateY(-2px);
        }

        .logout-btn {
            background: rgba(255,95,87,0.15);
            color: var(--red);
            border: 1px solid rgba(255,95,87,0.3);
        }

        .logout-btn:hover {
            background: var(--red);
            color: white;
        }

        /* Main Content Area */
        .dashboard-container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 40px;
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

        .artworks-section h2 {
            color: white;
            margin-bottom: 30px;
            font-size: 2rem;
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
    </style>
</head>
<body>

    <!-- Common Artist Header (reusable on all artist pages) -->
    <header class="artist-header">
        <div class="header-inner">
            <div class="logo-area">
                <img src="../assets/images/logo.svg" alt="Inkingi Logo">
                <span class="artist-name"><?= htmlspecialchars($artist['name']) ?></span>
            </div>
            <div class="nav-actions">
                <a href="artists_profile.php" class="nav-btn">Edit Profile</a>
                <a href="logout.php" class="nav-btn logout-btn">Logout</a>
            </div>
        </div>
    </header>

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

        <!-- Your Artworks -->
        <section class="artworks-section">
            <h2>Your Artworks</h2>
            <?php if (empty($artworks)): ?>
                <p class="no-art">You haven’t uploaded any artworks yet.<br><br>
                    <a href="#upload-section" style="color:var(--accent); text-decoration:none; font-weight:600;">Upload your first piece →</a>
                </p>
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

</body>
</html>