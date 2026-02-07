<?php
$password = "test123"; // Change this to whatever password you want to use
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Use this exact hash in your DB:<br><br>";
echo "<strong>" . $hash . "</strong>";
?>