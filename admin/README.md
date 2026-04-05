# Admin Panel - Muteesa II Memorial School

## 🔐 Access Information

### Hidden URL
The admin panel is accessed through a hidden URL that is NOT linked anywhere on the public website.

**Admin Panel URL:** `http://yourschool.com/admin`

### Default Admin Credentials
You need to create an admin user in the database first.

**Run this SQL in phpMyAdmin:**
```sql
-- Create admin user
-- Password: Admin@123
INSERT INTO users (username, email, password_hash, user_role, status) 
VALUES ('admin', 'admin@muteesa2school.ac.ug', '$2y$12$LQv3c1yduTi6xUrVGkz2L.JQIvr/HNe8J04B8qCOk.O9wV.1fLlHK', 'admin', 'active');
```

**Login Credentials:**
- Username: `admin`
- Password: `Admin@123`

**IMPORTANT:** Change the password after first login!

## 📁 Admin Panel Structure

```
admin/
├── index.php                    # Dashboard (main page)
├── login.php                    # Admin login page
├── logout.php                   # Logout handler
│
├── applications/
│   ├── list.php                # View all applications
│   └── view.php                # View single application details
│
├── export_applications.php      # Export to Excel/CSV
├── view_applications.php        # Simple view (legacy)
│
├── css/
│   └── admin.css               # Admin panel styles
│
└── includes/
    ├── header.php              # Common header
    └── sidebar.php             # Navigation sidebar
```

## 🎯 Features

### Dashboard
- Statistics overview (total, pending, approved applications)
- Quick stats (today's and this week's applications)
- Recent applications list
- User count

### Applications Management
- View all applications
- Filter by status (all, pending, approved, rejected)
- Search by student or parent name
- View full application details
- Update application status (approve/reject)
- Add admin notes
- Export to Excel/CSV

### Security Features
- Role-based access control (only admin role can access)
- Session-based authentication
- Hidden URL (not linked on public site)
- Automatic logout on session expiry
- Activity logging

## 🚀 How to Access

### For Admins:
1. Open browser
2. Type: `http://yourschool.com/admin` (or bookmark it)
3. Login with admin credentials
4. Access dashboard and all features

### For Public Users:
- The admin panel URL is NOT visible anywhere on the public website
- Only people who know the URL can access the login page
- Only users with "admin" role can login

## 🔒 Security Best Practices

1. **Change Default Password**
   - Login with default credentials
   - Go to Settings > Profile
   - Change password immediately

2. **Keep URL Secret**
   - Don't share the admin URL publicly
   - Only give it to authorized staff

3. **Use Strong Passwords**
   - Minimum 8 characters
   - Include uppercase, lowercase, numbers

4. **Regular Backups**
   - Backup database regularly
   - Export applications weekly

5. **Monitor Activity**
   - Check who's logging in
   - Review application status changes

## 📊 Using the Admin Panel

### Viewing Applications
1. Login to admin panel
2. Click "Applications" in sidebar
3. Use filters to find specific applications
4. Click "View" icon to see full details

### Approving/Rejecting Applications
1. Open application details
2. Scroll to "Application Status" section
3. Select new status (Approved/Rejected)
4. Add admin notes (optional)
5. Click "Update Status"

### Exporting Data
1. Go to Applications list
2. Click "Export to Excel" button
3. File downloads automatically
4. Open in Excel/Google Sheets

## 🛠️ Customization

### Change Admin URL
To make the URL even more secure, rename the `admin` folder:

```
admin/ → secure-panel-2024/
```

Then access via: `http://yourschool.com/secure-panel-2024`

### Add More Admin Users
Run this SQL (change username, email, password):

```sql
INSERT INTO users (username, email, password_hash, user_role, status) 
VALUES ('admin2', 'admin2@school.com', '$2y$12$...', 'admin', 'active');
```

Use online bcrypt generator to create password hash.

## 📞 Support

If you encounter any issues:
1. Check if you're using the correct URL
2. Verify your user role is 'admin' in database
3. Clear browser cache and cookies
4. Check PHP error logs

## 🎓 Training

### For School Staff:
1. Bookmark the admin URL
2. Keep login credentials secure
3. Review applications daily
4. Export data weekly for records
5. Update application status promptly

---

**Created for:** Muteesa II Memorial School - Mutundwe  
**Developer:** [Your Name]  
**Date:** <?php echo date('F Y'); ?>
