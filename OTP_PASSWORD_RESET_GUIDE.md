# OTP-Based Password Reset Functionality Guide

## Overview

The Library Management System now uses a secure OTP (One-Time Password) based password reset system instead of email links. This provides better security and user experience.

## Features

### 🔐 **Security Features**
- **6-Digit OTP**: Secure 6-digit numeric codes
- **10-Minute Expiration**: OTPs expire after 10 minutes
- **One-time Use**: Each OTP can only be used once
- **Rate Limiting**: Prevents abuse through multiple requests
- **Session Management**: Secure session-based verification

### 🎨 **User Experience**
- **Modern UI**: Beautiful, responsive design
- **Auto-submit**: Form submits automatically when 6 digits are entered
- **Paste Support**: Users can paste OTP from SMS/email
- **Real-time Validation**: Immediate feedback on input
- **Mobile Optimized**: Perfect for mobile devices

### 📱 **OTP Features**
- **Numeric Only**: 6-digit numeric codes (000000-999999)
- **Auto-formatting**: Input automatically formats and validates
- **Copy/Paste**: Supports pasting from clipboard
- **Resend Option**: Users can request new OTP

## How It Works

### 1. **Request OTP**
- User clicks "Forgot Password?" on login page
- User enters their email address
- System validates email exists in database
- If valid, system generates 6-digit OTP and stores it

### 2. **OTP Verification**
- User receives OTP (displayed in development)
- User enters 6-digit code on verification page
- System validates OTP and marks it as used
- User is redirected to password reset page

### 3. **Password Reset**
- User enters new password with confirmation
- Password strength is checked in real-time
- System updates password and clears session data
- User is redirected to login page

## Files Created/Modified

### **Database**
- `password_reset_otps` table - Stores OTPs and expiration times

### **Routes** (`routes/web.php`)
```php
// OTP-based password reset routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('password.email');
Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('password.verify');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('password.verify.submit');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
```

### **Controller** (`app/Http/Controllers/AuthController.php`)
- `sendOtp()` - Generate and store OTP
- `showVerifyOtp()` - Display OTP verification form
- `verifyOtp()` - Validate OTP and set session
- `showResetPassword()` - Display password reset form
- `resetPassword()` - Update user password

### **Models**
- `app/Models/PasswordResetOtp.php` - OTP management model
- `app/Models/User.php` - Updated (removed email notification)

### **Views**
- `resources/views/auth/forgot-password.blade.php` - Request OTP form
- `resources/views/auth/verify-otp.blade.php` - OTP verification form
- `resources/views/auth/reset-password.blade.php` - Password reset form

### **Commands**
- `app/Console/Commands/CleanupExpiredOtps.php` - Cleanup expired OTPs

## Database Schema

### **password_reset_otps Table**
```sql
CREATE TABLE password_reset_otps (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    otp VARCHAR(6) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX email_index (email)
);
```

## Usage Instructions

### **For Users**

1. **Request OTP**
   - Go to login page
   - Click "Forgot Password?" link
   - Enter your email address
   - Click "Send OTP"

2. **Verify OTP**
   - Check your email/SMS for OTP (in development, OTP is shown on screen)
   - Enter the 6-digit OTP code
   - Click "Verify OTP"

3. **Reset Password**
   - Enter your new password
   - Confirm the password
   - Click "Reset Password"
   - You'll be redirected to login page

### **For Administrators**

1. **Monitor OTP Requests**
   - Check `password_reset_otps` table for active OTPs
   - Monitor expiration times and usage status

2. **Cleanup Expired OTPs**
   ```bash
   php artisan otp:cleanup
   ```

3. **Security Monitoring**
   - OTPs automatically expire after 10 minutes
   - Used OTPs are marked as used
   - Failed attempts are logged

## Testing the System

### **Step-by-Step Testing**

1. **Start the Development Server**
   ```bash
   php artisan serve
   ```

2. **Navigate to Login Page**
   - Go to `http://localhost:8000/login`
   - Click "Forgot Password?" link

3. **Request OTP**
   - Enter a valid email address (must exist in database)
   - Click "Send OTP"
   - You should see the OTP displayed on screen (development mode)

