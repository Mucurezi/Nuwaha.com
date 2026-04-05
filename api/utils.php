<?php
// Utility functions for API endpoints

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate email format
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (basic validation)
 */
function validatePhone($phone) {
    return preg_match('/^[0-9+\-\s()]{10,20}$/', $phone);
}

/**
 * Validate password strength
 */
function validatePassword($password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
    if (strlen($password) < 8) {
        return ['valid' => false, 'message' => 'Password must be at least 8 characters long'];
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return ['valid' => false, 'message' => 'Password must contain at least one uppercase letter'];
    }
    if (!preg_match('/[a-z]/', $password)) {
        return ['valid' => false, 'message' => 'Password must contain at least one lowercase letter'];
    }
    if (!preg_match('/[0-9]/', $password)) {
        return ['valid' => false, 'message' => 'Password must contain at least one number'];
    }
    return ['valid' => true, 'message' => 'Password is valid'];
}

/**
 * Hash password securely
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Send JSON response
 */
function sendResponse($success, $message, $data = null, $statusCode = 200) {
    http_response_code($statusCode);
    $response = [
        'success' => $success,
        'message' => $message
    ];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

/**
 * Log error to file
 */
function logError($error, $context = '') {
    $logFile = '../logs/error.log';
    $logDir = dirname($logFile);
    
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $context: $error" . PHP_EOL;
    error_log($logMessage, 3, $logFile);
}

/**
 * Check if user is authenticated
 */
function requireAuth() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        sendResponse(false, 'Authentication required', null, 401);
    }
    return $_SESSION['user_id'];
}

/**
 * Check if user has required role
 */
function requireRole($allowedRoles) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_role'])) {
        sendResponse(false, 'Authentication required', null, 401);
    }
    
    if (!in_array($_SESSION['user_role'], $allowedRoles)) {
        sendResponse(false, 'Insufficient permissions', null, 403);
    }
    
    return $_SESSION['user_role'];
}

/**
 * Rate limiting check (simple implementation)
 */
function checkRateLimit($identifier, $maxAttempts = 5, $timeWindow = 300) {
    // Session should already be started by the calling script
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $key = 'rate_limit_' . $identifier;
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 1, 'start_time' => time()];
        return true;
    }
    
    $elapsed = time() - $_SESSION[$key]['start_time'];
    
    if ($elapsed > $timeWindow) {
        $_SESSION[$key] = ['count' => 1, 'start_time' => time()];
        return true;
    }
    
    if ($_SESSION[$key]['count'] >= $maxAttempts) {
        return false;
    }
    
    $_SESSION[$key]['count']++;
    return true;
}
?>
