<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean'
    ];

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if OTP is valid (not expired and not used)
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && !$this->used;
    }

    /**
     * Mark OTP as used
     */
    public function markAsUsed(): void
    {
        $this->update(['used' => true]);
    }

    /**
     * Generate a new OTP
     */
    public static function generateOtp(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create or update OTP for email
     */
    public static function createOrUpdateOtp(string $email): self
    {
        // Delete ALL existing OTPs for this email (used, unused, expired, etc.)
        $deletedCount = self::where('email', $email)->delete();
        
        // Log the cleanup for debugging
        if ($deletedCount > 0) {
            \Log::info("Cleaned up {$deletedCount} existing OTP(s) for email: {$email}");
        }

        // Create new OTP
        $newOtp = self::create([
            'email' => $email,
            'otp' => self::generateOtp(),
            'expires_at' => Carbon::now()->addMinutes(10), // 10 minutes expiry
            'used' => false
        ]);
        
        \Log::info("Created new OTP: {$newOtp->otp} for email: {$email}, expires at: {$newOtp->expires_at}");
        
        return $newOtp;
    }

    /**
     * Find valid OTP for email
     */
    public static function findValidOtp(string $email, string $otp): ?self
    {
        // First, find the OTP record
        $otpRecord = self::where('email', $email)
            ->where('otp', $otp)
            ->first();
        
        if (!$otpRecord) {
            \Log::warning("OTP not found for email: {$email}, OTP: {$otp}");
            return null;
        }
        
        // Check if it's used
        if ($otpRecord->used) {
            \Log::warning("OTP already used for email: {$email}, OTP: {$otp}");
            return null;
        }
        
        // Check if it's expired
        if ($otpRecord->isExpired()) {
            \Log::warning("OTP expired for email: {$email}, OTP: {$otp}, expired at: {$otpRecord->expires_at}");
            return null;
        }
        
        \Log::info("Valid OTP found for email: {$email}, OTP: {$otp}");
        return $otpRecord;
    }

    /**
     * Clean up expired OTPs
     */
    public static function cleanupExpired(): int
    {
        return self::where('expires_at', '<', Carbon::now())->delete();
    }
}