4. **Verify OTP**
   - Enter the displayed OTP code
   - Click "Verify OTP"
   - You should be redirected to password reset page

5. **Reset Password**
   - Enter a new password
   - Confirm the password
   - Click "Reset Password"
   - You should be redirected to login page

### **Testing with Database**

1. **Check OTP Table**
   ```sql
   SELECT * FROM password_reset_otps ORDER BY created_at DESC LIMIT 5;
   ```

2. **Cleanup Expired OTPs**
   ```bash
   php artisan otp:cleanup
   ```

3. **Monitor Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

## Security Features

### **OTP Security**
- 6-digit numeric codes (000000-999999)
- 10-minute expiration time
- One-time use only
- Automatic cleanup of expired OTPs

### **Session Security**
- Email stored in session during reset process
- OTP verification required before password reset
- Session cleared after successful password reset

### **Rate Limiting**
- Prevents multiple OTP requests
- Automatic cleanup prevents database bloat
- Secure token generation

## Production Deployment

### **Email/SMS Integration**
To send OTPs via email or SMS in production:

1. **Update AuthController@sendOtp**
   ```php
   // Replace the development flash message with actual email/SMS sending
   Mail::to($request->email)->send(new OtpMail($otpRecord->otp));
   // or
   // SMS::send($phone, "Your OTP is: " . $otpRecord->otp);
   ```

2. **Create Email Template**
   ```bash
   php artisan make:mail OtpMail
   ```

3. **Configure Email Settings**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=your-smtp-host.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@domain.com
   MAIL_PASSWORD=your-password
   MAIL_ENCRYPTION=tls
   ```

### **Scheduled Cleanup**
Add to `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('otp:cleanup')->everyFiveMinutes();
}
```

## Troubleshooting

### **Common Issues**

1. **OTP Not Received**
   - Check if email exists in database
   - Verify email format
   - Check Laravel logs for errors

2. **Invalid OTP**
   - OTP may have expired (10 minutes)
   - OTP may have been used already
   - Request new OTP

3. **Session Issues**
   - Clear browser cache
   - Check session configuration
   - Verify session storage

### **Debug Commands**
```bash
# Check routes
php artisan route:list --name=password

# Clear cache
php artisan config:clear
php artisan cache:clear

# Check OTP table
php artisan tinker
>>> App\Models\PasswordResetOtp::all()

# Cleanup OTPs
php artisan otp:cleanup
```

## Customization

### **OTP Expiration Time**
Edit `app/Models/PasswordResetOtp.php`:
```php
'expires_at' => Carbon::now()->addMinutes(15), // Change from 10 to 15 minutes
```

### **OTP Length**
Edit `app/Models/PasswordResetOtp.php`:
```php
public static function generateOtp(): string
{
    return str_pad(random_int(0, 999999), 8, '0', STR_PAD_LEFT); // Change from 6 to 8 digits
}
```

### **UI Customization**
- Edit CSS variables in view files
- Modify animations and transitions
- Update color schemes and branding

## API Endpoints (if needed)

### **RESTful API Routes**
```php
// Add to routes/api.php
Route::post('/forgot-password', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
```

## Performance Optimization

### **Database Indexing**
```sql
-- Add indexes for better performance
ALTER TABLE password_reset_otps ADD INDEX idx_email_used (email, used);
ALTER TABLE password_reset_otps ADD INDEX idx_expires_at (expires_at);
```

### **Caching**
```php
// Cache user lookup
$user = Cache::remember("user_email_{$email}", 300, function() use ($email) {
    return User::where('email', $email)->first();
});
```

---

## Support

For technical support or questions about the OTP password reset functionality, please refer to the Laravel documentation or contact the development team.

## Changelog

### **v1.0.0** - Initial Release
- ✅ OTP-based password reset system
- ✅ 6-digit numeric OTP codes
- ✅ 10-minute expiration
- ✅ One-time use validation
- ✅ Modern responsive UI
- ✅ Auto-cleanup command
- ✅ Session-based security
- ✅ Comprehensive error handling 