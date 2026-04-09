# Notification System Flow

## Complete Process Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    ADMISSION PROCESS                         │
└─────────────────────────────────────────────────────────────┘

1. STUDENT/PARENT SUBMITS APPLICATION
   ↓
   [Application saved in database with status: "pending"]
   ↓

2. ADMIN REVIEWS APPLICATION
   ↓
   Admin Panel → Applications → Click Eye Icon
   ↓
   [View full application details]
   ↓

3. ADMIN MAKES DECISION
   ↓
   ┌─────────────────────────────────────┐
   │ Update Status Form:                 │
   │                                     │
   │ Status: [Approved ▼]                │
   │                                     │
   │ Notes: [Optional message]           │
   │                                     │
   │ [Update Status Button]              │
   └─────────────────────────────────────┘
   ↓

4. SYSTEM PROCESSES UPDATE
   ↓
   ┌──────────────────────────────────────────────────┐
   │ Database Update:                                 │
   │ - Status changed to "approved"                   │
   │ - Admin notes saved                              │
   │ - Review date recorded                           │
   │ - Reviewer ID saved                              │
   └──────────────────────────────────────────────────┘
   ↓

5. NOTIFICATION SYSTEM TRIGGERED
   ↓
   ┌──────────────────────────────────────────────────┐
   │ System retrieves:                                │
   │ - Parent name                                    │
   │ - Parent email                                   │
   │ - Parent phone                                   │
   │ - Student name                                   │
   │ - Class                                          │
   └──────────────────────────────────────────────────┘
   ↓
   ├─────────────────────┬────────────────────────────┤
   ↓                     ↓                            ↓
   
6a. EMAIL SENT       6b. SMS SENT                6c. ADMIN FEEDBACK
   ↓                     ↓                            ↓
   📧                    📱                           ✅
   
   To: parent@email     To: +256-XXX-XXXXX          Success Message:
   Subject: Admission   Message: "Congratulations!   "Application status
   Approved             [Student] admitted to        updated to: Approved
                        [Class]. Contact             Email sent ✓
   HTML Template:       headteacher at [Phone]"      SMS sent ✓"
   - School logo                                     
   - Congratulations    Delivery: 30 seconds         Admin sees confirmation
   - Student details                                 and continues work
   - Next steps         Cost: ~UGX 50-100           
   - Contact info                                    
                                                     
   Delivery: 1-2 min    Provider: Africa's Talking  
   Cost: FREE                                        

   ↓                     ↓                            ↓

7. PARENT RECEIVES NOTIFICATIONS
   ↓
   ┌──────────────────────────────────────────────────┐
   │ Parent checks email inbox                        │
   │ Parent receives SMS on phone                     │
   │ Parent reads admission approval                  │
   │ Parent contacts headteacher                      │
   └──────────────────────────────────────────────────┘
   ↓

8. ADMISSION COMPLETED
   ✅ Parent contacts school
   ✅ Documents submitted
   ✅ Student enrolled
