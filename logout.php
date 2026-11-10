<?php
session_start();

// Destroy session to log out
session_unset();  // Unset all session variables
session_destroy();  // Destroy the session completely

// Redirect to index.php
header("Location: index.php");
exit();
?>
