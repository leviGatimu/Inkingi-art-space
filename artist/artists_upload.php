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

// Fetch artist name
$stmt = $pdo->prepare("SELECT name FROM artists WHERE id = ?");
$stmt->execute([$artist_id]);
$artist = $stmt->fetch(PDO::FETCH_ASSOC);

// Upload paths
$uploadDir = __DIR__ . '/../uploads/artworks/';
$uploadWebPath = '/inkingi/uploads/artworks/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Handle upload
$message = "";
$messageType = "info";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $price       = trim($_POST['price'] ?? '');

    if (empty($title) || !isset($_FILES['art_image']) || $_FILES['art_image']['error'] !== UPLOAD_ERR_OK) {
        $message = "Title and image are required.";
        $messageType = "error";
    } else {
        $file = $_FILES['art_image'];
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 10 * 1024 * 1024; // 10MB for high-res art

        if (!in_array($file['type'], $allowed)) {
            $message = "Only JPG, PNG, GIF, WEBP allowed.";
            $messageType = "error";
        } elseif ($file['size'] > $maxSize) {
            $message = "File too large (max 10MB).";
            $messageType = "error";
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName = 'art_' . $artist_id . '_' . uniqid() . '.' . $ext;
            $dest = $uploadDir . $newName;

            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $image_path = $uploadWebPath . $newName;

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO artworks (artist_id, title, description, image_path, category, price)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$artist_id, $title, $description, $image_path, $category, $price]);
                    $message = "Your creation has been unveiled in the gallery.";
                    $messageType = "success";
                } catch (Exception $e) {
                    $message = "Error: " . $e->getMessage();
                    $messageType = "error";
                }
            } else {
                $message = "Failed to reveal the artwork. Check permissions.";
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
    <title>Upload Creation | <?= htmlspecialchars($artist['name']) ?> • Inkingi</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
    <link rel="icon" type="image/png" href="../assets/images/logo.svg">
    <style>
        :root {
            --bg: #0a0c14;
            --canvas: #0f121b;
            --card: rgba(20, 24, 32, 0.94);
            --text: #f5f7fa;
            --text-muted: #a3b0c2;
            --accent: #FDB913;
            --accent-glow: rgba(253, 185, 19, 0.28);
            --green: #00e0b8;
            --red: #ff5f6b;
            --border: rgba(48, 54, 61, 0.45);
            --radius: 24px;
            --transition: all 0.45s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            background-image: 
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3Cpattern id='paint' width='20' height='20' patternUnits='userSpaceOnUse'%3E%3Ccircle cx='10' cy='10' r='2' fill='%23FDB913' fill-opacity='0.05'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23paint)'/%3E%3C/svg%3E"),
                radial-gradient(circle at 20% 30%, rgba(253,185,19,0.08) 0%, transparent 50%);
            background-attachment: fixed;
        }

        /* Header */
        .artist-header {
            background: linear-gradient(to bottom, rgba(10,12,20,0.98), rgba(15,17,25,0.98));
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 18px 30px;
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

        /* Upload Page */
        .dashboard-container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 24px;
        }

        .upload-section {
            background: var(--card);
            border-radius: var(--radius);
            padding: 70px 60px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.45);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .upload-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 50% 30%, rgba(253,185,19,0.09) 0%, transparent 70%);
            pointer-events: none;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            color: white;
            text-align: center;
            margin-bottom: 12px;
            letter-spacing: -1px;
        }

        .upload-intro {
            color: var(--text-muted);
            text-align: center;
            font-size: 1.2rem;
            margin-bottom: 60px;
            max-width: 780px;
            margin-left: auto;
            margin-right: auto;
        }

        .upload-form {
            max-width: 880px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 45px;
        }

        label {
            display: block;
            margin-bottom: 14px;
            color: var(--text-muted);
            font-weight: 500;
            font-size: 1.15rem;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 18px 22px;
            background: rgba(13,17,23,0.75);
            border: 1px solid var(--border);
            border-radius: 14px;
            color: white;
            font-size: 1.05rem;
            transition: all 0.3s ease;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 5px var(--glow);
        }

        textarea {
            min-height: 260px;
            resize: vertical;
        }

        /* Artistic Drag & Drop */
        .file-drop {
            border: 3px dashed var(--border);
            border-radius: var(--radius);
            padding: 80px 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s ease;
            background: rgba(253,185,19,0.03);
            position: relative;
        }

        .file-drop::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 50%, rgba(253,185,19,0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .file-drop:hover,
        .file-drop.dragover {
            border-color: var(--accent);
            background: rgba(253,185,19,0.08);
            box-shadow: 0 0 40px var(--glow);
        }

        .file-drop i {
            font-size: 5rem;
            color: var(--text-muted);
            margin-bottom: 25px;
            transition: var(--transition);
        }

        .file-drop:hover i,
        .file-drop.dragover i {
            color: var(--accent);
            transform: scale(1.15) rotate(10deg);
        }

        .file-drop p {
            color: var(--text-muted);
            margin: 0;
            font-size: 1.2rem;
        }

        .preview-area {
            margin: 40px 0;
            text-align: center;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 25px;
            background: rgba(0,0,0,0.25);
        }

        .preview-img {
            max-width: 100%;
            max-height: 500px;
            border-radius: 14px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.5);
            display: none;
        }

        .btn-upload {
            background: linear-gradient(135deg, var(--accent), #ffd166);
            color: #0a0e17;
            padding: 18px 80px;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1.3rem;
            cursor: pointer;
            transition: var(--transition);
            display: block;
            margin: 60px auto 0;
            box-shadow: 0 12px 40px rgba(253,185,19,0.35);
        }

        .btn-upload:hover {
            transform: translateY(-6px) scale(1.04);
            box-shadow: 0 25px 70px rgba(253,185,19,0.5);
        }

        .message {
            padding: 18px 28px;
            border-radius: 14px;
            margin-bottom: 50px;
            font-weight: 500;
            text-align: center;
            font-size: 1.15rem;
        }

        .success {
            background: rgba(100, 255, 218, 0.22);
            color: #64ffda;
            border-left: 6px solid #64ffda;
        }

        .error {
            background: rgba(255, 95, 87, 0.22);
            color: #ff5f57;
            border-left: 6px solid #ff5f57;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .hamburger { display: block; }
            .nav-actions { display: none; }
            .upload-section { padding: 50px 30px; }
        }

        @media (max-width: 576px) {
            h1 { font-size: 2.4rem; }
            .btn-upload { padding: 16px 50px; font-size: 1.15rem; width: 100%; }
        }
    </style>
</head>
<body>

    <!-- Common Artist Header -->
    <header class="artist-header">
        <div class="header-inner">
            <div class="logo-area">
                <img src="../assets/images/logo.svg" alt="Inkingi Logo">
                <span class="artist-name">Upload Creation</span>
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

        <section class="upload-section">
            <h1>Upload New Creation</h1>
            <p class="upload-intro">Pour your soul into the canvas. Give it a title, share its story, and let it breathe in the Inkingi Gallery.</p>

            <form method="POST" enctype="multipart/form-data" class="upload-form">
                <div class="form-group">
                    <label for="title">Title of the Work *</label>
                    <input type="text" id="title" name="title" required placeholder="What name does this creation carry?">
                </div>

                <div class="form-group">
                    <label for="description">Story, Emotion & Meaning</label>
                    <textarea id="description" name="description" placeholder="Whisper the soul of this piece — what moved you to create it? What does it whisper back?"></textarea>
                </div>

                <div class="form-group">
                    <label>Your Artwork *</label>
                    <div class="file-drop" id="dropZone">
                        <i class="fas fa-paint-brush fa-4x"></i>
                        <p>Drag your creation here or <span style="color:var(--accent); font-weight:600;">click to unveil</span></p>
                        <input type="file" id="art_image" name="art_image" accept="image/*" required hidden onchange="previewArtwork(event)">
                    </div>
                    <div class="preview-area">
                        <img id="artPreview" src="" alt="Your Creation" class="preview-img" style="display:none;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option value="">Select the medium</option>
                        <option value="Painting">Painting</option>
                        <option value="Sculpture">Sculpture</option>
                        <option value="Mixed Media">Mixed Media</option>
                        <option value="Photography">Photography</option>
                        <option value="Digital Art">Digital Art</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Price (e.g. 75,000 RWF)</label>
                    <input type="text" id="price" name="price" placeholder="What is its worth to the world? (optional)">
                </div>

                <button type="submit" class="btn-upload">
                    <i class="fas fa-feather-alt"></i> Release to the Gallery
                </button>
            </form>
        </section>
    </div>

    <script>
        tinymce.init({
            selector: '#description',
            height: 380,
            menubar: false,
            plugins: 'lists link image',
            toolbar: 'undo redo | bold italic underline strikethrough | bullist numlist outdent indent | link | removeformat',
            content_style: "body { font-family: 'Playfair Display', serif; font-size:17px; color:#f0f4f8; background:#0b0f1c; line-height:1.8; }"
        });

        // Drag & Drop + Preview
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('art_image');
        const preview = document.getElementById('artPreview');

        ['dragover', 'dragenter'].forEach(event => {
            dropZone.addEventListener(event, (e) => {
                e.preventDefault();
                dropZone.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(event => {
            dropZone.addEventListener(event, (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragover');
            });
        });

        dropZone.addEventListener('drop', (e) => {
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                fileInput.files = e.dataTransfer.files;
                previewArtwork({ target: { files: [file] } });
            }
        });

        dropZone.addEventListener('click', () => fileInput.click());

        function previewArtwork(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        // Hamburger Menu
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