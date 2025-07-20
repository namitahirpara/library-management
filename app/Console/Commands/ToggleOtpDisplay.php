<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ToggleOtpDisplay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:toggle-display {--enable : Enable OTP display in browser} {--disable : Disable OTP display in browser}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle OTP display in browser for development';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enable = $this->option('enable');
        $disable = $this->option('disable');
        
        if (!$enable && !$disable) {
            // Show current status
            $currentStatus = config('app.show_otp_in_browser', false);
            $this->info("Current OTP display status: " . ($currentStatus ? 'Enabled' : 'Disabled'));
            $this->info("Environment: " . app()->environment());
            
            if (app()->environment('local')) {
                $this->info("\nTo change the setting:");
                $this->info("  Enable:  php artisan otp:toggle-display --enable");
                $this->info("  Disable: php artisan otp:toggle-display --disable");
            } else {
                $this->warn("OTP display is only available in local environment.");
            }
            
            return Command::SUCCESS;
        }
        
        if (!app()->environment('local')) {
            $this->error("OTP display can only be configured in local environment.");
            return Command::FAILURE;
        }
        
        if ($enable && $disable) {
            $this->error("Cannot use both --enable and --disable options.");
            return Command::FAILURE;
        }
        
        $newStatus = $enable ? 'true' : 'false';
        $statusText = $enable ? 'enabled' : 'disabled';
        
        // Update .env file
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);
        
        if (strpos($envContent, 'SHOW_OTP_IN_BROWSER=') !== false) {
            // Update existing setting
            $envContent = preg_replace(
                '/SHOW_OTP_IN_BROWSER=.*/',
                'SHOW_OTP_IN_BROWSER=' . $newStatus,
                $envContent
            );
        } else {
            // Add new setting
            $envContent .= "\nSHOW_OTP_IN_BROWSER=" . $newStatus;
        }
        
        file_put_contents($envFile, $envContent);
        
        // Clear config cache
        $this->call('config:clear');
        
        $this->info("✅ OTP display in browser has been {$statusText}.");
        $this->info("📧 OTPs will " . ($enable ? 'now be shown' : 'no longer be shown') . " in the browser during development.");
        
        if ($enable) {
            $this->info("\n🔒 Security Note: This setting should only be used for development/testing.");
            $this->info("   Remember to disable it before deploying to production.");
        }
        
        return Command::SUCCESS;
    }
} 