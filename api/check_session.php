<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'logged_in' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => true,
        'logged_in' => true,
        'user' => [
            'user_id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['user_role']
        ]
    ]);
} else {
    echo json_encode([
        'success' => true,
        'logged_in' => false
    ]);
}
?>
