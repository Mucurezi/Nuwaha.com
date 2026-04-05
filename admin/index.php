<?php
/**
 * Admin Panel - Dashboard
 * Hidden URL: /admin
 * Requires admin role to access
 */

session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

// Get statistics
try {
    $conn = getDBConnection();
    
    // Total applications
    $total_apps = $conn->query("SELECT COUNT(*) as count FROM admissions")->fetch_assoc()['count'];
    
    // Pending applications
    $pending_apps = $conn->query("SELECT COUNT(*) as count FROM admissions WHERE status = 'pending'")->fetch_assoc()['count'];
    
    // Approved applications
    $approved_apps = $conn->query("SELECT COUNT(*) as count FROM admissions WHERE status = 'approved'")->fetch_assoc()['count'];
    
    // Total users
    $total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
    
    // Today's applications
    $today_apps = $conn->query("SELECT COUNT(*) as count FROM admissions WHERE DATE(application_date) = CURDATE()")->fetch_assoc()['count'];
    
    // This week's applications
    $week_apps = $conn->query("SELECT COUNT(*) as count FROM admissions WHERE YEARWEEK(application_date) = YEARWEEK(NOW())")->fetch_assoc()['count'];
    
    // Recent applications
    $recent_apps = $conn->query("SELECT a.*, u.username FROM admissions a LEFT JOIN users u ON a.submitted_by = u.user_id ORDER BY a.application_date DESC LIMIT 5");
    
    closeDBConnection($conn);
    
} catch (Exception $e) {
    $error = "Error loading dashboard: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Muteesa II Memorial School</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1><i class="fas fa-chart-line"></i> Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="stat-details">
                        <h3><?php echo $total_apps; ?></h3>
                        <p>Total Applications</p>
                    </div>
                </div>
                
                <div class="stat-card orange">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-details">
                        <h3><?php echo $pending_apps; ?></h3>
                        <p>Pending Review</p>
                    </div>
                </div>
                
                <div class="stat-card green">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-details">
                        <h3><?php echo $approved_apps; ?></h3>
                        <p>Approved</p>
                    </div>
                </div>
                
                <div class="stat-card purple">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-details">
                        <h3><?php echo $total_users; ?></h3>
                        <p>Registered Users</p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="quick-stats">
                <div class="quick-stat-item">
                    <i class="fas fa-calendar-day"></i>
                    <span><strong><?php echo $today_apps; ?></strong> applications today</span>
                </div>
                <div class="quick-stat-item">
                    <i class="fas fa-calendar-week"></i>
                    <span><strong><?php echo $week_apps; ?></strong> applications this week</span>
                </div>
            </div>
            
            <!-- Recent Applications -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-list"></i> Recent Applications</h2>
                    <a href="applications/list.php" class="btn btn-primary btn-sm">View All</a>
                </div>
                <div class="card-body">
                    <?php if ($recent_apps->num_rows > 0): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th>Class</th>
                                    <th>Parent</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($app = $recent_apps->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $app['application_id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($app['student_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($app['class_applying_for']); ?></td>
                                    <td><?php echo htmlspecialchars($app['parent_name']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($app['application_date'])); ?></td>
                                    <td><span class="badge badge-<?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span></td>
                                    <td>
                                        <a href="applications/view.php?id=<?php echo $app['application_id']; ?>" class="btn-icon" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-data">No applications yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
