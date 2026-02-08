<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// 2. Include Database
require '../includes/db_connect.php'; 

// 3. Initialize Stats
$pageCount = 0;
$progCount = 0;
$visitorCount = 0;
$adminMsg = "Welcome to the Command Center";

try {
    // Stats logic (We keep this so the top cards still look cool)
    $pageCount = $pdo->query("SELECT COUNT(*) FROM pages")->fetchColumn();
    $progCount = $pdo->query("SELECT COUNT(*) FROM programs")->fetchColumn();
    $currentMonth = date('Y-m'); 
    $visitorCount = $pdo->query("SELECT COUNT(*) FROM site_visits WHERE visit_date LIKE '$currentMonth%'")->fetchColumn();
} catch (Exception $e) {
    $adminMsg = "⚠️ Database Sync Required.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inkingi Admin | Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
     <link rel="icon" type="image/png" href="../assets/images/logo.svg">
</head>
<body>

    <aside class="sidebar">
        <div class="brand">INKINGI <span>CMS</span></div>
        <nav>
            <a href="dashboard.php" class="nav-link active"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="edit_footer.php" class="nav-link"><i class="fa-regular fa-calendar-check"></i></i> Edit Footer</a>
            <a href="admin_programs.php" class="nav-link"><i class="fa-solid fa-grip"></i></i> Edit programs</a>
            <a href="events_admin.php" class="nav-link"><i class="fas fa-map-marker-alt"></i> Add event</a>
            <a href="../index.php" target="_blank" class="nav-link"><i class="fas fa-external-link-alt"></i> View Site</a>
            <a href="logout.php" class="nav-link" style="margin-top:auto; color:#ff5f57;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        
        <div class="header">
            <div>
                <h1>Dashboard Overview</h1>
                <p style="color: #8892b0; font-size: 0.9rem; margin-top: 5px;"><?= $adminMsg ?></p>
            </div>
            <div style="background: rgba(100, 255, 218, 0.1); color: #64ffda; padding: 8px 20px; border-radius: 30px; font-weight: 600; font-size: 0.85rem; border: 1px solid rgba(100, 255, 218, 0.2);">
                <i class="fas fa-wifi" style="margin-right: 8px;"></i> System Online
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div style="background: rgba(253, 185, 19, 0.1); padding: 15px; border-radius: 12px;">
                    <i class="fas fa-file-alt" style="color: #FDB913; font-size: 1.5rem;"></i>
                </div>
                <div class="stat-info">
                    <h3><?= $pageCount ?></h3>
                    <p>Pages</p>
                </div>
            </div>

            <div class="stat-card">
                <div style="background: rgba(100, 255, 218, 0.1); padding: 15px; border-radius: 12px;">
                    <i class="fas fa-layer-group" style="color: #64ffda; font-size: 1.5rem;"></i>
                </div>
                <div class="stat-info">
                    <h3><?= $progCount ?></h3>
                    <p>Programs</p>
                </div>
            </div>

            <div class="stat-card">
                <div style="background: rgba(255, 95, 87, 0.1); padding: 15px; border-radius: 12px;">
                    <i class="fas fa-eye" style="color: #ff5f57; font-size: 1.5rem;"></i>
                </div>
                <div class="stat-info">
                    <h3><?= $visitorCount ?></h3>
                    <p>Visitors</p>
                </div>
            </div>
        </div>

        <h2 style="margin-top: 50px; color: white; margin-bottom: 25px; font-size: 1.5rem;">Quick Actions</h2>
        
        <div class="page-grid">
            
            <div class="page-card" style="border-color: #FDB913;">
                <div style="margin-bottom: 20px;">
                    <i class="fas fa-map-marker-alt" style="font-size: 2.5rem; color: #FDB913;"></i>
                </div>
                <h3>Footer & Contact</h3>
                <p style="color: #8892b0; margin-bottom: 20px; font-size: 0.9rem;">
                    Update phone numbers, location, emails, and social media links.
                </p>
                <a href="edit_footer.php" class="btn-edit" style="width: 100%; text-align: center;">Edit Footer</a>
            </div>

            <div class="page-card" style="border-color: #FDB913;">
                <div style="margin-bottom: 20px;">
                    <i class="fas fa-paint-brush" style="font-size: 2.5rem; color: #FDB913;"></i>
                </div>
                <h3>Manage Programs</h3>
                <p style="color: #8892b0; margin-bottom: 20px; font-size: 0.9rem;">
                    Add, edit, or delete programs, workshops, and experiences.
                </p>
                <a href="programs.php" class="btn-edit" style="width: 100%; text-align: center;">Manage Programs</a>
            </div>
             <div class="page-card" style="border-color: #FDB913;">
                <div style="margin-bottom: 20px;">
                 
                    <i class="fa-regular fa-calendar-check" style="font-size: 2.5rem; color: #FDB913;"></i>
                </div>
                <h3>Manage Programs</h3>
                <p style="color: #8892b0; margin-bottom: 20px; font-size: 0.9rem;">
                    Add, edit, or delete events, workshops, and news.
                </p>
                <a href="events_admin.php" class="btn-edit" style="width: 100%; text-align: center;">Manage events</a>
            </div>

        </div>

    </main>

</body>
</html>