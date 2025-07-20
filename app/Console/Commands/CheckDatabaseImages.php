<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckDatabaseImages extends Command
{
    protected $signature = 'books:check-database';
    protected $description = 'Check database records for book images and verify file existence';

    public function handle()
    {
        $this->info('Checking database records for book images...');
        
        $books = Book::whereNotNull('cover_image')->get();
        
        if ($books->isEmpty()) {
            $this->info('No books with cover images in database.');
            return;
        }

        $this->info("Found {$books->count()} books with cover images in database:");
        
        foreach ($books as $book) {
            $fileExists = Storage::disk('public')->exists($book->cover_image);
            $status = $fileExists ? '✓' : '✗';
            
            $this->line("  {$status} {$book->title} - {$book->cover_image}");
            
            if ($fileExists) {
                $this->line("    File exists in storage");
                $this->line("    URL: {$book->cover_image_url}");
            } else {
                $this->error("    File NOT found in storage!");
            }
        }
        
        // Check if storage directory has files
        $this->newLine();
        $this->info('Checking storage directory...');
        
        $files = Storage::disk('public')->files('book-covers');
        
        if (empty($files)) {
            $this->warn('No files found in storage/app/public/book-covers/');
        } else {
            $this->info("Found " . count($files) . " files in storage:");
            foreach ($files as $file) {
                $this->line("  - {$file}");
            }
        }
    }
} 