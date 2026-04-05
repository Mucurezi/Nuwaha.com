<?php
// PHP Test File
echo "<h1>PHP is working!</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Current File: " . __FILE__ . "</p>";

// Test database connection
echo "<hr>";
echo "<h2>Database Connection Test</h2>";

require_once 'config/database.php';

try {
    $conn = getDBConnection();
    echo "<p style='color:green;'>✓ Database connection successful!</p>";
    echo "<p>Database: " . DB_NAME . "</p>";
    closeDBConnection($conn);
} catch (Exception $e) {
    echo "<p style='color:red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}

// Test session
echo "<hr>";
echo "<h2>Session Test</h2>";
session_start();
$_SESSION['test'] = 'Session is working!';
echo "<p style='color:green;'>✓ " . $_SESSION['test'] . "</p>";
?>
