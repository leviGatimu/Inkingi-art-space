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

// Fetch current artist data
$stmt = $pdo->prepare("SELECT name, bio, profile_pic FROM artists WHERE id = ?");
$stmt->execute([$artist_id]);
$artist = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$artist) {
    session_destroy();
    header("Location: artists_login.php");
    exit;
}

// Upload settings
$uploadDir = __DIR__ . '/../uploads/artists/';
$uploadWebPath = '/inkingi/uploads/artists/'; // adjust if your site URL is different

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Handle form submission
$message = "";
$messageType = "info";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $profile_pic = $artist['profile_pic'] ?? ''; // keep old if no new upload

    if (empty($name)) {
        $message = "Display name is required.";
        $messageType = "error";
    } else {
        // Handle file upload
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['profile_pic'];
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            if (!in_array($file['type'], $allowed)) {
                $message = "Only JPG, PNG, GIF, WEBP allowed.";
                $messageType = "error";
            } elseif ($file['size'] > $maxSize) {
                $message = "File too large (max 5MB).";
                $messageType = "error";
            } else {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newName = 'artist_' . $artist_id . '_' . time() . '.' . $ext;
                $dest = $uploadDir . $newName;

                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $profile_pic = $uploadWebPath . $newName;
                } else {
                    $message = "Failed to upload image.";
                    $messageType = "error";
                }
            }
        }

        // Update DB if no errors
        if ($messageType !== "error") {
            try {
                $stmt = $pdo->prepare("UPDATE artists SET name = ?, bio = ?, profile_pic = ? WHERE id = ?");
                $stmt->execute([$name, $bio, $profile_pic, $artist_id]);
                $message = "Profile updated successfully!";
                $messageType = "success";

                // Refresh artist data
                $stmt = $pdo->prepare("SELECT name, bio, profile_pic FROM artists WHERE id = ?");
                $stmt->execute([$artist_id]);
                $artist = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $message = "Error updating profile: " . $e->getMessage();
                $messageType = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | <?= htmlspecialchars($artist['name']) ?> - Inkingi</title>
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
            overflow-x: hidden;
        }

        /* Common Artist Header */
        .artist-header {
            background: linear-gradient(135deg, rgba(15,15,26,0.95), rgba(22,27,34,0.95));
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
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
            gap: 12px;
        }

        .logo-area img {
            height: 48px;
            transition: transform 0.3s;
        }

        .logo-area img:hover {
            transform: scale(1.08);
        }

        .artist-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: white;
        }

        .nav-actions {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .nav-btn {
            background: rgba(253,185,19,0.15);
            color: var(--accent);
            border: 1px solid rgba(253,185,19,0.3);
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
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

        .hamburger {
            display: none;
            background: none;
            border: none;
            color: white;
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
            backdrop-filter: blur(12px);
            z-index: 1100;
            padding: 80px 30px 30px;
            transition: right 0.4s var(--transition);
            box-shadow: -10px 0 30px rgba(0,0,0,0.4);
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
            color: white;
            font-size: 2rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .mobile-close:hover {
            transform: rotate(90deg);
        }

        .mobile-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .mobile-link {
            color: white;
            font-size: 1.3rem;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }

        .mobile-link:hover {
            color: var(--accent);
        }

        .menu-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease;
        }

        .menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Main Content */
        .dashboard-container {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 24px;
        }

        .profile-section {
            background: var(--card);
            border-radius: var(--radius);
            padding: 40px 30px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .profile-header {
            display: flex;
            align-items: flex-start;
            gap: 40px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .profile-avatar-preview {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--accent);
            box-shadow: 0 10px 30px rgba(253,185,19,0.3);
            transition: var(--transition);
        }

        .profile-avatar-preview:hover {
            transform: scale(1.05);
        }

        .profile-form {
            flex: 1;
            min-width: 300px;
        }

        h1 {
            font-size: 2.3rem;
            margin-bottom: 10px;
            color: white;
        }

        .form-group {
            margin-bottom: 28px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: var(--text-muted);
            font-weight: 500;
        }

        input[type="text"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 14px 16px;
            background: rgba(13,17,23,0.7);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: white;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s;
            cursor: pointer;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(253,185,19,0.2);
        }

        textarea {
            min-height: 140px;
            resize: vertical;
        }

        input[type="file"] {
            padding: 8px;
            cursor: pointer;
        }

        .btn-save {
            background: var(--accent);
            color: #0f0f1a;
            padding: 14px 40px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-save:hover {
            background: var(--accent-hover);
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(253,185,19,0.3);
        }

        .btn-back {
            background: #21262d;
            color: var(--text);
            padding: 14px 40px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-left: 20px;
            transition: var(--transition);
        }

        .btn-back:hover {
            background: #30363d;
            transform: translateY(-3px);
        }

        .message {
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            font-weight: 500;
            text-align: center;
        }

        .success {
            background: rgba(100, 255, 218, 0.15);
            color: #64ffda;
            border-left: 4px solid #64ffda;
        }

        .error {
            background: rgba(255, 95, 87, 0.15);
            color: #ff5f57;
            border-left: 4px solid #ff5f57;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .hamburger { display: block; }
            .nav-actions { display: none; }
            .profile-header { flex-direction: column; text-align: center; }
            .profile-avatar-preview { margin: 0 auto; }
        }

        @media (max-width: 576px) {
            .header-inner { padding: 0 16px; }
            .dashboard-container { padding: 0 16px; }
            .profile-section { padding: 30px 20px; }
        }
    </style>
</head>
<body>

    <!-- Common Artist Header -->
    <header class="artist-header">
        <div class="header-inner">
            <div class="logo-area">
                <img src="../assets/images/logo.svg" alt="Inkingi Logo">
                <span class="artist-name">Edit Profile</span>
            </div>

            <!-- Desktop Nav -->
            <div class="nav-actions">
                <a href="artists_dashboard.php" class="nav-btn">Back to Dashboard</a>
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
            <a href="logout.php" class="mobile-link" style="color: var(--red);">Logout</a>
        </div>
    </div>

    <div class="menu-overlay" id="menuOverlay"></div>

    <div class="dashboard-container">
        <?php if ($message): ?>
            <div class="message <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <section class="profile-section">
            <div class="profile-header">
                <div>
                    <?php if (!empty($artist['profile_pic'])): ?>
                        <img src="<?= htmlspecialchars($artist['profile_pic']) ?>" alt="Profile Preview" class="profile-avatar-preview" id="avatarPreview">
                    <?php else: ?>
                        <div class="profile-avatar-preview" id="avatarPreview" style="background:#1a1f2e; display:flex; align-items:center; justify-content:center; font-size:4rem; color:var(--text-muted);">
                            <?= strtoupper(substr($artist['name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="profile-form">
                    <h1>Edit Your Public Profile</h1>
                    <p style="color:var(--text-muted); margin-bottom:30px;">This is how visitors see you on the Inkingi website.</p>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Display Name</label>
                            <input type="text" id="name" name="name" required value="<?= htmlspecialchars($artist['name']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="bio">Bio / Artist Statement</label>
                            <textarea id="bio" name="bio" rows="5"><?= htmlspecialchars($artist['bio'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="profile_pic">Profile Picture</label>
                            <input type="file" id="profile_pic" name="profile_pic" accept="image/*" onchange="previewImage(event)">
                            <p style="color:var(--text-muted); font-size:0.9rem; margin-top:8px;">Max 5MB — JPG, PNG, GIF, WEBP</p>
                            <?php if (!empty($artist['profile_pic'])): ?>
                                <p style="margin-top:10px; color:var(--text-muted);">Current image: <a href="<?= htmlspecialchars($artist['profile_pic']) ?>" target="_blank" style="color:var(--accent);">View</a></p>
                            <?php endif; ?>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save"></i> Save Profile
                            </button>
                            <a href="artists_dashboard.php" class="btn-back">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Live image preview
        function previewImage(event) {
            const preview = document.getElementById('avatarPreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.outerHTML = `<img src="${e.target.result}" alt="Profile Preview" class="profile-avatar-preview" id="avatarPreview">`;
                };
                reader.readAsDataURL(file);
            }
        }

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

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                toggleMenu();
            }
        });
    </script>

</body>
</html>