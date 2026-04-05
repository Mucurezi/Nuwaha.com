# 🎯 Admin Panel Setup Guide

## ✅ Admin Panel Created Successfully!

Your secure admin panel is now ready with a hidden URL.

---

## 🔐 Step 1: Create Admin User

1. Open **phpMyAdmin**: `http://localhost/phpmyadmin`
2. Select database: **`muteesaiidb`**
3. Click **SQL** tab
4. Copy and paste this code:

```sql
INSERT INTO users (username, email, password_hash, user_role, status, created_at) 
VALUES (
    'admin', 
    'admin@muteesa2school.ac.ug', 
    '$2y$12$LQv3c1yduTi6xUrVGkz2L.JQIvr/HNe8J04B8qCOk.O9wV.1fLlHK', 
    'admin', 
    'active', 
    NOW()
);
```

5. Click **Go**
6. You should see: "1 row inserted"

---

## 🚀 Step 2: Access Admin Panel

### Hidden Admin URL:
```
http://localhost/your-project-name/admin
```

Replace `your-project-name` with your actual folder name.

### Login Credentials:
- **Username:** `admin`
- **Password:** `Admin@123`

**⚠️ IMPORTANT:** Change this password after first login!

---

## 📊 Step 3: Test the Admin Panel

1. Open browser
2. Go to: `http://localhost/your-project-name/admin`
3. You'll see the admin login page
4. Enter username: `admin`
5. Enter password: `Admin@123`
6. Click "Login to Admin Panel"
7. You should see the dashboard!

---

## 🎨 Admin Panel Features

### ✅ Dashboard
- Total applications count
- Pending applications
- Approved applications
- Total users
- Today's applications
- This week's applications
- Recent applications list

### ✅ Applications Management
- View all applications
- Filter by status (pending, approved, rejected)
- Search by student or parent name
- View full application details
- Approve/Reject applications
- Add admin notes
- Export to Excel/CSV

### ✅ Security Features
- Hidden URL (not linked on public site)
- Role-based access (only admin role)
- Session-based authentication
- Secure password hashing
- Activity logging

---

## 📁 Admin Panel Structure

```
admin/
├── index.php                    # Dashboard
├── login.php                    # Admin login
├── logout.php                   # Logout
├── applications/
│   ├── list.php                # All applications
│   └── view.php                # View details
├── export_applications.php      # Export to Excel
├── css/
│   └── admin.css               # Styles
└── includes/
    ├── header.php              # Header
    └── sidebar.php             # Navigation
```

---

## 🔒 Security Best Practices

### 1. Change Default Password
After first login:
- Go to Settings (when implemented)
- Or run this SQL to change password:

```sql
-- Generate new password hash at: https://bcrypt-generator.com/
-- Then update:
UPDATE users 
SET password_hash = 'YOUR_NEW_HASH_HERE' 
WHERE username = 'admin';
```

### 2. Keep URL Secret
- Don't link admin panel on public website
- Only share URL with authorized staff
- Consider renaming `admin` folder to something unique

### 3. Create More Admin Users
```sql
-- Replace values with actual data
INSERT INTO users (username, email, password_hash, user_role, status) 
VALUES ('admin2', 'admin2@school.com', 'BCRYPT_HASH_HERE', 'admin', 'active');
```

---

## 📱 How to Use

### View Applications
1. Login to admin panel
2. Click "Applications" in sidebar
3. See all applications in a table
4. Use filters to find specific ones

### Approve/Reject Application
1. Click "View" icon on any application
2. Scroll to "Application Status" section
3. Select new status (Approved/Rejected)
4. Add notes (optional)
5. Click "Update Status"

### Export Data
1. Go to Applications list
2. Click "Export to Excel" button
3. File downloads automatically
4. Open in Excel

---

## 🎯 Admin Panel URLs

| Page | URL |
|------|-----|
| Login | `/admin/login.php` |
| Dashboard | `/admin/index.php` |
| Applications | `/admin/applications/list.php` |
| View Application | `/admin/applications/view.php?id=X` |
| Export | `/admin/export_applications.php` |
| Logout | `/admin/logout.php` |

---

## 🛠️ Customization

### Change Admin URL (More Secure)
Rename the `admin` folder:
```
admin/ → secure-panel-2024/
```

Then access via:
```
http://localhost/your-project/secure-panel-2024
```

### Add Logo
Place school logo in: `admin/images/logo.png`
Update header.php to display it

---

## ❓ Troubleshooting

### Can't Access Admin Panel
- Check URL is correct
- Make sure Apache is running
- Verify admin user exists in database

### Can't Login
- Check username and password
- Verify user_role is 'admin' in database
- Check user status is 'active'

### Page Shows Blank
- Check PHP errors in browser console
- Enable error display temporarily:
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```

---

## 💰 Value Added

With this admin panel, you can now charge:

**Current Project Value:** UGX 1,500,000 - 2,500,000

**Includes:**
- ✅ Public website (6 pages)
- ✅ User registration & login
- ✅ Online admission form
- ✅ Contact form
- ✅ **Secure admin panel**
- ✅ **Application management**
- ✅ **Export to Excel**
- ✅ **Dashboard with statistics**

---

## 📞 Next Steps

1. ✅ Create admin user (Step 1)
2. ✅ Login to admin panel (Step 2)
3. ✅ Test all features (Step 3)
4. 🔄 Change default password
5. 🔄 Add school logo
6. 🔄 Customize colors (optional)
7. 🔄 Train school staff
8. 🔄 Deploy to live server

---

**Congratulations! Your admin panel is ready to use!** 🎉

The admin panel is fully functional and secure. Only people who know the URL and have admin credentials can access it.
