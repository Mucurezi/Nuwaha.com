# Notification Setup Guide

## Email & SMS Notifications for Admission Approval/Rejection

When an admin approves or rejects an application, the system automatically sends notifications to the parent/guardian via email and SMS.

---

## What Gets Sent

### On Approval:
**Email:**
- Subject: "Admission Approved - Muteesa II Memorial School"
- Message: Congratulations message with student name, class, and instructions to contact headteacher
- Professional HTML template with school branding

**SMS:**
- Short message: "Congratulations! [Student Name] has been admitted to [Class] at Muteesa II Memorial School. Please contact the headteacher at [Phone] for more details."

### On Rejection:
**Email:**
- Subject: "Application Status Update - Muteesa II Memorial School"
- Message: Polite rejection with reason (if provided) and contact information

**SMS:**
- Short message: "Application update: We regret to inform you that [Student Name]'s application was not successful. Please contact [Phone] for details."

---

## Configuration Steps

### Step 1: Update School Information

Edit `config/notification.php` and update these lines:

```php
// School Information
define('SCHOOL_NAME', 'Muteesa II Memorial School');
define('SCHOOL_EMAIL', 'info@muteesaschool.com'); // ← Change this
define('SCHOOL_PHONE', '+256-XXX-XXXXXX'); // ← Change this
define('HEADTEACHER_PHONE', '+256-XXX-XXXXXX'); // ← Change this

// Email Configuration
define('EMAIL_FROM', 'noreply@muteesaschool.com'); // ← Change this
```

**Example:**
```php
define('SCHOOL_EMAIL', 'info@muteesaschool.ac.ug');
define('SCHOOL_PHONE', '+256-700-123456');
define('HEADTEACHER_PHONE', '+256-700-123456');
define('EMAIL_FROM', 'admissions@muteesaschool.ac.ug');
```

---

### Step 2: Email Setup (Already Working!)

**Good News:** Email notifications work immediately using PHP's built-in `mail()` function!

**Testing Email:**
1. Make sure your XAMPP has email configured (or use a live server)
2. On localhost, emails may not actually send but will be logged
3. On a live server (like Hostinger, Bluehost), emails will send automatically

**For Better Email Delivery (Optional):**
If you want professional email delivery, consider:
- **Gmail SMTP** (free, reliable)
- **SendGrid** (free tier: 100 emails/day)
- **Mailgun** (free tier: 5,000 emails/month)

---

### Step 3: SMS Setup (Africa's Talking)

SMS requires an API account. Here's how to set it up:

#### 3.1: Sign Up for Africa's Talking
1. Go to: https://africastalking.com
2. Click "Sign Up" (Free account available)
3. Verify your email and phone number
4. You'll get FREE test credits to try SMS

#### 3.2: Get API Credentials
1. Login to Africa's Talking dashboard
2. Go to "Settings" → "API Key"
3. Copy your API Key
4. Copy your Username (usually "sandbox" for testing)

#### 3.3: Update Configuration
Edit `config/notification.php`:

```php
// SMS Configuration
define('SMS_ENABLED', true); // ← Change to true
define('SMS_API_KEY', 'your_actual_api_key_here'); // ← Paste your API key
define('SMS_USERNAME', 'sandbox'); // ← Or your username
```

**Example:**
```php
define('SMS_ENABLED', true);
define('SMS_API_KEY', 'atsk_1234567890abcdef1234567890abcdef');
define('SMS_USERNAME', 'muteesaschool');
```

#### 3.4: Add Credits (For Production)
- Sandbox mode: Free test SMS (limited)
- Production mode: Buy SMS credits (very cheap in Uganda)
- Pricing: ~UGX 50-100 per SMS

---

## How It Works

### Automatic Flow:

```
Admin clicks "Update Status" button
    ↓
Status changed to "Approved" or "Rejected"
    ↓
System retrieves parent email and phone
    ↓
Email notification sent (HTML formatted)
    ↓
SMS notification sent (if enabled)
    ↓
Success message shown to admin
```

