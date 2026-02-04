<?php
session_start();
require '../includes/db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // --- SECURITY NOTE ---
    // For a real production site, you should hash passwords (password_hash).
    // For this example, I'm checking against a 'users' table or a hardcoded admin.
    
    // SIMPLE HARDCODED CHECK (Change this!)
    if ($username === 'admin' && $password === 'password123') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials. The art of access is denied.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inkingi Admin | Enter the Studio</title>
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.svg">
    <style>
        /* --- HIGH-CLASS ARTISTIC CSS --- */
        :root {
            --navy: #0a192f;
            --gold: #FDB913;
            --red: #C8102E;
            --green: #009E60;
            --glass: rgba(17, 34, 64, 0.85);
            --text: #ccd6f6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            height: 100vh;
            background-color: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            position: relative;
        }

        /* --- ANIMATED BACKGROUND SHAPES --- */
        .shape {
            position: absolute;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.6;
            animation: float 20s infinite ease-in-out;
        }
        .shape-1 { top: -10%; left: -10%; width: 500px; height: 500px; background: var(--gold); animation-delay: 0s; }
        .shape-2 { bottom: -10%; right: -10%; width: 400px; height: 400px; background: var(--red); animation-delay: -5s; }
        .shape-3 { bottom: 20%; left: 20%; width: 300px; height: 300px; background: var(--green); animation-delay: -10s; opacity: 0.3; }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(60px, 30px) rotate(20deg); }
            100% { transform: translate(0, 0) rotate(0deg); }
        }

        /* --- GLASS LOGIN CARD --- */
        .login-card {
            background: var(--glass);
            width: 100%;
            max-width: 450px;
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            z-index: 10;
            text-align: center;
            position: relative;
            transform: translateY(30px);
            opacity: 0;
            animation: revealCard 1s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        @keyframes revealCard { to { opacity: 1; transform: translateY(0); } }

        /* --- LOGO & TYPOGRAPHY --- */
        .brand-logo {
            font-family: 'Permanent Marker', cursive;
            font-size: 2.5rem;
            color: #fff;
            margin-bottom: 10px;
            text-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .brand-logo span { color: var(--gold); }
        
        .subtitle {
            color: #8892b0;
            margin-bottom: 40px;
            font-size: 0.95rem;
            font-weight: 300;
            letter-spacing: 1px;
        }

        /* --- FORM STYLING --- */
        .input-group {
            position: relative;
            margin-bottom: 30px;
            text-align: left;
        }

        .input-field {
            width: 100%;
            padding: 15px 10px 15px 45px; /* Space for icon */
            background: transparent;
            border: none;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 1rem;
            font-family: inherit;
            transition: 0.3s;
            outline: none;
        }

        .input-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #8892b0;
            font-size: 1.2rem;
            transition: 0.3s;
        }

        /* Focus Effects */
        .input-field:focus { border-bottom-color: var(--gold); }
        .input-field:focus + .input-icon { color: var(--gold); }

        .input-label {
            position: absolute;
            left: 45px;
            top: 50%;
            transform: translateY(-50%);
            color: #8892b0;
            pointer-events: none;
            transition: 0.3s ease;
        }

        /* Float label when focused or filled */
        .input-field:focus ~ .input-label,
        .input-field:valid ~ .input-label {
            top: -10px;
            left: 0;
            font-size: 0.8rem;
            color: var(--gold);
        }

        /* --- BUTTON --- */
        .btn-login {
            width: 100%;
            padding: 15px;
            background: var(--gold);
            color: var(--navy);
            border: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 1px;
            cursor: pointer;
            transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-top: 20px;
            box-shadow: 0 10px 20px rgba(253, 185, 19, 0.2);
        }

        .btn-login:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 30px rgba(253, 185, 19, 0.4);
            background: #fff;
        }

        /* --- ERROR MESSAGE --- */
        .error-msg {
            background: rgba(200, 16, 46, 0.1);
            color: #ff5f5f;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid rgba(200, 16, 46, 0.3);
            margin-bottom: 25px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* --- DECORATIVE PAINT SPLASH --- */
        .paint-splash {
            position: absolute;
            top: -60px;
            right: -60px;
            width: 150px;
            opacity: 0.8;
            z-index: -1;
            transform: rotate(45deg);
        }
    </style>
</head>
<body>

    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>

    <div class="login-card">
        
        <svg class="paint-splash" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#FDB913" d="M41.7,-68.3C52.6,-60.3,59.3,-46.2,65.6,-32.7C71.9,-19.2,77.8,-6.3,77.2,6.3C76.5,18.8,69.4,31,60.3,41.9C51.2,52.8,40.1,62.4,27.7,68.4C15.3,74.4,1.6,76.8,-11.2,75.4C-24,74,-35.9,68.8,-46.9,61.4C-57.9,54,-68,44.4,-74.6,32.6C-81.2,20.8,-84.3,6.8,-82.1,-6.3C-79.9,-19.4,-72.4,-31.6,-62.7,-41.2C-53,-50.8,-41.1,-57.8,-29.1,-64.8C-17.1,-71.8,-5.1,-78.8,7.9,-80C20.9,-81.2,33.9,-76.6,41.7,-68.3Z" transform="translate(100 100)" />
        </svg>

        <div class="brand-logo">Inkingi <span>Admin</span></div>
        <p class="subtitle">Enter the creative control center.</p>

        <?php if($error): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="input-group">
                <input type="text" name="username" class="input-field" required autocomplete="off">
                <i class="fas fa-user input-icon"></i>
                <label class="input-label">Username</label>
            </div>

            <div class="input-group">
                <input type="password" name="password" class="input-field" required>
                <i class="fas fa-lock input-icon"></i>
                <label class="input-label">Password</label>
            </div>

            <button type="submit" class="btn-login">Unlock Dashboard <i class="fas fa-arrow-right" style="margin-left:10px;"></i></button>
        </form>

        <p style="margin-top: 30px; font-size: 0.8rem; color: #555;">
            &copy; <?= date('Y') ?> Inkingi Arts Space
        </p>
    </div>

</body>
</html>