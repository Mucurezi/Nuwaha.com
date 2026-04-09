# Admin Application Approval/Rejection Guide

## How Admin Approves or Rejects Applications

---

## Step-by-Step Process

### Step 1: Login to Admin Panel
1. Go to: `http://localhost/mutunde%20parents/admin/login.php`
2. Enter credentials:
   - Username: `admin`
   - Password: `Admin@123`
3. Click "Login"

---

### Step 2: View Applications List
After login, you'll see the dashboard. You have two ways to view applications:

**Option A: From Dashboard**
- Scroll down to "Recent Applications" section
- Click the eye icon (👁️) next to any application

**Option B: From Applications Page**
- Click "Applications" in the left sidebar
- You'll see a list of ALL applications
- Click the eye icon (👁️) next to any application you want to review

---

### Step 3: Review Application Details
When you click the eye icon, you'll see a detailed page with:

**Student Information:**
- Full Name
- Date of Birth
- Gender
- Class Applying For
- Previous School

**Parent/Guardian Information:**
- Name
- Relationship (Father/Mother/Guardian)
- Phone Number
- Email
- Address

**Additional Information:**
- Medical Conditions
- Special Needs

**Application Status Section:**
- Current Status (Pending/Approved/Rejected)
- Application Date
- Submitted By (username)

---

### Step 4: Approve or Reject Application

At the bottom of the application details page, you'll see a form:

**Update Status Form:**
```
┌─────────────────────────────────────┐
│ Update Status:                      │
│ [Dropdown: Pending ▼]              │
│   - Pending                         │
│   - Approved    ← Select this       │
│   - Rejected    ← Or this           │
│                                     │
│ Admin Notes:                        │
│ ┌─────────────────────────────────┐│
│ │ Type your notes here...         ││
│ │ Example: "Student meets all     ││
│ │ requirements. Approved for      ││
│ │ admission."                     ││
│ └─────────────────────────────────┘│
│                                     │
│ [💾 Update Status]                 │
└─────────────────────────────────────┘
```

**To Approve:**
1. Select "Approved" from the dropdown
2. Add notes (optional): "Student approved for admission to [Class Name]"
3. Click "Update Status" button
4. You'll see a green success message: "Application status updated to: Approved"

**To Reject:**
1. Select "Rejected" from the dropdown
2. Add notes (optional): "Application rejected due to [reason]"
3. Click "Update Status" button
4. You'll see a green success message: "Application status updated to: Rejected"

---

## Visual Workflow

```
Admin Login
    ↓
Dashboard
    ↓
Applications List (Filter: All/Pending/Approved/Rejected)
    ↓
Click Eye Icon (👁️) on Application
    ↓
View Full Application Details
    ↓
Scroll to Bottom → "Update Status" Form
    ↓
Select Status: Approved or Rejected
    ↓
Add Admin Notes (Optional)
    ↓
Click "Update Status" Button
    ↓
✅ Success Message Displayed
    ↓
Application Status Updated in Database
```

---

## Filtering Applications

On the Applications List page, you can filter by status:

**Filter Tabs:**
- **All** - Shows all applications (pending, approved, rejected)
- **Pending** - Shows only applications waiting for review
- **Approved** - Shows only approved applications
- **Rejected** - Shows only rejected applications

**Example:**
- Click "Pending" tab to see only applications that need your review
- Review each one and approve/reject
- Click "Approved" tab to see all approved students

---

## Search Functionality

You can also search for specific applications:
1. Use the search box at the top
2. Type student name, parent name, or email
3. Click "Search" button
4. Results will be filtered

---

## Export Applications

To export all applications to Excel:
1. Click "Export to Excel" button on Applications List page
2. Excel file will download with all application data
3. You can open it in Microsoft Excel or Google Sheets

---

## What Happens After Approval/Rejection?

**In the Database:**
- Application status is updated to "approved" or "rejected"
- Admin notes are saved
- Review date is recorded
- Reviewer ID (your admin user ID) is saved

**For Future Features (Not Yet Implemented):**
- Email notification can be sent to parent/guardian
- Student record can be created in students table
- Admission letter can be generated

---

## Quick Tips

✅ **Best Practices:**
- Always add notes when rejecting (explain why)
- Review all information carefully before approving
- Use the "Pending" filter to focus on applications needing review
- Export data regularly for backup

⚠️ **Important:**
- Once approved, you can still change status back to pending or rejected
- All status changes are logged with date and admin ID
- Make sure to review medical conditions and special needs

---

## Example Scenarios

### Scenario 1: Approve Application
```
1. Click "Pending" filter tab
2. Click eye icon on "John Doe" application
3. Review all details
4. Select "Approved" from dropdown
5. Add note: "Student approved for Primary 3. Excellent academic record."
6. Click "Update Status"
7. ✅ Success! Application approved
```

### Scenario 2: Reject Application
```
1. Click "Pending" filter tab
2. Click eye icon on "Jane Smith" application
3. Review all details
4. Select "Rejected" from dropdown
5. Add note: "Class is full. Recommend applying next term."
6. Click "Update Status"
7. ✅ Success! Application rejected
```

### Scenario 3: Change Decision
```
1. Click "Approved" filter tab
2. Click eye icon on previously approved application
3. Select "Rejected" from dropdown (or "Pending" to review again)
4. Add note: "Status changed due to [reason]"
5. Click "Update Status"
6. ✅ Success! Status changed
```

---

## Need Help?

If you encounter any issues:
1. Check `logs/php_errors.log` for errors
2. Verify database connection in `test_connection.php`
3. Make sure you're logged in as admin
4. Clear browser cache and try again

---

## Summary

**The admin approves/rejects applications by:**
1. Logging into admin panel
2. Viewing application details (click eye icon)
3. Selecting "Approved" or "Rejected" from dropdown
4. Adding optional notes
5. Clicking "Update Status" button

**That's it!** The system handles everything else automatically.
