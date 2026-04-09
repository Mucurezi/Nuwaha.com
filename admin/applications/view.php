<?php
/**
 * View Application Details
 */

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$app_id = $_GET['id'] ?? 0;

if (!$app_id) {
    header('Location: list.php');
    exit;
}

require_once '../../config/database.php';
require_once '../../config/notification.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $new_status = $_POST['new_status'] ?? '';
    $notes = trim($_POST['notes'] ?? '');
    
    if (in_array($new_status, ['pending', 'approved', 'rejected'])) {
        try {
            $conn = getDBConnection();
            
            // Get application details before updating
            $stmt = $conn->prepare("SELECT student_name, parent_name, email, phone, class_applying_for FROM admissions WHERE application_id = ?");
            $stmt->bind_param("i", $app_id);
            $stmt->execute();
            $app_details = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            
            // Update status
            $stmt = $conn->prepare("UPDATE admissions SET status = ?, notes = ?, reviewed_by = ?, review_date = NOW() WHERE application_id = ?");
            $stmt->bind_param("ssii", $new_status, $notes, $_SESSION['user_id'], $app_id);
            
            if ($stmt->execute()) {
                $success = "Application status updated to: " . ucfirst($new_status);
                
                // Send notifications
                if ($new_status === 'approved' && $app_details) {
                    $notification_results = sendAdmissionApprovalNotification(
                        $app_details['parent_name'],
                        $app_details['student_name'],
                        $app_details['class_applying_for'],
                        $app_details['email'],
                        $app_details['phone']
                    );
                    
                    if ($notification_results['email']) {
                        $success .= " Email notification sent successfully.";
                    } else {
                        $success .= " (Email notification failed - check logs)";
                    }
                    
                    if ($notification_results['sms']) {
                        $success .= " SMS notification sent successfully.";
                    }
                    
                } elseif ($new_status === 'rejected' && $app_details) {
                    $notification_results = sendAdmissionRejectionNotification(
                        $app_details['parent_name'],
                        $app_details['student_name'],
                        $app_details['class_applying_for'],
                        $app_details['email'],
                        $app_details['phone'],
                        $notes
                    );
                    
                    if ($notification_results['email']) {
                        $success .= " Email notification sent successfully.";
                    } else {
                        $success .= " (Email notification failed - check logs)";
                    }
                }
            }
            
            $stmt->close();
            closeDBConnection($conn);
        } catch (Exception $e) {
            $error = "Error updating status: " . $e->getMessage();
        }
    }
}

// Get application details
try {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT a.*, u.username, u.email as user_email FROM admissions a LEFT JOIN users u ON a.submitted_by = u.user_id WHERE a.application_id = ?");
    $stmt->bind_param("i", $app_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header('Location: list.php');
        exit;
    }
    
    $app = $result->fetch_assoc();
    $stmt->close();
    closeDBConnection($conn);
    
} catch (Exception $e) {
    $error = "Error loading application: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application #<?php echo $app_id; ?> - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1><i class="fas fa-file-alt"></i> Application #<?php echo $app_id; ?></h1>
                <a href="list.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="details-grid">
                <!-- Student Information -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-user-graduate"></i> Student Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="detail-row">
                            <strong>Full Name:</strong>
                            <span><?php echo htmlspecialchars($app['student_name']); ?></span>
                        </div>
                        <div class="detail-row">
                            <strong>Date of Birth:</strong>
                            <span><?php echo date('F d, Y', strtotime($app['date_of_birth'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <strong>Gender:</strong>
                            <span><?php echo ucfirst($app['gender']); ?></span>
                        </div>
                        <div class="detail-row">
                            <strong>Class Applying For:</strong>
                            <span><?php echo htmlspecialchars($app['class_applying_for']); ?></span>
                        </div>
                        <div class="detail-row">
                            <strong>Previous School:</strong>
                            <span><?php echo htmlspecialchars($app['previous_school']) ?: 'N/A'; ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Parent/Guardian Information -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-users"></i> Parent/Guardian Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="detail-row">
                            <strong>Name:</strong>
                            <span><?php echo htmlspecialchars($app['parent_name']); ?></span>
                        </div>
                        <div class="detail-row">
                            <strong>Relationship:</strong>
                            <span><?php echo ucfirst($app['relationship']); ?></span>
                        </div>
                        <div class="detail-row">
                            <strong>Phone:</strong>
                            <span><?php echo htmlspecialchars($app['phone']); ?></span>
                        </div>
                        <div class="detail-row">
                            <strong>Email:</strong>
                            <span><?php echo htmlspecialchars($app['email']); ?></span>
                        </div>
                        <div class="detail-row">
                            <strong>Address:</strong>
                            <span><?php echo nl2br(htmlspecialchars($app['address'])); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-info-circle"></i> Additional Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="detail-row">
                            <strong>Medical Conditions:</strong>
                            <span><?php echo nl2br(htmlspecialchars($app['medical_conditions'])) ?: 'None'; ?></span>
                        </div>
                        <div class="detail-row">
                            <strong>Special Needs:</strong>
                            <span><?php echo nl2br(htmlspecialchars($app['special_needs'])) ?: 'None'; ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Application Status -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-clipboard-check"></i> Application Status</h2>
                    </div>
                    <div class="card-body">
                        <div class="detail-row">
                            <strong>Current Status:</strong>
                            <span class="badge badge-<?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span>
                        </div>
                        <div class="detail-row">
                            <strong>Application Date:</strong>
                            <span><?php echo date('F d, Y g:i A', strtotime($app['application_date'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <strong>Submitted By:</strong>
                            <span><?php echo htmlspecialchars($app['username']) ?: 'N/A'; ?></span>
                        </div>
                        
                        <!-- Update Status Form -->
                        <form method="POST" style="margin-top: 20px;">
                            <input type="hidden" name="action" value="update_status">
                            <div class="form-group">
                                <label><strong>Update Status:</strong></label>
                                <select name="new_status" class="form-control" required>
                                    <option value="pending" <?php echo $app['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="approved" <?php echo $app['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="rejected" <?php echo $app['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><strong>Admin Notes:</strong></label>
                                <textarea name="notes" class="form-control" rows="3"><?php echo htmlspecialchars($app['notes'] ?? ''); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <style>
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }
        .detail-row {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-row strong {
            color: #666;
            min-width: 150px;
        }
        .detail-row span {
            flex: 1;
            text-align: right;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</body>
</html>
