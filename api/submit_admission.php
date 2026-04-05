<?php
/**
 * Admission Application Handler
 * Processes admission applications from logged-in users
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
    header('Location: ../admission.php');
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    $_SESSION['error'] = 'You must be logged in to submit an application. Please login first.';
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get and sanitize form data
$student_name = trim($_POST['student_name'] ?? '');
$date_of_birth = trim($_POST['date_of_birth'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$class_applying_for = trim($_POST['class_applying_for'] ?? '');
$parent_name = trim($_POST['parent_name'] ?? '');
$relationship = trim($_POST['relationship'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');
$previous_school = trim($_POST['previous_school'] ?? '');
$medical_conditions = trim($_POST['medical_conditions'] ?? '');
$special_needs = trim($_POST['special_needs'] ?? '');

// Validation array
$errors = [];

// Validate required fields
if (empty($student_name)) $errors[] = 'Student name is required';
if (empty($date_of_birth)) $errors[] = 'Date of birth is required';
if (empty($gender)) $errors[] = 'Gender is required';
if (empty($class_applying_for)) $errors[] = 'Class is required';
if (empty($parent_name)) $errors[] = 'Parent/Guardian name is required';
if (empty($relationship)) $errors[] = 'Relationship is required';
if (empty($phone)) $errors[] = 'Phone number is required';
if (empty($email)) $errors[] = 'Email is required';
if (empty($address)) $errors[] = 'Address is required';

// If basic validation fails, return early
if (!empty($errors)) {
    $_SESSION['error'] = implode('. ', $errors);
    header('Location: ../admission.php');
    exit;
}

// Validate student name
if (strlen($student_name) < 3) {
    $errors[] = 'Student name must be at least 3 characters';
}
if (!preg_match('/^[a-zA-Z\s]+$/', $student_name)) {
    $errors[] = 'Student name can only contain letters and spaces';
}

// Validate date of birth
$dob = DateTime::createFromFormat('Y-m-d', $date_of_birth);
if (!$dob || $dob->format('Y-m-d') !== $date_of_birth) {
    $errors[] = 'Invalid date of birth format';
} else {
    // Check age (must be between 3 and 25 years old)
    $today = new DateTime();
    $age = $today->diff($dob)->y;
    
    if ($age < 3) {
        $errors[] = 'Student must be at least 3 years old';
    }
    if ($age > 25) {
        $errors[] = 'Student age cannot exceed 25 years';
    }
    
    // Check if date is not in the future
    if ($dob > $today) {
        $errors[] = 'Date of birth cannot be in the future';
    }
}

// Validate gender
$valid_genders = ['male', 'female', 'other'];
if (!in_array(strtolower($gender), $valid_genders)) {
    $errors[] = 'Invalid gender selection';
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

// Validate phone number (basic validation)
$phone_clean = preg_replace('/[^0-9+]/', '', $phone);
if (strlen($phone_clean) < 10) {
    $errors[] = 'Phone number must be at least 10 digits';
}

// Validate relationship
$valid_relationships = ['father', 'mother', 'guardian', 'other'];
if (!in_array(strtolower($relationship), $valid_relationships)) {
    $errors[] = 'Invalid relationship selection';
}

// If validation errors exist, return them
if (!empty($errors)) {
    $_SESSION['error'] = implode('. ', $errors);
    header('Location: ../admission.php');
    exit;
}

// Database operations
try {
    $conn = getDBConnection();
    
    // Check if user already has a pending application
    $checkStmt = $conn->prepare("SELECT application_id, student_name FROM admissions WHERE submitted_by = ? AND status = 'pending' LIMIT 1");
    
    if (!$checkStmt) {
        throw new Exception('Database error: Unable to prepare check statement');
    }
    
    $checkStmt->bind_param("i", $user_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $existing = $checkResult->fetch_assoc();
        $checkStmt->close();
        closeDBConnection($conn);
        
        $_SESSION['error'] = 'You already have a pending application for ' . htmlspecialchars($existing['student_name']) . '. Please wait for review before submitting another.';
        header('Location: ../admission.php');
        exit;
    }
    $checkStmt->close();
    
    // Insert admission application
    $stmt = $conn->prepare("INSERT INTO admissions (
        student_name, date_of_birth, gender, class_applying_for,
        parent_name, relationship, phone, email, address,
        previous_school, medical_conditions, special_needs,
        application_date, status, submitted_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pending', ?)");
    
    if (!$stmt) {
        throw new Exception('Database error: Unable to prepare insert statement');
    }
    
    $stmt->bind_param("ssssssssssssi",
        $student_name, $date_of_birth, $gender, $class_applying_for,
        $parent_name, $relationship, $phone, $email, $address,
        $previous_school, $medical_conditions, $special_needs,
        $user_id
    );
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to submit application: ' . $stmt->error);
    }
    
    $application_id = $stmt->insert_id;
    $stmt->close();
    closeDBConnection($conn);
    
    // Log successful submission
    error_log("Admission application submitted: ID=$application_id, User=$user_id, Student=$student_name");
    
    // Set success message
    $_SESSION['success'] = 'Application submitted successfully! Your Application ID is: ' . $application_id . '. We will contact you at ' . htmlspecialchars($email) . ' soon.';
    
    header('Location: ../admission.php');
    exit;
    
} catch (Exception $e) {
    // Log the error
    error_log("Admission Submission Error: " . $e->getMessage());
    
    // Show user-friendly error
    $_SESSION['error'] = 'Failed to submit application. Please try again later.';
    header('Location: ../admission.php');
    exit;
}
?>
