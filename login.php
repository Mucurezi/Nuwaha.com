<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Muteesa II Memorial School</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <h2>Muteesa II Memorial School</h2>
                <p>Mutundwe</p>
            </div>
            <button class="menu-toggle" id="menuToggle" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-links" id="navLinks">
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="admission.html">Admission</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="login.php" class="login-btn active">Login</a></li>
            </ul>
        </div>
    </nav>

    <section class="login-section">
        <div class="login-container">
            <div class="login-box">
                <h2>Login</h2>
                <p>Access your account</p>
                
                <?php
                session_start();
                if (isset($_SESSION['error'])) {
                    echo '<div style="background:#f8d7da;color:#721c24;padding:15px;border-radius:4px;margin-bottom:20px;border:1px solid #f5c6cb;">';
                    echo '<strong>Error:</strong> ' . htmlspecialchars($_SESSION['error']);
                    echo '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div style="background:#d4edda;color:#155724;padding:15px;border-radius:4px;margin-bottom:20px;border:1px solid #c3e6cb;">';
                    echo '<strong>Success:</strong> ' . htmlspecialchars($_SESSION['success']);
                    echo '</div>';
                    unset($_SESSION['success']);
                }
                ?>
                
                <form method="POST" action="api/login.php">
                    <div class="form-group">
                        <label>Username or Email</label>
                        <input type="text" name="username" required autocomplete="username">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required autocomplete="current-password">
                    </div>
                    <div class="form-options">
                        <label class="checkbox">
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>
                    <button type="submit" class="submit-btn">Login</button>
                </form>
                <div class="login-footer">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Muteesa II Memorial School</h3>
                    <p>Mutundwe, Kampala, Uganda</p>
                    <p>Excellence in Education</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="about.html">About Us</a></li>
                        <li><a href="admission.php">Admissions</a></li>
                        <li><a href="gallery.html">Gallery</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <a href="https://facebook.com" target="_blank" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="https://twitter.com" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="https://instagram.com" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://tiktok.com" target="_blank" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                        <a href="https://wa.me/256XXXXXXXXX" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://youtube.com" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Muteesa II Memorial School - Mutundwe. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
