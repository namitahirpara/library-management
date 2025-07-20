<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\PasswordResetOtpNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class TestOtpWithLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:otp-log {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test OTP with log driver for development';

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
        
        $this->info("Testing OTP with log driver for user: {$user->name} ({$user->email})");
        
        // Temporarily set mail driver to log
        Config::set('mail.default', 'log');
        
        try {
            // Generate a test OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Send the notification
            $user->notify(new PasswordResetOtpNotification($otp));
            
            $this->info("✅ OTP sent successfully with log driver!");
            $this->info("Test OTP: {$otp}");
            $this->info("Check the log file: storage/logs/laravel.log");
            
            // Show the last few lines of the log
            $this->info("\n📧 Email content from log:");
            $logContent = file_get_contents(storage_path('logs/laravel.log'));
            $lines = explode("\n", $logContent);
            $emailLines = array_slice($lines, -20); // Last 20 lines
            foreach ($emailLines as $line) {
                if (strpos($line, 'hirparanamita40@gmail.com') !== false || strpos($line, 'OTP') !== false) {
                    $this->line($line);
                }
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send OTP: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 