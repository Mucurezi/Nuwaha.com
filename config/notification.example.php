<?php
/**
 * Notification Configuration Example
 * Copy this file to notification.php and update with your details
 */

// School Information - UPDATE THESE!
define('SCHOOL_NAME', 'Muteesa II Memorial School');
define('SCHOOL_EMAIL', 'info@muteesaschool.com'); // Your school email
define('SCHOOL_PHONE', '+256-700-123456'); // Your school phone
define('HEADTEACHER_PHONE', '+256-700-123456'); // Headteacher direct line

// Email Configuration - UPDATE THESE!
define('EMAIL_FROM', 'noreply@muteesaschool.com'); // Sender email
define('EMAIL_FROM_NAME', 'Muteesa II Memorial School');

// SMS Configuration (Africa's Talking) - UPDATE WHEN READY!
define('SMS_ENABLED', false); // Set to true when you have API credentials
define('SMS_API_KEY', 'your_api_key_here'); // Get from https://africastalking.com
define('SMS_USERNAME', 'sandbox'); // Your Africa's Talking username

// NOTE: See NOTIFICATION_SETUP.md for detailed setup instructions
?>
