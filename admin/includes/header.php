<header class="admin-header">
    <div class="header-left">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h2>Muteesa II Memorial School</h2>
    </div>
    <div class="header-right">
        <div class="user-menu">
            <i class="fas fa-user-circle"></i>
            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" class="btn-logout" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</header>
