<?php
// Registration handler
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method';
    header('Location: ../register.php');
    exit;
}

// Get form data
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$user_role = trim($_POST['user_role'] ?? 'parent');

// Validate required fields
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $_SESSION['error'] = 'All fields are required';
    header('Location: ../register.php');
    exit;
}

// Validate username length
if (strlen($username) < 3 || strlen($username) > 50) {
    $_SESSION['error'] = 'Username must be between 3 and 50 characters';
    header('Location: ../register.php');
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email address';
    header('Location: ../register.php');
    exit;
}

// Validate password match
if ($password !== $confirm_password) {
    $_SESSION['error'] = 'Passwords do not match';
    header('Location: ../register.php');
    exit;
}

// Validate password strength
if (strlen($password) < 8) {
    $_SESSION['error'] = 'Password must be at least 8 characters long';
    header('Location: ../register.php');
    exit;
}
if (!preg_match('/[A-Z]/', $password)) {
    $_SESSION['error'] = 'Password must contain at least one uppercase letter';
    header('Location: ../register.php');
    exit;
}
if (!preg_match('/[a-z]/', $password)) {
    $_SESSION['error'] = 'Password must contain at least one lowercase letter';
    header('Location: ../register.php');
    exit;
}
if (!preg_match('/[0-9]/', $password)) {
    $_SESSION['error'] = 'Password must contain at least one number';
    header('Location: ../register.php');
    exit;
}

// Validate user role
$allowedRoles = ['student', 'parent'];
if (!in_array($user_role, $allowedRoles)) {
    $_SESSION['error'] = 'Invalid user role';
    header('Location: ../register.php');
    exit;
}

try {
    $conn = getDBConnection();
    
    // Check if username exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        closeDBConnection($conn);
        $_SESSION['error'] = 'Username already exists';
        header('Location: ../register.php');
        exit;
    }
    $stmt->close();
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        closeDBConnection($conn);
        $_SESSION['error'] = 'Email already registered';
        header('Location: ../register.php');
        exit;
    }
    $stmt->close();
    
    // Hash password
    $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, user_role, status, created_at) VALUES (?, ?, ?, ?, 'active', NOW())");
    $stmt->bind_param("ssss", $username, $email, $password_hash, $user_role);
    
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        
        // Don't auto-login, just save success message
        $_SESSION['success'] = 'Registration successful! Please login with your credentials.';
        
        $stmt->close();
        closeDBConnection($conn);
        
        // Redirect to login page
        header('Location: ../login.php');
        exit;
    } else {
        throw new Exception('Failed to create account');
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Registration error: ' . $e->getMessage();
    header('Location: ../register.php');
    exit;
}
?>
