<?php
require '../includes/db_connect.php';

// 1. HANDLE SAVE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page_id = $_POST['page_id'];
    
    // Loop through all posted fields (dynamic keys)
    foreach ($_POST as $key => $value) {
        if ($key == 'page_id') continue;
        
        // Update content_blocks table
        $stmt = $pdo->prepare("UPDATE content_blocks SET content_value = ? WHERE section_key = ? AND page_id = ?");
        $stmt->execute([$value, $key, $page_id]);
    }
    
    // Redirect to refresh data
    $slug = $_POST['page_slug'];
    header("Location: page_edit.php?page=$slug&saved=true");
    exit;
}

// 2. DETERMINE VIEW (Selector vs Editor)
$selectedPage = $_GET['page'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inkingi Admin | Editor</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>

    <aside class="sidebar">
        <div class="brand">INKINGI <span style="color:#fff;">CMS</span></div>
        <nav>
            <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="page_edit.php" class="nav-link active"><i class="fas fa-edit"></i> Edit Pages</a>
            <a href="programs.php" class="nav-link"><i class="fas fa-calendar-check"></i> Programs</a>
        </nav>
    </aside>

    <main class="main-content">
        
        <?php if (!$selectedPage): ?>
            <div class="header">
                <h1>Select a Page to Edit</h1>
            </div>
            <div class="page-grid">
                <?php 
                $pages = $pdo->query("SELECT * FROM pages")->fetchAll();
                foreach($pages as $p): 
                ?>
                <div class="page-card">
                    <h3><?= htmlspecialchars($p['page_name']) ?></h3>
                    <p style="color: #8892b0;">Last updated: <?= date('M d, Y', strtotime($p['last_updated'])) ?></p>
                    <a href="page_edit.php?page=<?= $p['page_slug'] ?>" class="btn-edit">Edit Content <i class="fas fa-arrow-right"></i></a>
                </div>
                <?php endforeach; ?>
            </div>

        <?php else: 
            // Fetch Page Info & Blocks
            $pageInfo = $pdo->prepare("SELECT * FROM pages WHERE page_slug = ?");
            $pageInfo->execute([$selectedPage]);
            $page = $pageInfo->fetch();

            if(!$page) die("Page not found.");

            $blocks = $pdo->prepare("SELECT * FROM content_blocks WHERE page_id = ?");
            $blocks->execute([$page['id']]);
            $content = $blocks->fetchAll();
        ?>
            <div class="header">
                <div>
                    <a href="page_edit.php" style="color:var(--text); text-decoration:none; opacity:0.7;"><i class="fas fa-arrow-left"></i> Back</a>
                    <h1 style="margin-top: 10px;">Editing: <?= htmlspecialchars($page['page_name']) ?></h1>
                </div>
                <?php if(isset($_GET['saved'])): ?>
                    <div style="background: rgba(100, 255, 218, 0.1); color: #64ffda; padding: 10px 20px; border-radius: 30px;">
                        <i class="fas fa-check"></i> Changes Saved!
                    </div>
                <?php endif; ?>
            </div>

            <div class="editor-container">
                <form method="POST">
                    <input type="hidden" name="page_id" value="<?= $page['id'] ?>">
                    <input type="hidden" name="page_slug" value="<?= $selectedPage ?>">

                    <?php foreach($content as $block): ?>
                        <div class="form-group">
                            <label class="form-label"><?= ucwords(str_replace('_', ' ', $block['section_key'])) ?></label>
                            
                            <?php if($block['content_type'] == 'textarea'): ?>
                                <textarea name="<?= $block['section_key'] ?>" class="form-control" rows="5"><?= htmlspecialchars($block['content_value']) ?></textarea>
                            
                            <?php elseif($block['content_type'] == 'text'): ?>
                                <input type="text" name="<?= $block['section_key'] ?>" class="form-control" value="<?= htmlspecialchars($block['content_value']) ?>">
                            
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <?php if(empty($content)): ?>
                        <p style="color: #8892b0;">No editable text blocks found for this page. Check database configuration.</p>
                    <?php else: ?>
                        <button type="submit" class="btn-save">Save Changes</button>
                    <?php endif; ?>
                </form>
            </div>
        <?php endif; ?>

    </main>
</body>
</html>