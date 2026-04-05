-- Use existing database
USE muteesaiidb;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    user_role ENUM('student', 'parent', 'teacher', 'admin') NOT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    profile_picture VARCHAR(255) NULL
);

-- Admissions table
CREATE TABLE IF NOT EXISTS admissions (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    class_applying_for VARCHAR(50) NOT NULL,
    parent_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected', 'interview_scheduled') DEFAULT 'pending',
    reviewed_by INT NULL,
    review_date TIMESTAMP NULL,
    notes TEXT NULL,
    FOREIGN KEY (reviewed_by) REFERENCES users(user_id)
);

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    replied_by INT NULL,
    reply_date TIMESTAMP NULL,
    FOREIGN KEY (replied_by) REFERENCES users(user_id)
);

-- Students table
CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    admission_number VARCHAR(50) UNIQUE NOT NULL,
    admission_date DATE NOT NULL,
    current_class VARCHAR(50) NOT NULL,
    section VARCHAR(10) NULL,
    blood_group VARCHAR(5) NULL,
    address TEXT NULL,
    photo VARCHAR(255) NULL,
    status ENUM('active', 'graduated', 'transferred', 'expelled') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Insert sample admin user (password: admin123)
INSERT INTO users (username, email, password_hash, user_role, status) 
VALUES ('admin', 'admin@muteesa2school.ac.ug', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');

-- Insert sample teacher user (password: teacher123)
INSERT INTO users (username, email, password_hash, user_role, status) 
VALUES ('teacher1', 'teacher@muteesa2school.ac.ug', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active');
