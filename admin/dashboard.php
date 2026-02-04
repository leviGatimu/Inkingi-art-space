<?php
session_start();

// 1. Security Check: Kick out if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// 2. Include Database
require '../includes/db_connect.php'; 

// 3. Initialize Stats Variables
$pageCount = 0;
$progCount = 0;
$visitorCount = 0;
$adminMsg = "Welcome to the Command Center";

try {
    // A. Count Pages
    $pageCount = $pdo->query("SELECT COUNT(*) FROM pages")->fetchColumn();
    
    // B. Count Programs
    $progCount = $pdo->query("SELECT COUNT(*) FROM programs")->fetchColumn();

    // C. Count Unique Visitors (Current Month)
    $currentMonth = date('Y-m'); // Gets "2026-02"
    // We use LIKE to match any day in this month
    $visitorCount = $pdo->query("SELECT COUNT(*) FROM site_visits WHERE visit_date LIKE '$currentMonth%'")->fetchColumn();

} catch (Exception $e) {
    // Graceful fallback if tables don't exist yet
    $adminMsg = "⚠️ Database Sync Required. Visitor tracking inactive.";
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
</head>
<body>

    <aside class="sidebar">
        <div class="brand">INKINGI <span style="color:#fff;">CMS</span></div>
        <nav>
            <a href="dashboard.php" class="nav-link active"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="page_edit.php" class="nav-link"><i class="fas fa-edit"></i> Edit Pages</a>
            <a href="programs.php" class="nav-link"><i class="fas fa-calendar-check"></i> Programs</a>
            <a href="../index.php" target="_blank" class="nav-link"><i class="fas fa-external-link-alt"></i> View Site</a>
            <a href="logout.php" class="nav-link" style="margin-top:auto; color:#ff5f57;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        
        <div class="header">
            <div>
                <h1>Dashboard Overview</h1>
                <p style="color: #8892b0; margin-top:5px; font-size:0.9rem;"><?= $adminMsg ?></p>
            </div>
            <div style="background: rgba(100, 255, 218, 0.1); color: #64ffda; padding: 8px 20px; border-radius: 30px; font-weight: 600; font-size: 0.85rem; border: 1px solid rgba(100, 255, 218, 0.2);">
                <i class="fas fa-wifi" style="margin-right: 8px;"></i> Live Data
            </div>
        </div>

        <div class="stats-grid">
            
            <div class="stat-card">
                <div style="background: rgba(253, 185, 19, 0.1); padding: 15px; border-radius: 12px;">
                    <i class="fas fa-file-alt stat-icon" style="color: #FDB913; font-size: 1.5rem;"></i>
                </div>
                <div class="stat-info">
                    <h3><?= $pageCount ?></h3>
                    <p>Active Pages</p>
                </div>
            </div>

            <div class="stat-card">
                <div style="background: rgba(100, 255, 218, 0.1); padding: 15px; border-radius: 12px;">
                    <i class="fas fa-layer-group stat-icon" style="color: #64ffda; font-size: 1.5rem;"></i>
                </div>
                <div class="stat-info">
                    <h3><?= $progCount ?></h3>
                    <p>Programs Listed</p>
                </div>
            </div>

            <div class="stat-card">
                <div style="background: rgba(255, 95, 87, 0.1); padding: 15px; border-radius: 12px;">
                    <i class="fas fa-eye stat-icon" style="color: #ff5f57; font-size: 1.5rem;"></i>
                </div>
                <div class="stat-info">
                    <h3><?= $visitorCount ?></h3>
                    <p>Unique Visitors (This Month)</p>
                </div>
            </div>

        </div>

        <h2 style="margin-top: 50px; color: white; margin-bottom: 25px; font-size: 1.5rem;">Quick Actions</h2>
        
        <div class="page-grid">
            <div class="page-card">
                <div style="margin-bottom: 20px;">
                    <i class="fas fa-pen-nib" style="font-size: 2rem; color: #FDB913;"></i>
                </div>
                <h3>Edit Site Content</h3>
                <p style="color: #8892b0; margin-bottom: 20px; font-size: 0.9rem;">Update headlines, hero text, and descriptions across the website.</p>
                <a href="page_edit.php" class="btn-edit">Open Editor</a>
            </div>

            <div class="page-card">
                <div style="margin-bottom: 20px;">
                    <i class="fas fa-calendar-plus" style="font-size: 2rem; color: #64ffda;"></i>
                </div>
                <h3>Manage Programs</h3>
                <p style="color: #8892b0; margin-bottom: 20px; font-size: 0.9rem;">Add new workshops, change prices, or update class schedules.</p>
                <a href="programs.php" class="btn-edit">Manage Programs</a>
            </div>
        </div>

    </main>

</body>
</html>