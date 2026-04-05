# Quick Setup Guide

## Step 1: Database Setup (5 minutes)

1. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start Apache
   - Start MySQL

2. **Create Database**
   - Open browser: `http://localhost/phpmyadmin`
   - Click "New" in left sidebar
   - Database name: `muteesaiidb`
   - Click "Create"

3. **Import Schema**
   - Select `muteesaiidb` database
   - Click "SQL" tab
   - Open file: `database/schema.sql`
   - Copy all content and paste
   - Click "Go"

4. **Add User Link Column**
   - Still in SQL tab
   - Open file: `database/update_admissions_table.sql`
   - Copy content and paste
   - Click "Go"

## Step 2: Test the System

### Test Registration Flow
1. Open: `http://localhost/your-project-folder/index.html`
2. Click "Apply Now" button
3. Fill registration form:
   - Username: `testuser`
   - Email: `test@example.com`
   - Password: `Test1234`
   - Confirm Password: `Test1234`
   - Role: Parent
4. Click "Register"
5. ✓ Should show success message
6. ✓ Should redirect to admission page automatically

### Test Login Flow
1. Open: `http://localhost/your-project-folder/login.html`
2. Enter credentials:
   - Username: `testuser` (or email: `test@example.com`)
   - Password: `Test1234`
3. Click "Login"
4. ✓ Should show welcome message
5. ✓ Should redirect to admission page

### Test Admission Submission
1. After login, you're on admission page
2. Fill the form:
   - Student Name: John Doe
   - Date of Birth: 2018-01-15
   - Class: Primary 1
   - Parent Name: Jane Doe
   - Phone: 0777123456
   - Email: parent@example.com
3. Click "Submit Application"
4. ✓ Should show success with Application ID
5. ✓ Check database: `admissions` table should have your entry with `submitted_by` = your user_id

## Complete Flow Summary

```
User Journey:
┌─────────────────┐
│   Homepage      │
│  Click "Apply"  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Register Page  │
│  Fill Form      │
│  Submit         │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Auto-Login     │
│  Session Created│
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Admission Page  │
│ Fill Form       │
│ Submit          │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Success!       │
│  Application    │
│  Saved with     │
│  User ID        │
└─────────────────┘
```

## Verify Everything Works

### Check Database
1. Open phpMyAdmin
2. Select `muteesaiidb`
3. Click `users` table
   - Should see your registered user
   - Password should be hashed (starts with $2y$)
4. Click `admissions` table
   - Should see your application
   - `submitted_by` should match your `user_id` from users table

### Check Session
1. After login, open browser console (F12)
2. Type: `document.cookie`
3. Should see PHP session cookie

## Default Admin Account

For testing admin features (when implemented):
- Username: `admin`
- Password: `admin123`

## Troubleshooting

### "Database connection failed"
- Check XAMPP MySQL is running
- Verify database name in `config/database.php`

### "Nothing happens when I click Login"
- Open browser console (F12)
- Check for JavaScript errors
- Verify `js/script.js` is loading

### "Password is incorrect"
- Make sure password meets requirements:
  - Min 8 characters
  - 1 uppercase letter
  - 1 lowercase letter
  - 1 number

### "You must be logged in"
- Clear browser cookies
- Register new account
- Try login again

## Security Notes

- All passwords are hashed with bcrypt
- SQL injection prevented with prepared statements
- Sessions are secure
- Input is sanitized
- Email validation enabled

## Next Steps

Your system is ready! Users can now:
1. Register accounts
2. Login securely
3. Submit admission applications
4. Contact the school

All data is properly linked in the database with user IDs.
