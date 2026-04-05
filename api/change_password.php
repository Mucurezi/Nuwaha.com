<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once '../config/database.php';
require_once 'utils.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method', null, 405);
}

try {
    // Require authentication
    $user_id = requireAuth();
    
    // Get form data
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate required fields
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        sendResponse(false, 'All fields are required', null, 400);
    }
    
    // Validate password match
    if ($new_password !== $confirm_password) {
        sendResponse(false, 'New passwords do not match', null, 400);
    }
    
    // Validate password strength
    $passwordValidation = validatePassword($new_password);
    if (!$passwordValidation['valid']) {
        sendResponse(false, $passwordValidation['message'], null, 400);
    }
    
    // Get database connection
    $conn = getDBConnection();
    
    // Get current password hash
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
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
    
    // Verify current password
    if (!password_verify($current_password, $user['password_hash'])) {
        closeDBConnection($conn);
        sendResponse(false, 'Current password is incorrect', null, 401);
    }
    
    // Hash new password
    $new_password_hash = hashPassword($new_password);
    
    // Update password
    $update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
    $update_stmt->bind_param("si", $new_password_hash, $user_id);
    
    if ($update_stmt->execute()) {
        $update_stmt->close();
        closeDBConnection($conn);
        sendResponse(true, 'Password changed successfully');
    } else {
        throw new Exception('Failed to update password');
    }
    
} catch (Exception $e) {
    logError($e->getMessage(), 'Change Password Error');
    sendResponse(false, 'An error occurred while changing password. Please try again later.', null, 500);
}
?>
