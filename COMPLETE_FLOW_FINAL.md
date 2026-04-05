# Complete User Flow - Registration → Login → Admission

## ✅ Final Flow Implementation

### Step 1: Register (register.html)
**User Action:**
1. Goes to register.html
2. Fills registration form:
   - Username
   - Email
   - Password
   - Confirm Password
   - Role (Parent/Student)
3. Clicks "Register"

**What Happens:**
1. Form submits to `api/register_simple.php`
2. PHP validates all inputs
3. Checks if username/email already exists
4. Hashes password with bcrypt (cost 12)
5. Saves user to database
6. **Does NOT auto-login**
7. Shows success message: "Registration successful! Please login with your credentials."
8. **Redirects to login.php**

### Step 2: Login (login.php)
**User Action:**
1. On login.php (redirected from registration)
2. Sees success message from registration
3. Enters credentials:
   - Username or Email (same as registered)
   - Password (same as registered)
4. Clicks "Login"

**What Happens:**
1. Form submits to `api/login_simple.php`
2. PHP finds user in database by username or email
3. Verifies password using `password_verify()` against hashed password
4. If correct:
   - Creates session with user_id, username, user_role
   - Updates last_login timestamp
   - Shows success message: "Login successful! Welcome back, [username]!"
   - **Redirects to admission.html**
5. If incorrect:
   - Shows error message
   - Stays on login.php

### Step 3: Admission (admission.html)
**User Action:**
1. On admission.html (redirected from login)
2. Sees success message from login
3. Fills complete admission form:
   - Student Information (name, DOB, gender, class)
   - Parent/Guardian Information (name, relationship, phone, email, address)
   - Additional Information (previous school, medical conditions, special needs)
4. Clicks "Submit Application"

**What Happens:**
1. Form submits to `api/submit_admission_simple.php`
2. PHP checks if user is logged in (session check)
3. If not logged in → redirects to register.html
4. If logged in:
   - Validates all required fields
   - Checks age (minimum 3 years old)
   - Checks for duplicate pending applications
   - Saves to database with `submitted_by` = user_id
   - Shows success message with Application ID
   - Stays on admission.html

## Complete Flow Diagram

```
┌─────────────────────┐
│   register.html     │
│   Fill Form         │
│   Click Register    │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ api/register_simple │
│ - Validate inputs   │
│ - Hash password     │
│ - Save to database  │
│ - NO auto-login     │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│    login.php        │
│ "Registration       │
│  successful!"       │
│ Enter credentials   │
│ Click Login         │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  api/login_simple   │
│ - Find user         │
│ - Verify password   │
│ - Create session    │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│  admission.html     │
│ "Login successful!" │
│ Fill admission form │
│ Click Submit        │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│api/submit_admission │
│ - Check session     │
│ - Validate data     │
│ - Save with user_id │
│ - Show success      │
└─────────────────────┘
```

## Database Flow

### Registration
```sql
INSERT INTO users (username, email, password_hash, user_role, status, created_at)
VALUES ('testuser', 'test@example.com', '$2y$12$...', 'parent', 'active', NOW());
```

### Login
```sql
SELECT user_id, username, email, password_hash, user_role, status 
FROM users 
WHERE username = 'testuser' OR email = 'testuser';

-- Then verify password with password_verify()
-- If correct, create session
```

### Admission
```sql
INSERT INTO admissions (
    student_name, date_of_birth, gender, class_applying_for,
    parent_name, relationship, phone, email, address,
    previous_school, medical_conditions, special_needs,
    application_date, status, submitted_by
) VALUES (..., NOW(), 'pending', 123);
-- submitted_by = user_id from session
```

## Key Points

✅ **Registration saves data** - User credentials stored in database
✅ **No auto-login** - User must manually login after registration
✅ **Password hashed** - Stored securely with bcrypt
✅ **Login uses saved credentials** - Username/email and password from registration
✅ **Session created on login** - Tracks logged-in user
✅ **Admission requires login** - Must be logged in to submit
✅ **Application linked to user** - submitted_by field stores user_id

## Testing the Flow

1. **Register**:
   - Go to: `http://localhost/your-project/register.html`
   - Username: testuser
   - Email: test@example.com
   - Password: Test1234
   - Confirm: Test1234
   - Role: Parent
   - Click "Register"
   - ✓ Should redirect to login.php with success message

2. **Login**:
   - Already on login.php
   - Username: testuser (or test@example.com)
   - Password: Test1234
   - Click "Login"
   - ✓ Should redirect to admission.html with welcome message

3. **Admission**:
   - Already on admission.html
   - Fill all required fields
   - Click "Submit Application"
   - ✓ Should show success with Application ID
   - ✓ Check database: admissions table has entry with your user_id

## Verify in Database

```sql
-- Check user was created
SELECT * FROM users WHERE username = 'testuser';
-- Should see hashed password (starts with $2y$)

-- Check login session
-- Session is stored in PHP, not database

-- Check admission was saved
SELECT * FROM admissions WHERE submitted_by = [your_user_id];
-- Should see your application linked to your user
```

Your complete flow is working: **Register → Login → Admission**
