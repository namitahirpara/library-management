<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestGmailSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:gmail {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Gmail SMTP configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("🔧 Testing Gmail SMTP Configuration...");
        $this->info("==========================================");
        
        // Check configuration
        $this->info("📧 Mail Configuration:");
        $this->info("   Driver: " . config('mail.default'));
        $this->info("   Host: " . config('mail.mailers.smtp.host'));
        $this->info("   Port: " . config('mail.mailers.smtp.port'));
        $this->info("   Username: " . config('mail.mailers.smtp.username'));
        $this->info("   Encryption: " . config('mail.mailers.smtp.encryption'));
        $this->info("   From Address: " . config('mail.from.address'));
        $this->info("   From Name: " . config('mail.from.name'));
        
        // Check if password is set
        $password = config('mail.mailers.smtp.password');
        if (empty($password)) {
            $this->error("❌ MAIL_PASSWORD is not set in .env file!");
            $this->error("   Please add your Gmail app password to .env file");
            return Command::FAILURE;
        }
        
        if (strlen($password) < 16) {
            $this->warn("⚠️  MAIL_PASSWORD seems too short. Make sure you're using a Gmail app password (16 characters)");
        }
        
        $this->info("✅ MAIL_PASSWORD is configured");
        
        $this->info("\n📤 Sending test email...");
        
        try {
            Mail::raw('This is a test email from Laravel Library Management System. If you receive this, your Gmail SMTP is working correctly!', function($message) use ($email) {
                $message->to($email)
                        ->subject('✅ Gmail SMTP Test - Library Management System')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            $this->info("✅ Test email sent successfully!");
            $this->info("📧 Check your inbox at: {$email}");
            $this->info("📧 Also check: Spam/Junk folder, Gmail Promotions tab");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            $this->error("\n🔧 Troubleshooting steps:");
            $this->error("1. Make sure 2-Factor Authentication is enabled on your Gmail account");
            $this->error("2. Generate a new App Password for 'Mail'");
            $this->error("3. Update MAIL_PASSWORD in .env file with the 16-character app password");
            $this->error("4. Clear config cache: php artisan config:clear");
            
            return Command::FAILURE;
        }
    }
} 