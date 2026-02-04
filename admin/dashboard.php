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
    $pageCount = $pdo->query("SELECT COUNT(*) FROM pages")->fetchColumn();
    $progCount = $pdo->query("SELECT COUNT(*) FROM programs")->fetchColumn();
    
    $currentMonth = date('Y-m'); 
    $visitorCount = $pdo->query("SELECT COUNT(*) FROM site_visits WHERE visit_date LIKE '$currentMonth%'")->fetchColumn();

} catch (Exception $e) {
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
    
    <style>
        :root {
            --navy: #0a192f;
            --navy-light: #112240;
            --gold: #FDB913;
            --green: #64ffda;
            --red: #ff5f57;
            --text-main: #ccd6f6;
            --text-muted: #8892b0;
            --glass: rgba(17, 34, 64, 0.7);
            --border: 1px solid rgba(255, 255, 255, 0.1);
        }

        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Poppins', sans-serif; background: var(--navy); color: var(--text-main); display: flex; height: 100vh; overflow: hidden; }

        /* SIDEBAR */
        .sidebar { width: 260px; background: var(--navy-light); border-right: var(--border); padding: 30px; display: flex; flex-direction: column; }
        .brand { font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 50px; letter-spacing: 1px; }
        .brand span { color: var(--gold); }
        
        .nav-link { 
            display: flex; align-items: center; padding: 15px; color: var(--text-muted); 
            text-decoration: none; margin-bottom: 10px; border-radius: 10px; transition: 0.3s; 
        }
        .nav-link:hover, .nav-link.active { background: rgba(253, 185, 19, 0.1); color: var(--gold); transform: translateX(5px); }
        .nav-link i { width: 25px; }

        /* MAIN CONTENT */
        .main-content { flex: 1; padding: 40px; overflow-y: auto; }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .header h1 { font-size: 2rem; color: #fff; margin: 0; }
        .status-badge { 
            background: rgba(100, 255, 218, 0.1); color: var(--green); padding: 8px 20px; 
            border-radius: 30px; font-size: 0.85rem; border: 1px solid rgba(100, 255, 218, 0.2); 
        }

        /* STATS CARDS */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 50px; }
        
        .stat-card {
            background: var(--navy-light); border: var(--border); padding: 25px; border-radius: 16px;
            display: flex; align-items: center; gap: 20px; transition: 0.3s; position: relative; overflow: hidden;
        }
        .stat-card:hover { transform: translateY(-5px); border-color: var(--gold); box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5); }
        
        .icon-box {
            width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
        }
        .icon-gold { background: rgba(253, 185, 19, 0.1); color: var(--gold); }
        .icon-green { background: rgba(100, 255, 218, 0.1); color: var(--green); }
        .icon-red { background: rgba(255, 95, 87, 0.1); color: var(--red); }

        .stat-info h3 { font-size: 2rem; margin: 0; color: #fff; font-weight: 700; }
        .stat-info p { margin: 0; color: var(--text-muted); font-size: 0.9rem; }

        /* QUICK ACTIONS */
        .section-title { font-size: 1.2rem; color: #fff; margin-bottom: 20px; border-left: 4px solid var(--gold); padding-left: 15px; }
        
        .actions-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; }
        
        .action-card {
            background: rgba(255,255,255,0.02); border: var(--border); padding: 30px; border-radius: 16px;
            transition: 0.3s; cursor: pointer; text-decoration: none; display: block;
        }
        .action-card:hover { background: var(--navy-light); border-color: var(--gold); }
        
        .action-icon { font-size: 2rem; color: var(--text-muted); margin-bottom: 20px; transition: 0.3s; }
        .action-card:hover .action-icon { color: var(--gold); transform: scale(1.1); }
        
        .action-card h3 { color: #fff; margin: 0 0 10px 0; font-size: 1.1rem; }
        .action-card p { color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; margin: 0; }

    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="brand">INKINGI <span>ADMIN</span></div>
        <nav>
            <a href="dashboard.php" class="nav-link active"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="edit_home.php" class="nav-link"><i class="fas fa-home"></i> Edit Home</a>
            <a href="programs.php" class="nav-link"><i class="fas fa-calendar-check"></i> Programs</a>
            <a href="../index.php" target="_blank" class="nav-link"><i class="fas fa-external-link-alt"></i> View Site</a>
            <a href="logout.php" class="nav-link" style="margin-top:auto; color:var(--red);"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        
        <div class="header">
            <div>
                <h1>Dashboard Overview</h1>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 5px;"><?= $adminMsg ?></p>
            </div>
            <div class="status-badge">
                <i class="fas fa-wifi"></i> System Online
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon-box icon-gold"><i class="fas fa-file-alt"></i></div>
                <div class="stat-info">
                    <h3><?= $pageCount ?></h3>
                    <p>Active Pages</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="icon-box icon-green"><i class="fas fa-layer-group"></i></div>
                <div class="stat-info">
                    <h3><?= $progCount ?></h3>
                    <p>Programs Listed</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="icon-box icon-red"><i class="fas fa-eye"></i></div>
                <div class="stat-info">
                    <h3><?= $visitorCount ?></h3>
                    <p>Unique Visitors (Month)</p>
                </div>
            </div>
        </div>

        <h2 class="section-title">Quick Actions</h2>
        
        <div class="actions-grid">
            
            <a href="edit_home.php" class="action-card">
                <i class="fas fa-pen-nib action-icon"></i>
                <h3>Edit Homepage</h3>
                <p>Update the hero text, background images, and welcome message seen by visitors.</p>
            </a>

            <a href="programs.php" class="action-card">
                <i class="fas fa-calendar-plus action-icon"></i>
                <h3>Manage Programs</h3>
                <p>Add new workshops, update pricing for classes, or change event schedules.</p>
            </a>

            <a href="#" class="action-card">
                <i class="fas fa-images action-icon"></i>
                <h3>Gallery Manager</h3>
                <p>Upload new artwork photos or remove old exhibition images.</p>
            </a>

        </div>

    </main>

</body>
</html>