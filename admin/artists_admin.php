<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require '../includes/db_connect.php';

$message = "";
$messageType = "";

// 2. HANDLE FORM SUBMISSIONS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- ADD NEW ARTIST ---
    if (isset($_POST['add_artist'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $username = trim($_POST['username']);
        
        // Basic Validation
        if (empty($name) || empty($email) || empty($password) || empty($username)) {
            $message = "All fields are required.";
            $messageType = "error";
        } else {
            // Hash Password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $profile_pic = ''; // Default empty

            // Handle Profile Pic Upload
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
                $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                $newName = 'artist_' . time() . '.' . $ext;
                $target = '../uploads/artists/' . $newName;
                
                if (!is_dir('../uploads/artists/')) mkdir('../uploads/artists/', 0755, true);
                
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
                    $profile_pic = 'uploads/artists/' . $newName;
                }
            }

            try {
                $stmt = $pdo->prepare("INSERT INTO artists (name, email, username, password, profile_pic, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$name, $email, $username, $hashed_password, $profile_pic]);
                $message = "Artist added successfully! They can now login.";
                $messageType = "success";
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) { // Duplicate entry
                    $message = "Error: Username or Email already exists.";
                } else {
                    $message = "Database Error: " . $e->getMessage();
                }
                $messageType = "error";
            }
        }
    }

    // --- DELETE ARTIST ---
    if (isset($_POST['delete_artist'])) {
        $id = (int)$_POST['artist_id'];
        $stmt = $pdo->prepare("DELETE FROM artists WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Artist removed from roster.";
        $messageType = "success";
    }
}

// 3. FETCH ARTISTS
$stmt = $pdo->query("
    SELECT a.*, COUNT(w.id) as artwork_count 
    FROM artists a 
    LEFT JOIN artworks w ON a.id = w.artist_id 
    GROUP BY a.id 
    ORDER BY a.created_at DESC
");
$artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Artists | Inkingi Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
    
    <style>
        /* Exact Dashboard Colors Override */
        :root { --bg: #060818; --card: #101522; --gold: #FDB913; --text: #fff; --border: #1e293b; --red: #ff5f57; }
        body { background: var(--bg); color: var(--text); font-family: 'Poppins', sans-serif; display: flex; }
        
        /* Grid Layout for Artists */
        .artist-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 30px; }
        .artist-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px; transition: 0.3s; }
        .artist-card:hover { border-color: var(--gold); transform: translateY(-3px); }
        
        .artist-img { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid var(--gold); }
        .artist-info h3 { margin: 0 0 5px 0; font-size: 1.1rem; color: #fff; }
        .artist-info p { margin: 0; color: #8892b0; font-size: 0.85rem; }
        .artist-stats { font-size: 0.8rem; color: var(--gold); margin-top: 5px; font-weight: 600; }
        
        .delete-btn { margin-left: auto; background: rgba(255, 95, 87, 0.1); color: var(--red); border: none; width: 35px; height: 35px; border-radius: 8px; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; }
        .delete-btn:hover { background: var(--red); color: white; }

        /* Form Styles matching edit_footer */
        .editor-container { background: var(--card); padding: 30px; border-radius: 12px; border: 1px solid var(--border); margin-bottom: 40px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        label { color: #8892b0; font-size: 0.9rem; margin-bottom: 5px; display: block; }
        input { background: #060818; border: 1px solid var(--border); color: #fff; padding: 12px; border-radius: 8px; width: 100%; outline: none; }
        input:focus { border-color: var(--gold); }
        
        .btn-gold { background: var(--gold); color: #000; padding: 12px 25px; border: none; border-radius: 30px; font-weight: 600; cursor: pointer; width: 100%; margin-top: 20px; transition:0.3s;}
        .btn-gold:hover { opacity: 0.9; transform: translateY(-2px); }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="brand">INKINGI <span>CMS</span></div>
        <nav>
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="artists_admin.php" class="nav-link active"><i class="fas fa-users"></i> Artists</a>
            <a href="edit_footer.php" class="nav-link"><i class="fa-regular fa-calendar-check"></i> Edit Footer</a>
            <a href="admin_programs.php" class="nav-link"><i class="fa-solid fa-grip"></i> Edit programs</a>
            <a href="events_admin.php" class="nav-link"><i class="fas fa-map-marker-alt"></i> Add event</a>
            <a href="../index.php" target="_blank" class="nav-link"><i class="fas fa-external-link-alt"></i> View Site</a>
            <a href="logout.php" class="nav-link" style="margin-top:auto; color:#ff5f57;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        
        <div class="header">
            <div>
                <h1>Manage Artists</h1>
                <p style="color:#8892b0; margin-top:5px;">Add new talent to the platform or manage the current roster.</p>
            </div>
            
            <?php if($message): ?>
                <div style="color: <?= $messageType=='success' ? '#64ffda' : '#ff5f57' ?>; background: rgba(<?= $messageType=='success' ? '100,255,218' : '255,95,87' ?>, 0.1); padding: 10px 20px; border-radius: 20px; font-size: 0.9rem;">
                    <i class="fas <?= $messageType=='success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i> <?= $message ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="editor-container">
            <h3 style="color:#fff; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                <i class="fas fa-user-plus" style="color:var(--gold);"></i> Register New Artist
            </h3>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="add_artist" value="1">
                
                <div class="form-grid">
                    <div>
                        <label>Artist Full Name</label>
                        <input type="text" name="name" placeholder="e.g. Keza Amata" required>
                    </div>
                    <div>
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="artist@inkingi.com" required>
                    </div>
                </div>

                <div class="form-grid" style="margin-top: 20px;">
                    <div>
                        <label>Login Username</label>
                        <input type="text" name="username" placeholder="User123" required>
                    </div>
                    <div>
                        <label>Login Password</label>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <label>Profile Picture</label>
                    <input type="file" name="profile_pic" accept="image/*" style="padding: 10px;">
                </div>

                <button type="submit" class="btn-gold">Create Artist Account</button>
            </form>
        </div>

        <h3 style="color: #fff; margin-top: 40px;">Current Roster (<?= count($artists) ?>)</h3>
        
        <div class="artist-grid">
            <?php if (empty($artists)): ?>
                <p style="color:#8892b0; padding:20px;">No artists found.</p>
            <?php else: ?>
                <?php foreach ($artists as $artist): ?>
                    <div class="artist-card">
                        <?php if ($artist['profile_pic']): ?>
                            <img src="../<?= htmlspecialchars($artist['profile_pic']) ?>" class="artist-img">
                        <?php else: ?>
                            <div class="artist-img" style="display:flex; align-items:center; justify-content:center; background:#222; color:#555;">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>

                        <div class="artist-info">
                            <h3><?= htmlspecialchars($artist['name']) ?></h3>
                            <p>@<?= htmlspecialchars($artist['username']) ?></p>
                            <div class="artist-stats">
                                <i class="fas fa-palette"></i> <?= $artist['artwork_count'] ?> Artworks
                            </div>
                        </div>

                        <form method="POST" onsubmit="return confirm('Delete this artist? This will remove all their artworks too.');" style="margin:0;">
                            <input type="hidden" name="delete_artist" value="1">
                            <input type="hidden" name="artist_id" value="<?= $artist['id'] ?>">
                            <button type="submit" class="delete-btn" title="Remove Artist">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>

</body>
</html>