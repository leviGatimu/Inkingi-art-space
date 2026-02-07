<?php
// === DEBUG MODE ON (remove later in production) ===
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering EARLY
ob_start();

session_start();

// Debug: Show session status
if (isset($_SESSION['artist_id'])) {
    echo "<pre>Already logged in as artist ID: " . $_SESSION['artist_id'] . "</pre>";
    header("Location: artists_dashboard.php");
    exit;
}

// Database connection - fixed path
$root = __DIR__ . '/../';
require_once $root . 'includes/db_connect.php';

// Debug: Check if $pdo exists
if (!isset($pdo)) {
    die("CRITICAL ERROR: Database connection failed - \$pdo not defined.");
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Debug: Show what was submitted
    echo "<pre>Submitted username: '$username'\nPassword length: " . strlen($password) . "</pre>";

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, password FROM artists WHERE username = ?");
            $stmt->execute([$username]);
            $artist = $stmt->fetch(PDO::FETCH_ASSOC);

            // Debug: Show query result
            if ($artist) {
                echo "<pre>Found user ID: " . $artist['id'] . "</pre>";
                if (password_verify($password, $artist['password'])) {
                    echo "<pre>Password verified! Logging in...</pre>";
                    $_SESSION['artist_id'] = $artist['id'];
                    // Clear buffer and redirect
                    ob_end_clean();
                    header("Location: artists_dashboard.php");
                    exit;
                } else {
                    $error = "Invalid password.";
                    echo "<pre>Password verification FAILED.</pre>";
                }
            } else {
                $error = "User not found.";
                echo "<pre>No user found with username '$username'.</pre>";
            }
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
            echo "<pre>DB Error: " . $e->getMessage() . "</pre>";
        }
    }
}

// End output buffer (if no redirect happened)
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Login | Inkingi Arts</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.svg">
    <style>
        :root {
            --bg: #0f0f1a;
            --card: rgba(22, 27, 34, 0.85);
            --text: #e6e6e6;
            --accent: #FDB913;
            --accent-hover: #e6a50a;
            --border: rgba(48, 54, 61, 0.6);
            --glow: rgba(253, 185, 19, 0.15);
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(253,185,19,0.08) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(0,158,96,0.06) 0%, transparent 20%);
            background-attachment: fixed;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: var(--card);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5), inset 0 0 20px var(--glow);
            overflow: hidden;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header {
            text-align: center;
            padding: 40px 30px 30px;
        }

        .login-header img {
            width: 100px;
            margin-bottom: 20px;
        }

        .login-header h2 {
            color: white;
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .form-container {
            padding: 0 40px 40px;
        }

        .form-group {
            margin-bottom: 28px;
            position: relative;
        }

        .form-group label {
            position: absolute;
            top: 16px;
            left: 16px;
            color: #8892b0;
            font-size: 0.95rem;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label {
            top: -8px;
            left: 12px;
            font-size: 0.75rem;
            color: var(--accent);
            background: var(--card);
            padding: 0 6px;
        }

        input {
            width: 100%;
            padding: 16px;
            background: rgba(13,17,23,0.8);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(253,185,19,0.2);
        }

        .error {
            color: #ff5f57;
            text-align: center;
            margin: 15px 0;
            font-size: 0.95rem;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: var(--accent);
            color: #0f0f1a;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .login-btn:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(253,185,19,0.3);
        }

        .extra-links {
            text-align: center;
            margin-top: 25px;
            font-size: 0.9rem;
            color: #8892b0;
        }

        .extra-links a {
            color: var(--accent);
            text-decoration: none;
        }

        .extra-links a:hover {
            color: var(--accent-hover);
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <img src="../assets/images/logo.svg" alt="Inkingi Arts Logo">
            <h2>Artist Login</h2>
            <p>Access your creative dashboard</p>
        </div>

        <div class="form-container">
            <?php if ($error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <input type="text" id="username" name="username" placeholder=" " required value="<?= htmlspecialchars($username ?? '') ?>">
                    <label for="username">Username</label>
                </div>

                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder=" " required>
                    <label for="password">Password</label>
                </div>

                <button type="submit" class="login-btn">Login to Dashboard</button>
            </form>

            <div class="extra-links">
                Don't have an account? <a href="../contact.php">Contact Admin</a>
            </div>
        </div>
    </div>

</body>
</html>