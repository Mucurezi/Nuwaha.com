<?php
/**
 * User Login Handler
 * Authenticates users and creates session
 */

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php_errors.log');

require_once '../config/database.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method';
    header('Location: ../login.php');
    exit;
}

// Get and sanitize form data
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// Validate required fields
if (empty($username)) {
    $_SESSION['error'] = 'Username or email is required';
    header('Location: ../login.php');
    exit;
}

if (empty($password)) {
    $_SESSION['error'] = 'Password is required';
    header('Location: ../login.php');
    exit;
}

// Database operations
try {
    $conn = getDBConnection();
    
    // Find user by username or email
    $stmt = $conn->prepare("SELECT user_id, username, email, password_hash, user_role, status FROM users WHERE username = ? OR email = ? LIMIT 1");
    
    if (!$stmt) {
        throw new Exception('Database error: Unable to prepare statement');
    }
    
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if user exists
    if ($result->num_rows === 0) {
        $stmt->close();
        closeDBConnection($conn);
        
        // Generic error message for security
        $_SESSION['error'] = 'Invalid username or password';
        error_log("Login attempt failed: User not found - $username");
        header('Location: ../login.php');
        exit;
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        closeDBConnection($conn);
        
        // Generic error message for security
        $_SESSION['error'] = 'Invalid username or password';
        error_log("Login attempt failed: Wrong password for user - " . $user['username']);
        header('Location: ../login.php');
        exit;
    }
    
    // Check account status
    if ($user['status'] !== 'active') {
        closeDBConnection($conn);
        $_SESSION['error'] = 'Your account is ' . htmlspecialchars($user['status']) . '. Please contact administration.';
        error_log("Login attempt failed: Account status " . $user['status'] . " for user - " . $user['username']);
        header('Location: ../login.php');
        exit;
    }
    
    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);
    
    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['user_role'] = $user['user_role'];
    $_SESSION['login_time'] = time();
    $_SESSION['last_activity'] = time();
    
    // Update last login timestamp
    $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
    if ($updateStmt) {
        $updateStmt->bind_param("i", $user['user_id']);
        $updateStmt->execute();
        $updateStmt->close();
    }
    
    closeDBConnection($conn);
    
    // Log successful login
    error_log("User logged in: ID=" . $user['user_id'] . ", Username=" . $user['username']);
    
    // Set success message
    $_SESSION['success'] = 'Welcome back, ' . htmlspecialchars($user['username']) . '!';
    
    // Redirect to admission page
    header('Location: ../admission.php');
    exit;
    
} catch (Exception $e) {
    // Log the error
    error_log("Login Error: " . $e->getMessage());
    
    // Show user-friendly error
    $_SESSION['error'] = 'Login failed. Please try again later.';
    header('Location: ../login.php');
    exit;
}
?>