```

---

## Notification Content

### APPROVAL EMAIL (HTML)

```
┌────────────────────────────────────────────────────┐
│                                                    │
│  ╔══════════════════════════════════════════╗     │
│  ║   MUTEESA II MEMORIAL SCHOOL             ║     │
│  ╚══════════════════════════════════════════╝     │
│                                                    │
│  Dear Mr. John Doe,                               │
│                                                    │
│  🎉 Congratulations! Admission Approved            │
│                                                    │
│  We are pleased to inform you that Mary Doe       │
│  has been admitted to Primary 3 at Muteesa II     │
│  Memorial School.                                 │
│                                                    │
│  Next Steps:                                      │
│  1. Please contact the headteacher for details    │
│  2. Bring required documents for registration     │
│  3. Complete the admission formalities            │
│                                                    │
│  Contact Information:                             │
│  Phone: +256-700-123456                           │
│  Email: info@muteesaschool.com                    │
│                                                    │
│  We look forward to welcoming Mary Doe to our     │
│  school community!                                │
│                                                    │
│  Best regards,                                    │
│  Muteesa II Memorial School                       │
│                                                    │
│  ────────────────────────────────────────────     │
│  Muteesa II Memorial School                       │
│  Email: info@muteesaschool.com                    │
│  Phone: +256-700-123456                           │
│                                                    │
└────────────────────────────────────────────────────┘
```

### APPROVAL SMS

```
┌────────────────────────────────────────────────────┐
│ 📱 SMS Message                                     │
├────────────────────────────────────────────────────┤
│                                                    │
│ Congratulations! Mary Doe has been admitted to    │
│ Primary 3 at Muteesa II Memorial School. Please   │
│ contact the headteacher at +256-700-123456 for    │
│ more details.                                      │
│                                                    │
│ Length: ~150 characters (1 SMS)                    │
│ Cost: ~UGX 50-100                                  │
│ Delivery: 30 seconds                               │
│                                                    │
└────────────────────────────────────────────────────┘
```

---

## Technical Flow

```
admin/applications/view.php
    ↓
    [Admin submits form with new status]
    ↓
    POST request received
    ↓
    Validate status (pending/approved/rejected)
    ↓
    Get application details from database
    ↓
    Update status in database
    ↓
    IF status == "approved":
        ↓
        Call: sendAdmissionApprovalNotification()
            ↓
            ├─→ sendEmailNotification()
            │       ↓
            │       Build HTML email template
            │       ↓
            │       PHP mail() function
            │       ↓
            │       Return success/failure
            │
            └─→ sendSMSNotification()
                    ↓
                    Format phone number
                    ↓
                    Call Africa's Talking API
                    ↓
                    Return success/failure
        ↓
        Display success message to admin
    ↓
    Redirect back to application view
```

---

## Configuration Files

```
config/notification.php
├─ School information (name, email, phone)
├─ Email settings (from address, from name)
├─ SMS settings (API key, username, enabled)
├─ sendEmailNotification() function
├─ sendSMSNotification() function
├─ sendAdmissionApprovalNotification() function
└─ sendAdmissionRejectionNotification() function

admin/applications/view.php
├─ Includes notification.php
├─ Handles status update form
├─ Calls notification functions
└─ Displays success/error messages
```

---

## Error Handling

```
IF email fails:
    ↓
    Log error to logs/php_errors.log
    ↓
    Show admin: "(Email notification failed - check logs)"
    ↓
    Status still updated (notification failure doesn't block)

IF SMS fails:
    ↓
    Log error to logs/php_errors.log
    ↓
    Continue (email may have succeeded)
    ↓
    Admin can check Africa's Talking dashboard

IF database update fails:
    ↓
    Show error message
    ↓
    No notifications sent
    ↓
    Admin can retry
```

---

## Success Indicators

**Admin sees:**
```
✅ Application status updated to: Approved
   Email notification sent successfully.
   SMS notification sent successfully.
```

**Parent receives:**
- 📧 Email in inbox (1-2 minutes)
- 📱 SMS on phone (30 seconds)

**Database records:**
- Status: approved
- Review date: 2026-04-07 14:30:00
- Reviewed by: admin (user_id: 1)
- Notes: [Admin's notes]

**Logs show:**
```
[2026-04-07 14:30:00] Email sent successfully to: parent@email.com
[2026-04-07 14:30:01] SMS sent successfully to: +256-700-123456
```

---

## Benefits

✅ **Instant notification** - Parents know immediately
✅ **Professional** - HTML email with branding
✅ **Reliable** - Multiple channels (email + SMS)
✅ **Trackable** - Logged in system
✅ **Affordable** - ~UGX 50-100 per SMS
✅ **Automatic** - No manual work for admin
✅ **Scalable** - Handles many applications

---

## Future Enhancements

Possible additions:
- WhatsApp notifications
- Push notifications (mobile app)
- Admission letter PDF attachment
- Payment link in email
- Calendar invite for orientation
- Parent portal login link
- QR code for verification
