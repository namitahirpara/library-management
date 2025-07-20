<?php

namespace App\Console\Commands;

use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class TestOtpValidation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:otp-validation {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test OTP validation logic';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return Command::FAILURE;
        }
        
        $this->info("🧪 Testing OTP Validation Logic...");
        $this->info("==========================================");
        
        // Step 1: Generate a new OTP
        $this->info("1. Generating new OTP...");
        $otpRecord = PasswordResetOtp::createOrUpdateOtp($email);
        $this->info("   ✅ Generated OTP: {$otpRecord->otp}");
        $this->info("   ✅ Expires at: {$otpRecord->expires_at}");
        $this->info("   ✅ Is valid: " . ($otpRecord->isValid() ? 'Yes' : 'No'));
        
        // Step 2: Test valid OTP
        $this->info("\n2. Testing valid OTP...");
        $validOtp = PasswordResetOtp::findValidOtp($email, $otpRecord->otp);
        if ($validOtp) {
            $this->info("   ✅ Valid OTP found: {$validOtp->otp}");
        } else {
            $this->error("   ❌ Valid OTP not found!");
            return Command::FAILURE;
        }
        
        // Step 3: Test invalid OTP
        $this->info("\n3. Testing invalid OTP...");
        $invalidOtp = PasswordResetOtp::findValidOtp($email, '000000');
        if (!$invalidOtp) {
            $this->info("   ✅ Invalid OTP correctly rejected");
        } else {
            $this->error("   ❌ Invalid OTP incorrectly accepted!");
            return Command::FAILURE;
        }
        
        // Step 4: Test used OTP
        $this->info("\n4. Testing used OTP...");
        $otpRecord->markAsUsed();
        $usedOtp = PasswordResetOtp::findValidOtp($email, $otpRecord->otp);
        if (!$usedOtp) {
            $this->info("   ✅ Used OTP correctly rejected");
        } else {
            $this->error("   ❌ Used OTP incorrectly accepted!");
            return Command::FAILURE;
        }
        
        // Step 5: Test expired OTP
        $this->info("\n5. Testing expired OTP...");
        $expiredOtp = PasswordResetOtp::create([
            'email' => $email,
            'otp' => '999999',
            'expires_at' => Carbon::now()->subMinutes(1), // Expired 1 minute ago
            'used' => false
        ]);
        
        $expiredResult = PasswordResetOtp::findValidOtp($email, '999999');
        if (!$expiredResult) {
            $this->info("   ✅ Expired OTP correctly rejected");
        } else {
            $this->error("   ❌ Expired OTP incorrectly accepted!");
            return Command::FAILURE;
        }
        
        $this->info("\n🎉 All OTP validation tests passed!");
        $this->info("The OTP system is working correctly.");
        
        return Command::SUCCESS;
    }
} 