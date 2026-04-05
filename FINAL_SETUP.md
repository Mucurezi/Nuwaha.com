# Final Setup - Complete System

## ✅ Files Created

### HTML Forms
1. **register.html** - User registration form
2. **login.html** - User login form  
3. **admission.html** - Complete admission application form

### PHP Handlers
1. **api/register_simple.php** - Processes registration
2. **api/login_simple.php** - Processes login
3. **api/submit_admission_simple.php** - Processes admission

## Database Setup

### Step 1: Run Base Schema
```sql
-- Run: database/schema.sql
```

### Step 2: Add User Link
```sql
-- Run: database/update_admissions_table.sql
ALTER TABLE admissions 
ADD COLUMN submitted_by INT NULL AFTER email;
```

### Step 3: Add New Admission Fields
```sql
-- Run: database/update_admissions_fields.sql
ALTER TABLE admissions 
ADD COLUMN gender ENUM('male', 'female') NULL AFTER date_of_birth,
ADD COLUMN relationship VARCHAR(50) NULL AFTER parent_name,
ADD COLUMN address TEXT NULL AFTER email,
ADD COLUMN previous_school VARCHAR(200) NULL AFTER address,
ADD COLUMN medical_conditions TEXT NULL AFTER previous_school,
ADD COLUMN special_needs TEXT NULL AFTER medical_conditions;
```

## Complete Flow

### 1. Register (register.html)
**Form Fields:**
- Username (3-50 characters)
- Email
- Password (min 8 chars, 1 uppercase, 1 lowercase, 1 number)
- Confirm Password
- Role (Parent/Student)

**What Happens:**
1. User fills form
2. Submits to `api/register_simple.php`
3. PHP validates all inputs
4. Hashes password with bcrypt
5. Saves to `users` table
6. Creates session (auto-login)
7. Redirects to `admission.html`

### 2. Login (login.html)
**Form Fields:**
- Username or Email
- Password

**What Happens:**
1. User enters credentials
2. Submits to `api/login_simple.php`
3. PHP finds user in database
4. Verifies password with `password_verify()`
5. Creates session
6. Redirects to `admission.html`

### 3. Admission (admission.html)
**Form Fields:**

**Student Information:**
- Student Full Name
- Date of Birth
- Gender (Male/Female)
- Class Applying For

**Parent/Guardian Information:**
- Parent/Guardian Full Name
- Relationship to Student
- Phone Number
- Email Address
- Home Address

**Additional Information:**
- Previous School (optional)
- Medical Conditions (optional)
- Special Needs (optional)
- Terms & Conditions checkbox

**What Happens:**
1. Checks if user is logged in
2. If not → redirects to register.html
3. If yes → user fills form
4. Submits to `api/submit_admission_simple.php`
5. PHP validates all fields
6. Saves to `admissions` table with `submitted_by` = user_id
7. Redirects back to `admission.html`
8. Shows success message with Application ID

## Database Tables

### users
```sql
- user_id (Primary Key)
- username (Unique)
- email (Unique)
- password_hash (bcrypt)
- user_role (student, parent, teacher, admin)
- status (active, inactive, suspended)
- created_at
- last_login
```

### admissions
```sql
- application_id (Primary Key)
- student_name
- date_of_birth
- gender
- class_applying_for
- parent_name
- relationship
- phone
- email
- address
- previous_school
- medical_conditions
- special_needs
- application_date
- status (pending, approved, rejected)
- submitted_by (Foreign Key → users.user_id)
```

## Authentication Flow

```
1. User Registers
   ↓
2. Password Hashed (bcrypt cost 12)
   ↓
3. Saved to Database
   ↓
4. Session Created (auto-login)
   ↓
5. User Can Submit Admission
   ↓
6. Admission Linked to User ID
```

## Security Features

✅ Password hashing with bcrypt
✅ SQL injection prevention (prepared statements)
✅ Input validation and sanitization
✅ Session management
✅ Email validation
✅ Age validation (minimum 3 years)
✅ Duplicate application prevention
✅ Login required for admission

## Testing

### Test Registration
1. Open: `http://localhost/your-project/register.html`
2. Fill form:
   - Username: testuser
   - Email: test@example.com
   - Password: Test1234
   - Confirm: Test1234
   - Role: Parent
3. Click "Register"
4. Should redirect to admission.html
5. Check database: user should be in `users` table with hashed password

### Test Login
1. Open: `http://localhost/your-project/login.html`
2. Enter:
   - Username: testuser
   - Password: Test1234
3. Click "Login"
4. Should redirect to admission.html

### Test Admission
1. After login, fill admission form
2. Click "Submit Application"
3. Should show success with Application ID
4. Check database: admission should be in `admissions` table
5. `submitted_by` field should match your user_id

## All Information Stored in Database

✅ User credentials (hashed passwords)
✅ Student information
✅ Parent/Guardian information
✅ Contact details
✅ Medical information
✅ Special needs
✅ Application status
✅ Link between user and application

Your complete school management system is ready!