### What Admin Sees:

**On Success:**
```
✅ Application status updated to: Approved
   Email notification sent successfully.
   SMS notification sent successfully.
```

**If Email Fails:**
```
✅ Application status updated to: Approved
   (Email notification failed - check logs)
```

**If SMS Disabled:**
```
✅ Application status updated to: Approved
   Email notification sent successfully.
```

---

## Testing Notifications

### Test Email (Localhost):
1. Approve an application
2. Check `logs/php_errors.log` for email log
3. You'll see: "Email sent successfully to: parent@email.com"

### Test Email (Live Server):
1. Approve an application
2. Check the parent's email inbox
3. Email should arrive within 1-2 minutes

### Test SMS:
1. Make sure SMS_ENABLED is true
2. Make sure you have API credentials
3. Approve an application
4. SMS should arrive within 30 seconds
5. Check Africa's Talking dashboard for delivery status

---

## Troubleshooting

### Email Not Sending?

**On Localhost (XAMPP):**
- Emails won't actually send on localhost
- Check logs to confirm function is called
- Deploy to live server for real email sending

**On Live Server:**
- Check if server has `mail()` function enabled
- Contact your hosting provider
- Consider using SMTP (Gmail, SendGrid)

### SMS Not Sending?

**Check These:**
1. Is `SMS_ENABLED` set to `true`?
2. Is API key correct?
3. Is phone number in correct format? (+256XXXXXXXXX)
4. Do you have SMS credits in Africa's Talking?
5. Check Africa's Talking dashboard for error messages

**Common Issues:**
- Phone format: Must be +256XXXXXXXXX (Uganda)
- API key: Must be valid and active
- Credits: Must have sufficient balance
- Sandbox mode: Limited to verified numbers only

---

## Cost Estimation

### Email:
- **Free** using PHP mail() function
- **Free** using Gmail SMTP (limited)
- **Free tier** on SendGrid (100/day) or Mailgun (5,000/month)

### SMS (Africa's Talking):
- **Sandbox:** Free test credits (limited)
- **Production:** ~UGX 50-100 per SMS
- **Example:** 100 SMS = ~UGX 5,000-10,000

### Monthly Cost Estimate:
- 50 applications/month
- 50 emails (free) + 50 SMS (UGX 5,000)
- **Total: ~UGX 5,000/month**

Very affordable!

---

## Customizing Messages

### Change Email Template:
Edit `config/notification.php` → `sendAdmissionApprovalNotification()` function

**Example - Add School Logo:**
```php
$email_message = "
    <div style='text-align: center;'>
        <img src='https://yourschool.com/logo.png' alt='School Logo' style='width: 150px;'>
    </div>
    <h2 style='color: #2c5f2d;'>Congratulations! Admission Approved</h2>
    ...
";
```

### Change SMS Message:
Edit `config/notification.php` → find this line:

```php
$sms_message = "Congratulations! " . $student_name . " has been admitted...";
```

Change to your preferred message (max 160 characters for single SMS).

---

## Advanced Features (Future)

You can add:
- **WhatsApp notifications** (using WhatsApp Business API)
- **Push notifications** (using Firebase)
- **Admission letter PDF** attached to email
- **Payment link** in approval email
- **Calendar invite** for orientation day

---

## Summary

✅ **Email notifications:** Work immediately (already configured)
✅ **SMS notifications:** Need Africa's Talking account (5 minutes setup)
✅ **Automatic sending:** When admin approves/rejects application
✅ **Professional templates:** HTML email with school branding
✅ **Cost effective:** ~UGX 5,000/month for SMS

**Next Steps:**
1. Update school contact information in `config/notification.php`
2. Test email on live server
3. Sign up for Africa's Talking (optional, for SMS)
4. Test with real application approval

---

## Support

If you need help:
- Email issues: Check `logs/php_errors.log`
- SMS issues: Check Africa's Talking dashboard
- Configuration: Review `config/notification.php`
- Testing: Use sandbox mode first
