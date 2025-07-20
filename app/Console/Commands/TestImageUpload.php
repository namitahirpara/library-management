<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestImageUpload extends Command
{
    protected $signature = 'test:image-upload';
    protected $description = 'Test the image upload functionality';

    public function handle()
    {
        $this->info('Testing image upload functionality...');
        
        // Check if storage link exists
        if (!file_exists(public_path('storage'))) {
            $this->error('Storage link does not exist. Run: php artisan storage:link');
            return 1;
        }
        
        // Check if book-covers directory exists
        if (!Storage::disk('public')->exists('book-covers')) {
            $this->info('Creating book-covers directory...');
            Storage::disk('public')->makeDirectory('book-covers');
        }
        
        // List existing books with images
        $books = Book::whereNotNull('cover_image')->get();
        
        if ($books->isEmpty()) {
            $this->info('No books with cover images found.');
        } else {
            $this->info("Found {$books->count()} books with cover images:");
            
            foreach ($books as $book) {
                $imageExists = Storage::disk('public')->exists($book->cover_image);
                $status = $imageExists ? '✓' : '✗';
                $this->line("  {$status} {$book->title} - {$book->cover_image}");
                
                if ($imageExists) {
                    $url = $book->cover_image_url;
                    $this->line("    URL: {$url}");
                }
            }
        }
        
        // Test storage permissions
        $testFile = 'book-covers/test_' . time() . '.txt';
        try {
            Storage::disk('public')->put($testFile, 'test');
            Storage::disk('public')->delete($testFile);
            $this->info('✓ Storage permissions are working correctly.');
        } catch (\Exception $e) {
            $this->error('✗ Storage permissions issue: ' . $e->getMessage());
            return 1;
        }
        
        $this->info('Image upload functionality test completed successfully!');
        return 0;
    }
}
