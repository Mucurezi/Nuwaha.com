# Quick Start: Email & SMS Notifications

## 🚀 5-Minute Setup

### Step 1: Update School Information (2 minutes)

Open `config/notification.php` and change these lines:

```php
define('SCHOOL_EMAIL', 'info@muteesaschool.com'); // ← Your email
define('SCHOOL_PHONE', '+256-700-123456'); // ← Your phone
define('HEADTEACHER_PHONE', '+256-700-123456'); // ← Headteacher phone
define('EMAIL_FROM', 'admissions@muteesaschool.com'); // ← Sender email
```

**That's it for email!** Email notifications will work immediately.

---

### Step 2: Test It! (1 minute)

1. Login to admin panel
2. Go to Applications
3. Click eye icon on any application
4. Change status to "Approved"
5. Click "Update Status"

**You'll see:**
```
✅ Application status updated to: Approved
   Email notification sent successfully.
```

The parent will receive an email like this:

```
Subject: Admission Approved - Muteesa II Memorial School

Dear [Parent Name],

Congratulations! [Student Name] has been admitted to [Class] 
at Muteesa II Memorial School.

Next Steps:
1. Please contact the headteacher for more details
2. Bring required documents for registration
3. Complete the admission formalities

Contact Information:
Phone: +256-700-123456
Email: info@muteesaschool.com

We look forward to welcoming [Student Name] to our school!
```

---

### Step 3: Enable SMS (Optional - 2 minutes)

**Want to send SMS too?**

1. Sign up at: https://africastalking.com (Free account)
2. Get your API Key from dashboard
3. Update `config/notification.php`:

```php
define('SMS_ENABLED', true); // ← Change to true
define('SMS_API_KEY', 'your_api_key_here'); // ← Paste your key
```

**Done!** Now SMS will also be sent automatically.

---

## What Happens When Admin Approves?

```
Admin clicks "Update Status" → Approved
    ↓
✅ Status saved in database
    ↓
📧 Email sent to parent (HTML formatted)
    ↓
📱 SMS sent to parent (if enabled)
    ↓
✅ Success message shown to admin
```

---

## Message Templates

### Approval Email:
- Professional HTML template
- School branding
- Student name and class
- Instructions to contact headteacher
- School contact information

### Approval SMS:
```
Congratulations! [Student Name] has been admitted to [Class] 
at Muteesa II Memorial School. Please contact the headteacher 
at [Phone] for more details.
```

### Rejection Email:
- Polite message
- Reason (if admin provided notes)
- Encouragement to apply again
- School contact information

---

## Cost

**Email:** FREE ✅
**SMS:** ~UGX 50-100 per message (Africa's Talking)

**Example:**
- 50 approvals/month = 50 emails (free) + 50 SMS (UGX 5,000)
- **Total: ~UGX 5,000/month**

---

## Troubleshooting

**Email not sending on localhost?**
- Normal! Localhost can't send real emails
- Deploy to live server (Hostinger, Bluehost, etc.)
- Or configure XAMPP for email (advanced)

**SMS not sending?**
- Check if `SMS_ENABLED` is `true`
- Verify API key is correct
- Check Africa's Talking dashboard for credits
- Phone must be in format: +256XXXXXXXXX

---

## Need Help?

See detailed guide: `NOTIFICATION_SETUP.md`

Or check logs: `logs/php_errors.log`

---

## Summary

✅ Email works immediately (just update school info)
✅ SMS optional (needs Africa's Talking account)
✅ Automatic on approval/rejection
✅ Professional templates
✅ Very affordable

**You're all set!** 🎉
