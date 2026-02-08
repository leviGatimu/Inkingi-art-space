<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require '../includes/db_connect.php';

// Handle Form Logic
$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ADD POST
    if (isset($_POST['add_post'])) {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $category = trim($_POST['category'] ?? 'Events');
        $date = trim($_POST['date'] ?? date('Y-m-d'));
        $image_path = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/posts/';
            $uploadWebPath = 'uploads/posts/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $file = $_FILES['image'];
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            
            if (in_array($file['type'], $allowed)) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newName = 'post_' . time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                    $image_path = $uploadWebPath . $newName;
                }
            }
        }

        if (!empty($title) && !empty($content)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO posts (title, content, category, image, date) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$title, $content, $category, $image_path, $date]);
                $message = "Event published successfully!";
                $messageType = "success";
            } catch (Exception $e) {
                $message = "Database Error: " . $e->getMessage();
                $messageType = "error";
            }
        }
    }

    // DELETE POST
    if (isset($_POST['delete_post'])) {
        $id = (int)$_POST['delete_id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Item removed successfully.";
            $messageType = "success";
        } catch (Exception $e) {
            $message = "Error deleting item.";
            $messageType = "error";
        }
    }
}

// Fetch Posts
$posts = $pdo->query("SELECT * FROM posts ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inkingi Admin | Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin-style.css"> 
    <link rel="icon" type="image/png" href="../assets/images/logo.svg">
    
    <style>
        /* Exact overrides to match your Screenshot colors if admin-style.css varies slightly */
        :root {
            --bg-dark: #060818;       /* The deep navy background */
            --sidebar-bg: #0b101a;    /* Slightly lighter navy for sidebar */
            --card-bg: #101522;       /* Card background */
            --gold: #FDB913;          /* Inkingi Gold */
            --text-white: #ffffff;
            --text-muted: #8892b0;
            --border: #1e293b;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-white);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
        }

        /* Sidebar Styling (Matching Dashboard Code) */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            height: 100vh;
            padding: 20px;
            position: fixed;
            border-right: 1px solid rgba(255,255,255,0.05);
            display: flex;
            flex-direction: column;
        }

        .brand {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gold);
            margin-bottom: 40px;
            letter-spacing: 1px;
        }
        .brand span { color: white; }

        .nav-link {
            display: flex;
            align-items: center;
            color: var(--text-muted);
            text-decoration: none;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: 0.3s;
        }
        
        .nav-link i { width: 25px; }

        .nav-link:hover, .nav-link.active {
            background: rgba(253, 185, 19, 0.1); /* Gold tint */
            color: var(--gold);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 40px;
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        h1 { font-size: 2rem; margin: 0; }
        .sub-text { color: var(--text-muted); font-size: 0.9rem; margin-top: 5px; }

        /* Forms & Cards (Matching "Quick Actions" look) */
        .admin-card {
            background: var(--card-bg);
            border: 1px solid var(--gold); /* Gold border like dashboard cards */
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 40px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .full-width { grid-column: span 2; }

        label {
            display: block;
            color: var(--text-white);
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            background: #060818;
            border: 1px solid #2d3748;
            border-radius: 8px;
            color: white;
            font-family: inherit;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--gold);
        }

        /* Buttons (Matching Dashboard Buttons) */
        .btn-gold {
            background: var(--gold);
            color: #000;
            border: none;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }
        .btn-gold:hover { transform: translateY(-2px); opacity: 0.9; }

        .btn-back {
            background: rgba(255,255,255,0.05);
            color: var(--text-muted);
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: 0.3s;
        }
        .btn-back:hover { color: white; background: rgba(255,255,255,0.1); }

        /* List Items */
        .post-item {
            background: var(--card-bg);
            border: 1px solid #1e293b;
            padding: 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .post-info h4 { margin: 0 0 5px 0; font-size: 1.1rem; }
        .post-meta { font-size: 0.85rem; color: var(--text-muted); }
        .post-cat { color: var(--gold); font-weight: 600; margin-right: 10px; }

        .action-btn {
            background: transparent;
            border: none;
            color: #ef4444;
            cursor: pointer;
            font-size: 1rem;
            padding: 8px;
            transition: 0.3s;
        }
        .action-btn:hover { color: #ff0000; transform: scale(1.1); }

        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid transparent; }
        .alert.success { background: rgba(16, 185, 129, 0.1); color: #10b981; border-color: #10b981; }
        .alert.error { background: rgba(239, 68, 68, 0.1); color: #ef4444; border-color: #ef4444; }

    </style>
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
        
        <div class="header-flex">
            <div>
                <h1>Manage Events & Gallery</h1>
                <p class="sub-text">Add, edit, or remove content from the Events page.</p>
            </div>
            <a href="dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>

        <?php if ($message): ?>
            <div class="alert <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="admin-card">
            <h3 style="margin-bottom: 25px; color: white; font-weight:600;"><i class="fas fa-plus-circle" style="color: var(--gold);"></i> Create New Entry</h3>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="add_post" value="1">
                
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Summer Art Workshop" required>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control">
                            <option value="Events">Event</option>
                            <option value="Photos">Photo Gallery</option>
                            <option value="Exhibition">Exhibition</option>
                            <option value="Workshop">Workshop</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="form-group full-width">
                        <label>Description / Content</label>
                        <textarea name="content" class="form-control" rows="5" placeholder="Details about the event..."></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Upload Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>

                <div style="margin-top: 25px; text-align: right;">
                    <button type="submit" class="btn-gold">Publish Event</button>
                </div>
            </form>
        </div>

        <h3 style="margin-bottom: 20px; margin-top:40px;">Existing Entries</h3>

        <div class="posts-list">
            <?php if (empty($posts)): ?>
                <p style="color: var(--text-muted);">No events found.</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post-item">
                        <div class="post-info">
                            <h4><?= htmlspecialchars($post['title']) ?></h4>
                            <div class="post-meta">
                                <span class="post-cat"><?= htmlspecialchars($post['category']) ?></span> 
                                <span style="color: #666;">|</span> 
                                <?= date('M d, Y', strtotime($post['date'])) ?>
                            </div>
                        </div>
                        <div class="post-actions">
                            <form method="POST" onsubmit="return confirm('Delete this event?');" style="margin:0;">
                                <input type="hidden" name="delete_post" value="1">
                                <input type="hidden" name="delete_id" value="<?= $post['id'] ?>">
                                <button type="submit" class="action-btn" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>

</body>
</html>