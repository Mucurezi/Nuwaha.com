<?php
// Contact form submission
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method';
    header('Location: ../contact.html');
    exit;
}

// Get and sanitize form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate required fields
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    $_SESSION['error'] = 'All fields are required';
    header('Location: ../contact.html');
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email address';
    header('Location: ../contact.html');
    exit;
}

// Validate message length
if (strlen($message) < 10) {
    $_SESSION['error'] = 'Message must be at least 10 characters long';
    header('Location: ../contact.html');
    exit;
}

if (strlen($message) > 5000) {
    $_SESSION['error'] = 'Message is too long (maximum 5000 characters)';
    header('Location: ../contact.html');
    exit;
}

try {
    $conn = getDBConnection();
    
    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, submitted_at, status) VALUES (?, ?, ?, ?, NOW(), 'new')");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    
    if ($stmt->execute()) {
        $stmt->close();
        closeDBConnection($conn);
        $_SESSION['success'] = 'Thank you for your message! We will get back to you soon.';
        header('Location: ../contact.html');
        exit;
    } else {
        throw new Exception('Failed to send message');
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = 'An error occurred while sending your message. Please try again later.';
    header('Location: ../contact.html');
    exit;
}
?>
