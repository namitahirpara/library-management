<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\PasswordResetOtpNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class VerifyOtpEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:otp-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify OTP email is being sent properly';

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
        
        $this->info("🔍 Verifying OTP email functionality...");
        $this->info("==========================================");
        
        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $this->info("📧 Sending OTP email to: {$email}");
        $this->info("🔢 Generated OTP: {$otp}");
        
        try {
            // Send the notification
            $user->notify(new PasswordResetOtpNotification($otp));
            
            $this->info("✅ OTP email sent successfully!");
            $this->info("📧 Check your email for OTP: {$otp}");
            $this->info("📧 Email subject: 🔐 Password Reset OTP - Library Management System");
            $this->info("📧 From: " . config('mail.from.address'));
            $this->info("📧 To: {$email}");
            
            $this->info("\n🔍 Email Status:");
            $this->info("   - Mail driver: " . config('mail.default'));
            $this->info("   - Mail host: " . config('mail.mailers.smtp.host'));
            $this->info("   - Mail port: " . config('mail.mailers.smtp.port'));
            $this->info("   - Mail encryption: " . config('mail.mailers.smtp.encryption'));
            
            $this->info("\n📋 Next Steps:");
            $this->info("1. Check your Gmail inbox");
            $this->info("2. Check Spam/Junk folder");
            $this->info("3. Check Gmail Promotions tab");
            $this->info("4. Search Gmail for 'Library Management System'");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send OTP email: " . $e->getMessage());
            $this->error("\n🔧 Troubleshooting:");
            $this->error("1. Check Gmail app password in .env file");
            $this->error("2. Verify 2-Factor Authentication is enabled");
            $this->error("3. Check mail configuration");
            
            return Command::FAILURE;
        }
    }
} 