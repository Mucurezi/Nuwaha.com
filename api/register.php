<?php
/**
 * User Registration Handler
 * Handles new user registration with validation and security
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
    header('Location: ../register.php');
    exit;
}

// Sanitize and get form data
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$user_role = trim($_POST['user_role'] ?? '');

// Validation array to collect all errors
$errors = [];

// Validate required fields
if (empty($username)) $errors[] = 'Username is required';
if (empty($email)) $errors[] = 'Email is required';
if (empty($password)) $errors[] = 'Password is required';
if (empty($confirm_password)) $errors[] = 'Password confirmation is required';
if (empty($user_role)) $errors[] = 'User role is required';

// If basic validation fails, return early
if (!empty($errors)) {
    $_SESSION['error'] = implode(', ', $errors);
    header('Location: ../register.php');
    exit;
}

// Validate username
if (strlen($username) < 3) {
    $errors[] = 'Username must be at least 3 characters';
}
if (strlen($username) > 50) {
    $errors[] = 'Username must not exceed 50 characters';
}
if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errors[] = 'Username can only contain letters, numbers, and underscores';
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

// Validate password match
if ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match';
}

// Validate password strength
if (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters';
}
if (!preg_match('/[A-Z]/', $password)) {
    $errors[] = 'Password must contain at least one uppercase letter';
}
if (!preg_match('/[a-z]/', $password)) {
    $errors[] = 'Password must contain at least one lowercase letter';
}
if (!preg_match('/[0-9]/', $password)) {
    $errors[] = 'Password must contain at least one number';
}

// Validate user role
$allowed_roles = ['student', 'parent'];
if (!in_array($user_role, $allowed_roles)) {
    $errors[] = 'Invalid user role selected';
}

// If validation errors exist, return them
if (!empty($errors)) {
    $_SESSION['error'] = implode('. ', $errors);
    header('Location: ../register.php');
    exit;
}

// Database operations
try {
    $conn = getDBConnection();
    
    // Check if username already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    if (!$stmt) {
        throw new Exception('Database error: Unable to prepare statement');
    }
    
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        closeDBConnection($conn);
        $_SESSION['error'] = 'Username already exists. Please choose another.';
        header('Location: ../register.php');
        exit;
    }
    $stmt->close();
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    if (!$stmt) {
        throw new Exception('Database error: Unable to prepare statement');
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        closeDBConnection($conn);
        $_SESSION['error'] = 'Email already registered. Please use another email or login.';
        header('Location: ../register.php');
        exit;
    }
    $stmt->close();
    
    // Hash password with bcrypt
    $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    
    if (!$password_hash) {
        throw new Exception('Password hashing failed');
    }
    
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, user_role, status, created_at) VALUES (?, ?, ?, ?, 'active', NOW())");
    
    if (!$stmt) {
        throw new Exception('Database error: Unable to prepare insert statement');
    }
    
    $stmt->bind_param("ssss", $username, $email, $password_hash, $user_role);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to create user account');
    }
    
    $user_id = $stmt->insert_id;
    $stmt->close();
    closeDBConnection($conn);
    
    // Log successful registration
    error_log("New user registered: ID=$user_id, Username=$username, Role=$user_role");
    
    // Set success message
    $_SESSION['success'] = 'Registration successful! Please login with your credentials.';
    
    // Redirect to login page
    header('Location: ../login.php');
    exit;
    
} catch (Exception $e) {
    // Log the error
    error_log("Registration Error: " . $e->getMessage());
    
    // Show user-friendly error
    $_SESSION['error'] = 'Registration failed. Please try again later.';
    header('Location: ../register.php');
    exit;
}
?>
