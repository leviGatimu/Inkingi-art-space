<?php
// DB Connection
$host = 'localhost'; $db = 'inkingi_db'; $user = 'root'; $pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { die("DB Error"); }

// --- HANDLE POST (ADD & EDIT) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['program_id'] ?? ''; // Check if ID exists (Edit Mode)
    $title = $_POST['title'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $schedule = $_POST['schedule'];
    $description = $_POST['description'];
    
    // Image Handling
    $imagePath = $_POST['current_image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $target = "../assets/uploads/" . basename($_FILES['image']['name']);
        if(move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $imagePath = "assets/uploads/" . basename($_FILES['image']['name']);
        }
    }

    if ($id) {
        // UPDATE Existing
        $sql = "UPDATE programs SET title=?, category=?, price=?, schedule=?, description=?, image_path=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $category, $price, $schedule, $description, $imagePath, $id]);
    } else {
        // INSERT New
        $sql = "INSERT INTO programs (title, category, price, schedule, description, image_path) VALUES (?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $category, $price, $schedule, $description, $imagePath]);
    }
    
    header("Location: programs.php"); exit;
}

// --- HANDLE DELETE ---
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM programs WHERE id = ?")->execute([$_GET['delete']]);
    header("Location: programs.php"); exit;
}

$programs = $pdo->query("SELECT * FROM programs ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inkingi Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>

    <aside class="sidebar">
        <div class="admin-logo">Inkingi <span style="color:#fff;">Admin</span></div>
        <nav>
            <a href="programs.php" class="nav-item active"><i class="fas fa-layer-group"></i> Programs</a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="page-header">
            <div class="page-title">
                <h1>Manage Programs</h1>
                <p>Edit prices, schedules, and images for the website.</p>
            </div>
            <button class="btn-add" onclick="openDrawer()">
                <i class="fas fa-plus"></i> Add New
            </button>
        </header>

        <div class="programs-grid">
            <?php foreach($programs as $prog): ?>
            <div class="program-card">
                <img src="../<?= $prog['image_path'] ?>" class="card-img" style="height:150px; object-fit:cover;">
                <div class="card-body">
                    <span class="badge"><?= $prog['category'] ?></span>
                    <h3 class="card-title"><?= $prog['title'] ?></h3>
                    <div class="card-price"><?= $prog['price'] ?></div>
                </div>
                <div class="card-actions">
                    <div class="action-btn" onclick='editProgram(<?= json_encode($prog) ?>)'>
                        <i class="fas fa-pen"></i>
                    </div>
                    <a href="programs.php?delete=<?= $prog['id'] ?>" class="action-btn btn-delete" onclick="return confirm('Delete?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <div class="overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
    <div class="slide-panel" id="programDrawer">
        <div class="panel-header">
            <h2 id="drawerTitle" style="color:#fff;">Add Program</h2>
            <i class="fas fa-times btn-close" onclick="closeDrawer()"></i>
        </div>

        <form action="programs.php" method="POST" enctype="multipart/form-data" id="progForm">
            <input type="hidden" name="program_id" id="progId">
            <input type="hidden" name="current_image" id="currentImage">

            <div class="form-group">
                <label class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category" id="category" class="form-select">
                    <option value="Workshop">Workshop</option>
                    <option value="Event">Event</option>
                    <option value="Class">Class</option>
                </select>
            </div>

            <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label class="form-label">Price</label>
                    <input type="text" name="price" id="price" class="form-input">
                </div>
                <div>
                    <label class="form-label">Schedule</label>
                    <input type="text" name="schedule" id="schedule" class="form-input">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" id="description" class="form-textarea" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-input">
            </div>

            <button type="submit" class="btn-add" style="width: 100%; justify-content: center;">Save Program</button>
        </form>
    </div>

    <script>
        const drawer = document.getElementById('programDrawer');
        const overlay = document.getElementById('drawerOverlay');
        const form = document.getElementById('progForm');

        function openDrawer() {
            drawer.classList.add('open');
            overlay.classList.add('active');
            // Reset form for "Add" mode
            form.reset();
            document.getElementById('progId').value = '';
            document.getElementById('drawerTitle').innerText = 'Add Program';
        }

        function closeDrawer() {
            drawer.classList.remove('open');
            overlay.classList.remove('active');
        }

        // POPULATE FORM FOR EDITING
        function editProgram(data) {
            openDrawer();
            document.getElementById('drawerTitle').innerText = 'Edit Program';
            document.getElementById('progId').value = data.id;
            document.getElementById('title').value = data.title;
            document.getElementById('category').value = data.category;
            document.getElementById('price').value = data.price;
            document.getElementById('schedule').value = data.schedule;
            document.getElementById('description').value = data.description;
            document.getElementById('currentImage').value = data.image_path;
        }
    </script>
</body>
</html>