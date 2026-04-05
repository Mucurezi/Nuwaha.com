<?php
/**
 * Database Connection Test Script
 * This script tests the connection to the muteesaiidb database
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';  // Default XAMPP password is empty
$database = 'muteesaiidb';

echo "<h2>Testing Database Connection</h2>";
echo "<hr>";

// Test 1: Create connection
echo "<h3>Test 1: Connecting to MySQL Server...</h3>";
$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("<p style='color: red;'>❌ Connection failed: " . $conn->connect_error . "</p>");
} else {
    echo "<p style='color: green;'>✅ Successfully connected to MySQL server!</p>";
}

// Test 2: Select database
echo "<h3>Test 2: Selecting Database 'muteesaiidb'...</h3>";
if (!$conn->select_db($database)) {
    die("<p style='color: red;'>❌ Database selection failed: " . $conn->error . "</p>");
} else {
    echo "<p style='color: green;'>✅ Successfully selected database 'muteesaiidb'!</p>";
}

// Test 3: Check tables
echo "<h3>Test 3: Checking Tables...</h3>";
$result = $conn->query("SHOW TABLES");

if ($result) {
    echo "<p style='color: green;'>✅ Found " . $result->num_rows . " tables:</p>";
    echo "<ul>";
    while ($row = $result->fetch_array()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ Error checking tables: " . $conn->error . "</p>";
}

// Test 4: Check users table
echo "<h3>Test 4: Checking Users Table...</h3>";
$result = $conn->query("SELECT user_id, username, email, user_role, status FROM users");

if ($result) {
    echo "<p style='color: green;'>✅ Found " . $result->num_rows . " users:</p>";
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr style='background-color: #1a5f3f; color: white;'>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
          </tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['user_id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['user_role'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ Error querying users: " . $conn->error . "</p>";
}

// Test 5: Test config/database.php
echo "<h3>Test 5: Testing config/database.php...</h3>";
if (file_exists('config/database.php')) {
    require_once 'config/database.php';
    
    $test_conn = getDBConnection();
    if ($test_conn) {
        echo "<p style='color: green;'>✅ config/database.php is working correctly!</p>";
        closeDBConnection($test_conn);
    } else {
        echo "<p style='color: red;'>❌ config/database.php connection failed!</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ config/database.php file not found!</p>";
}

// Close connection
$conn->close();

echo "<hr>";
echo "<h3>Summary</h3>";
echo "<p style='color: green; font-weight: bold;'>✅ All tests passed! Your database is ready to use.</p>";
echo "<p><a href='index.html'>Go to Homepage</a> | <a href='login.html'>Go to Login</a></p>";
?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 900px;
        margin: 50px auto;
        padding: 20px;
        background-color: #f5f5f5;
    }
    h2 {
        color: #1a5f3f;
    }
    h3 {
        color: #2d8659;
        margin-top: 20px;
    }
    table {
        margin: 10px 0;
        width: 100%;
    }
    a {
        color: #1a5f3f;
        text-decoration: none;
        font-weight: bold;
    }
    a:hover {
        color: #2d8659;
    }
</style>
