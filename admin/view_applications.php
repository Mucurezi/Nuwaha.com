<?php
/**
 * Simple Admin View for Applications
 * View all submitted applications
 */

session_start();

// Simple password protection
$admin_password = "admin123"; // Change this!

if (!isset($_POST['password']) && !isset($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>View Applications - Admin Access</title>
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
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Applications - Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .header { background: #2c5f2d; color: white; padding: 20px; margin-bottom: 20px; border-radius: 5px; }
        .header h1 { margin-bottom: 10px; }
        .stats { display: flex; gap: 20px; margin-bottom: 20px; }
        .stat-box { background: white; padding: 20px; border-radius: 5px; flex: 1; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stat-box h3 { color: #666; font-size: 14px; margin-bottom: 10px; }
        .stat-box .number { font-size: 32px; color: #2c5f2d; font-weight: bold; }
        .actions { margin-bottom: 20px; }
        .btn { padding: 10px 20px; background: #2c5f2d; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin-right: 10px; }
        .btn:hover { background: #1e4620; }
        .btn-secondary { background: #666; }
        table { width: 100%; background: white; border-collapse: collapse; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th { background: #2c5f2d; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #ddd; }
        tr:hover { background: #f9f9f9; }
        .status { padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .view-btn { padding: 5px 10px; background: #007bff; color: white; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .view-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Admission Applications</h1>
        <p>Muteesa II Memorial School - Admin Panel</p>
    </div>

    <?php
    try {
        $conn = getDBConnection();
        
        // Get statistics
        $total_query = "SELECT COUNT(*) as total FROM admissions";
        $pending_query = "SELECT COUNT(*) as pending FROM admissions WHERE status = 'pending'";
        $approved_query = "SELECT COUNT(*) as approved FROM admissions WHERE status = 'approved'";
        
        $total = $conn->query($total_query)->fetch_assoc()['total'];
        $pending = $conn->query($pending_query)->fetch_assoc()['pending'];
        $approved = $conn->query($approved_query)->fetch_assoc()['approved'];
        ?>
        
        <div class="stats">
            <div class="stat-box">
                <h3>Total Applications</h3>
                <div class="number"><?php echo $total; ?></div>
            </div>
            <div class="stat-box">
                <h3>Pending Review</h3>
                <div class="number"><?php echo $pending; ?></div>
            </div>
            <div class="stat-box">
                <h3>Approved</h3>
                <div class="number"><?php echo $approved; ?></div>
            </div>
        </div>

        <div class="actions">
            <a href="export_applications.php" class="btn">📥 Export to Excel</a>
            <a href="?logout=1" class="btn btn-secondary">🚪 Logout</a>
        </div>

        <?php
        // Handle logout
        if (isset($_GET['logout'])) {
            unset($_SESSION['admin_logged_in']);
            header('Location: view_applications.php');
            exit;
        }
        
        // Get all applications
        $query = "SELECT 
            a.*,
            u.username as submitted_by_username
        FROM admissions a
        LEFT JOIN users u ON a.submitted_by = u.user_id
        ORDER BY a.application_date DESC";
        
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Parent/Guardian</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Date Applied</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['application_id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['student_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['class_applying_for']); ?></td>
                        <td><?php echo htmlspecialchars($row['parent_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['application_date'])); ?></td>
                        <td>
                            <span class="status status-<?php echo $row['status']; ?>">
                                <?php echo strtoupper($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="view_details.php?id=<?php echo $row['application_id']; ?>" class="view-btn">View Details</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php
        } else {
            echo "<p style='text-align:center; padding:40px; background:white;'>No applications found.</p>";
        }
        
        closeDBConnection($conn);
        
    } catch (Exception $e) {
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>
