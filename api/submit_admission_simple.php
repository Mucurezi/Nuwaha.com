<?php
// Admission submission - requires login
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method';
    header('Location: ../admission.html');
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'You must be logged in to submit an application. Please register or login first.';
    header('Location: ../register.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get form data - Student Information
$student_name = trim($_POST['student_name'] ?? '');
$date_of_birth = trim($_POST['date_of_birth'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$class_applying_for = trim($_POST['class_applying_for'] ?? '');

// Parent/Guardian Information
$parent_name = trim($_POST['parent_name'] ?? '');
$relationship = trim($_POST['relationship'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');

// Additional Information
$previous_school = trim($_POST['previous_school'] ?? '');
$medical_conditions = trim($_POST['medical_conditions'] ?? '');
$special_needs = trim($_POST['special_needs'] ?? '');

// Validate required fields
if (empty($student_name) || empty($date_of_birth) || empty($gender) || empty($class_applying_for) || 
    empty($parent_name) || empty($relationship) || empty($phone) || empty($email) || empty($address)) {
    $_SESSION['error'] = 'All required fields must be filled';
    header('Location: ../admission.html');
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email address';
    header('Location: ../admission.html');
    exit;
}

// Validate date of birth
$dob = DateTime::createFromFormat('Y-m-d', $date_of_birth);
if (!$dob || $dob->format('Y-m-d') !== $date_of_birth) {
    $_SESSION['error'] = 'Invalid date of birth format';
    header('Location: ../admission.html');
    exit;
}

// Check if student is at least 3 years old
$age = $dob->diff(new DateTime())->y;
if ($age < 3) {
    $_SESSION['error'] = 'Student must be at least 3 years old';
    header('Location: ../admission.html');
    exit;
}

try {
    $conn = getDBConnection();
    
    // Check if user already has a pending application
    $checkStmt = $conn->prepare("SELECT application_id FROM admissions WHERE submitted_by = ? AND status = 'pending'");
    $checkStmt->bind_param("i", $user_id);
    $checkStmt->execute();
    if ($checkStmt->get_result()->num_rows > 0) {
        $checkStmt->close();
        closeDBConnection($conn);
        $_SESSION['error'] = 'You already have a pending application. Please wait for review.';
        header('Location: ../admission.html');
        exit;
    }
    $checkStmt->close();
    
    // Prepare SQL statement with all fields
    $stmt = $conn->prepare("INSERT INTO admissions (
        student_name, date_of_birth, gender, class_applying_for, 
        parent_name, relationship, phone, email, address,
        previous_school, medical_conditions, special_needs,
        application_date, status, submitted_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pending', ?)");
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("ssssssssssssi", 
        $student_name, $date_of_birth, $gender, $class_applying_for,
        $parent_name, $relationship, $phone, $email, $address,
        $previous_school, $medical_conditions, $special_needs,
        $user_id
    );
    
    if ($stmt->execute()) {
        $application_id = $stmt->insert_id;
        $stmt->close();
        closeDBConnection($conn);
        
        $_SESSION['success'] = 'Application submitted successfully! Your Application ID is: ' . $application_id . '. We will contact you soon.';
        header('Location: ../admission.html');
        exit;
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = 'An error occurred while submitting your application. Please try again later.';
    error_log("Admission Error: " . $e->getMessage());
    header('Location: ../admission.html');
    exit;
}
?>
