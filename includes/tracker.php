<?php
/* Visitor Tracking Script
   -----------------------
   Records unique daily visits based on IP address.
*/

// Ensure DB connection exists
if (!isset($pdo)) {
    // Determine path based on where script is called
    $path = file_exists('includes/db_connect.php') ? 'includes/db_connect.php' : '../includes/db_connect.php';
    if (file_exists($path)) {
        require_once $path;
    }
}

if (isset($pdo)) {
    try {
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $today = date('Y-m-d');

        // Try to insert. "INSERT IGNORE" fails silently if IP+Date already exists.
        $stmt = $pdo->prepare("INSERT IGNORE INTO site_visits (ip_address, visit_date) VALUES (?, ?)");
        $stmt->execute([$user_ip, $today]);
        
    } catch (Exception $e) {
        // Silently fail so user experience isn't affected
    }
}
?>