<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require '../includes/db_connect.php';

// --- Logic Handling ---
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

        // Image upload logic
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
                $message = "Entry published successfully!";
                $messageType = "success";
            } catch (Exception $e) {
                $message = "Database Error: " . $e->getMessage();
                $messageType = "error";
            }
        } else {
            $message = "Title and Content are required.";
            $messageType = "error";
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
    <title>Inkingi Admin | Events Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- DASHBOARD CSS (Provided) --- */
        :root {
            --navy-dark: #020c1b;
            --navy-light: #112240;
            --accent: #64ffda; /* Teal */
            --gold: #FDB913;
            --text: #ccd6f6;
            --border: 1px solid rgba(255,255,255,0.1);
            --red: #ff5f57;
        }

        * { box-sizing: border-box; outline: none; }
        body { margin: 0; font-family: 'Poppins', sans-serif; background: var(--navy-dark); color: var(--text); display: flex; height: 100vh; overflow: hidden; }

        /* SIDEBAR */
        .sidebar { width: 260px; background: var(--navy-light); border-right: var(--border); display: flex; flex-direction: column; padding: 30px; flex-shrink: 0;}
        .brand { font-family: 'Permanent Marker', cursive; font-size: 1.8rem; color: var(--gold); margin-bottom: 50px; }
        .nav-link { 
            display: flex; align-items: center; gap: 15px; padding: 15px; color: #8892b0; text-decoration: none; 
            border-radius: 10px; transition: 0.3s; margin-bottom: 5px; 
        }
        .nav-link:hover, .nav-link.active { background: rgba(253, 185, 19, 0.1); color: var(--gold); }
        .nav-link i { width: 20px; text-align: center; }

        /* MAIN AREA */
        .main-content { flex: 1; padding: 40px; overflow-y: auto; position: relative; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .header h1 { margin: 0; font-size: 2rem; color: #fff; }

        /* EDITOR FORM STYLE */
        .editor-container { background: var(--navy-light); padding: 40px; border-radius: 20px; border: var(--border); margin-bottom: 50px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .full-width { grid-column: span 2; }
        .form-group { margin-bottom: 25px; }
        .form-label { display: block; margin-bottom: 10px; color: var(--gold); font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .form-control {
            width: 100%; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);
            padding: 15px; color: #fff; border-radius: 8px; font-family: inherit; font-size: 1rem;
        }
        .form-control:focus { border-color: var(--gold); }
        
        .btn-save {
            background: var(--accent); color: var(--navy-dark); padding: 15px 40px; border: none;
            border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 1rem; transition: 0.3s;
            display: inline-flex; align-items: center; gap: 10px; text-decoration: none;
        }
        .btn-save:hover { box-shadow: 0 0 20px rgba(100, 255, 218, 0.4); transform: scale(1.02); }

        .btn-back {
            color: #8892b0; text-decoration: none; font-size: 0.9rem; transition: 0.3s; display: flex; align-items: center; gap: 8px;
        }
        .btn-back:hover { color: var(--accent); }

        /* POSTS GRID (Using Page Card Style) */
        .page-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
        .page-card {
            background: var(--navy-light); border: var(--border); border-radius: 16px; padding: 25px;
            transition: 0.3s; position: relative; overflow: hidden; display: flex; flex-direction: column;
        }
        .page-card:hover { transform: translateY(-5px); border-color: var(--accent); }
        
        .card-thumb {
            width: 100%; height: 180px; object-fit: cover; border-radius: 10px; margin-bottom: 15px;
            background: #0b162a;
        }
        .page-card h3 { color: #fff; margin: 0 0 5px 0; font-size: 1.2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .card-meta { color: var(--gold); font-size: 0.85rem; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; }
        
        .card-actions { margin-top: auto; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 15px; }
        
        .btn-delete {
            background: transparent; color: var(--red); border: 1px solid var(--red);
            padding: 8px 15px; border-radius: 5px; cursor: pointer; transition: 0.3s; font-size: 0.85rem;
        }
        .btn-delete:hover { background: var(--red); color: white; }

        /* ALERTS */
        .alert { padding: 15px; border-radius: 10px; margin-bottom: 30px; font-weight: 500; border: 1px solid transparent; }
        .alert.success { background: rgba(100, 255, 218, 0.1); color: var(--accent); border-color: var(--accent); }
        .alert.error { background: rgba(255, 95, 87, 0.1); color: var(--red); border-color: var(--red); }

        /* Form file input fix */
        input[type="file"]::file-selector-button {
            background: var(--navy-dark); color: var(--text); border: 1px solid var(--gold);
            padding: 8px 15px; border-radius: 5px; margin-right: 15px; cursor: pointer; transition: 0.3s;
        }
        input[type="file"]::file-selector-button:hover { background: var(--gold); color: var(--navy-dark); }

    </style>
</head>
<body>

   <aside class="sidebar">
        <div class="brand">INKINGI <span>CMS</span></div>
        <nav>
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="edit_footer.php" class="nav-link"><i class="fa-regular fa-calendar-check"></i></i> Edit Footer</a>
            <a href="admin_programs.php" class="nav-link"><i class="fa-solid fa-grip"></i></i> Edit programs</a>
            <a href="events_admin.php" class="nav-link active"><i class="fas fa-map-marker-alt"></i> Add event</a>
            <a href="../index.php" target="_blank" class="nav-link"><i class="fas fa-external-link-alt"></i> View Site</a>
            <a href="logout.php" class="nav-link" style="margin-top:auto; color:#ff5f57;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>


    <main class="main-content">
        
        <div class="header">
            <div>
                <h1>Manage Events & News</h1>
                <p style="color: #8892b0; margin-top: 5px;">Create and manage content for the Events & Photos page.</p>
            </div>
            <a href="dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>

        <?php if ($message): ?>
            <div class="alert <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="editor-container">
            <h2 style="color:white; margin-bottom:30px; font-size: 1.5rem;">Create New Entry</h2>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="add_post" value="1">
                
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">Event / News Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Summer Art Exhibition Opening" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control">
                            <option value="Events">Event</option>
                            <option value="Photos">Photo Gallery</option>
                            <option value="Exhibition">Exhibition</option>
                            <option value="Workshop">Workshop</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Description / Content</label>
                        <textarea name="content" class="form-control" rows="6" placeholder="Write the details about the event here..."></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Featured Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-paper-plane"></i> Publish Event
                    </button>
                </div>
            </form>
        </div>

        <h2 style="color: white; margin-bottom: 25px; font-size: 1.5rem;">Existing Entries</h2>
        
        <?php if (empty($posts)): ?>
            <div style="text-align: center; color: #8892b0; padding: 50px; background: rgba(255,255,255,0.05); border-radius: 15px;">
                <i class="fas fa-folder-open" style="font-size: 2rem; margin-bottom: 10px;"></i><br>
                No entries found.
            </div>
        <?php else: ?>
            <div class="page-grid">
                <?php foreach ($posts as $post): ?>
                    <div class="page-card">
                        <?php if ($post['image']): ?>
                            <img src="../<?= htmlspecialchars($post['image']) ?>" class="card-thumb" alt="Event Image">
                        <?php else: ?>
                            <div class="card-thumb" style="display:flex; align-items:center; justify-content:center; color:#555;">
                                <i class="fas fa-image fa-2x"></i>
                            </div>
                        <?php endif; ?>

                        <div class="card-meta">
                            <?= htmlspecialchars($post['category']) ?> &bull; <?= date('M d', strtotime($post['date'])) ?>
                        </div>
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        
                        <div class="card-actions">
                            <span style="color: #8892b0; font-size: 0.85rem;">ID: #<?= $post['id'] ?></span>
                            <form method="POST" onsubmit="return confirm('Permanently delete this item?');" style="margin:0;">
                                <input type="hidden" name="delete_post" value="1">
                                <input type="hidden" name="delete_id" value="<?= $post['id'] ?>">
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

</body>
</html>