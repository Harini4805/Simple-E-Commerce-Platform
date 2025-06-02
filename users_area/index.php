<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to main index.php
header("Location: ../index.php");
exit();
?>
