-- Create Admin User for Admin Panel
-- Run this in phpMyAdmin to create your first admin user

USE muteesaiidb;

-- Create admin user
-- Username: admin
-- Password: Admin@123
-- IMPORTANT: Change this password after first login!

INSERT INTO users (username, email, password_hash, user_role, status, created_at) 
VALUES (
    'admin', 
    'admin@muteesa2school.ac.ug', 
    '$2y$12$LQv3c1yduTi6xUrVGkz2L.JQIvr/HNe8J04B8qCOk.O9wV.1fLlHK', 
    'admin', 
    'active', 
    NOW()
);

-- Verify the admin user was created
SELECT user_id, username, email, user_role, status, created_at 
FROM users 
WHERE user_role = 'admin';

-- Show success message
SELECT 'Admin user created successfully!' AS Status,
       'Username: admin' AS Username,
       'Password: Admin@123' AS Password,
       'CHANGE PASSWORD AFTER FIRST LOGIN!' AS Important;
