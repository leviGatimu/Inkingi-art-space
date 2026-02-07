<?php
session_start();

// Clear session
$_SESSION = [];

// Destroy session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out | Inkingi Arts</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0c14;
            --text: #f0f4f8;
            --accent: #FDB913;
            --card: rgba(22, 27, 34, 0.92);
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: var(--bg);
            color: var(--text);
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logout-container {
            text-align: center;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            width: 120px;
            margin-bottom: 30px;
            filter: drop-shadow(0 8px 20px rgba(253,185,19,0.4));
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            margin-bottom: 15px;
            color: white;
        }

        p {
            font-size: 1.2rem;
            color: #a1b0c2;
            margin-bottom: 40px;
        }

        .redirect-message {
            font-size: 1.1rem;
            color: var(--accent);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }
    </style>
    <script>
        // Auto-redirect after 2 seconds
        setTimeout(() => {
            window.location.href = "../index.php";
        }, 2000);
    </script>
</head>
<body>

    <div class="logout-container">
        <img src="../assets/images/logo.svg" alt="Inkingi Arts" class="logo">
        <h1>Goodbye, <?= htmlspecialchars($_SESSION['artist_name'] ?? 'Artist') ?></h1>
        <p>You have been successfully logged out.</p>
        <p class="redirect-message">Redirecting to landing page...</p>
    </div>

</body>
</html>