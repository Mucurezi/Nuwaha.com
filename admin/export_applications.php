<?php
/**
 * Simple Export Tool for Admissions
 * Exports all applications to CSV file
 */

session_start();

// Simple password protection
$admin_password = "admin123"; // Change this!

if (!isset($_POST['password']) && !isset($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Export Applications - Admin Access</title>
        <style>
            body { font-family: Arial; max-width: 400px; margin: 100px auto; padding: 20px; }
            input { width: 100%; padding: 10px; margin: 10px 0; }
            button { width: 100%; padding: 12px; background: #2c5f2d; color: white; border: none; cursor: pointer; }
            button:hover { background: #1e4620; }
        </style>
    </head>
    <body>
        <h2>Admin Access Required</h2>
        <form method="POST">
            <input type="password" name="password" placeholder="Enter admin password" required>
            <button type="submit">Login</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

if (isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        die("Wrong password!");
    }
}

require_once '../config/database.php';

try {
    $conn = getDBConnection();
    
    // Get all applications with user info
    $query = "SELECT 
        a.application_id,
        a.student_name,
        a.date_of_birth,
        a.gender,
        a.class_applying_for,
        a.parent_name,
        a.relationship,
        a.phone,
        a.email,
        a.address,
        a.previous_school,
        a.medical_conditions,
        a.special_needs,
        a.application_date,
        a.status,
        u.username as submitted_by_username,
        u.email as submitted_by_email
    FROM admissions a
    LEFT JOIN users u ON a.submitted_by = u.user_id
    ORDER BY a.application_date DESC";
    
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=admissions_export_' . date('Y-m-d_H-i-s') . '.csv');
    
    // Create output stream
    $output = fopen('php://output', 'w');
    
    // Add BOM for Excel UTF-8 support
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Add column headers
    fputcsv($output, [
        'Application ID',
        'Student Name',
        'Date of Birth',
        'Age',
        'Gender',
        'Class Applying For',
        'Parent/Guardian Name',
        'Relationship',
        'Phone',
        'Email',
        'Address',
        'Previous School',
        'Medical Conditions',
        'Special Needs',
        'Application Date',
        'Status',
        'Submitted By (Username)',
        'Submitted By (Email)'
    ]);
    
    // Add data rows
    while ($row = $result->fetch_assoc()) {
        // Calculate age
        $dob = new DateTime($row['date_of_birth']);
        $now = new DateTime();
        $age = $now->diff($dob)->y;
        
        fputcsv($output, [
            $row['application_id'],
            $row['student_name'],
            $row['date_of_birth'],
            $age . ' years',
            ucfirst($row['gender']),
            $row['class_applying_for'],
            $row['parent_name'],
            ucfirst($row['relationship']),
            $row['phone'],
            $row['email'],
            $row['address'],
            $row['previous_school'] ?: 'N/A',
            $row['medical_conditions'] ?: 'None',
            $row['special_needs'] ?: 'None',
            $row['application_date'],
            ucfirst($row['status']),
            $row['submitted_by_username'] ?: 'N/A',
            $row['submitted_by_email'] ?: 'N/A'
        ]);
    }
    
    fclose($output);
    closeDBConnection($conn);
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
