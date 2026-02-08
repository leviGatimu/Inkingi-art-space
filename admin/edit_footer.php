<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit; }
require '../includes/db_connect.php';

// 1. HANDLE SAVE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        $stmt = $pdo->prepare("UPDATE content_blocks SET content_value = ? WHERE section_key = ?");
        $stmt->execute([$value, $key]);
    }
    $success = "Footer updated successfully!";
}

// 2. FETCH DATA
$stmt = $pdo->query("SELECT section_key, content_value FROM content_blocks");
$data = [];
while ($row = $stmt->fetch()) {
    $data[$row['section_key']] = $row['content_value'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Footer | Inkingi Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>

<aside class="sidebar">
        <div class="brand">INKINGI <span>CMS</span></div>
        <nav>
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="edit_footer.php" class="nav-link active"><i class="fa-regular fa-calendar-check"></i></i> Edit Footer</a>
            <a href="admin_programs.php" class="nav-link"><i class="fa-solid fa-grip"></i></i> Edit programs</a>
            <a href="events_admin.php" class="nav-link"><i class="fas fa-map-marker-alt"></i> Add event</a>
            <a href="../index.php" target="_blank" class="nav-link"><i class="fas fa-external-link-alt"></i> View Site</a>
            <a href="logout.php" class="nav-link" style="margin-top:auto; color:#ff5f57;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Footer & Contact Info</h1>
            <?php if(isset($success)): ?>
                <div style="color:#64ffda; background:rgba(100,255,218,0.1); padding:10px 20px; border-radius:20px;">
                    <i class="fas fa-check-circle"></i> <?= $success ?>
                </div>
            <?php endif; ?>
        </div>

        <form method="POST" class="editor-container" style="max-width: 800px;">
            
            <div class="form-group">
                <label class="form-label">Footer Description</label>
                <textarea name="footer_about" class="form-control" rows="3"><?= htmlspecialchars($data['footer_about'] ?? '') ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="footer_phone" class="form-control" value="<?= htmlspecialchars($data['footer_phone'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="text" name="footer_email" class="form-control" value="<?= htmlspecialchars($data['footer_email'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Physical Location</label>
                <input type="text" name="footer_location" class="form-control" value="<?= htmlspecialchars($data['footer_location'] ?? '') ?>">
            </div>

            <hr style="border-color: rgba(255,255,255,0.1); margin: 30px 0;">
            <h3 style="color: #fff; margin-bottom: 20px;">Social Media Links</h3>

            <div class="form-group">
                <label class="form-label"><i class="fab fa-instagram"></i> Instagram URL</label>
                <input type="text" name="social_instagram" class="form-control" value="<?= htmlspecialchars($data['social_instagram'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fab fa-twitter"></i> Twitter (X) URL</label>
                <input type="text" name="social_twitter" class="form-control" value="<?= htmlspecialchars($data['social_twitter'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fab fa-facebook"></i> Facebook URL</label>
                <input type="text" name="social_facebook" class="form-control" value="<?= htmlspecialchars($data['social_facebook'] ?? '') ?>">
            </div>

            <button type="submit" class="btn-save" style="width:100%; margin-top: 20px;">Save Contact Info</button>
        </form>
    </main>

</body>
</html>