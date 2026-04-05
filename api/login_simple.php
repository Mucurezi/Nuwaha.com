<?php
// Login handler
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method';
    header('Location: ../login.php');
    exit;
}

// Get form data
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validate required fields
if (empty($username) || empty($password)) {
    $_SESSION['error'] = 'Username and password are required';
    header('Location: ../login.php');
    exit;
}

try {
    $conn = getDBConnection();
    
    // Get user by username or email
    $stmt = $conn->prepare("SELECT user_id, username, email, password_hash, user_role, status FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        closeDBConnection($conn);
        $_SESSION['error'] = 'Invalid username or password';
        header('Location: ../login.php');
        exit;
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        closeDBConnection($conn);
        $_SESSION['error'] = 'Invalid username or password';
        header('Location: ../login.php');
        exit;
    }
    
    // Check if user is active
    if ($user['status'] !== 'active') {
        closeDBConnection($conn);
        $_SESSION['error'] = 'Your account is ' . $user['status'];
        header('Location: ../login.php');
        exit;
    }
    
    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_role'] = $user['user_role'];
    $_SESSION['login_time'] = time();
    
    // Update last login
    $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
    $updateStmt->bind_param("i", $user['user_id']);
    $updateStmt->execute();
    $updateStmt->close();
    
    closeDBConnection($conn);
    
    $_SESSION['success'] = 'Login successful! Welcome back, ' . $user['username'] . '!';
    
    // Redirect to admission page
    header('Location: ../admission.html');
    exit;
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Login error: ' . $e->getMessage();
    header('Location: ../login.php');
    exit;
}
?>
