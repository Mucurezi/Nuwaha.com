<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'You must be logged in to access the admission form. Please login or register first.';
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission - Muteesa II Memorial School</title>
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
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-links" id="navLinks">
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="admission.php" class="active">Admission</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="register.php">Register</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="api/logout.php" class="login-btn">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="login-btn">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <section class="page-header">
        <h1>Admission Application</h1>
        <p>Complete the form below to apply for admission</p>
    </section>

    <section class="admission-content">
        <div class="container">
            <div class="admission-info">
                <h2>Admission Requirements</h2>
                <ul class="requirements-list">
                    <li><i class="fas fa-check-circle"></i> Completed application form</li>
                    <li><i class="fas fa-check-circle"></i> Birth certificate (copy)</li>
                    <li><i class="fas fa-check-circle"></i> Previous school report (if applicable)</li>
                    <li><i class="fas fa-check-circle"></i> Passport-size photos (2)</li>
                    <li><i class="fas fa-check-circle"></i> Immunization card</li>
                </ul>
            </div>

            <div class="admission-form">
                <h2>Application Form</h2>
                <p style="color: #666; margin-bottom: 20px;">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
                
                <?php
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
                
                <form method="POST" action="api/submit_admission.php">
                    
                    <h3 style="color: #2c5f2d; margin-top: 20px;">Student Information</h3>
                    
                    <div class="form-group">
                        <label>Student Full Name <span style="color:red;">*</span></label>
                        <input type="text" name="student_name" required placeholder="Enter student's full name">
                    </div>
                    
                    <div class="form-group">
                        <label>Date of Birth <span style="color:red;">*</span></label>
                        <input type="date" name="date_of_birth" required max="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Gender <span style="color:red;">*</span></label>
                        <select name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Class Applying For <span style="color:red;">*</span></label>
                        <select name="class_applying_for" required>
                            <option value="">Select Class</option>
                            <option value="Baby Class">Baby Class</option>
                            <option value="Primary 1">Primary 1</option>
                            <option value="Primary 2">Primary 2</option>
                            <option value="Primary 3">Primary 3</option>
                            <option value="Primary 4">Primary 4</option>
                            <option value="Primary 5">Primary 5</option>
                            <option value="Primary 6">Primary 6</option>
                            <option value="Primary 7">Primary 7</option>
                        </select>
                    </div>
                    
                    <h3 style="color: #2c5f2d; margin-top: 30px;">Parent/Guardian Information</h3>
                    
                    <div class="form-group">
                        <label>Parent/Guardian Full Name <span style="color:red;">*</span></label>
                        <input type="text" name="parent_name" required placeholder="Enter parent/guardian full name">
                    </div>
                    
                    <div class="form-group">
                        <label>Relationship to Student <span style="color:red;">*</span></label>
                        <select name="relationship" required>
                            <option value="">Select Relationship</option>
                            <option value="father">Father</option>
                            <option value="mother">Mother</option>
                            <option value="guardian">Guardian</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Phone Number <span style="color:red;">*</span></label>
                        <input type="tel" name="phone" required placeholder="e.g., 0777123456" pattern="[0-9+\-\s()]+">
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address <span style="color:red;">*</span></label>
                        <input type="email" name="email" required placeholder="your.email@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label>Home Address <span style="color:red;">*</span></label>
                        <textarea name="address" rows="3" required placeholder="Enter complete home address"></textarea>
                    </div>
                    
                    <h3 style="color: #2c5f2d; margin-top: 30px;">Additional Information</h3>
                    
                    <div class="form-group">
                        <label>Previous School (if any)</label>
                        <input type="text" name="previous_school" placeholder="Name of previous school">
                    </div>
                    
                    <div class="form-group">
                        <label>Medical Conditions (if any)</label>
                        <textarea name="medical_conditions" rows="2" placeholder="Any medical conditions we should know about"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Special Needs (if any)</label>
                        <textarea name="special_needs" rows="2" placeholder="Any special needs or accommodations required"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" name="terms" required>
                            I confirm that all information provided is accurate and complete <span style="color:red;">*</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="submit-btn">Submit Application</button>
                </form>
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
