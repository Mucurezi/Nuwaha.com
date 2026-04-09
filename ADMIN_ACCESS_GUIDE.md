# Admin Panel Access Guide

## How to Access Admin Panel

### URL Format:
```
http://localhost/mutunde%20parents/admin/login.php
```

OR (if space is replaced with +):
```
http://localhost/mutunde+parents/admin/login.php
```

OR (if you renamed the folder without space):
```
http://localhost/mutunde-parents/admin/login.php
```

### Login Credentials:
- **Username:** admin
- **Password:** Admin@123

---

## Troubleshooting Access Issues

### Issue 1: "Page Not Found" or 404 Error

**Solution:** Check your folder name
1. Open File Explorer
2. Go to `C:\xampp\htdocs\`
3. Check the exact folder name
4. Use that exact name in URL (with %20 for spaces)

**Example:**
- Folder name: `mutunde parents` → URL: `http://localhost/mutunde%20parents/admin/login.php`
- Folder name: `mutunde-parents` → URL: `http://localhost/mutunde-parents/admin/login.php`

### Issue 2: "Access Denied" Error

**Solution:** Make sure you're using admin credentials
- Username: `admin`
- Password: `Admin@123`

If you forgot password, run:
```
admin/reset_password.php
```

### Issue 3: Blank Page or PHP Error

**Solution:** Check XAMPP is running
1. Open XAMPP Control Panel
2. Make sure Apache is running (green)
3. Make sure MySQL is running (green)
4. Restart both if needed

### Issue 4: Database Connection Error

**Solution:** Check database configuration
1. Open `config/database.php`
2. Verify database name: `muteesaiidb`
3. Verify username: `root`
4. Verify password: (empty for XAMPP)

---

## Complete Access Flow

```
1. Start XAMPP
   ↓
2. Open browser
   ↓
3. Go to: http://localhost/mutunde%20parents/admin/login.php
   ↓
4. Enter credentials:
   - Username: admin
   - Password: Admin@123
   ↓
5. Click "Login to Admin Panel"
   ↓
6. You're in! Dashboard will load
```

---

## Quick Test

Open this URL in your browser:
```
http://localhost/mutunde%20parents/admin/login.php
```

You should see:
- Green background
- Shield icon
- "Admin Panel" heading
- Username and Password fields
- "Login to Admin Panel" button

If you see this, the admin panel is working!

---

## After Login

You'll see:
- Dashboard with statistics
- Sidebar with menu:
  - Dashboard
  - Applications
  - Export Data
  - Logout

---

## Common URLs

**Admin Login:**
```
http://localhost/mutunde%20parents/admin/login.php
```

**Admin Dashboard (after login):**
```
http://localhost/mutunde%20parents/admin/index.php
```

**Applications List:**
```
http://localhost/mutunde%20parents/admin/applications/list.php
```

**Public Website:**
```
http://localhost/mutunde%20parents/index.html
```

---

## Security Note

The admin panel is "hidden" - it's not linked from the public website. Only people who know the URL can access it.

**Admin URL:** `/admin/login.php` (not linked on public site)
**Public URLs:** `/index.html`, `/about.html`, etc. (visible to everyone)

---

## Need Help?

1. Check XAMPP is running
2. Verify folder name matches URL
3. Check database is created
4. Try reset password if login fails
5. Check `logs/php_errors.log` for errors
