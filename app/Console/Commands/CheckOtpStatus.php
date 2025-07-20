<?php

namespace App\Console\Commands;

use App\Models\PasswordResetOtp;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckOtpStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:status {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check OTP status for debugging';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("🔍 Checking OTP Status...");
        $this->info("==========================================");
        
        if ($email) {
            $otps = PasswordResetOtp::where('email', $email)->get();
            $this->info("OTPs for email: {$email}");
        } else {
            $otps = PasswordResetOtp::all();
            $this->info("All OTPs in database:");
        }
        
        if ($otps->isEmpty()) {
            $this->warn("No OTPs found in database.");
            return Command::SUCCESS;
        }
        
        $this->info("\n📋 OTP Details:");
        $this->info("Email | OTP | Expires At | Used | Valid | Time Left");
        $this->info("------|-----|------------|------|-------|----------");
        
        foreach ($otps as $otp) {
            $timeLeft = $otp->expires_at->diffForHumans();
            $isValid = $otp->isValid() ? 'Yes' : 'No';
            $isUsed = $otp->used ? 'Yes' : 'No';
            
            $this->line("{$otp->email} | {$otp->otp} | {$otp->expires_at->format('H:i:s')} | {$isUsed} | {$isValid} | {$timeLeft}");
        }
        
        $this->info("\n🔧 Current Time: " . Carbon::now()->format('Y-m-d H:i:s'));
        
        // Test findValidOtp method
        if ($email) {
            $this->info("\n🧪 Testing findValidOtp method:");
            $validOtp = PasswordResetOtp::findValidOtp($email, $otps->first()->otp);
            if ($validOtp) {
                $this->info("✅ findValidOtp found valid OTP: {$validOtp->otp}");
            } else {
                $this->info("❌ findValidOtp returned null");
            }
        }
        
        return Command::SUCCESS;
    }
} 