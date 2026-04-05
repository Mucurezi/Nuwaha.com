<?php
// Database configuration - EXAMPLE FILE
// Copy this file to database.php and update with your actual credentials

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Your database password
define('DB_NAME', 'muteesaiidb');  // Your database name

// Create connection with error handling
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }
        
        // Set charset to utf8mb4 for better Unicode support
        if (!$conn->set_charset("utf8mb4")) {
            throw new Exception("Error setting charset: " . $conn->error);
        }
        
        return $conn;
        
    } catch (Exception $e) {
        // Log error
        error_log("Database Connection Error: " . $e->getMessage());
        
        // Return user-friendly error
        http_response_code(500);
        die(json_encode([
            'success' => false,
            'message' => 'Database connection error. Please try again later.'
        ]));
    }
}

// Close connection safely
function closeDBConnection($conn) {
    if ($conn && $conn instanceof mysqli) {
        $conn->close();
    }
}

// Execute prepared statement with error handling
function executeQuery($conn, $query, $types = '', $params = []) {
    try {
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Query preparation failed: " . $conn->error);
        }
        
        if (!empty($types) && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Query execution failed: " . $stmt->error);
        }
        
        return $stmt;
        
    } catch (Exception $e) {
        error_log("Query Error: " . $e->getMessage());
        throw $e;
    }
}
?>
