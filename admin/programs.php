<?php
session_start();

// Security Check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Database
require '../includes/db_connect.php';

// Handle Add / Edit
$message = $messageType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $title       = trim($_POST['title'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    $schedule    = trim($_POST['schedule'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image_path  = trim($_POST['image_path'] ?? '');

    if (!empty($title) && !empty($category) && !empty($price)) {
        try {
            if ($_POST['action'] === 'update' && !empty($_POST['edit_id'])) {
                // Update
                $id = (int)$_POST['edit_id'];
                $stmt = $pdo->prepare("
                    UPDATE programs 
                    SET title = ?, category = ?, price = ?, schedule = ?, description = ?, image_path = ?
                    WHERE id = ?
                ");
                $stmt->execute([$title, $category, $price, $schedule, $description, $image_path, $id]);
                $message = "Program updated successfully!";
                $messageType = "success";
            } else if ($_POST['action'] === 'add') {
                // Insert
                $stmt = $pdo->prepare("
                    INSERT INTO programs (title, category, price, schedule, description, image_path)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$title, $category, $price, $schedule, $description, $image_path]);
                $message = "New program added successfully!";
                $messageType = "success";
            }
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
            $messageType = "error";
        }
    } else {
        $message = "Please fill in all required fields.";
        $messageType = "error";
    }
}

// Handle Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM programs WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Program deleted successfully!";
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Error deleting: " . $e->getMessage();
        $messageType = "error";
    }
}

// Fetch Programs
try {
    $stmt = $pdo->query("SELECT * FROM programs ORDER BY id DESC");
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $programs = [];
    $message = "Could not load programs: " . $e->getMessage();
    $messageType = "error";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inkingi Admin | Manage Programs</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- TinyMCE Free CDN (no API key needed) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
    <link rel="icon" type="image/png" href="../assets/images/logo.svg">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        :root {
            --bg: #0f0f1a;           /* Your requested dark navy background */
            --card: #161b22;
            --text: #c9d1d9;
            --accent: #58a6ff;
            --yellow: #FDB913;
            --green: #64ffda;
            --red: #ff5f57;
            --border: #30363d;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        .main-content {
            margin-left: 240px;
            padding: 40px 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        h1 {
            font-size: 2.2rem;
            color: white;
            font-weight: 600;
        }

        .btn-add {
            background: var(--green);
            color: black;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-add:hover {
            background: #4ae9c8;
            transform: translateY(-2px);
        }

        .message {
            padding: 14px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            font-weight: 500;
        }

        .message.success {
            background: rgba(100, 255, 218, 0.15);
            color: #64ffda;
            border-left: 4px solid #64ffda;
        }

        .message.error {
            background: rgba(255, 95, 87, 0.15);
            color: #ff5f57;
            border-left: 4px solid #ff5f57;
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        th, td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            background: #1a1f2e;
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background: rgba(88,166,255,0.08);
        }

        .program-image-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid var(--border);
        }

        .no-image {
            width: 60px;
            height: 60px;
            background: #0d1117;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 0.7rem;
            color: #444;
        }

        .action-btn {
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-edit {
            background: rgba(100, 255, 218, 0.15);
            color: #64ffda;
            border: 1px solid rgba(100, 255, 218, 0.3);
        }

        .btn-delete {
            background: rgba(255, 95, 87, 0.15);
            color: #ff5f57;
            border: 1px solid rgba(255, 95, 87, 0.3);
        }

        .action-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.85);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--card);
            border-radius: 12px;
            width: 90%;
            max-width: 720px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 35px;
            position: relative;
            border: 1px solid var(--border);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .modal-header h2 {
            color: white;
            margin: 0;
            font-size: 1.7rem;
        }

        .close-modal {
            background: none;
            border: none;
            color: #aaa;
            font-size: 2rem;
            cursor: pointer;
        }

        .close-modal:hover {
            color: white;
        }

        /* Form */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #8b949e;
            font-weight: 500;
        }

        .form-group.required label::after {
            content: " *";
            color: #ff5f57;
        }

        input[type="text"],
        input[type="url"],
        select,
        textarea {
            width: 100%;
            padding: 12px 16px;
            background: #0d1117;
            border: 1px solid var(--border);
            border-radius: 6px;
            color: white;
            font-family: inherit;
            font-size: 1rem;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(88,166,255,0.2);
        }

        textarea {
            min-height: 180px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 35px;
        }

        .btn {
            padding: 12px 28px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background: #5390e9;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #21262d;
            color: #c9d1d9;
        }

        .btn-secondary:hover {
            background: #30363d;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="brand">INKINGI <span>CMS</span></div>
        <nav>
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="programs.php" class="nav-link active"><i class="fas fa-paint-brush"></i> Programs</a>
            <a href="edit_footer.php" class="nav-link"><i class="fas fa-map-marker-alt"></i> Edit Footer</a>
            <a href="../index.php" target="_blank" class="nav-link"><i class="fas fa-external-link-alt"></i> View Site</a>
            <a href="logout.php" class="nav-link" style="margin-top:auto; color:#ff5f57;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        
        <div class="header">
            <div>
                <h1>Manage Programs</h1>
                <p style="color: #8892b0; font-size: 0.95rem; margin-top: 5px;">Create, edit or remove workshops, classes & cultural experiences</p>
            </div>
            <button class="btn-add" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Add New Program
            </button>
        </div>

        <?php if ($message): ?>
            <div class="message <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Programs Table -->
        <?php if (!empty($programs)): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Schedule</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($programs as $p): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($p['image_path'])): ?>
                                        <img src="<?= htmlspecialchars($p['image_path']) ?>" alt="Thumb" class="program-image-thumb">
                                    <?php else: ?>
                                        <div class="no-image">No Image</div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($p['title']) ?></td>
                                <td><?= htmlspecialchars($p['category']) ?></td>
                                <td><?= htmlspecialchars($p['price']) ?></td>
                                <td><?= htmlspecialchars($p['schedule'] ?: '—') ?></td>
                                <td>
                                    <button class="action-btn btn-edit" onclick="openEditModal(<?= $p['id'] ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <a href="?delete=<?= $p['id'] ?>" class="action-btn btn-delete" onclick="return confirm('Delete <?= htmlspecialchars(addslashes($p['title'])) ?>?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align:center; color:#8892b0; padding:100px 0; font-size:1.2rem;">
                No programs added yet.<br>
                <button class="btn-add" onclick="openAddModal()" style="margin-top:20px;">
                    <i class="fas fa-plus"></i> Add Your First Program
                </button>
            </div>
        <?php endif; ?>

    </main>

    <!-- Add / Edit Modal -->
    <div class="modal" id="programModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Program</h2>
                <button class="close-modal" onclick="closeModal()">×</button>
            </div>

            <form method="POST" id="programForm">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="edit_id" id="editId" value="">

                <div class="form-group required">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group required">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="">Select category...</option>
                        <option value="Class">Class</option>
                        <option value="Workshop">Workshop</option>
                        <option value="Experience">Experience</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group required">
                    <label for="price">Price (e.g. 20,000 Rwf)</label>
                    <input type="text" id="price" name="price" required>
                </div>

                <div class="form-group">
                    <label for="schedule">Schedule</label>
                    <input type="text" id="schedule" name="schedule" placeholder="e.g. Saturdays: 10am - 5pm">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"></textarea>
                </div>

                <div class="form-group">
                    <label for="image_path">Image URL (optional)</label>
                    <input type="url" id="image_path" name="image_path" placeholder="https://example.com/image.jpg">
                    <div id="imagePreview" style="margin-top:12px;"></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Program
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TinyMCE Free CDN Initialization (no API key needed) -->
    <script>
        tinymce.init({
            selector: '#description',
            height: 280,
            menubar: false,
            plugins: 'lists link image',
            toolbar: 'undo redo | bold italic | bullist numlist | link | removeformat',
            content_style: "body { font-family: 'Poppins', sans-serif; font-size:14px; color:#c9d1d9; background:#0d1117; }"
        });
    </script>

    <script>
        // Modal Controls
        const modal = document.getElementById('programModal');
        const modalTitle = document.getElementById('modalTitle');
        const formAction = document.getElementById('formAction');
        const editIdInput = document.getElementById('editId');
        const imagePreview = document.getElementById('imagePreview');

        function openAddModal() {
            modalTitle.textContent = "Add New Program";
            formAction.value = "add";
            editIdInput.value = "";
            document.getElementById('programForm').reset();
            imagePreview.innerHTML = "";
            modal.style.display = "flex";
            tinymce.get('description').setContent('');
        }

        function openEditModal(id) {
            fetch(`programs.php?get_program=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.id) {
                        modalTitle.textContent = "Edit Program";
                        formAction.value = "update";
                        editIdInput.value = data.id;
                        document.getElementById('title').value = data.title || '';
                        document.getElementById('category').value = data.category || '';
                        document.getElementById('price').value = data.price || '';
                        document.getElementById('schedule').value = data.schedule || '';
                        tinymce.get('description').setContent(data.description || '');
                        document.getElementById('image_path').value = data.image_path || '';

                        // Image preview
                        imagePreview.innerHTML = data.image_path
                            ? `<img src="${data.image_path}" alt="Preview" style="max-width:180px; border-radius:6px; margin-top:8px; border:1px solid var(--border);">`
                            : '';

                        modal.style.display = "flex";
                    }
                })
                .catch(err => console.error("Fetch error:", err));
        }

        function closeModal() {
            modal.style.display = "none";
        }

        // Close modal on outside click
        window.onclick = function(event) {
            if (event.target === modal) closeModal();
        }

        // Auto-dismiss message
        <?php if ($message): ?>
            setTimeout(() => {
                const msg = document.querySelector('.message');
                if (msg) {
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                }
            }, 5000);
        <?php endif; ?>
    </script>

    <!-- Fetch single program for edit -->
    <?php
    if (isset($_GET['get_program']) && is_numeric($_GET['get_program'])) {
        $id = (int)$_GET['get_program'];
        $stmt = $pdo->prepare("SELECT * FROM programs WHERE id = ?");
        $stmt->execute([$id]);
        $program = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($program ?: []);
        exit;
    }
    ?>

</body>
</html>