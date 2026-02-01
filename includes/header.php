<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ikingi Arts Space</title>

    <link rel="icon" type="image/png" href="assets/images/logo.svg">
    <link
        href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Poppins:wght@300;400;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* Internal CSS Overrides for Animations & Lines */
        .connection-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: visible;
        }

        .connector-path {
            fill: none;
            stroke: #FDB913;
            /* Yellow Line */
            stroke-width: 4;
            stroke-dasharray: 20, 10;
            /* Dashed */
            opacity: 0.8;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.2));
        }

        .hero-title {
            position: relative;
            z-index: 20 !important;
            /* Forces text to be on top */
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="nav-brand">
            <img src="assets/images/logo.svg" alt="Logo" class="nav-logo-icon">
            <span>IKINGI ARTS SPACE</span>
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="artwork.php">Artwork</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="programs.php">Programs</a></li>
            <li><a href="contact.php">Contacts</a></li>
        </ul>

        <a href="join.php" class="btn-join">Join now</a>
    </nav>