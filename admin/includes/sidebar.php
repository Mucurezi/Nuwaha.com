<?php
// Determine the base path based on current location
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
$base_path = ($current_dir === 'admin') ? '' : '../';
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-graduation-cap"></i>
        <span>Admin Panel</span>
    </div>
    
    <nav class="sidebar-nav">
        <a href="<?php echo $base_path; ?>index.php" class="nav-item">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="<?php echo $base_path; ?>applications/list.php" class="nav-item">
            <i class="fas fa-file-alt"></i>
            <span>Applications</span>
            <?php if (isset($pending_apps) && $pending_apps > 0): ?>
                <span class="badge"><?php echo $pending_apps; ?></span>
            <?php endif; ?>
        </a>
        
        <a href="<?php echo $base_path; ?>export_applications.php" class="nav-item">
            <i class="fas fa-download"></i>
            <span>Export Data</span>
        </a>
        
        <a href="<?php echo $base_path; ?>logout.php" class="nav-item nav-logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </nav>
</aside>

<script>
document.getElementById('menuToggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('collapsed');
});
</script>
