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

    $errors = [];

    if (empty($title))       $errors[] = "Title is required.";
    if (empty($category))    $errors[] = "Category is required.";
    if (empty($price))       $errors[] = "Price is required.";

    if (empty($errors)) {
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
            $message = "Database error: " . $e->getMessage();
            $messageType = "error";
        }
    } else {
        $message = implode("<br>", $errors);
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
        $message = "Error deleting program: " . $e->getMessage();
        $messageType = "error";
    }
    // Redirect to clean URL
    header("Location: programs.php?msg=" . urlencode($message) . "&type=" . $messageType);
    exit;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
    <link rel="icon" type="image/png" href="../assets/images/logo.svg">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        :root {
            --bg: #0f0f1a;
            --card: #161b22;
            --text: #c9d1d9;
            --accent: #58a6ff;
            --yellow: #FDB913;
            --green: #64ffda;
            --red: #ff5f57;
            --border: #30363d;
            --input-bg: #0d1117;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            margin: 0;
        }

        .main-content {
            margin-left: 240px;
            padding: 40px 32px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 48px;
        }

        h1 {
            font-size: 2.4rem;
            color: white;
            margin: 0;
            font-weight: 600;
        }

        .btn-add {
            background: var(--green);
            color: #0f0f1a;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add:hover {
            background: #4ae9c8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(100,255,218,0.25);
        }

        .message {
            padding: 16px 24px;
            border-radius: 10px;
            margin-bottom: 32px;
            font-weight: 500;
            line-height: 1.5;
        }

        .message.success {
            background: rgba(100, 255, 218, 0.12);
            color: #64ffda;
            border-left: 5px solid #64ffda;
        }

        .message.error {
            background: rgba(255, 95, 87, 0.12);
            color: #ff5f57;
            border-left: 5px solid #ff5f57;
        }

        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        th, td {
            padding: 16px 20px;
            text-align: left;
        }

        th {
            background: #1a1f2e;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.4px;
        }

        tr {
            border-bottom: 1px solid var(--border);
        }

        tr:hover {
            background: rgba(88,166,255,0.07);
        }

        .program-image-thumb {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .no-image {
            width: 64px;
            height: 64px;
            background: #0d1117;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: #555;
            border: 1px solid var(--border);
        }

        .action-btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            margin-right: 6px;
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
            opacity: 0.92;
            transform: translateY(-1px);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.88);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: var(--card);
            border-radius: 16px;
            width: 92%;
            max-width: 760px;
            max-height: 92vh;
            overflow-y: auto;
            padding: 40px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 36px;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.9rem;
            color: white;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 2.4rem;
            color: #777;
            cursor: pointer;
            transition: color 0.2s;
        }

        .close-modal:hover {
            color: white;
        }

        .form-group {
            margin-bottom: 28px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #a0a8b8;
            font-weight: 500;
            font-size: 0.98rem;
        }

        .form-group.required label::after {
            content: " *";
            color: var(--red);
        }

        input[type="text"],
        input[type="url"],
        select,
        textarea {
            width: 100%;
            padding: 14px 18px;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: white;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(88,166,255,0.18);
        }

        textarea {
            min-height: 160px;
            resize: vertical;
        }

        #imagePreview img {
            max-width: 220px;
            border-radius: 10px;
            margin-top: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .form-actions {
            display: flex;
            gap: 16px;
            margin-top: 40px;
            justify-content: flex-end;
        }

        .btn {
            padding: 13px 32px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.98rem;
            transition: all 0.25s;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background: #4a90e2;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #21262d;
            color: #c9d1d9;
        }

        .btn-secondary:hover {
            background: #2d333b;
        }
    </style>
