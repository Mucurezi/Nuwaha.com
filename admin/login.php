<?php
/**
 * Admin Login Page
 * Only for admin users
 */

session_start();

// If already logged in as admin, redirect to dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header('Location: index.php');
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../config/database.php';
    
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        try {
            $conn = getDBConnection();
            
            $stmt = $conn->prepare("SELECT user_id, username, email, password_hash, user_role, status FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                $error = 'Invalid credentials';
            } else {
                $user = $result->fetch_assoc();
                
                // Check if user is admin
                if ($user['user_role'] !== 'admin') {
                    $error = 'Access denied. Admin privileges required.';
                } elseif (!password_verify($password, $user['password_hash'])) {
                    $error = 'Invalid credentials';
                } elseif ($user['status'] !== 'active') {
                    $error = 'Account is ' . $user['status'];
                } else {
                    // Login successful
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_role'] = $user['user_role'];
                    $_SESSION['login_time'] = time();
                    
                    // Update last login
                    $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
                    $updateStmt->bind_param("i", $user['user_id']);
                    $updateStmt->execute();
                    
                    error_log("Admin login: " . $user['username']);
                    
                    header('Location: index.php');
                    exit;
                }
            }
            
            $stmt->close();
            closeDBConnection($conn);
            
        } catch (Exception $e) {
            $error = 'Login error. Please try again.';
            error_log("Admin login error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Muteesa II Memorial School</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2c5f2d 0%, #1e4620 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header i {
            font-size: 50px;
            color: #2c5f2d;
            margin-bottom: 15px;
        }
        .login-header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-group input:focus {
            outline: none;
            border-color: #2c5f2d;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #2c5f2d;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-login:hover {
            background: #1e4620;
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .login-footer a {
            color: #2c5f2d;
            text-decoration: none;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-shield-alt"></i>
            <h1>Admin Panel</h1>
            <p>Muteesa II Memorial School</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Username or Email</label>
                <input type="text" name="username" required autofocus>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login to Admin Panel
            </button>
        </form>
        
        <div class="login-footer">
            <a href="../index.html"><i class="fas fa-home"></i> Back to Website</a>
        </div>
    </div>
</body>
</html>
