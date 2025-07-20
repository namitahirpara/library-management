<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\PasswordResetOtp;
use App\Notifications\PasswordResetOtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Registration successful!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Rate limiting: max 3 attempts per 15 minutes
        $key = 'password_reset_' . $request->ip();
        $attempts = cache()->get($key, 0);
        
        if ($attempts >= 3) {
            return back()->withErrors(['email' => 'Too many password reset attempts. Please try again in 15 minutes.']);
        }

        try {
            // Check if user exists
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return back()->withErrors(['email' => 'No account found with this email address.']);
            }

            // Generate and store OTP
            $otpRecord = PasswordResetOtp::createOrUpdateOtp($request->email);
            
            // Send OTP via email
            try {
                // Log the attempt
                \Log::info('Attempting to send OTP email to: ' . $request->email . ' with OTP: ' . $otpRecord->otp);
                
                $user->notify(new PasswordResetOtpNotification($otpRecord->otp));
                
                \Log::info('OTP email sent successfully to: ' . $request->email);
                $emailStatus = 'OTP sent successfully! Please check your email.';
                
                // Only show OTP in browser if explicitly enabled in development
                if (app()->environment('local') && config('app.show_otp_in_browser', false)) {
                    $emailStatus .= ' (Development OTP: ' . $otpRecord->otp . ')';
                }
            } catch (\Exception $e) {
                // Log the error for debugging
                \Log::error('Failed to send OTP email: ' . $e->getMessage());
                \Log::error('Error details: ' . $e->getTraceAsString());
                
                // If email fails, show OTP in browser as fallback only in development
                if (app()->environment('local')) {
                    $emailStatus = 'Email sending failed. For development, your OTP is: ' . $otpRecord->otp;
                } else {
                    $emailStatus = 'Email sending failed. Please try again later.';
                }
                
                // Also log the OTP for debugging
                \Log::info('OTP generated but email failed: ' . $otpRecord->otp . ' for email: ' . $request->email);
            }
            
            // Increment rate limiting counter
            cache()->put($key, $attempts + 1, 900); // 15 minutes
            
            // Store email in session for verification step
            $request->session()->put('reset_email', $request->email);
            
            return redirect()->route('password.verify')
                           ->with('status', $emailStatus)
                           ->with('email', $request->email);

        } catch (\Exception $e) {
            \Log::error('General error in sendOtp: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again.']);
        }
    }

    public function showVerifyOtp()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp', ['email' => session('reset_email')]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request');
        }

        // Rate limiting for OTP verification: max 5 attempts per 15 minutes
        $key = 'otp_verify_' . $request->ip();
        $attempts = cache()->get($key, 0);
        
        if ($attempts >= 5) {
            return back()->withErrors(['otp' => 'Too many OTP verification attempts. Please try again in 15 minutes.']);
        }

        // Log the verification attempt
        \Log::info("OTP verification attempt for email: {$email}, OTP: {$request->otp}");

        $otpRecord = PasswordResetOtp::findValidOtp($email, $request->otp);
        
        if (!$otpRecord) {
            // Increment rate limiting counter
            cache()->put($key, $attempts + 1, 900); // 15 minutes
            
            // Check what specific issue occurred
            $existingOtp = PasswordResetOtp::where('email', $email)
                ->where('otp', $request->otp)
                ->first();
            
            if (!$existingOtp) {
                $errorMessage = 'Invalid OTP. Please check the code and try again.';
            } elseif ($existingOtp->used) {
                $errorMessage = 'This OTP has already been used. Please request a new OTP.';
            } elseif ($existingOtp->isExpired()) {
                $errorMessage = 'OTP has expired. Please request a new OTP.';
            } else {
                $errorMessage = 'Invalid or expired OTP. Please try again.';
            }
            
            return back()->withErrors(['otp' => $errorMessage]);
        }

        // Clear rate limiting on successful verification
        cache()->forget($key);

        // Mark OTP as used
        $otpRecord->markAsUsed();
        
        // Store verification in session
        $request->session()->put('otp_verified', true);
        
        \Log::info("OTP verified successfully for email: {$email}");
        
        return redirect()->route('password.reset')
                       ->with('status', 'OTP verified successfully! You can now reset your password.');
    }

    public function showResetPassword(Request $request)
    {
        if (!session('reset_email') || !session('otp_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', ['email' => session('reset_email')]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!session('otp_verified')) {
            return redirect()->route('password.request');
        }

        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return back()->withErrors(['email' => 'User not found.']);
            }

            // Update password
            $user->forceFill([
                'password' => Hash::make($request->password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));

            // Clear session data
            $request->session()->forget(['reset_email', 'otp_verified']);

            return redirect()->route('login')
                           ->with('status', 'Password reset successfully! You can now login with your new password.');

        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to reset password. Please try again.']);
        }
    }
} 