<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }
require '../includes/db_connect.php';

// 1. HANDLE SAVE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        $stmt = $pdo->prepare("UPDATE content_blocks SET content_value = ? WHERE section_key = ? AND page_id = 1");
        $stmt->execute([$value, $key]);
    }

    // Handle File Uploads (Hero BG & About Image)
    $files = ['hero_bg', 'about_image'];
    foreach ($files as $fileKey) {
        if (!empty($_FILES[$fileKey]['name'])) {
            $target = "../assets/uploads/" . basename($_FILES[$fileKey]['name']);
            if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $target)) {
                $dbPath = "assets/uploads/" . basename($_FILES[$fileKey]['name']);
                $stmt = $pdo->prepare("UPDATE content_blocks SET content_value = ? WHERE section_key = ? AND page_id = 1");
                $stmt->execute([$dbPath, $fileKey]);
            }
        }
    }
    
    $success = "Homepage updated successfully!";
}

// 2. FETCH DATA
$stmt = $pdo->prepare("SELECT section_key, content_value FROM content_blocks WHERE page_id = 1");
$stmt->execute();
$data = [];
while ($row = $stmt->fetch()) {
    $data[$row['section_key']] = $row['content_value'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Homepage | Inkingi Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        /* Specific Styles for this Editor */
        .editor-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
        .editor-card { 
            background: var(--navy-light); border: 1px solid rgba(255,255,255,0.05); 
            border-radius: 15px; padding: 25px; margin-bottom: 25px;
        }
        .editor-card h3 { color: var(--gold); border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; margin-bottom: 20px; }
        
        .img-preview { 
            width: 100%; height: 150px; object-fit: cover; border-radius: 8px; 
            margin-top: 10px; border: 2px dashed rgba(255,255,255,0.2); 
        }
        
        .row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; }
        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

        @media(max-width: 900px) { .editor-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="brand">INKINGI <span style="color:#fff;">CMS</span></div>
        <nav>
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="edit_home.php" class="nav-link active"><i class="fas fa-home"></i> Edit Home</a>
            <a href="programs.php" class="nav-link"><i class="fas fa-calendar-check"></i> Programs</a>
            <a href="../index.php" target="_blank" class="nav-link"><i class="fas fa-external-link-alt"></i> View Site</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Homepage Commander</h1>
            <?php if(isset($success)): ?>
                <div style="color:#64ffda; background:rgba(100,255,218,0.1); padding:10px 20px; border-radius:20px;">
                    <i class="fas fa-check-circle"></i> <?= $success ?>
                </div>
            <?php endif; ?>
        </div>

        <form method="POST" enctype="multipart/form-data" class="editor-grid">
            
            <div>
                <div class="editor-card">
                    <h3><i class="fas fa-star"></i> Hero Section</h3>
                    <div class="form-group">
                        <label class="form-label">Main Title</label>
                        <input type="text" name="hero_title" class="form-control" value="<?= htmlspecialchars($data['hero_title'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="hero_subtitle" class="form-control" value="<?= htmlspecialchars($data['hero_subtitle'] ?? '') ?>">
                    </div>
                    <div class="row-2">
                        <div class="form-group">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="hero_btn_text" class="form-control" value="<?= htmlspecialchars($data['hero_btn_text'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hero Background Image</label>
                            <input type="file" name="hero_bg" class="form-control">
                            <?php if(!empty($data['hero_bg'])): ?>
                                <img src="../<?= $data['hero_bg'] ?>" class="img-preview">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="editor-card">
                    <h3><i class="fas fa-info-circle"></i> About Section</h3>
                    <div class="form-group">
                        <label class="form-label">Section Title</label>
                        <input type="text" name="about_title" class="form-control" value="<?= htmlspecialchars($data['about_title'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">About Description</label>
                        <textarea name="about_text" class="form-control" rows="5"><?= htmlspecialchars($data['about_text'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">About Image</label>
                        <input type="file" name="about_image" class="form-control">
                        <?php if(!empty($data['about_image'])): ?>
                            <img src="../<?= $data['about_image'] ?>" class="img-preview">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div>
                <div class="editor-card">
                    <h3><i class="fas fa-chart-bar"></i> Impact Stats</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Stat 1 (Art Pieces)</label>
                        <div class="row-2">
                            <input type="text" name="stat_1_num" class="form-control" placeholder="Number" value="<?= $data['stat_1_num'] ?? '' ?>">
                            <input type="text" name="stat_1_label" class="form-control" placeholder="Label" value="<?= $data['stat_1_label'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stat 2 (Visitors)</label>
                        <div class="row-2">
                            <input type="text" name="stat_2_num" class="form-control" placeholder="Number" value="<?= $data['stat_2_num'] ?? '' ?>">
                            <input type="text" name="stat_2_label" class="form-control" placeholder="Label" value="<?= $data['stat_2_label'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stat 3 (Artists)</label>
                        <div class="row-2">
                            <input type="text" name="stat_3_num" class="form-control" placeholder="Number" value="<?= $data['stat_3_num'] ?? '' ?>">
                            <input type="text" name="stat_3_label" class="form-control" placeholder="Label" value="<?= $data['stat_3_label'] ?? '' ?>">
                        </div>
                    </div>
                </div>

                <div class="editor-card">
                    <h3><i class="fas fa-address-book"></i> Top Bar Info</h3>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="contact_phone" class="form-control" value="<?= $data['contact_phone'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="text" name="contact_email" class="form-control" value="<?= $data['contact_email'] ?? '' ?>">
                    </div>
                </div>

                <button type="submit" class="btn-save" style="width:100%; height:60px; font-size:1.2rem;">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>

        </form>
    </main>

</body>
</html>