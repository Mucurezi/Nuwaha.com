<?php
/**
 * Admin Logout
 */

session_start();

// Log the logout
if (isset($_SESSION['username'])) {
    error_log("Admin logout: " . $_SESSION['username']);
}

// Destroy session
$_SESSION = array();

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

session_destroy();

// Redirect to admin login
header('Location: login.php');
exit;
?>
