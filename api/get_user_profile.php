<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../config/database.php';
require_once 'utils.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, 'Invalid request method', null, 405);
}

try {
    // Require authentication
    $user_id = requireAuth();
    
    // Get database connection
    $conn = getDBConnection();
    
    // Get user profile
    $stmt = $conn->prepare("SELECT user_id, username, email, user_role, status, created_at, last_login FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        closeDBConnection($conn);
        sendResponse(false, 'User not found', null, 404);
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    closeDBConnection($conn);
    
    sendResponse(true, 'Profile retrieved successfully', $user);
    
} catch (Exception $e) {
    logError($e->getMessage(), 'Get Profile Error');
    sendResponse(false, 'An error occurred while retrieving profile', null, 500);
}
?>
