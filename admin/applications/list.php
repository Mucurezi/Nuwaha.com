<?php
/**
 * Applications List
 * View all admission applications
 */

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../../config/database.php';

// Get filter parameters
$status_filter = $_GET['status'] ?? 'all';
$search = $_GET['search'] ?? '';
$class_filter = $_GET['class'] ?? 'all';

// Build query
$query = "SELECT a.*, u.username FROM admissions a 
          LEFT JOIN users u ON a.submitted_by = u.user_id 
          WHERE 1=1";

if ($status_filter !== 'all') {
    $query .= " AND a.status = '" . $conn->real_escape_string($status_filter) . "'";
}

if (!empty($search)) {
    $search_term = $conn->real_escape_string($search);
    $query .= " AND (a.student_name LIKE '%$search_term%' OR a.parent_name LIKE '%$search_term%' OR a.email LIKE '%$search_term%')";
}

if ($class_filter !== 'all') {
    $query .= " AND a.class_applying_for = '" . $conn->real_escape_string($class_filter) . "'";
}

$query .= " ORDER BY a.application_date DESC";

try {
    $conn = getDBConnection();
    $result = $conn->query($query);
    
    // Get counts for filters
    $total_count = $conn->query("SELECT COUNT(*) as count FROM admissions")->fetch_assoc()['count'];
    $pending_count = $conn->query("SELECT COUNT(*) as count FROM admissions WHERE status = 'pending'")->fetch_assoc()['count'];
    $approved_count = $conn->query("SELECT COUNT(*) as count FROM admissions WHERE status = 'approved'")->fetch_assoc()['count'];
    $rejected_count = $conn->query("SELECT COUNT(*) as count FROM admissions WHERE status = 'rejected'")->fetch_assoc()['count'];
    
} catch (Exception $e) {
    $error = "Error loading applications: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1><i class="fas fa-file-alt"></i> Admission Applications</h1>
                <p>Manage all admission applications</p>
            </div>
            
            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <a href="?status=all" class="filter-tab <?php echo $status_filter === 'all' ? 'active' : ''; ?>">
                    All (<?php echo $total_count; ?>)
                </a>
                <a href="?status=pending" class="filter-tab <?php echo $status_filter === 'pending' ? 'active' : ''; ?>">
                    Pending (<?php echo $pending_count; ?>)
                </a>
                <a href="?status=approved" class="filter-tab <?php echo $status_filter === 'approved' ? 'active' : ''; ?>">
                    Approved (<?php echo $approved_count; ?>)
                </a>
                <a href="?status=rejected" class="filter-tab <?php echo $status_filter === 'rejected' ? 'active' : ''; ?>">
                    Rejected (<?php echo $rejected_count; ?>)
                </a>
            </div>
            
            <!-- Search and Export -->
            <div class="card">
                <div class="card-body">
                    <div class="actions-bar">
                        <form method="GET" class="search-form">
                            <input type="text" name="search" placeholder="Search by student or parent name..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </form>
                        <a href="../export_applications.php" class="btn btn-primary">
                            <i class="fas fa-download"></i> Export to Excel
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Applications Table -->
            <div class="card">
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-error"><?php echo $error; ?></div>
                    <?php elseif ($result && $result->num_rows > 0): ?>
                        <table class="data-table">
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($app = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $app['application_id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($app['student_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($app['class_applying_for']); ?></td>
                                    <td><?php echo htmlspecialchars($app['parent_name']); ?></td>
                                    <td><?php echo htmlspecialchars($app['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($app['email']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($app['application_date'])); ?></td>
                                    <td><span class="badge badge-<?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span></td>
                                    <td>
                                        <a href="view.php?id=<?php echo $app['application_id']; ?>" class="btn-icon" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="no-data">No applications found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <style>
        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .filter-tab {
            padding: 10px 20px;
            background: white;
            border-radius: 5px;
            text-decoration: none;
            color: #666;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .filter-tab.active {
            background: #2c5f2d;
            color: white;
        }
        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }
        .search-form {
            display: flex;
            gap: 10px;
            flex: 1;
        }
        .search-form input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</body>
</html>
<?php if (isset($conn)) closeDBConnection($conn); ?>
