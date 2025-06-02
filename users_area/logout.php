<?php
session_start();
session_unset();
session_destroy();
// Redirect to home page after logout
header('Location: http://localhost/E%20Commerce%20Website/index.php');
exit();
?>