</head>
<body>

    <!-- Sidebar (assuming it's the same as before) -->
   <aside class="sidebar">
        <div class="brand">INKINGI <span>CMS</span></div>
        <nav>
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="edit_footer.php" class="nav-link"><i class="fa-regular fa-calendar-check"></i></i> Edit Footer</a>
            <a href="admin_programs.php" class="nav-link active"><i class="fa-solid fa-grip"></i></i> Edit programs</a>
            <a href="events_admin.php" class="nav-link "><i class="fas fa-map-marker-alt"></i> Add event</a>
            <a href="../index.php" target="_blank" class="nav-link"><i class="fas fa-external-link-alt"></i> View Site</a>
            <a href="logout.php" class="nav-link" style="margin-top:auto; color:#ff5f57;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        
        <div class="header">
            <div>
                <h1>Manage Programs</h1>
                <p style="color:#8892b0; margin-top:8px; font-size:0.98rem;">
                    Create, edit and organize workshops, classes & cultural experiences
                </p>
            </div>
            <button class="btn-add" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Add New Program
            </button>
        </div>

        <?php if ($message): ?>
            <div class="message <?= $messageType ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

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
                                        <img src="<?= htmlspecialchars($p['image_path']) ?>" alt="Program" class="program-image-thumb">
                                    <?php else: ?>
                                        <div class="no-image">No Image</div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($p['title']) ?></td>
                                <td><?= htmlspecialchars($p['category']) ?></td>
                                <td><?= htmlspecialchars($p['price']) ?></td>
                                <td><?= htmlspecialchars($p['schedule'] ?: '—') ?></td>
                                <td>
                                    <button class="action-btn btn-edit" 
                                            onclick="openEditModal(<?= $p['id'] ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <a href="?delete=<?= $p['id'] ?>" 
                                       class="action-btn btn-delete"
                                       onclick="return confirm('Delete « <?= htmlspecialchars(addslashes($p['title'])) ?> » ? This cannot be undone.')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align:center; padding:120px 0; color:#8892b0; font-size:1.25rem;">
                No programs added yet.<br><br>
                <button class="btn-add" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add Your First Program
                </button>
            </div>
        <?php endif; ?>

    </main>

    <!-- Modal -->
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
                    <label for="title">Program Title</label>
                    <input type="text" id="title" name="title" required placeholder="e.g. Batik Painting Workshop">
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
                    <label for="price">Price</label>
                    <input type="text" id="price" name="price" required placeholder="e.g. 25,000 RWF or $20">
                </div>

                <div class="form-group">
                    <label for="schedule">Schedule / Timing</label>
                    <input type="text" id="schedule" name="schedule" placeholder="e.g. Every Saturday 9:00 AM – 1:00 PM">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"></textarea>
                </div>

                <div class="form-group">
                    <label for="image_path">Image URL (optional)</label>
                    <input type="url" id="image_path" name="image_path" 
                           placeholder="https://example.com/images/program.jpg"
                           oninput="updateImagePreview(this.value)">
                    <div id="imagePreview"></div>
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

    <script>
        tinymce.init({
            selector: '#description',
            height: 300,
            menubar: false,
            plugins: 'lists link image code',
            toolbar: 'undo redo | blocks | bold italic | bullist numlist | link | removeformat | code',
            content_style: "body { font-family: 'Poppins', sans-serif; font-size:15px; color:#e6e6e6; background:#0d1117; line-height:1.6; }"
        });

        const modal = document.getElementById('programModal');
        const modalTitle = document.getElementById('modalTitle');
        const formAction = document.getElementById('formAction');
        const editId = document.getElementById('editId');
        const imagePreview = document.getElementById('imagePreview');

        function openAddModal() {
            modalTitle.textContent = "Add New Program";
            formAction.value = "add";
            editId.value = "";
            document.getElementById('programForm').reset();
            imagePreview.innerHTML = "";
            tinymce.get('description').setContent('');
            modal.style.display = "flex";
            document.getElementById('title').focus();
        }

        async function openEditModal(id) {
            try {
                const res = await fetch(`programs.php?get_program=${id}`);
                const data = await res.json();

                if (data && data.id) {
                    modalTitle.textContent = "Edit Program";
                    formAction.value = "update";
                    editId.value = data.id;

                    document.getElementById('title').value       = data.title || '';
                    document.getElementById('category').value    = data.category || '';
                    document.getElementById('price').value       = data.price || '';
                    document.getElementById('schedule').value    = data.schedule || '';
                    document.getElementById('image_path').value  = data.image_path || '';

                    tinymce.get('description').setContent(data.description || '');

                    updateImagePreview(data.image_path || '');

                    modal.style.display = "flex";
                    document.getElementById('title').focus();
                }
            } catch (err) {
                console.error("Error loading program:", err);
                alert("Could not load program data.");
            }
        }

        function closeModal() {
            modal.style.display = "none";
        }

        function updateImagePreview(url) {
            if (url.trim()) {
                imagePreview.innerHTML = `<img src="${url}" alt="Preview" onerror="this.src=''; this.parentNode.innerHTML='<div style=\'color:#ff5f57;\'>Image not found</div>'">`;
            } else {
                imagePreview.innerHTML = "";
            }
        }

        window.onclick = (e) => {
            if (e.target === modal) closeModal();
        };

        // Auto-dismiss success message
        <?php if ($message && $messageType === 'success'): ?>
            setTimeout(() => {
                const msg = document.querySelector('.message');
                if (msg) {
                    msg.style.transition = 'opacity 0.6s';
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 600);
                }
            }, 4800);
        <?php endif; ?>
    </script>

    <?php
    // AJAX endpoint for edit
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