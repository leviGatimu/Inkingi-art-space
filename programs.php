<?php 
require 'includes/header.php'; 

// --- DB CONNECTION & AUTO-SEEDING ---
$host = 'localhost'; $db = 'inkingi_db'; $user = 'root'; $pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if table exists, if not create it
    $pdo->exec("CREATE TABLE IF NOT EXISTS programs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        category VARCHAR(50) NOT NULL,
        price VARCHAR(50),
        schedule VARCHAR(100),
        description TEXT,
        image_path VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Check if empty, if so, SEED DATA FROM FLYER
    $check = $pdo->query("SELECT COUNT(*) FROM programs")->fetchColumn();
    if ($check == 0) {
        $seedData = [
            ['Art Painting Class', 'Class', '20,000 Rwf', 'Daily', 'Open every day to kids and adults. Take your own masterpiece home! Includes all materials.', 'assets/images/image_49929b.jpg'],
            ['Saturday Pottery', 'Workshop', '25,000 Rwf', 'Sat 10am - 5pm', 'Two hours session to mold your creativity. Open to everyone.', 'assets/images/image_49929b.jpg'],
            ['Rwandan Cooking Class', 'Workshop', '20,000 Rwf', 'By Booking', 'Experience an interactive cooking session with Ikoma Art. Prepare authentic dishes and enjoy a shared meal.', 'assets/images/image_49929b.jpg'],
            ['Inkingi Open Mic', 'Event', 'Free Entry', '3rd Sat of Month', 'An evening of captivating poetry, storytelling, and live music. Enjoy traditional snacks.', 'assets/images/image_492d06.jpg']
        ];
        $stmt = $pdo->prepare("INSERT INTO programs (title, category, price, schedule, description, image_path) VALUES (?,?,?,?,?,?)");
        foreach ($seedData as $row) $stmt->execute($row);
    }

    // Fetch Programs
    $programs = $pdo->query("SELECT * FROM programs ORDER BY created_at ASC")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<div style='padding:100px; text-align:center;'>Database Connection Error. Please check admin settings.</div>";
    die();
}
?>

<style>
    /* --- PAGE SPECIFIC CSS --- */
    .page-hero {
        height: 60vh; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center;
        background: var(--primary); color: white; text-align: center;
    }
    .page-hero-bg {
        position: absolute; top:0; left:0; width:100%; height:100%; opacity: 0.4;
        background-image: url('assets/images/image_492d06.jpg'); background-size: cover; background-position: center;
        filter: grayscale(100%);
    }
    .page-hero h1 { font-family: var(--font-serif); font-size: 4rem; position: relative; z-index: 2; margin-bottom: 10px; }
    .page-hero p { position: relative; z-index: 2; font-size: 1.2rem; max-width: 600px; margin: 0 auto; color: #ddd; }

    /* Filters */
    .filter-bar {
        display: flex; justify-content: center; gap: 20px; margin-bottom: 50px;
        flex-wrap: wrap;
    }
    .filter-btn {
        padding: 10px 30px; border: 2px solid var(--primary); border-radius: 30px;
        background: transparent; color: var(--primary); font-weight: 600; cursor: pointer;
        transition: 0.3s;
    }
    .filter-btn.active, .filter-btn:hover {
        background: var(--primary); color: white;
    }

    /* Cards */
    .program-card-lg {
        display: grid; grid-template-columns: 1fr 1.5fr; gap: 0;
        background: white; border-radius: 20px; overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        margin-bottom: 40px; transition: 0.3s;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .program-card-lg:hover { transform: translateY(-5px); box-shadow: 0 25px 60px rgba(0,0,0,0.12); }
    
    .pc-img { height: 100%; min-height: 300px; object-fit: cover; width: 100%; }
    .pc-content { padding: 40px; position: relative; display: flex; flex-direction: column; justify-content: center; }
    
    .pc-badge {
        position: absolute; top: 30px; right: 30px;
        background: var(--accent-yellow); color: var(--primary);
        padding: 5px 15px; border-radius: 20px; font-weight: 700; font-size: 0.9rem;
    }
    .pc-cat { color: var(--accent-red); font-weight: 700; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; margin-bottom: 10px; display: block; }
    .pc-title { font-family: var(--font-serif); font-size: 2.2rem; margin-bottom: 15px; color: var(--primary); }
    .pc-schedule { display: flex; align-items: center; gap: 10px; color: #666; margin-bottom: 20px; font-weight: 500; }
    .pc-desc { color: #555; line-height: 1.8; margin-bottom: 30px; }

    @media (max-width: 992px) {
        .program-card-lg { grid-template-columns: 1fr; }
        .pc-img { height: 250px; }
    }
</style>

<section class="page-hero">
    <div class="page-hero-bg"></div>
    <div style="position:relative; z-index:2;">
        <h1>Our <span class="text-yellow">Programs</span></h1>
        <p>Join our practical art classes, trainings, and workshops taught by masters.</p>
    </div>
    <div style="position: absolute; bottom: -1px; left: 0; width: 100%; overflow: hidden; line-height: 0;">
        <svg viewBox="0 0 1440 320" preserveAspectRatio="none" style="width: 100%; height: 100px;">
            <path fill="#FAFAFA" fill-opacity="1" d="M0,128L48,138.7C96,149,192,171,288,165.3C384,160,480,128,576,128C672,128,768,160,864,176C960,192,1056,192,1152,176C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</section>

<section class="section-padding">
    <div class="container" style="max-width: 1000px; margin: 0 auto;">
        
        <div class="filter-bar">
            <button class="filter-btn active" onclick="filterPrograms('all')">All</button>
            <button class="filter-btn" onclick="filterPrograms('Class')">Classes</button>
            <button class="filter-btn" onclick="filterPrograms('Workshop')">Workshops</button>
            <button class="filter-btn" onclick="filterPrograms('Event')">Events</button>
        </div>

        <div id="programList">
            <?php foreach($programs as $prog): ?>
            <div class="program-card-lg item-<?= strtolower($prog['category']) ?>">
                <div>
                    <img src="<?= htmlspecialchars($prog['image_path']) ?>" alt="<?= htmlspecialchars($prog['title']) ?>" class="pc-img">
                </div>
                <div class="pc-content">
                    <span class="pc-badge"><?= htmlspecialchars($prog['price']) ?></span>
                    <span class="pc-cat"><?= htmlspecialchars($prog['category']) ?></span>
                    <h3 class="pc-title"><?= htmlspecialchars($prog['title']) ?></h3>
                    <div class="pc-schedule">
                        <i class="far fa-clock text-yellow"></i> <?= htmlspecialchars($prog['schedule']) ?>
                    </div>
                    <p class="pc-desc"><?= htmlspecialchars($prog['description']) ?></p>
                    
                    <a href="contact.php?book=<?= urlencode($prog['title']) ?>" class="nav-btn" style="width: fit-content; text-decoration:none; text-align:center;">
                        Book Now
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
    // Simple Filter Logic
    function filterPrograms(cat) {
        const items = document.querySelectorAll('.program-card-lg');
        const btns = document.querySelectorAll('.filter-btn');
        
        // Active Button
        btns.forEach(b => b.classList.remove('active'));
        event.target.classList.add('active');

        items.forEach(item => {
            if (cat === 'all') {
                item.style.display = 'grid';
            } else {
                if (item.classList.contains('item-' + cat.toLowerCase())) {
                    item.style.display = 'grid';
                } else {
                    item.style.display = 'none';
                }
            }
        });
    }
</script>