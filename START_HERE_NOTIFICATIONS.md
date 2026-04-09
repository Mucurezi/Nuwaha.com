# 🎯 START HERE - Enable Notifications

## ✅ Notification System is Ready!

Your system will now automatically send email and SMS when you approve/reject applications.

---

## 🚀 Quick Setup (2 Minutes)

### Open This File:
```
config/notification.php
```

### Change These 4 Lines:

**Line 8:** Your school email
```php
define('SCHOOL_EMAIL', 'info@muteesaschool.com'); // ← Change this
```

**Line 9:** Your school phone
```php
define('SCHOOL_PHONE', '+256-700-123456'); // ← Change this
```

**Line 10:** Headteacher phone
```php
define('HEADTEACHER_PHONE', '+256-700-123456'); // ← Change this
```

**Line 14:** Sender email
```php
define('EMAIL_FROM', 'admissions@muteesaschool.com'); // ← Change this
```

### Save the file. Done! ✅

---

## 🧪 Test It Now

1. Open admin panel: `http://localhost/mutunde%20parents/admin/login.php`
2. Login (admin / Admin@123)
3. Click "Applications"
4. Click eye icon on any application
5. Select "Approved" from dropdown
6. Click "Update Status"

**You'll see:**
```
✅ Application status updated to: Approved
   Email notification sent successfully.
```

**Check logs:**
```
logs/php_errors.log
```

You'll see: "Email sent successfully to: parent@email.com"

---

## 📱 Want SMS Too? (Optional)

### 1. Sign up (Free):
https://africastalking.com

### 2. Get API Key:
Dashboard → Settings → API Key

### 3. Update config:
```php
define('SMS_ENABLED', true); // ← Change to true
define('SMS_API_KEY', 'paste_your_key_here'); // ← Paste key
```

### 4. Test:
Approve application → SMS sent automatically!

---

## 💰 Cost

**Email:** FREE ✅
**SMS:** ~UGX 50 per message

**Example:**
- 50 approvals/month = UGX 2,500
- Very affordable!

---

## 📧 What Parent Receives

### Email (HTML):
```
Subject: Admission Approved - Muteesa II Memorial School

Dear Mr. John Doe,

Congratulations! Mary Doe has been admitted to Primary 3 
at Muteesa II Memorial School.

Next Steps:
1. Contact headteacher for details
2. Bring required documents
3. Complete admission formalities

Contact: +256-700-123456
Email: info@muteesaschool.com

We look forward to welcoming Mary Doe!
```

### SMS:
```
Congratulations! Mary Doe has been admitted to Primary 3 
at Muteesa II Memorial School. Contact headteacher at 
+256-700-123456 for details.
```

---

## ✅ Checklist

- [ ] Update SCHOOL_EMAIL in config/notification.php
- [ ] Update SCHOOL_PHONE in config/notification.php
- [ ] Update HEADTEACHER_PHONE in config/notification.php
- [ ] Update EMAIL_FROM in config/notification.php
- [ ] Test by approving an application
- [ ] Check logs/php_errors.log for confirmation
- [ ] (Optional) Sign up for Africa's Talking
- [ ] (Optional) Enable SMS in config
- [ ] Deploy to live server for real emails

---

## 🎯 That's It!

**Setup:** 2 minutes
**Testing:** 1 minute
**Total:** 3 minutes

Your notification system is ready! 🎉

---

## 📚 More Info

- **Quick Start:** QUICK_START_NOTIFICATIONS.md
- **Detailed Guide:** NOTIFICATION_SETUP.md
- **Flow Diagrams:** NOTIFICATION_FLOW.md
- **Summary:** NOTIFICATION_SUMMARY.md

---

## 🆘 Problems?

**Email not sending on localhost?**
→ Normal! Deploy to live server

**SMS not working?**
→ Check SMS_ENABLED is true
→ Verify API key is correct

**Need help?**
→ Check logs/php_errors.log
→ Read NOTIFICATION_SETUP.md

---

**Ready to go!** Just update those 4 lines and test! ✅
