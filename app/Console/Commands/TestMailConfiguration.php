<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailConfiguration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:mail {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test mail configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing mail configuration...");
        $this->info("Mail driver: " . config('mail.default'));
        $this->info("Mail host: " . config('mail.mailers.smtp.host'));
        $this->info("Mail port: " . config('mail.mailers.smtp.port'));
        $this->info("Mail username: " . config('mail.mailers.smtp.username'));
        $this->info("Mail from address: " . config('mail.from.address'));
        $this->info("Mail from name: " . config('mail.from.name'));
        
        try {
            Mail::raw('This is a test email from Laravel Library Management System.', function($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - Library Management System');
            });
            
            $this->info("✅ Test email sent successfully to {$email}!");
            $this->info("Check your inbox for the test message.");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send test email: " . $e->getMessage());
            $this->error("Please check your mail configuration in .env file.");
            return Command::FAILURE;
        }
    }
} 