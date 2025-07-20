<?php

namespace App\Console\Commands;

use App\Models\PasswordResetOtp;
use Illuminate\Console\Command;

class CleanupExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired password reset OTPs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedCount = PasswordResetOtp::cleanupExpired();
        
        $this->info("Successfully deleted {$deletedCount} expired OTPs.");
        
        return Command::SUCCESS;
    }
}
