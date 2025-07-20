# Forgot Password with Email OTP - Setup Guide

This guide explains how to set up and use the forgot password feature with email OTP in your Laravel Library Management System.

## Features Implemented

✅ **Complete Forgot Password Flow**
- Email-based OTP generation and verification
- Secure password reset process
- Rate limiting to prevent abuse
- Automatic cleanup of expired OTPs
- Beautiful, responsive UI

✅ **Security Features**
- 6-digit numeric OTP with 10-minute expiry
- Rate limiting (3 password reset attempts per 15 minutes)
- Rate limiting for OTP verification (5 attempts per 15 minutes)
- Automatic cleanup of expired OTPs
- Secure session management

✅ **User Experience**
- Modern, responsive design
- Auto-focus and auto-submit on OTP entry
- Paste support for OTP
- Clear error messages
- Resend OTP functionality

## Setup Instructions

### 1. Database Migration

The password reset OTP table migration is already included. Run the migration:

```bash
php artisan migrate
```

### 2. Email Configuration

Configure your email settings in the `.env` file:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Library Management System"

# For development/testing, you can use log driver
MAIL_MAILER=log
```

### 3. Queue Configuration (Optional)

For better performance, configure queues for email sending:

```env
QUEUE_CONNECTION=database
```

Then run:
```bash
php artisan queue:table
php artisan migrate
php artisan queue:work
```

### 4. Scheduled Cleanup

Add this to your `app/Console/Kernel.php` to automatically clean up expired OTPs:

```php
protected function schedule(Schedule $schedule): void
{
    $schedule->command('otp:cleanup')->hourly();
}
```

## Usage

### For Users

1. **Request Password Reset**
   - Go to the login page
   - Click "Forgot Password?"
   - Enter your email address
   - Click "Send OTP"

2. **Verify OTP**
   - Check your email for the 6-digit OTP
   - Enter the OTP in the verification form
   - Click "Verify OTP"

3. **Reset Password**
   - Enter your new password
   - Confirm the new password
   - Click "Reset Password"

### For Developers

#### Testing Email Functionality

Test the email OTP functionality:

```bash
php artisan test:email-otp user@example.com
```

#### Manual OTP Cleanup

Clean up expired OTPs manually:

```bash
php artisan otp:cleanup
```

#### Routes Available

- `GET /forgot-password` - Show forgot password form
- `POST /forgot-password` - Send OTP
- `GET /verify-otp` - Show OTP verification form
- `POST /verify-otp` - Verify OTP
- `GET /reset-password` - Show password reset form
- `POST /reset-password` - Reset password

## Security Considerations

### Rate Limiting
- **Password Reset Requests**: 3 attempts per 15 minutes per IP
- **OTP Verification**: 5 attempts per 15 minutes per IP

### OTP Security
- 6-digit numeric OTP
- 10-minute expiry time
- Single-use (marked as used after verification)
- Automatic cleanup of expired OTPs

### Session Security
- Email stored in session during reset process
- OTP verification status stored in session
- Session data cleared after successful password reset

## Customization

### Email Template

To customize the email template, edit `app/Notifications/PasswordResetOtpNotification.php`:

```php
public function toMail(object $notifiable): MailMessage
{
    return (new MailMessage)
        ->subject('Your Custom Subject')
        ->greeting('Hello!')
        ->line('Your custom message here.')
        ->line('Your OTP is: **' . $this->otp . '**')
        ->line('This OTP expires in 10 minutes.')
        ->salutation('Best regards, Your App Name');
}
```

### OTP Expiry Time

To change the OTP expiry time, edit `app/Models/PasswordResetOtp.php`:

```php
public static function createOrUpdateOtp(string $email): self
{
    // ... existing code ...
    
    return self::create([
        'email' => $email,
        'otp' => self::generateOtp(),
        'expires_at' => Carbon::now()->addMinutes(15), // Change to 15 minutes
        'used' => false
    ]);
}
```

### Rate Limiting

To adjust rate limiting, edit the `sendOtp` and `verifyOtp` methods in `app/Http/Controllers/AuthController.php`.

## Troubleshooting

### Email Not Sending

1. Check your email configuration in `.env`
2. Verify SMTP credentials
3. Check mail logs: `storage/logs/laravel.log`
4. Test with log driver first: `MAIL_MAILER=log`

### OTP Not Working

1. Check if OTP is expired (10 minutes)
2. Verify OTP format (6 digits)
3. Check database for OTP record
4. Run cleanup command: `php artisan otp:cleanup`

### Rate Limiting Issues

1. Wait for the rate limit to expire (15 minutes)
2. Check cache configuration
3. Clear cache: `php artisan cache:clear`

## Files Structure

```
app/
├── Console/Commands/
│   ├── CleanupExpiredOtps.php
│   └── TestEmailOtp.php
├── Http/Controllers/
│   └── AuthController.php (updated)
├── Models/
│   └── PasswordResetOtp.php
└── Notifications/
    └── PasswordResetOtpNotification.php

database/migrations/
└── 2025_07_18_120937_create_password_reset_otps_table.php

resources/views/auth/
├── forgot-password.blade.php
├── verify-otp.blade.php
└── reset-password.blade.php

routes/
└── web.php (updated)
```

## Support

If you encounter any issues:

1. Check the Laravel logs: `storage/logs/laravel.log`
2. Verify all migrations are run: `php artisan migrate:status`
3. Test email configuration: `php artisan test:email-otp user@example.com`
4. Check cache configuration: `php artisan cache:clear`

The forgot password feature is now fully implemented and ready to use! 