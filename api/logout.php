<?php
/**
 * Logout Handler
 * Destroys user session and redirects to home page
 */

session_start();

// Log the logout
if (isset($_SESSION['username'])) {
    error_log("User logged out: " . $_SESSION['username']);
}

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Redirect to home page
header('Location: ../index.html');
exit;
?>
