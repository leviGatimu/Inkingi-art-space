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

// Handle form submission
$message = "";
$messageType = "info";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $profile_pic = trim($_POST['profile_pic'] ?? '');

    if (empty($name)) {
        $message = "Name is required.";
        $messageType = "error";
    } else {
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
        }

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

        .dashboard-container {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 40px;
        }

        .profile-section {
            background: var(--card);
            border-radius: var(--radius);
            padding: 50px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .profile-section::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(253,185,19,0.08) 0%, transparent 60%);
            animation: rotateGlow 25s infinite linear;
        }

        @keyframes rotateGlow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 40px;
            margin-bottom: 50px;
            flex-wrap: wrap;
        }

        .profile-avatar-preview {
            width: 180px;
            height: 180px;
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
            min-width: 320px;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: white;
        }

        .form-group {
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: var(--text-muted);
            font-weight: 500;
        }

        input[type="text"],
        input[type="url"],
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
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            .profile-avatar-preview {
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>

    <!-- Common Artist Header -->
    <header class="artist-header">
        <div class="header-inner">
            <div class="logo-area">
                <img src="../assets/images/logo.svg" alt="Inkingi Logo">
                <span class="artist-name">Artist Dashboard</span>
            </div>
            <div class="nav-actions">
                <a href="artists_dashboard.php" class="nav-btn">Back to Dashboard</a>
                <a href="logout.php" class="nav-btn logout-btn">Logout</a>
            </div>
        </div>
    </header>

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

                    <form method="POST">
                        <div class="form-group">
                            <label for="name">Display Name</label>
                            <input type="text" id="name" name="name" required value="<?= htmlspecialchars($artist['name']) ?>" oninput="updatePreview()">
                        </div>

                        <div class="form-group">
                            <label for="bio">Bio / Artist Statement</label>
                            <textarea id="bio" name="bio" rows="5" oninput="updatePreview()"><?= htmlspecialchars($artist['bio'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="profile_pic">Profile Picture URL</label>
                            <input type="url" id="profile_pic" name="profile_pic" placeholder="https://example.com/your-photo.jpg" value="<?= htmlspecialchars($artist['profile_pic'] ?? '') ?>" oninput="updatePreview()">
                        </div>

                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> Save Profile
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Live preview update
        function updatePreview() {
            const name = document.getElementById('name').value.trim();
            const bio = document.getElementById('bio').value.trim();
            const pic = document.getElementById('profile_pic').value.trim();

            // Update avatar
            const previewImg = document.getElementById('avatarPreview');
            if (pic) {
                previewImg.outerHTML = `<img src="${pic}" alt="Profile Preview" class="profile-avatar-preview" id="avatarPreview">`;
            } else {
                previewImg.outerHTML = `<div class="profile-avatar-preview" id="avatarPreview" style="background:#1a1f2e; display:flex; align-items:center; justify-content:center; font-size:4rem; color:var(--text-muted);">
                    ${name ? name.charAt(0).toUpperCase() : '?'}
                </div>`;
            }
        }
    </script>

</body>
</html>