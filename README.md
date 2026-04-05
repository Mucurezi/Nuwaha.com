# Muteesa II Memorial School - Mutundwe

A complete school management system with user authentication and admission processing.

## Features

- User Registration & Authentication
- Secure Login System
- Admission Application Form
- Contact Form
- Gallery
- Responsive Design

## Setup Instructions

### 1. Database Setup
1. Start XAMPP (Apache + MySQL)
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Create database: `muteesaiidb`
4. Import schema: Run SQL from `database/schema.sql`
5. Add submitted_by column: Run SQL from `database/update_admissions_table.sql`

### 2. Configuration
- Database settings are in `config/database.php`
- Default settings:
  - Host: localhost
  - User: root
  - Password: (empty)
  - Database: muteesaiidb

### 3. Access the System
Open in browser: `http://localhost/your-project-folder/`

## User Flow

### New User Registration
1. Go to homepage → Click "Apply Now"
2. Fill registration form:
   - Username (3-50 characters)
   - Email
   - Password (min 8 chars, 1 uppercase, 1 lowercase, 1 number)
   - Confirm Password
   - Select Role (Parent or Student)
3. Click "Register"
4. Automatically logged in and redirected to admission page

### Existing User Login
1. Click "Login" in navigation
2. Enter username/email and password
3. Click "Login"
4. Redirected to admission page

### Submit Admission Application
1. After login, fill admission form:
   - Student Name
   - Date of Birth
   - Class Applying For
   - Parent/Guardian Name
   - Phone Number
   - Email
2. Click "Submit Application"
3. Application saved with your user ID

## Security Features

- ✅ Password hashing with bcrypt (cost 12)
- ✅ SQL injection prevention (prepared statements)
- ✅ Input sanitization and validation
- ✅ Session management
- ✅ Login required for admissions
- ✅ Email validation
- ✅ Password strength requirements

## Default Admin Account

- Username: `admin`
- Email: `admin@muteesa2school.ac.ug`
- Password: `admin123`

## File Structure

```
project/
├── index.html              # Homepage
├── about.html              # About page
├── admission.html          # Admission form (requires login)
├── contact.html            # Contact form
├── gallery.html            # Photo gallery
├── login.html              # Login page
├── register.html           # Registration page
├── css/
│   └── style.css          # Styles
├── js/
│   └── script.js          # JavaScript
├── api/
│   ├── register_simple.php        # User registration
│   ├── login_simple.php           # User login
│   ├── logout.php                 # Logout
│   ├── check_session.php          # Session check
│   ├── submit_admission_simple.php # Admission submission
│   ├── submit_contact.php         # Contact form
│   ├── change_password.php        # Password change
│   ├── get_user_profile.php       # User profile
│   └── utils.php                  # Utility functions
├── config/
│   └── database.php       # Database configuration
├── database/
│   ├── schema.sql         # Database schema
│   └── update_admissions_table.sql # Add submitted_by column
└── logs/
    └── .gitkeep           # Error logs directory
```

## Database Tables

### users
- user_id (Primary Key)
- username (Unique)
- email (Unique)
- password_hash (bcrypt)
- user_role (student, parent, teacher, admin)
- status (active, inactive, suspended)
- created_at
- last_login

### admissions
- application_id (Primary Key)
- student_name
- date_of_birth
- class_applying_for
- parent_name
- phone
- email
- application_date
- status (pending, approved, rejected, interview_scheduled)
- submitted_by (Foreign Key → users.user_id)

### contact_messages
- message_id (Primary Key)
- name
- email
- subject
- message
- submitted_at
- status (new, read, replied, archived)

## Password Requirements

- Minimum 8 characters
- At least 1 uppercase letter (A-Z)
- At least 1 lowercase letter (a-z)
- At least 1 number (0-9)

## Support

For issues or questions, contact: info@muteesa2school.ac.ug

## License

© 2026 Muteesa II Memorial School - Mutundwe. All rights reserved.
