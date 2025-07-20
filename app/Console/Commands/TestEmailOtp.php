<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\PasswordResetOtpNotification;
use Illuminate\Console\Command;

class TestEmailOtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-otp {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email OTP functionality';

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
        
        $this->info("Testing email OTP for user: {$user->name} ({$user->email})");
        
        try {
            // Generate a test OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            $this->info("Generated OTP: {$otp}");
            $this->info("Mail configuration:");
            $this->info("  - Driver: " . config('mail.default'));
            $this->info("  - Host: " . config('mail.mailers.smtp.host'));
            $this->info("  - Port: " . config('mail.mailers.smtp.port'));
            $this->info("  - Username: " . config('mail.mailers.smtp.username'));
            $this->info("  - From: " . config('mail.from.address'));
            
            // Send the notification
            $user->notify(new PasswordResetOtpNotification($otp));
            
            $this->info("✅ Email OTP sent successfully!");
            $this->info("Test OTP: {$otp}");
            $this->info("Check your email inbox for the test message.");
            $this->info("Also check: Spam/Junk folder, Gmail Promotions tab");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email OTP: " . $e->getMessage());
            $this->error("Error details: " . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
} 