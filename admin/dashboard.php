<?php
require '../includes/db_connect.php'; // Correct path to your DB file

// Fetch Stats for Dashboard
$pageCount = $pdo->query("SELECT COUNT(*) FROM pages")->fetchColumn();
$progCount = $pdo->query("SELECT COUNT(*) FROM programs")->fetchColumn();
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
        </nav>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Dashboard Overview</h1>
            <div style="color: #8892b0;">Welcome back, Admin</div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-file-alt stat-icon"></i>
                <div class="stat-info">
                    <h3><?= $pageCount ?></h3>
                    <p>Active Pages</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-layer-group stat-icon" style="color: #64ffda;"></i>
                <div class="stat-info">
                    <h3><?= $progCount ?></h3>
                    <p>Programs Listed</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-users stat-icon" style="color: #ff5f57;"></i>
                <div class="stat-info">
                    <h3>1.2k</h3>
                    <p>Monthly Views</p>
                </div>
            </div>
        </div>

        <h2 style="margin-top: 50px; color: white;">Quick Actions</h2>
        <div class="page-grid">
            <div class="page-card">
                <h3>Homepage Content</h3>
                <p style="color: #8892b0; margin-bottom: 20px;">Edit the hero section, intro text, and main images.</p>
                <a href="page_edit.php?page=home" class="btn-edit">Edit Home</a>
            </div>
            <div class="page-card">
                <h3>Programs List</h3>
                <p style="color: #8892b0; margin-bottom: 20px;">Manage workshops, classes, and prices.</p>
                <a href="programs.php" class="btn-edit">Manage Programs</a>
            </div>
        </div>
    </main>

</body>
</html>